<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

if (!isset($_SESSION['pending_uid'])) { header('Location: register.php'); exit(); }

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    $uid = $_SESSION['pending_uid'];

    $stmt = $pdo->prepare("SELECT MaTaiKhoan FROM taikhoan WHERE MaTaiKhoan = ? AND otp_code = ? AND otp_expires_at > NOW()");
    $stmt->execute([$uid, $otp]);
    
    if ($stmt->fetch()) {
        $pdo->prepare("UPDATE taikhoan SET is_verified = 1, otp_code = NULL WHERE MaTaiKhoan = ?")->execute([$uid]);
        $pdo->prepare("INSERT INTO taikhoan_vaitro (MaTaiKhoan, MaVaiTro) VALUES (?, 7)")->execute([$uid]);
        unset($_SESSION['pending_uid']);
        header('Location: login.php?registered=1');
        exit();
    } else {
        $message = "Mã OTP sai hoặc đã hết hạn!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác thực OTP - Hương Sơn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#242424] flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">Xác thực OTP</h2>
        <p class="text-sm text-gray-600 mb-6 text-center">Vui lòng kiểm tra email của bạn để lấy mã OTP.</p>
        <?php if($message) echo "<p class='text-red-500 text-sm mb-4 text-center'>$message</p>"; ?>
        <form method="POST" class="space-y-4">
            <input type="text" name="otp" placeholder="Nhập 6 chữ số" class="w-full border border-gray-300 p-3 rounded-md text-center tracking-widest text-lg" required>
            <button type="submit" class="w-full bg-[#1b61b5] text-white py-3 rounded-md font-semibold hover:bg-blue-700">Xác nhận</button>
        </form>
    </div>
</body>
</html>