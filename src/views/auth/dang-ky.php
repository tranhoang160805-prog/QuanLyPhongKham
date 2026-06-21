<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';

// BƯỚC 1: Kiểm tra thông tin và tạo tài khoản tạm (is_verified = 0) vào DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validate_register') {
    ob_start();
    header('Content-Type: application/json');
    
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';

    if (empty($fullname) || empty($phone) || empty($username) || empty($password)) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc.']);
        exit();
    }

    if ($password !== $repassword) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu xác nhận không khớp.']);
        exit();
    }

    // Kiểm tra độ mạnh mật khẩu phía Back-end
    $uppercase    = preg_match('@[A-Z]@', $password);
    $lowercase    = preg_match('@[a-z]@', $password);
    $number       = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không đạt yêu cầu độ mạnh.']);
        exit();
    }

    try {
        // Kiểm tra trùng tên đăng nhập
        $checkUserStmt = $pdo->prepare("SELECT MaTaiKhoan, is_verified FROM taikhoan WHERE TenDangNhap = ?");
        $checkUserStmt->execute([$username]);
        $existingUser = $checkUserStmt->fetch();

        if ($existingUser) {
            if ($existingUser['is_verified'] == 1) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập này đã tồn tại trên hệ thống.']);
                exit();
            } else {
                // Nếu tài khoản trùng nhưng chưa xác thực (bỏ ngang) -> Tiến hành xóa bỏ tài khoản cũ này để đăng ký lại
                $deleteOld = $pdo->prepare("DELETE FROM taikhoan WHERE MaTaiKhoan = ?");
                $deleteOld->execute([$existingUser['MaTaiKhoan']]);
            }
        }

        // Kiểm tra trùng số điện thoại
        $checkPhoneStmt = $pdo->prepare("SELECT MaTaiKhoan, is_verified FROM taikhoan WHERE SoDienThoai = ?");
        $checkPhoneStmt->execute([$phone]);
        $existingPhone = $checkPhoneStmt->fetch();

        if ($existingPhone) {
            if ($existingPhone['is_verified'] == 1) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Số điện thoại này đã được đăng ký.']);
                exit();
            } else {
                $deleteOldPhone = $pdo->prepare("DELETE FROM taikhoan WHERE MaTaiKhoan = ?");
                $deleteOldPhone->execute([$existingPhone['MaTaiKhoan']]);
            }
        }

        // Kiểm tra trùng email (ở bảng bệnh nhân có tài khoản đã kích hoạt)
        if (!empty($email)) {
            $checkEmailStmt = $pdo->prepare("SELECT bn.MaBenhNhan FROM benhnhan bn 
                                           INNER JOIN taikhoan tk ON bn.MaTaiKhoan = tk.MaTaiKhoan 
                                           WHERE bn.Email = ? AND tk.is_verified = 1");
            $checkEmailStmt->execute([$email]);
            if ($checkEmailStmt->fetch()) {
                ob_clean();
                echo json_encode(['status' => 'error', 'message' => 'Địa chỉ email này đã được sử dụng.']);
                exit();
            }
        }

        // Sinh OTP và thời gian hết hạn (5 phút)
        $otp = rand(100000, 999999);
        $otp_expires_at = date('Y-m-d H:i:s', time() + (5 * 60));
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Gọi hàm gửi email từ file otp-handler nếu cần hoặc xử lý riêng qua cờ truyền tải
        // Tạo trước tài khoản lưu thẳng vào database với trạng thái chưa xác thực (is_verified = 0)
        $insertTk = $pdo->prepare("INSERT INTO taikhoan (TenDangNhap, MatKhauHash, SoDienThoai, is_verified, otp_code, otp_expires_at) VALUES (?, ?, ?, 0, ?, ?)");
        $insertTk->execute([$username, $password_hash, $phone, $otp, $otp_expires_at]);

        // Gửi Email chứa mã OTP sang Client thông qua việc gọi helper gửi mail trong otp-handler
        $_POST['email_to_send'] = $email;
        $_POST['otp_to_send'] = $otp;
        
        ob_clean();
        echo json_encode([
            'status' => 'success', 
            'message' => 'Thông tin hợp lệ, đang gửi mã OTP!', 
            'email' => $email, 
            'otp' => $otp
        ]);
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// BƯỚC 3: Xử lý kích hoạt tài khoản và chèn dữ liệu bệnh nhân (Sau khi OTP thông báo thành công)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'final_submit_register') {
    ob_start();
    header('Content-Type: application/json');
    
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');

    try {
        // Tìm kiếm tài khoản vừa xác thực thành công nhưng chưa kích hoạt hoàn toàn
        $stmt = $pdo->prepare("SELECT MaTaiKhoan FROM taikhoan WHERE TenDangNhap = ? AND is_verified = 0");
        $stmt->execute([$username]);
        $tk = $stmt->fetch();

        if (!$tk) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Tài khoản không tồn tại hoặc đã kích hoạt từ trước.']);
            exit();
        }

        $maTaiKhoan = $tk['MaTaiKhoan'];

        $pdo->beginTransaction();

        // Cập nhật trạng thái chính thức kích hoạt cho tài khoản, dọn sạch trường otp
        $updateTk = $pdo->prepare("UPDATE taikhoan SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE MaTaiKhoan = ?");
        $updateTk->execute([$maTaiKhoan]);

        // Thêm dữ liệu vào bảng bệnh nhân tương ứng
        $maBN = 'BN' . date('Ymd') . rand(1000, 9999);
        $insertBn = $pdo->prepare("INSERT INTO benhnhan (MaTaiKhoan, MaBN, HoTen, SoDienThoai, Email) VALUES (?, ?, ?, ?, ?)");
        $insertBn->execute([$maTaiKhoan, $maBN, $fullname, $phone, $email]);

        $pdo->commit();
        
        ob_clean();
        echo json_encode(['status' => 'success', 'message' => 'Đăng ký tài khoản và xác thực thành công!']);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản - Hương Sơn</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <link rel="stylesheet" href="../../../public/assets/css/LayOuts/login.css">
    <style>
        .input-wrapper { position: relative; }
        .form-input { padding-right: 40px !important; }
        .toggle-password-btn {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #9ca3af; cursor: pointer;
            display: flex; align-items: center; justify-content: center; padding: 0; user-select: none;
        }
        .toggle-password-btn:hover { color: #4b5563; }
        .password-strength-container { margin-top: 6px; }
        .strength-meter-bg { height: 4px; width: 100%; background-color: #e5e7eb; border-radius: 2px; overflow: hidden; }
        .strength-meter-bar { height: 100%; width: 0%; transition: width 0.3s ease, background-color 0.3s ease; }
        .strength-feedback-text { font-size: 11px; font-weight: 500; color: #6b7280; margin-top: 4px; display: inline-block; }
    </style>
</head>
<body class="login-page">

    <div class="login-card shadow-lg" style="height: auto; max-height: 720px;">
        <div class="login-sidebar">
            <h2 class="sidebar-title">PHÒNG KHÁM HƯƠNG SƠN</h2>
            <p class="sidebar-desc">Hệ thống quản lý y tế thông minh, nâng cao hiệu quả vận hành và chất lượng phục vụ bệnh nhân tại Hương Sơn Systems.</p>
        </div>

        <div class="login-main" style="overflow-y: auto;">
            <h1 class="form-main-title">ĐĂNG KÝ</h1>

            <form id="registerForm" class="login-form">
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">person_outline</span>
                        <input type="text" name="fullname" placeholder="Nguyễn Văn A" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">phone</span>
                        <input type="text" id="registerPhone" name="phone" placeholder="0123 456 789" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">mail</span>
                        <input type="email" id="registerEmail" name="email" placeholder="example@gmail.com" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">person</span>
                        <input type="text" id="registerUsername" name="username" placeholder="Tên đăng nhập" class="form-input" required>
                    </div>
                </div>

                <div class="form-group-row" style="display: flex; gap: 1rem;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Mật khẩu</label>
                        <div class="input-wrapper">
                            <span class="material-symbols-outlined input-icon-left">lock</span>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="form-input" required>
                            <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('password', this)">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                        <div class="password-strength-container">
                            <div class="strength-meter-bg">
                                <div id="strengthMeterBar" class="strength-meter-bar"></div>
                            </div>
                            <span id="strengthText" class="strength-feedback-text">Mật khẩu chưa nhập</span>
                        </div>
                    </div>
                    
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <div class="input-wrapper">
                            <span class="material-symbols-outlined input-icon-left">password</span>
                            <input type="password" name="repassword" id="repassword" placeholder="••••••••" class="form-input" required>
                            <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('repassword', this)">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-options" style="margin-top: 0.25rem;">
                    <label class="remember-me-label">
                        <input type="checkbox" name="terms" class="form-checkbox" required>
                        <span style="font-size: 0.75rem; color: #6b7280;">Tôi đồng ý với các điều khoản và chính sách.</span>
                    </label>
                </div>

                <button type="submit" id="btnSubmit" class="btn-submit">Đăng ký ngay</button>
            </form>
            <p class="register-redirect-text">
                Bạn đã có tài khoản? <a href="login.php" class="register-link">Đăng nhập ngay</a>
            </p>
        </div>
    </div>

    <?php include_once __DIR__ . '../../layouts/alert.php'; ?>
    <?php include_once 'otp-handler.php'; ?>

    <script>
        const registerForm = document.getElementById('registerForm');
        const btnSubmit = document.getElementById('btnSubmit');
        const passwordInput = document.getElementById('password');
        const strengthMeterBar = document.getElementById('strengthMeterBar');
        const strengthText = document.getElementById('strengthText');

        let isStrongPassword = false;

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }

        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            let score = 0;

            if (val.length === 0) {
                strengthMeterBar.style.width = '0%';
                strengthText.textContent = 'Mật khẩu chưa nhập';
                strengthText.style.color = '#6b7280';
                isStrongPassword = false;
                return;
            }

            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^\w]/.test(val)) score++;

            if (score <= 2) {
                strengthMeterBar.style.width = '33%';
                strengthMeterBar.style.backgroundColor = '#ef4444';
                strengthText.textContent = 'Độ mạnh: Yếu';
                strengthText.style.color = '#ef4444';
                isStrongPassword = false;
            } else if (score <= 4) {
                strengthMeterBar.style.width = '66%';
                strengthMeterBar.style.backgroundColor = '#f59e0b';
                strengthText.textContent = 'Độ mạnh: Trung bình';
                strengthText.style.color = '#f59e0b';
                isStrongPassword = false;
            } else if (score === 5) {
                strengthMeterBar.style.width = '100%';
                strengthMeterBar.style.backgroundColor = '#10b981';
                strengthText.textContent = 'Độ mạnh: Mạnh';
                strengthText.style.color = '#10b981';
                isStrongPassword = true;
            }
        });

        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!isStrongPassword) {
                showAlert('Đăng ký không thành công! Mật khẩu yếu.', 'warning');
                passwordInput.focus();
                return;
            }

            if (passwordInput.value !== document.getElementById('repassword').value) {
                showAlert('Mật khẩu xác nhận không trùng khớp.', 'warning'); 
                return;
            }

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `Đang xử lý...`;

            const formData = new FormData(registerForm);
            formData.append('action', 'validate_register');

            fetch('', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Gọi sang otp-handler để thực hiện gửi mail kích hoạt thực tế
                    const otpData = new FormData();
                    otpData.append('action', 'send_only_email');
                    otpData.append('email', data.email);
                    otpData.append('otp', data.otp);

                    return fetch('otp-handler.php', { method: 'POST', body: otpData });
                } else {
                    throw new Error(data.message);
                }
            })
            .then(res => res ? res.json() : null)
            .then(otpResult => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Đăng ký ngay';

                if (otpResult && otpResult.status === 'success') {
                    const currentRegUser = document.getElementById('registerUsername').value.trim();
                    openOtpModal(currentRegUser);
                    showAlert(otpResult.message, 'success');
                } else if (otpResult) {
                    showAlert(otpResult.message, 'warning');
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Đăng ký ngay';
                showAlert(err.message || 'Hệ thống gián đoạn.', 'warning');
            });
        });

        // Đón nhận tín hiệu thành công từ otp-handler.php tự động bắn ra
        document.addEventListener('otpSuccess', function(e) {
            showAlert('Mã chính xác! Đang khởi tạo tài khoản...', 'success');

            const finalData = new FormData(registerForm);
            finalData.append('action', 'final_submit_register');

            fetch('', { method: 'POST', body: finalData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showAlert(data.message + ' Đang chuyển hướng...', 'success');
                    setTimeout(() => { window.location.href = 'login.php'; }, 2000);
                } else {
                    showAlert(data.message, 'warning');
                }
            })
            .catch(err => {
                showAlert('Lỗi hệ thống khi lưu dữ liệu đăng ký.', 'warning');
            });
        });
    </script>
</body>
</html>