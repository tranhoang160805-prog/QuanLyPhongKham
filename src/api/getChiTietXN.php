<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php'; 

// 3. Kiểm tra bảo mật đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập lại.']);
    exit;
}

$maChiDinh = isset($_GET['ma_chi_dinh']) ? (int)$_GET['ma_chi_dinh'] : 0;

if ($maChiDinh <= 0) {
    echo json_encode(['success' => false, 'message' => 'Mã chỉ định không hợp lệ.']);
    exit;
}

try {
    // Câu lệnh SELECT thứ 2 của bạn để bóc tách kết quả cận lâm sàng
    $stmtDetail = $pdo->prepare("
        SELECT 
            cls.NgayChiDinh,
            kq.KetQuaText,
            kq.KetLuan,
            kq.FileKetQua,
            kq.NgayThucHien
        FROM chidinhcls cls
        JOIN ketquacls kq ON kq.MaChiDinh = cls.MaChiDinh
        WHERE cls.MaChiDinh = :ma_chi_dinh
    ");
    $stmtDetail->execute(['ma_chi_dinh' => $maChiDinh]);
    $detail = $stmtDetail->fetch(PDO::FETCH_ASSOC);

    if ($detail) {
        // Định dạng lại ngày giờ cho chuẩn giao diện giống file cũ của bạn
        $detail['NgayChiDinhFmt'] = date('H:i d/m/Y', strtotime($detail['NgayChiDinh']));
        $detail['NgayThucHienFmt'] = $detail['NgayThucHien'] ? date('H:i d/m/Y', strtotime($detail['NgayThucHien'])) : 'Chưa cập nhật';
        
        echo json_encode(['success' => true, 'data' => $detail]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Hiện chưa có dữ liệu kết quả xét nghiệm cho chỉ định này.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage()]);
}
exit;