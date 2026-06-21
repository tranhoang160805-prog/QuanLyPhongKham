<?php
/**
 * API: saveBenhNhanTiepNhan.php
 * Chức năng:
 * - Thêm bệnh nhân mới HOẶC cập nhật bệnh nhân cũ
 * - Tiếp nhận phiếu khám đặt trước (Trạng thái từ 9 chuyển sang 2)
 * - Đồng bộ thông tin lâm sàng (Dị ứng, Nhóm máu) và thông tin cá nhân bệnh nhân
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Content-Type: application/json; charset=UTF-8");

// Thiết lập múi giờ Việt Nam để tránh lệch giờ khi lưu GioTiepNhan và sinh mã phiếu khám
date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$idNguoiXuLy = $_SESSION['user_id'] ?? 113;

// =====================================================================
// 1. ĐỌC VÀ VALIDATE DỮ LIỆU ĐẦU VÀO
// =====================================================================
$inputData = json_decode(file_get_contents("php://input"), true);

if (!$inputData) {
    echo json_encode([
        "success" => false,
        "message" => "Không nhận được dữ liệu hợp lệ từ biểu mẫu tiếp nhận."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Lấy đầy đủ các dữ liệu từ payload hàm handleConfirmTiepNhanPK() gửi lên
$maPhieuKham = !empty($inputData['maPhieuKham']) ? (int)$inputData['maPhieuKham'] : null;
$maBenhNhan  = !empty($inputData['maBenhNhan'])  ? (int)$inputData['maBenhNhan']  : null;
$hoTen       = !empty($inputData['hoTen'])       ? trim($inputData['hoTen'])       : null;
$sdt         = !empty($inputData['sdt'])         ? trim($inputData['sdt'])         : null;
$ngaySinh    = !empty($inputData['ngaySinh'])    ? $inputData['ngaySinh']          : null;
$gioiTinh    = !empty($inputData['gioiTinh'])    ? trim($inputData['gioiTinh'])    : 'M';
$cccd        = !empty($inputData['cccd'])        ? trim($inputData['cccd'])        : null;
$diaChi      = !empty($inputData['diaChi'])      ? trim($inputData['diaChi'])      : null;
$diUng       = !empty($inputData['diUng'])       ? trim($inputData['diUng'])       : 'Không';
$ghiChu      = !empty($inputData['ghiChu'])      ? trim($inputData['ghiChu'])      : null;
$soBhyt      = !empty($inputData['BHYT'])        ? trim($inputData['BHYT'])        : null;
$nhomMau     = !empty($inputData['nhomMau'])     ? trim($inputData['nhomMau'])     : 'Chưa xác định';
$email       = !empty($inputData['email'])       ? trim($inputData['email'])       : null;

// Kiểm tra các trường bắt buộc để đảm bảo hồ sơ bệnh nhân đầy đủ hợp lệ
if (empty($hoTen) || empty($sdt) || empty($cccd)) {
    echo json_encode([
        "success" => false,
        "message" => "Vui lòng nhập đầy đủ các trường bắt buộc: Họ tên, Số điện thoại và Số CCCD."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// =====================================================================
// 2. XỬ LÝ TRONG TRANSACTION ĐỂ ĐẢM BẢO AN TOÀN DỮ LIỆU
// =====================================================================
try {
    if (!isset($pdo)) {
        throw new Exception("Hệ thống mất kết nối Database.");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    $maBenhNhanTarget = $maBenhNhan;

    // -----------------------------------------------------------------
    // BƯỚC 1: Xác định bệnh nhân Target nếu payload chưa gán sẵn mã ID
    // -----------------------------------------------------------------
    if (!$maBenhNhanTarget && !empty($cccd)) {
        $stmt = $pdo->prepare("SELECT MaBenhNhan FROM BENHNHAN WHERE CCCD = ? LIMIT 1");
        $stmt->execute([$cccd]);
        $maBenhNhanTarget = $stmt->fetchColumn();
    }

    if (!$maBenhNhanTarget && !empty($sdt)) {
        $stmt = $pdo->prepare("SELECT MaBenhNhan FROM BENHNHAN WHERE SoDienThoai = ? LIMIT 1");
        $stmt->execute([$sdt]);
        $maBenhNhanTarget = $stmt->fetchColumn();
    }

    // -----------------------------------------------------------------
    // BƯỚC 2: INSERT hoặc UPDATE bảng BENHNHAN
    // -----------------------------------------------------------------
    if ($maBenhNhanTarget) {
        // Cập nhật thông tin hành chính & nhóm máu của bệnh nhân cũ tại quầy tiếp đón
        $stmtU = $pdo->prepare("
            UPDATE BENHNHAN
            SET CCCD = ?, HoTen = ?, NgaySinh = ?, GioiTinh = ?, SoDienThoai = ?,
                Email = ?, DiaChi = ?, SoBHYT = ?, NhomMau = ?, DiUng = ?,
                NgayCapNhat = NOW()
            WHERE MaBenhNhan = ?
        ");
        $stmtU->execute([
            $cccd, $hoTen, $ngaySinh, $gioiTinh, $sdt,
            $email, $diaChi, $soBhyt, $nhomMau, $diUng,
            $maBenhNhanTarget
        ]);
    } else {
        // Thêm mới hồ sơ nếu bệnh nhân chưa từng có dữ liệu trên hệ thống phòng khám
        $stmtI = $pdo->prepare("
            INSERT INTO BENHNHAN
                (CCCD, HoTen, NgaySinh, GioiTinh, SoDienThoai, Email, DiaChi, SoBHYT, NhomMau, DiUng, TrangThai, NgayTao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
        ");
        $stmtI->execute([
            $cccd, $hoTen, $ngaySinh, $gioiTinh, $sdt,
            $email, $diaChi, $soBhyt, $nhomMau, $diUng
        ]);

        $maBenhNhanTarget = (int)$pdo->lastInsertId();

        // Định dạng sinh mã hiển thị (Ví dụ: BN00123)
        $maBNStr = "BN" . str_pad($maBenhNhanTarget, 5, "0", STR_PAD_LEFT);
        $stmtCode = $pdo->prepare("UPDATE BENHNHAN SET MaBN = ? WHERE MaBenhNhan = ?");
        $stmtCode->execute([$maBNStr, $maBenhNhanTarget]);
    }

    // -----------------------------------------------------------------
    // BƯỚC 3: XỬ LÝ TIẾP NHẬN PHIẾU KHÁM ĐẶT TRƯỚC (MaTrangThai: 9 -> 2)
    // -----------------------------------------------------------------
    if ($maPhieuKham) {
        // Kiểm tra xem phiếu khám này có thực sự tồn tại và đang ở trạng thái Chờ tiếp nhận (9) không
        $sqlCheck = "SELECT MaTrangThai FROM PHIEUKHAM WHERE MaPhieuKham = ?";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([$maPhieuKham]);
        $currentStatus = $stmtCheck->fetchColumn();

        if ($currentStatus === false) {
            throw new Exception("Phiếu khám số #{$maPhieuKham} không tồn tại trên hệ thống.");
        }
        
        if ((int)$currentStatus !== 9) {
            throw new Exception("Phiếu khám này đã được xử lý tiếp nhận hoặc hủy bỏ trước đó.");
        }

        // Thực hiện lệnh SET MaTrangThai = 2, đồng bộ MaBenhNhan, cập nhật DiUng và lưu giờ tiếp nhận thực tế
        $sql = "UPDATE PHIEUKHAM 
                SET MaBenhNhan = :maBenhNhan,
                    MaTrangThai = 2,
                    GioTiepNhan = CURTIME(), 
                    NgayCapNhat = NOW()
                WHERE MaPhieuKham = :maPhieuKham";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':maBenhNhan'  => $maBenhNhanTarget,
            ':maPhieuKham' => $maPhieuKham
        ]);
    } else {
        // Logic bổ sung: Nếu quầy Lễ tân muốn tạo một phiếu khám mới hoàn toàn trực tiếp tại chỗ (Vãng lai)
        // Bạn có thể gọi hàm generatePhieuKhamCode($pdo) ở đây nếu cần thiết thiết lập
    }

    // Xác nhận hoàn tất thành công lưu trữ CSDL
    $pdo->commit();

    // Phản hồi chuỗi dữ liệu JSON hoàn hảo về cho Frontend JavaScript xử lý hiển thị giao diện
    echo json_encode([
        "success" => true,
        "message" => "Tiếp nhận bệnh nhân và cập nhật thông tin vào hệ thống phòng khám thành công!",
        "data" => [
            "maBenhNhan"   => $maBenhNhanTarget,
            "maPhieuKham"  => $maPhieuKham
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Thu hồi (Rollback) toàn bộ dữ liệu lại nếu xuất hiện lỗi tính toán hoặc ràng buộc SQL dữ liệu
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        "success" => false,
        "message" => "Lỗi xử lý hệ thống: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Hàm sinh mã số thứ tự tự tăng theo ngày, reset mỗi ngày mới phục vụ tạo phiếu vãng lai
 */
function generatePhieuKhamCode(PDO $pdo): array
{
    $today = date('Ymd');
    $prefix = "PK{$today}";

    $stmt = $pdo->prepare("
        SELECT COUNT(*) as total
        FROM PHIEUKHAM
        WHERE MaPhieuKhamCode LIKE ?
    ");
    $stmt->execute(["{$prefix}%"]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stt             = (int)($row['total'] ?? 0) + 1;
    $sttNgay         = str_pad($stt, 3, '0', STR_PAD_LEFT);
    $maPhieuKhamCode = $prefix . $sttNgay;

    $stmtCheck = $pdo->prepare("SELECT MaPhieuKhamCode FROM PHIEUKHAM WHERE MaPhieuKhamCode = ?");
    $stmtCheck->execute([$maPhieuKhamCode]);
    if ($stmtCheck->fetch()) {
        $maPhieuKhamCode = $prefix . $sttNgay . rand(10, 99);
    }

    return [$maPhieuKhamCode, $sttNgay];
}