<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

// Kiểm tra trạng thái đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập hệ thống.']);
    exit;
}

$maTaiKhoan = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

// --- 1. HÀM LẤY THÔNG TIN TÀI KHOẢN & BỆNH NHÂN (GET) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get_profile') {
    try {
        $stmt = $pdo->prepare("
            SELECT t.TenDangNhap, t.SoDienThoai AS SdtTaiKhoan, t.DangHoatDong,
                   b.MaBN, b.HoTen, b.CCCD, b.NgaySinh, b.GioiTinh, b.SoDienThoai AS SDTBenhNhan, 
                   b.Email, b.DiaChi, b.SoBHYT, b.NhomMau, b.DiUng
            FROM TAIKHOAN t
            LEFT JOIN BENHNHAN b ON t.MaTaiKhoan = b.MaTaiKhoan
            WHERE t.MaTaiKhoan = ?
        ");
        $stmt->execute([$maTaiKhoan]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy dữ liệu hồ sơ.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối DB: ' . $e->getMessage()]);
    }
    exit;
}

// --- 2. HÀM CẬP NHẬT THÔNG TIN HỒ SƠ (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update_profile') {
    $input = json_decode(file_get_contents('php://input'), true);

    $hoTen = trim($input['HoTen'] ?? '');
    $cccd = trim($input['CCCD'] ?? '');
    $ngaySinh = trim($input['NgaySinh'] ?? '');
    $gioiTinh = trim($input['GioiTinh'] ?? 'O');
    $sdt = trim($input['SoDienThoai'] ?? '');
    $email = trim($input['Email'] ?? '');
    $diaChi = trim($input['DiaChi'] ?? '');
    $soBHYT = trim($input['SoBHYT'] ?? '');
    $nhomMau = trim($input['NhomMau'] ?? '');
    $diUng = trim($input['DiUng'] ?? '');

    if (empty($hoTen)) {
        echo json_encode(['status' => 'error', 'message' => 'Họ tên không được để trống.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Kiểm tra xem người dùng có truyền số điện thoại mới lên không
        if (empty($sdt)) {
            $stmtCheck = $pdo->prepare("SELECT SoDienThoai FROM BENHNHAN WHERE MaTaiKhoan = ?");
            $stmtCheck->execute([$maTaiKhoan]);
            $currentData = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            $sdt = $currentData['SoDienThoai'] ?? null; 
        }

        // Chuyển sang giá trị NULL thay vì chuỗi rỗng '' để tránh lỗi UNIQUE của MySQL
        $sdtValue = !empty($sdt) ? $sdt : null;
        $cccdValue = !empty($cccd) ? $cccd : null; // <-- BỔ SUNG: Chuẩn hóa dữ liệu CCCD rỗng về NULL
        $ngaySinhValue = !empty($ngaySinh) ? $ngaySinh : null;

        // 2. Cập nhật bảng BENHNHAN (Đã thêm trường CCCD = ? vào câu lệnh UPDATE)
        $stmt = $pdo->prepare("
            UPDATE BENHNHAN 
            SET HoTen = ?, CCCD = ?, NgaySinh = ?, GioiTinh = ?, SoDienThoai = ?, Email = ?, DiaChi = ?, SoBHYT = ?, NhomMau = ?, DiUng = ?, NgayCapNhat = NOW()
            WHERE MaTaiKhoan = ?
        ");
        $stmt->execute([$hoTen, $cccdValue, $ngaySinhValue, $gioiTinh, $sdtValue, $email, $diaChi, $soBHYT, $nhomMau, $diUng, $maTaiKhoan]);

        // 3. Chỉ cập nhật sang bảng TAIKHOAN khi biến số điện thoại có dữ liệu hợp lệ
        if (!empty($sdtValue)) {
            $stmt2 = $pdo->prepare("UPDATE TAIKHOAN SET SoDienThoai = ? WHERE MaTaiKhoan = ?");
            $stmt2->execute([$sdtValue, $maTaiKhoan]);
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin thành công!']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        
        // Bắt lỗi trùng lặp (Mã 23000) nhằm hiển thị thông báo chi tiết cho cả SĐT hoặc CCCD
        if ($e->getCode() == 23000) {
            // Kiểm tra chuỗi thông báo lỗi từ hệ thống để phân biệt trùng CCCD hay Số điện thoại
            if (strpos($e->getMessage(), 'CCCD') !== false) {
                echo json_encode(['status' => 'error', 'message' => 'Số Căn cước công dân (CCCD) này đã được đăng ký bởi người khác.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Số điện thoại này đã được sử dụng bởi một tài khoản khác.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
        }
    }
    exit;
}

// --- 3. HÀM ĐỔI MẬT KHẨU (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'change_password') {
    $input = json_decode(file_get_contents('php://input'), true);

    $oldPass = $input['old_password'] ?? '';
    $newPass = $input['new_password'] ?? '';
    $confirmPass = $input['confirm_password'] ?? '';

    if (empty($oldPass) || empty($newPass) || empty($confirmPass)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ các trường mật khẩu.']);
        exit;
    }

    if ($newPass !== $confirmPass) {
        echo json_encode(['status' => 'error', 'message' => 'Xác nhận mật khẩu mới không trùng khớp.']);
        exit;
    }

    try {
        // Lấy Hash mật khẩu cũ hiện tại trong DB
        $stmt = $pdo->prepare("SELECT MatKhauHash FROM TAIKHOAN WHERE MaTaiKhoan = ?");
        $stmt->execute([$maTaiKhoan]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($oldPass, $user['MatKhauHash'])) {
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu cũ không chính xác.']);
            exit;
        }

        // Tạo chuỗi Hash mới và cập nhật
        $newHash = password_hash($newPass, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE TAIKHOAN SET MatKhauHash = ? WHERE MaTaiKhoan = ?");
        $updateStmt->execute([$newHash, $maTaiKhoan]);

        echo json_encode(['status' => 'success', 'message' => 'Thay đổi mật khẩu thành công!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit;
}

// Trả về lỗi nếu action không hợp lệ
echo json_encode(['status' => 'error', 'message' => 'Yêu cầu hành động không hợp lệ.']);