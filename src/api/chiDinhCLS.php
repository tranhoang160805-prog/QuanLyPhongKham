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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'save_chi_dinh') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $maPhieuKham = isset($input['ma_phieu_kham']) ? (int)$input['ma_phieu_kham'] : 0;
        $danhSachDichVu = isset($input['danh_sach_cls']) ? $input['danh_sach_cls'] : [];

        if ($maPhieuKham <= 0) {
            echo json_encode(['success' => false, 'message' => 'Mã phiếu khám không hợp lệ.']);
            exit;
        }
        if (empty($danhSachDichVu)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng chọn ít nhất một loại chỉ định cận lâm sàng!']);
            exit;
        }

        $pdo->beginTransaction();

        // 1. Cập nhật trạng thái phiếu khám thành Chờ xét nghiệm (MaTrangThai = 4)
        $stmtUpdatePhieu = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 4, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
        $stmtUpdatePhieu->execute([$maPhieuKham]);

        // 2. Insert các dòng dịch vụ đã chọn vào bảng CHIDINHCLS
        $stmtInsert = $pdo->prepare("INSERT INTO CHIDINHCLS (MaPhieuKham, MaLoaiCLS, MoTaChiDinh, NgayChiDinh) VALUES (?, ?, ?, NOW())");
        foreach ($danhSachDichVu as $item) {
            $maLoaiCLS = (int)$item['ma_loai_cls'];
            $moTaChiDinh = trim($item['mo_ta_chi_dinh']);
            $stmtInsert->execute([$maPhieuKham, $maLoaiCLS, $moTaChiDinh]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Chỉ định cận lâm sàng thành công! Đã chuyển trạng thái bệnh nhân sang phòng xét nghiệm.']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống dữ liệu: ' . $e->getMessage()]);
    }
    exit;
}
?>
