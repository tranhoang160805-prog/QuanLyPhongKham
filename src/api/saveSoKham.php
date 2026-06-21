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

$inputData = json_decode(file_get_contents("php://input"), true);
if (!$inputData) {
    $inputData = $_POST;
}

if (!isset($_SESSION['user_code'])) {
    echo json_encode(['success' => false, 'message' => 'Phiên làm việc hết hạn, vui lòng đăng nhập lại.']);
    exit;
}

$maNhanVien = $_SESSION['user_code'];

try {
    $sql = "SELECT MaChuyenKhoa FROM nhanvien WHERE MaNhanVien = :ma_nhan_vien LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ma_nhan_vien' => $maNhanVien]);
    $nhanVien = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($nhanVien) {
        $maChuyenKhoa = $nhanVien['MaChuyenKhoa'];
    } else {
        $maChuyenKhoa = null;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn thông tin nhân viên: ' . $e->getMessage()]);
    exit;
}

$maPhieuKham = isset($inputData['target_ma_phieu']) ? (int)$inputData['target_ma_phieu'] : 0;

if ($maPhieuKham <= 0) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn một bệnh nhân hợp lệ từ danh sách!']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmtCheck = $pdo->prepare("SELECT MaTrangThai FROM PHIEUKHAM WHERE MaPhieuKham = ?");
    $stmtCheck->execute([$maPhieuKham]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus === false) {
        throw new Exception("Phiếu khám không tồn tại trên hệ thống.");
    }

    $updateFields = [];
    $params = [':ma_phieu_kham' => $maPhieuKham];

    // Khối 1: Cập nhật thông số sinh tồn (Dành cho Điều dưỡng sơ khám)
    // Khối 1: Cập nhật thông số sinh tồn (Chỉ chạy khi thực sự có dữ liệu sinh tồn truyền lên)
    if (
        (!empty($inputData['nhiet_do']) && trim($inputData['nhiet_do']) !== '') || 
        (!empty($inputData['huyet_ap']) && trim($inputData['huyet_ap']) !== '') || 
        (!empty($inputData['chieu_cao']) && trim($inputData['chieu_cao']) !== '') || 
        (!empty($inputData['can_nang']) && trim($inputData['can_nang']) !== '')
    ) {
        $sinhTonArray = [
            'nhiet_do' => !empty($inputData['nhiet_do']) ? (float)$inputData['nhiet_do'] : null,
            'huyet_ap' => !empty($inputData['huyet_ap']) ? trim($inputData['huyet_ap']) : null,
            'chieu_cao' => !empty($inputData['chieu_cao']) ? (float)$inputData['chieu_cao'] : null,
            'can_nang' => !empty($inputData['can_nang']) ? trim($inputData['can_nang']) : null,
        ];
        $thongSoSinhTonJson = json_encode($sinhTonArray, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        
        $updateFields[] = "ThongSoSinhTon = :thong_so_sinh_ton";
        $params[':thong_so_sinh_ton'] = $thongSoSinhTonJson;

        if ((int)$currentStatus === 2) {
            $updateFields[] = "MaTrangThai = 3";
        }
    }

    // Khối 2: Cập nhật thông tin chi tiết bệnh án (Dành cho Bác sĩ)
    if (isset($inputData['ghi_chu']) || isset($inputData['ly_do_kham']) || isset($inputData['trieu_chung']) || isset($inputData['tien_su_benh']) || isset($inputData['chan_doan']) || isset($inputData['loi_dan_bs']) || isset($inputData['cd_so_bo'])) {
        if (isset($inputData['ghi_chu'])) {
            $updateFields[] = "GhiChu = :ghi_chu";
            $params[':ghi_chu'] = trim($inputData['ghi_chu']);
        }
        if (isset($inputData['ly_do_kham'])) {
            $updateFields[] = "LyDoKham = :ly_do_kham";
            $params[':ly_do_kham'] = trim($inputData['ly_do_kham']);
        }
        if (isset($inputData['trieu_chung'])) {
            $updateFields[] = "TrieuChung = :trieu_chung";
            $params[':trieu_chung'] = trim($inputData['trieu_chung']);
        }
        if (isset($inputData['tien_su_benh'])) {
            $updateFields[] = "TienSuBenh = :tien_su_benh";
            $params[':tien_su_benh'] = trim($inputData['tien_su_benh']);
        }
        if (isset($inputData['chan_doan'])) {
            $updateFields[] = "ChanDoan = :chan_doan";
            $params[':chan_doan'] = trim($inputData['chan_doan']);
        }
        if (isset($inputData['cd_so_bo'])) {
            $updateFields[] = "ChanDoanSoBo = :cd_so_bo";
            $params[':cd_so_bo'] = trim($inputData['cd_so_bo']);
        }
        if (isset($inputData['loi_dan_bs'])) {
            $updateFields[] = "LoiDanBS = :loi_dan_bs";
            $params[':loi_dan_bs'] = trim($inputData['loi_dan_bs']);
        }
    }

    if (count($updateFields) > 0) {
        $updateFields[] = "MaBacSi = :ma_nhan_vien_update";
        $updateFields[] = "MaChuyenKhoa = :ma_chuyen_khoa_update";
        $updateFields[] = "NgayCapNhat = NOW()";
        $updateFields[] = "NgayKham = CURRENT_DATE()";
        $updateFields[] = "GioKham = CURRENT_TIME()";
        
        $params[':ma_nhan_vien_update'] = (int)$maNhanVien;
        $params[':ma_chuyen_khoa_update'] = $maChuyenKhoa !== null ? (int)$maChuyenKhoa : null;

        $sql_update = "UPDATE PHIEUKHAM SET " . implode(", ", $updateFields) . " WHERE MaPhieuKham = :ma_phieu_kham";
        
        $stmt = $pdo->prepare($sql_update);
        $stmt->execute($params);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Cập nhật hồ sơ bệnh án thành công!']);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Lỗi xử lý hệ thống: ' . $e->getMessage()]);
}