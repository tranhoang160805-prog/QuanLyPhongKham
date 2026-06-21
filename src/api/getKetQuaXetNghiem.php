<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';
function json_response($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$maNVThucHien = isset($_SESSION['user_code']) ? (int)$_SESSION['user_code'] : 0;

// 1. XỬ LÝ LƯU KẾT QUẢ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_results') {
    $maChiDinh = isset($_POST['ma_chi_dinh']) ? (int)$_POST['ma_chi_dinh'] : 0;
    $maPhieuKham = isset($_POST['ma_phieu_kham']) ? (int)$_POST['ma_phieu_kham'] : 0;
    $maLoaiCLS = isset($_POST['ma_loai_cls']) ? (int)$_POST['ma_loai_cls'] : 0;
    $ketLuan = isset($_POST['ket_luan']) ? trim($_POST['ket_luan']) : '';
    
    if ($maChiDinh <= 0 || $maPhieuKham <= 0) {
        json_response('error', 'Mã chỉ định hoặc phiếu khám không hợp lệ.');
    }

    $uploaded_file = null;
    $ketQuaText = '';
    
    $is_imaging = in_array($maLoaiCLS, [3, 4, 6, 7]);
    if ($is_imaging) {
        if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file_upload']['tmp_name'];
            $fileName = $_FILES['file_upload']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'dicom'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = 'cls_' . $maChiDinh . '_' . time() . '_' . md5(uniqid()) . '.' . $fileExtension;
                $uploadFileDir = 'uploads/ket-qua-cls/';
                
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }
                
                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $uploaded_file = $newFileName;
                } else {
                    json_response('error', 'Lỗi khi di chuyển tệp tải lên thư mục lưu trữ.');
                }
            } else {
                json_response('error', 'Định dạng tệp không được hỗ trợ (chỉ cho phép JPG, PNG, GIF, PDF, DICOM).');
            }
        } else {
            $uploaded_file = isset($_POST['existing_file']) ? trim($_POST['existing_file']) : null;
        }
    }
    
    if ($maLoaiCLS == 1) {
        $glucose = trim($_POST['blood_glucose'] ?? '');
        $cholesterol = trim($_POST['blood_cholesterol'] ?? '');
        $ast = trim($_POST['blood_ast'] ?? '');
        $alt = trim($_POST['blood_alt'] ?? '');
        $ketQuaText = "Glucose: {$glucose} mmol/L\nCholesterol: {$cholesterol} mmol/L\nAST (GOT): {$ast} U/L\nALT (GPT): {$alt} U/L";
    } elseif ($maLoaiCLS == 2) {
        $urine_ph = trim($_POST['urine_ph'] ?? '');
        $urine_sg = trim($_POST['urine_sg'] ?? '');
        $urine_protein = trim($_POST['urine_protein'] ?? '');
        $urine_glucose = trim($_POST['urine_glucose'] ?? '');
        $urine_rbc = trim($_POST['urine_rbc'] ?? '');
        $ketQuaText = "pH: {$urine_ph}\nTỷ trọng: {$urine_sg}\nProtein: {$urine_protein} mg/dL\nGlucose: {$urine_glucose} mg/dL\nHồng cầu: {$urine_rbc} /vi trường";
    } else {
        $ketQuaText = isset($_POST['ket_qua_text']) ? trim($_POST['ket_qua_text']) : '';
    }
    
    try {
        $pdo->beginTransaction();
        
        $stmtInsertKq = $pdo->prepare("
            INSERT INTO ketquacls (MaChiDinh, KetQuaText, KetLuan, FileKetQua, MaNVThucHien, NgayThucHien) 
            VALUES (:maChiDinh, :ketQuaText, :ketLuan, :fileKetQua, :maNVThucHien, NOW())
            ON DUPLICATE KEY UPDATE 
                KetQuaText = VALUES(KetQuaText),
                KetLuan = VALUES(KetLuan),
                FileKetQua = IF(VALUES(FileKetQua) IS NULL, FileKetQua, VALUES(FileKetQua)),
                MaNVThucHien = VALUES(MaNVThucHien),
                NgayThucHien = NOW()
        ");
        $stmtInsertKq->execute([
            'maChiDinh' => $maChiDinh,
            'ketQuaText' => $ketQuaText,
            'ketLuan' => $ketLuan,
            'fileKetQua' => $uploaded_file,
            'maNVThucHien' => $maNVThucHien > 0 ? $maNVThucHien : null
        ]);
        
        $stmtUpdateCd = $pdo->prepare("UPDATE CHIDINHCLS SET trangthai = 2 WHERE MaChiDinh = ?");
        $stmtUpdateCd->execute([$maChiDinh]);
        
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM CHIDINHCLS WHERE MaPhieuKham = ? AND trangthai < 2");
        $stmtCheck->execute([$maPhieuKham]);
        $remaining = (int)$stmtCheck->fetchColumn();
        
        if ($remaining === 0) {
            $stmtUpdatePk = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 6, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
            $stmtUpdatePk->execute([$maPhieuKham]);
        }
        
        $pdo->commit();
        $_SESSION['toast_success'] = "Lưu kết quả xét nghiệm thành công!";
        json_response('success', 'Lưu kết quả thành công');
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        json_response('error', 'Lỗi khi lưu dữ liệu kết quả: ' . $e->getMessage());
    }
}

// 2. LẤY TOÀN BỘ DANH SÁCH CHỈ ĐỊNH ĐANG THỰC HIỆN (GET)
try {
    $fetchSql = "
        SELECT 
            cd.MaChiDinh,
            cd.MaPhieuKham,
            cd.MoTaChiDinh,
            cd.NgayChiDinh,
            bn.HoTen,
            bn.NgaySinh,
            lcls.TenLoaiCLS,
            pk.MaPhieuKhamCode
        FROM CHIDINHCLS cd
        JOIN PHIEUKHAM pk ON pk.MaPhieuKham = cd.MaPhieuKham
        JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
        JOIN LOAICLSN lcls ON lcls.MaLoaiCLS = cd.MaLoaiCLS
        WHERE cd.trangthai = 1
        ORDER BY cd.NgayChiDinh DESC
    ";
    $stmtFetch = $pdo->query($fetchSql);
    $executing_tests = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);
    
    json_response('success', 'Tải danh sách thành công', $executing_tests);
} catch (PDOException $e) {
    json_response('error', 'Lỗi tải danh sách chỉ định: ' . $e->getMessage());
}