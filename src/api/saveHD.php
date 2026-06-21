<?php
header('Content-Type: application/json; charset=utf-8');

$inputData = json_decode(file_get_contents('php://input'), true);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

if (!$inputData) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Dữ liệu yêu cầu không hợp lệ hoặc rỗng.'
    ]);
    exit;
}

// Bóc tách dữ liệu từ payload JSON
$maPhieuKham      = $inputData['MaPhieuKham'] ?? null;
$tongTienCLS      = floatval($inputData['TongTienCLS'] ?? 0);
$tongTienThuoc    = floatval($inputData['TongTienThuoc'] ?? 0);
$tongCong         = floatval($inputData['TongCong'] ?? 0);
$giamGia          = floatval($inputData['GiamGia'] ?? 0);
$tongThanhToan    = floatval($inputData['TongThanhToan'] ?? 0);
$trangThai        = $inputData['TrangThai'] ?? 1;

$phuongThucTT     = $inputData['PhuongThucThanhToan'] ?? 'tien-mat';
$soTienKhachDua   = floatval($inputData['SoTienKhachDua'] ?? 0);
$soTienTraKhach   = floatval($inputData['SoTienTraKhach'] ?? 0);

// Kiểm tra bắt buộc phải có Mã phiếu khám
if (empty($maPhieuKham)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu thông tin Mã phiếu khám để lập hóa đơn.'
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    $namHienTai = date('Y');
    
    $stmtCount = $pdo->prepare("SELECT COUNT(*) as total FROM hoadon WHERE YEAR(NgayTao) = :nam");
    $stmtCount->execute([':nam' => $namHienTai]);
    $resultCount = $stmtCount->fetch();
    $nextNumber = intval($resultCount['total']) + 1;
    
    $soHoaDonDinhDang = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    
    $maHoaDon = "MHD-" . $namHienTai . "-" . $soHoaDonDinhDang; // Định dạng chuỗi nhận diện duy nhất
    $soHoaDon = "HD" . $namHienTai . $soHoaDonDinhDang;       // Số hóa đơn kế toán

    // Thời gian hiện tại cho việc tạo và thanh toán
    $now = date('Y-m-d H:i:s');

    // 4. TIẾN HÀNH INSERT DỮ LIỆU VÀO BẢNG HOADON
    $sqlInsert = "INSERT INTO hoadon (
                    MaHoaDon, 
                    SoHoaDon, 
                    MaPhieuKham, 
                    TongTienCLS, 
                    TongTienThuoc, 
                    TongCong, 
                    GiamGia, 
                    TongThanhToan, 
                    TrangThai, 
                    NgayTao, 
                    NgayThanhToan
                  ) VALUES (
                    :MaHoaDon, 
                    :SoHoaDon, 
                    :MaPhieuKham, 
                    :TongTienCLS, 
                    :TongTienThuoc, 
                    :TongCong, 
                    :GiamGia, 
                    :TongThanhToan, 
                    :TrangThai, 
                    :NgayTao, 
                    :NgayThanhToan
                  )";

    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute([
        ':MaHoaDon'       => $maHoaDon,
        ':SoHoaDon'       => $soHoaDon,
        ':MaPhieuKham'    => $maPhieuKham,
        ':TongTienCLS'    => $tongTienCLS,
        ':TongTienThuoc'  => $tongTienThuoc,
        ':TongCong'       => $tongCong,
        ':GiamGia'        => $giamGia,
        ':TongThanhToan'  => $tongThanhToan,
        ':TrangThai'      => $trangThai,
        ':NgayTao'        => $now,
        ':NgayThanhToan'  => $now
    ]);

    // 5. [TÙY CHỌN] Cập nhật trạng thái của Phiếu khám sang "Đã thanh toán" 
    $sqlUpdatePK = "UPDATE phieukham SET matrangthai = 9 WHERE MaPhieuKham = :maPK";
    $stmtUpdatePK = $pdo->prepare($sqlUpdatePK);
    $stmtUpdatePK->execute([':maPK' => $maPhieuKham]);

    // Xác nhận lưu mọi thay đổi vào Database thành công
    $pdo->commit();

    // Trả lời kết quả về cho giao diện JavaScript tiếp nhận
    echo json_encode([
        'status' => 'success',
        'message' => 'Lưu hóa đơn thành công!',
        'data' => [
            'MaHoaDon' => $maHoaDon,
            'SoHoaDon' => $soHoaDon
        ]
    ]);

} catch (Exception $e) {
    // Nếu có bất kỳ lỗi nào xảy ra, hủy bỏ toàn bộ các lệnh SQL đã chạy trong block này
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi xử lý Database: ' . $e->getMessage()
    ]);
}