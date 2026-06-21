<?php
// Đường dẫn: src/api/saveCapPhat.php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
    exit;
}

// Lấy thông tin tài khoản đang đăng nhập (NguoiXuat)
$nguoiXuat = $_SESSION['user_code'] ?? null;

if (!$nguoiXuat) {
    echo json_encode(['success' => false, 'message' => 'Phiên làm việc hết hạn hoặc tài khoản không có quyền.']);
    exit;
}

// Lấy dữ liệu Payload từ Client
$input = json_encode(json_decode(file_get_contents('php://input'), true));
$data = json_decode($input, true);

$maPhieuKham = isset($data['ma_phieu_kham']) ? (int)$data['ma_phieu_kham'] : 0;
$thuocList = isset($data['thuoc_list']) ? $data['thuoc_list'] : [];

if ($maPhieuKham <= 0 || empty($thuocList)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu cấp phát không hợp lệ hoặc trống.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Lấy thông tin MaBenhNhan từ MaPhieuKham
    $sqlGetBN = "SELECT MaBenhNhan FROM PHIEUKHAM WHERE MaPhieuKham = ?";
    $stmtGetBN = $pdo->prepare($sqlGetBN);
    $stmtGetBN->execute([$maPhieuKham]);
    $maBenhNhan = $stmtGetBN->fetchColumn();

    if (!$maBenhNhan) {
        throw new Exception("Không tìm thấy thông tin bệnh nhân từ phiếu khám này.");
    }

    // 2. Tính tổng tiền và chuẩn bị tạo Số Phiếu Xuất tự động
    $tongTien = 0;
    foreach ($thuocList as $thuoc) {
        $tongTien += (float)$thuoc['DonGia'] * (int)$thuoc['SoLuong'];
    }
    $soPhieuXuat = 'PX-' . date('Ymd') . '-' . rand(1000, 9999);

    // 3. INSERT vào bảng PHIEUXUAT
    $sqlPX = "INSERT INTO PHIEUXUAT (SoPhieuXuat, MaBenhNhan, NgayXuat, NguoiXuat, TongTien) 
              VALUES (?, ?, CURDATE(), ?, ?)";
    $stmtPX = $pdo->prepare($sqlPX);
    $stmtPX->execute([$soPhieuXuat, $maBenhNhan, $nguoiXuat, $tongTien]);
    
    // Lấy ID tự tăng của Phiếu Xuất vừa tạo
    $maPhieuXuat = $pdo->lastInsertId();

    // Chuẩn bị các câu lệnh mẫu tối ưu hiệu năng
    $sqlCT = "INSERT INTO CHITIETPHIEUXUAT (MaPhieuXuat, MaThuoc, SoLuong, DonGia, ThanhTien) 
              VALUES (?, ?, ?, ?, ?)";
    $stmtCT = $pdo->prepare($sqlCT);

    $sqlCheckStock = "SELECT TenThuoc, SoLuongTon FROM THUOC WHERE MaThuoc = ? FOR UPDATE";
    $stmtCheckStock = $pdo->prepare($sqlCheckStock);

    $sqlReduceStock = "UPDATE THUOC 
                       SET SoLuongTon = SoLuongTon - ?, NgayCapNhat = NOW() 
                       WHERE MaThuoc = ? AND SoLuongTon >= ?";
    $stmtReduceStock = $pdo->prepare($sqlReduceStock);

    // 4. Duyệt mảng thuốc: Kiểm tra tồn kho -> Thêm chi tiết -> Cập nhật kho thuốc
    foreach ($thuocList as $thuoc) {
        $maThuoc = (int)$thuoc['MaThuoc'];
        $soLuongXuat = (int)$thuoc['SoLuong'];
        $donGia = (float)$thuoc['DonGia'];
        $thanhTien = $donGia * $soLuongXuat;

        // A. Kiểm tra nghiêm ngặt số lượng tồn hiện tại của thuốc trong CSDL
        $stmtCheckStock->execute([$maThuoc]);
        $stockInfo = $stmtCheckStock->fetch(PDO::FETCH_ASSOC);

        if (!$stockInfo) {
            throw new Exception("Mã thuốc #{$maThuoc} không tồn tại trên hệ thống kho.");
        }
        if ($stockInfo['SoLuongTon'] < $soLuongXuat) {
            throw new Exception("Thuốc [{$stockInfo['TenThuoc']}] không đủ số lượng cấp phát (Tồn hiện tại: {$stockInfo['SoLuongTon']}, Yêu cầu: {$soLuongXuat}).");
        }

        // B. INSERT vào CHITIETPHIEUXUAT
        $stmtCT->execute([$maPhieuXuat, $maThuoc, $soLuongXuat, $donGia, $thanhTien]);

        // C. UPDATE trừ số lượng tồn kho thuốc
        $stmtReduceStock->execute([$soLuongXuat, $maThuoc, $soLuongXuat]);
        
        if ($stmtReduceStock->rowCount() === 0) {
            throw new Exception("Quá trình cập nhật kho cho thuốc [{$stockInfo['TenThuoc']}] thất bại do biến động dữ liệu đồng thời.");
        }
    }

    // 5. CẬP NHẬT: Đặt MaTrangThai = 8 cho phiếu khám sau khi hoàn tất cấp thuốc
    $sqlUpdatePK = "UPDATE PHIEUKHAM SET MaTrangThai = 8 WHERE MaPhieuKham = ?";
    $stmtUpdatePK = $pdo->prepare($sqlUpdatePK);
    $stmtUpdatePK->execute([$maPhieuKham]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Cấp phát thuốc và cập nhật đồng bộ kho bãi thành công!']);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}