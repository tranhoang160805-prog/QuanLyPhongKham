<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/database.php';
include_once __DIR__ . '/../../../config/site.php';

define('SITE_KEY', '6LeuyAwsAAAAAG6nys7-Q631QKIUx1i1BRRVu0Ph');
define('SECRET_KEY', '6LeuyAwsAAAAABFSO8QGWKO3WXUhFPrDFU7lqq2i'); 

$error = '';

// Đọc thông tin từ Cookie đã lưu (nếu có) để tự động điền vào form
$cookie_username = $_COOKIE['remember_username'] ?? '';
$cookie_password = $_COOKIE['remember_password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token = $_POST['g-recaptcha-response'] ?? '';
    $remember = isset($_POST['remember']); // Kiểm tra xem có tích chọn Ghi nhớ không

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu';
    } else {
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $verify_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret'   => SECRET_KEY,
            'response' => $token
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response);

        if (!$data || !$data->success) {
            $error = 'Xác thực Captcha thất bại, vui lòng thử lại!';
        } else {
            try {
                // Lấy thông tin tài khoản và vai trò của User
                $stmt = $pdo->prepare("SELECT tk.*, tv.MaVaiTro, vt.TenVaiTro 
                                       FROM taikhoan tk 
                                       LEFT JOIN taikhoan_vaitro tv ON tk.MaTaiKhoan = tv.MaTaiKhoan
                                       LEFT JOIN vaitro vt ON tv.MaVaiTro = vt.MaVaiTro
                                       WHERE tk.TenDangNhap = ? OR tk.SoDienThoai = ?");
                $stmt->execute([$username, $username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['MatKhauHash'])) {
                    
                    if ($user['DangHoatDong'] == 0) {
                        $error = 'Tài khoản của bạn đã bị khóa!';
                    } else {
                        // Cập nhật lượt đăng nhập mới
                        $stmtUpdate = $pdo->prepare("UPDATE taikhoan SET LanDangNhapCuoi = NOW(), SoLanSaiMK = 0 WHERE MaTaiKhoan = ?");
                        $stmtUpdate->execute([$user['MaTaiKhoan']]);

                        // Chuẩn hóa phân quyền dựa vào dữ liệu DB
                        $roleMap = [
                            'QUAN_LY' => 'admin', 'BENH_NHAN' => 'benh-nhan', 'LE_TAN' => 'le-tan',
                            'DIEU_DUONG' => 'dieu-duong', 'BAC_SI' => 'bac-si', 'KY_THUAT_VIEN' => 'ky-thuat-vien', 'DUOC_SI' => 'duoc-si'
                        ];
                        $currentRole = $roleMap[$user['TenVaiTro']] ?? 'benh-nhan';

                        // --- LOGIC LẤY THÔNG TIN CHI TIẾT TỪNG VAI TRÒ ĐỂ HIỂN THỊ ---
                        $displayName = 'Thành viên';
                        $displayCode = '';

                        if ($currentRole === 'benh-nhan') {
                            $stmtProfile = $pdo->prepare("SELECT MaBN, HoTen FROM benhnhan WHERE MaTaiKhoan = ?");
                            $stmtProfile->execute([$user['MaTaiKhoan']]);
                            $profile = $stmtProfile->fetch(PDO::FETCH_ASSOC);
                            if ($profile) {
                                $displayName = $profile['HoTen'];
                                $displayCode = $profile['MaBN'];
                            }
                        } else {
                            $stmtProfile = $pdo->prepare("SELECT Manhanvien, HoTen FROM nhanvien WHERE MaTaiKhoan = ?");
                            $stmtProfile->execute([$user['MaTaiKhoan']]);
                            $profile = $stmtProfile->fetch(PDO::FETCH_ASSOC);
                            if ($profile) {
                                $displayName = $profile['HoTen'];
                                $displayCode = $profile['Manhanvien'];
                            }
                        }

                        // Xử lý Cookie Ghi nhớ Đăng nhập (Lưu tối đa 3 ngày)
                        if ($remember) {
                            setcookie('remember_username', $username, time() + (3 * 24 * 60 * 60), "/");
                            setcookie('remember_password', $password, time() + (3 * 24 * 60 * 60), "/");
                        } else {
                            // Nếu không chọn, xóa bỏ Cookie cũ
                            setcookie('remember_username', '', time() - 3600, "/");
                            setcookie('remember_password', '', time() - 3600, "/");
                        }

                        // LƯU THÔNG TIN VÀO SESSION
                        $_SESSION['user_id']     = $user['MaTaiKhoan'];
                        $_SESSION['user_role']   = $currentRole;
                        $_SESSION['user_name']   = $displayName; 
                        $_SESSION['user_code']   = $displayCode; 
                        $_SESSION['user_phone']  = $user['SoDienThoai'];
                        
                        // LƯU THỜI GIAN ĐĂNG NHẬP ĐỂ KIỂM TRA PHIÊN 3 NGÀY
                        $_SESSION['login_time']  = time();

                        // Điều hướng về trang chủ
                        header('Location: ../../../index.php');
                        exit;
                    }
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không chính xác';
                }
            } catch (PDOException $e) {
                $error = 'Lỗi hệ thống: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hương Sơn</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <link rel="stylesheet" href="../../../public/assets/css/LayOuts/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body class="login-page">

<?php include_once __DIR__ . '../../layouts/alert.php'; ?>

    <div class="login-card card-container">
        
        <div class="login-sidebar">
            <h2 class="sidebar-title">PHÒNG KHÁM HƯƠNG SƠN</h2>
            <p class="sidebar-desc">Hệ thống quản lý y tế thông minh, nâng cao hiệu quả vận hành và chất lượng phục vụ bệnh nhân tại Hương Sơn Systems.</p>
            
            <div class="sidebar-badge-wrapper">
                <div class="sidebar-badge-inner">
                    <div class="badge-bg-overlay"></div>
                    <div class="badge-blend-overlay"></div>
                    
                    <div class="badge-content-box">
                        <div class="badge-icon-circle">
                            <span class="material-symbols-outlined icon-medical">medical_services</span>
                        </div>
                        <div class="badge-line-short"></div>
                        <div class="badge-skeleton-group">
                            <div class="badge-skeleton-line"></div>
                            <div class="badge-skeleton-line"></div>
                            <div class="badge-skeleton-btn"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-main">

            <h1 class="form-main-title">ĐĂNG NHẬP</h1>

            <form action="" method="POST" class="login-form">
                
                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">person</span>
                        <input type="text" name="username" placeholder="Nhập tên đăng nhập" 
                               value="<?= htmlspecialchars($_POST['username'] ?? $cookie_username) ?>" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">lock</span>
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu" 
                               value="<?= htmlspecialchars($cookie_password) ?>" class="form-input">
                        <span class="material-symbols-outlined input-icon-toggle" onclick="togglePassword()">visibility</span>
                    </div>
                </div>

                <div class="captcha-container">
                    <div class="g-recaptcha" data-sitekey="<?= SITE_KEY ?>"></div>
                </div>

                <div class="form-options">
                    <label class="remember-me-label">
                        <input type="checkbox" name="remember" class="form-checkbox" <?= !empty($cookie_username) ? 'checked' : '' ?>>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="quen-mat-khau.php" class="forgot-password-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Đăng nhập
                </button>
            </form>

            <p class="register-redirect-text">
                Chưa có tài khoản? <a href="dang-ky.php" class="register-link">Đăng ký ngay</a>
            </p>

            <div class="form-footer-copyright">
                <span>© 2026 HOANGPHANDIY. All rights reserved.</span>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.input-icon-toggle');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        <?php if (!empty($error)): ?>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof showAlert === "function") {
                    showAlert('<?= addslashes($error) ?>', 'error');
                } else {
                    console.error("chưa sẵn sàng.");
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>