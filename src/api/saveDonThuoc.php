<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'save_prescription') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $maPhieuKham = isset($input['ma_phieu_kham']) ? (int)$input['ma_phieu_kham'] : 0;
        $loiDan = isset($input['loi_dan']) ? trim($input['loi_dan']) : '';
        $prescriptionItems = isset($input['items']) ? $input['items'] : [];

        if ($maPhieuKham <= 0) {
            echo json_encode(['success' => false, 'message' => 'Hệ thống không tìm thấy mã phiếu khám hợp lệ.']);
            exit;
        }
        if (empty($prescriptionItems)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ít nhất một loại thuốc vào đơn trước khi xác nhận!']);
            exit;
        }

        $pdo->beginTransaction();

        $stmtPresc = $pdo->prepare("INSERT INTO DONTHUOC (MaPhieuKham, NgayKeToa, LoiDan) VALUES (?, NOW(), ?)");
        $stmtPresc->execute([$maPhieuKham, $loiDan]);
        $maDonThuoc = $pdo->lastInsertId();

        $stmtDetail = $pdo->prepare("INSERT INTO CHITIETDONTHUOC (MaDonThuoc, MaThuoc, SoLuong, DonGia, CachDung) VALUES (?, ?, ?, ?, ?)");
        foreach ($prescriptionItems as $item) {
            $maThuoc = (int)$item['ma_thuoc'];
            $soLuong = (int)$item['so_luong'];
            $donGia = (float)$item['don_gia'];
            $cachDung = trim($item['cach_dung']);
            
            $stmtDetail->execute([$maDonThuoc, $maThuoc, $soLuong, $donGia, $cachDung]);
        }

        $stmtUpdatePhieu = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 7, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
        $stmtUpdatePhieu->execute([$maPhieuKham]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Kê đơn thuốc và hoàn tất hồ sơ khám bệnh thành công!']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi xử lý cơ sở dữ liệu: ' . $e->getMessage()]);
    }
    exit;
}
?>