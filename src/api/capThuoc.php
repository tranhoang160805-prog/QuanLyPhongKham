<?php
// Đường dẫn: src/api/capThuoc.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
// Lấy ID người dùng thực thi xuất quầy từ Session, mặc định là 1 nếu chưa đăng nhập
$maNguoiXuat = ($_SESSION['user_code']); 

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['ma_phieu_kham']) || empty($input['medicines'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu đầu vào không hợp lệ hoặc đơn thuốc trống.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$maPhieuKham = (int)$input['ma_phieu_kham'];
$medicines   = $input['medicines']; 

try {
    $pdo->beginTransaction();

    // =========================================================================
    // GIẢI PHÁP SỬA LỖI 1452: Tự động tra cứu trực tiếp ID số nguyên từ DB
    // Loại bỏ hoàn toàn sự sai lệch giữa MaBN (chuỗi hiển thị) và MaBenhNhan (Khóa ngoại thật)
    // =========================================================================
    $sql_get_bn = "SELECT MaBenhNhan FROM PHIEUKHAM WHERE MaPhieuKham = :ma_pk";
    $stmt_get_bn = $pdo->prepare($sql_get_bn);
    $stmt_get_bn->execute([':ma_pk' => $maPhieuKham]);
    $real_ma_benh_nhan = $stmt_get_bn->fetchColumn();

    if (!$real_ma_benh_nhan) {
        throw new Exception("Mã phiếu khám không tồn tại hoặc không tìm thấy liên kết bệnh nhân.");
    }

    // 1. Tạo số phiếu xuất kho (Unique Code)
    $soPhieuXuat = "PX-" . date('Ymd') . "-" . strtoupper(bin2hex(random_bytes(2)));

    // 2. Tính tổng tiền hóa đơn các thuốc được phát thực tế
    $tongTienThucTe = 0;
    foreach ($medicines as $med) {
        $tongTienThucTe += (int)$med['so_luong'] * (float)$med['don_gia'];
    }

    // 3. Thêm mới dữ liệu vào bảng PHIEUXUAT với ID số nguyên an toàn ($real_ma_benh_nhan)
    $sql_px = "INSERT INTO PHIEUXUAT (SoPhieuXuat, MaBenhNhan, NgayXuat, NguoiXuat, TongTien) 
               VALUES (:so_phieu, :ma_bn, CURDATE(), :nguoi_xuat, :tong_tien)";
    $stmt_px = $pdo->prepare($sql_px);
    $stmt_px->execute([
        ':so_phieu'    => $soPhieuXuat,
        ':ma_bn'       => (int)$real_ma_benh_nhan, // Khớp chuẩn xác 100% Khóa Ngoại tham chiếu tới bảng benhnhan
        ':nguoi_xuat'  => $maNguoiXuat,
        ':tong_tien'   => $tongTienThucTe
    ]);
    $maPhieuXuatID = $pdo->lastInsertId();

    // 4. Thiết lập câu lệnh chuẩn bị để ghi chi tiết phiếu xuất và trừ tồn kho
    $sql_ctpx = "INSERT INTO CHITIETPHIEUXUAT (MaPhieuXuat, MaThuoc, SoLuong, DonGia, ThanhTien) 
                 VALUES (:ma_px, :ma_thuoc, :so_luong, :don_gia, :thanh_tien)";
    $stmt_ctpx = $pdo->prepare($sql_ctpx);

    // Đồng thời kiểm tra điều kiện nguyên tử (Atomic Update) chống xuất âm kho
    $sql_update_kho = "UPDATE THUOC 
                       SET SoLuongTon = SoLuongTon - :sub_qty, NgayCapNhat = NOW() 
                       WHERE MaThuoc = :ma_thuoc AND SoLuongTon >= :check_qty";
    $stmt_update_kho = $pdo->prepare($sql_update_kho);

    foreach ($medicines as $med) {
        $maThuoc   = (int)$med['ma_thuoc'];
        $soLuong   = (int)$med['so_luong'];
        $donGia    = (float)$med['don_gia'];
        $thanhTien = $soLuong * $donGia;

        // Lưu chi tiết hàng xuất
        $stmt_ctpx->execute([
            ':ma_px'     => $maPhieuXuatID,
            ':ma_thuoc'  => $maThuoc,
            ':so_luong'  => $soLuong,
            ':don_gia'   => $donGia,
            ':thanh_tien'=> $thanhTien
        ]);

        // Trừ tồn kho dược quầy
        $stmt_update_kho->execute([
            ':sub_qty'   => $soLuong,
            ':ma_thuoc'  => $maThuoc,
            ':check_qty' => $soLuong
        ]);

        // Nếu số dòng tác động bằng 0 chứng tỏ kho vừa bị hết hoặc thiếu hàng giữa chừng
        if ($stmt_update_kho->rowCount() === 0) {
            throw new Exception("Thuốc có mã định danh ID {$maThuoc} không đủ số lượng tồn khả dụng tại quầy!");
        }
    }

    // 5. CẬP NHẬT TRẠNG THÁI PHIẾU KHÁM SANG ĐÃ CẤP THUỐC (MaTrangThai = 6)
    $sql_update_pk = "UPDATE PHIEUKHAM SET MaTrangThai = 6, NgayCapNhat = NOW() WHERE MaPhieuKham = :ma_pk";
    $stmt_update_pk = $pdo->prepare($sql_update_pk);
    $stmt_update_pk->execute([':ma_pk' => $maPhieuKham]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Cấp phát thuốc thành công! Đã trừ tồn kho và cập nhật trạng thái phiếu khám thành công.',
        'so_phieu_xuat' => $soPhieuXuat
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if ($pdo->inTransaction()) { 
        $pdo->rollBack(); 
    }
    echo json_encode([
        'success' => false,
        'message' => 'Quy trình xử lý dữ liệu thất bại: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
exit;