<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$inputData = json_decode(file_get_contents("php://input"), true);

if (!$inputData) {
    echo json_encode(["success" => false, "message" => "Dữ liệu đầu vào không hợp lệ."], JSON_UNESCAPED_UNICODE);
    exit;
}

$maBenhNhan = !empty($inputData['maBenhNhan']) ? (int)$inputData['maBenhNhan'] : null;
$ghiChu     = !empty($inputData['ghiChu'])     ? trim($inputData['ghiChu'])     : null;
$gioTiepNhan      = isset($inputData['gioTiepNhan']) ? $inputData['gioTiepNhan'] : null;
$maTrangThai = !empty($inputData['maTrangThai']) ? (int)$inputData['maTrangThai'] : 2;

if (!$maBenhNhan) {
    echo json_encode([
        "success" => false,
        "message" => "Vui lòng cung cấp mã bệnh nhân."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if (!isset($pdo)) {
        throw new Exception("Mất kết nối Database.");
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    // 1. Kiểm tra bệnh nhân tồn tại và đang hoạt động
    $stmtBN = $pdo->prepare("SELECT MaBenhNhan, HoTen FROM BENHNHAN WHERE MaBenhNhan = ?");
    $stmtBN->execute([$maBenhNhan]);
    $bnRow = $stmtBN->fetch(PDO::FETCH_ASSOC);
    if (!$bnRow) {
        throw new Exception("Bệnh nhân không tồn tại hoặc hồ sơ đã bị vô hiệu hóa.");
    }

    // 2. Sinh mã phiếu khám dựa trên bộ đếm số lượng bản ghi trong ngày (STT tự tăng)
    list($maPhieuKhamCode, $sttNgay) = generatePhieuKhamCode($pdo);
    
    // 3. Thực hiện Insert dữ liệu vào bảng PHIEUKHAM
    // PDO sẽ tự động chuyển biến mang giá trị PHP null thành từ khóa NULL chuẩn trong MySQL dữ liệu của Bệnh nhân đặt lịch
    $stmtPK = $pdo->prepare("
        INSERT INTO PHIEUKHAM
            (STT, MaPhieuKhamCode, MaBenhNhan, MaChuyenKhoa, MaBacSi, MaLichHen, LyDoKham, MaTrangThai, NgayTao, NgayCapNhat, GioTiepNhan)
        VALUES (?, ?, ?, NULL, NULL, NULL, ?, ?, NOW(), NOW(), ?)
    ");
    $stmtPK->execute([$sttNgay, $maPhieuKhamCode, $maBenhNhan, $ghiChu, $maTrangThai, $gioTiepNhan]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Tạo thành công phiếu khám bước đầu cho bệnh nhân: " . $bnRow['HoTen'],
        "data" => [
            "maBenhNhan"      => $maBenhNhan,
            "maPhieuKhamCode" => $maPhieuKhamCode,
            "sttNgay"         => $sttNgay,
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        "success" => false,
        "message" => "Lỗi xử lý hệ thống: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}


/**
 * Sinh mã phiếu khám theo ngày với STT tự tăng
 * Format: PK + YYYYMMDD + 001/002/...
 * Reset về 001 mỗi ngày mới
 */
function generatePhieuKhamCode(PDO $pdo): array
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $today  = date('Ymd');
    $prefix = "PK{$today}";

    // Tính tổng số phiếu khám đã tạo trong ngày hôm nay để lấy số thứ tự tiếp theo
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM PHIEUKHAM WHERE MaPhieuKhamCode LIKE ?");
    $stmt->execute(["{$prefix}%"]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stt             = (int)($row['total'] ?? 0) + 1;
    $sttNgay         = str_pad($stt, 3, '0', STR_PAD_LEFT);
    $maPhieuKhamCode = $prefix . $sttNgay;

    // Kiểm tra chống race-condition (tránh trùng lặp mã khi hai máy bấm cùng giây)
    $stmtChk = $pdo->prepare("SELECT 1 FROM PHIEUKHAM WHERE MaPhieuKhamCode = ?");
    $stmtChk->execute([$maPhieuKhamCode]);
    if ($stmtChk->fetch()) {
        $maPhieuKhamCode .= rand(10, 99);
    }

    return [$maPhieuKhamCode, $sttNgay];
}