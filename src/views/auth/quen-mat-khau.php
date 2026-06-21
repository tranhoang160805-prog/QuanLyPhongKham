<?php
session_start();
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/otp-handler.php'; // Đảm bảo gọi hàm sendOTPEmail

// BƯỚC 1: Kiểm tra Email và tìm thông tin tài khoản
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'validate_email') {
    ob_start();
    header('Content-Type: application/json');
    
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập địa chỉ Email.']);
        exit();
    }

    try {
        // Tìm tài khoản dựa trên Email liên kết ở bảng benhnhan
        $stmt = $pdo->prepare("SELECT tk.TenDangNhap, tk.SoDienThoai FROM taikhoan tk 
                               INNER JOIN benhnhan bn ON tk.MaTaiKhoan = bn.MaTaiKhoan 
                               WHERE bn.Email = ?");
        $stmt->execute([$email]);
        $account = $stmt->fetch();

        if (!$account) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Email này không tồn tại trên hệ thống.']);
            exit();
        }

        // Hàm che giấu thông tin hiển thị bảo mật dạng ***xxx
        function maskInfo($string, $type = 'username') {
            $len = mb_strlen($string, 'UTF-8');
            if ($type === 'phone') {
                return substr($string, 0, 3) . str_repeat('*', $len - 6) . substr($string, -3);
            }
            if ($len <= 3) return str_repeat('*', $len);
            return substr($string, 0, 2) . str_repeat('*', $len - 4) . substr($string, -2);
        }

        // Lưu tạm Tên đăng nhập vào session để các bước sau đối chiếu phục vụ Update DB
        $_SESSION['reset_password_username'] = $account['TenDangNhap'];

        ob_clean();
        echo json_encode([
            'status' => 'success', 
            'message' => 'Tìm thấy tài khoản.',
            'data' => [
                'username' => maskInfo($account['TenDangNhap'], 'username'),
                'raw_username' => $account['TenDangNhap'],
                'phone' => maskInfo($account['SoDienThoai'], 'phone'),
                'email' => $email
            ]
        ]);
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
    exit();
}

// BƯỚC 3: Tạo mã OTP, lưu trực tiếp vào bảng taikhoan và gửi Email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate_and_send_otp') {
    ob_start();
    header('Content-Type: application/json');
    
    $username = $_SESSION['reset_password_username'] ?? null;
    $email = trim($_POST['email'] ?? '');

    if (!$username || empty($email)) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Phiên làm việc không hợp lệ hoặc thiếu dữ liệu.']);
        exit();
    }

    $otp = rand(100000, 999999);
    $expires_at = date('Y-m-d H:i:s', time() + (5 * 60)); // Hết hạn sau 5 phút

    try {
        // Cập nhật mã OTP và thời gian hết hạn trực tiếp vào tài khoản đang yêu cầu
        $updateOtpStmt = $pdo->prepare("UPDATE taikhoan SET otp_code = ?, otp_expires_at = ? WHERE TenDangNhap = ?");
        $updateOtpStmt->execute([$otp, $expires_at, $username]);

        // Tiến hành gửi Email chứa mã OTP
        if (sendOTPEmail($email, $otp)) {
            ob_clean();
            echo json_encode(['status' => 'success', 'message' => 'Mã OTP khôi phục đã được gửi thành công đến Email của bạn!']);
        } else {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Không thể gửi mã xác thực đến Email.']);
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống ghi nhận OTP: ' . $e->getMessage()]);
    }
    exit();
}

// BƯỚC 4b: Xử lý cập nhật mật khẩu mới sau khi mã xác thực đã khớp ở bảng taikhoan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'final_reset_password') {
    ob_start();
    header('Content-Type: application/json');
    
    $username = $_SESSION['reset_password_username'] ?? null;
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';

    if (!$username) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ hoặc phiên khôi phục đã hết hạn.']);
        exit();
    }

    if (empty($password)) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mật khẩu mới.']);
        exit();
    }

    if ($password !== $repassword) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu xác nhận không trùng khớp.']);
        exit();
    }

    // Kiểm tra độ mạnh mật khẩu phía Back-end
    $uppercase    = preg_match('@[A-Z]@', $password);
    $lowercase    = preg_match('@[a-z]@', $password);
    $number       = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không đạt độ mạnh yêu cầu.']);
        exit();
    }

    try {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu mới, đồng thời kích hoạt lại trạng thái verified và xóa trắng dữ liệu OTP đã dùng
        $updateStmt = $pdo->prepare("UPDATE taikhoan SET MatKhauHash = ?, is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE TenDangNhap = ?");
        $updateStmt->execute([$passwordHash, $username]);

        unset($_SESSION['reset_password_username']);

        ob_clean();
        echo json_encode(['status' => 'success', 'message' => 'Khôi phục và đổi mật khẩu thành công!']);
    } catch (Exception $e) {
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
    <title>Quên mật khẩu - Hương Sơn</title>
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
        .hidden-step { display: none !important; }
        
        /* CSS cho hộp thông tin tài khoản ở Bước 2 */
        .account-preview-box {
            background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;
        }
        .preview-item { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem; color: #334155; }
        .preview-item:last-child { margin-bottom: 0; }
        .preview-label { font-weight: 500; color: #64748b; }
        .preview-value { font-weight: 600; color: #1e293b; }
    </style>
</head>
<body class="login-page">

    <div class="login-card shadow-lg" style="height: auto; max-height: 720px;">
        <div class="login-sidebar">
            <h2 class="sidebar-title">PHÒNG KHÁM HƯƠNG SƠN</h2>
            <p class="sidebar-desc">Khôi phục quyền truy cập vào hồ sơ y tế cá nhân và lịch hẹn khám của bạn một cách nhanh chóng.</p>
        </div>

        <div class="login-main" style="overflow-y: auto;">
            <h1 class="form-main-title">QUÊN MẬT KHẨU</h1>

            <form id="emailForm" class="login-form">
                <div class="form-group">
                    <label class="form-label">Nhập Email tài khoản</label>
                    <div class="input-wrapper">
                        <span class="material-symbols-outlined input-icon-left">mail</span>
                        <input type="email" id="resetEmail" name="email" placeholder="example@gmail.com" class="form-input" required>
                    </div>
                </div>
                <button type="submit" id="btnSubmitEmail" class="btn-submit">Kiểm tra tài khoản</button>
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="login.php" style="font-size: 0.85rem; color: #1b61b5; text-decoration: none;">Quay lại Đăng nhập</a>
                </div>
            </form>

            <div id="accountConfirmStep" class="login-form hidden-step">
                <p style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem;">Hệ thống tìm thấy tài khoản liên kết với email này. Vui lòng xác nhận thông tin:</p>
                <div class="account-preview-box">
                    <div class="preview-item">
                        <span class="preview-label">Tên đăng nhập:</span>
                        <span class="preview-value" id="lblUsername">N/A</span>
                    </div>
                    <div class="preview-item">
                        <span class="preview-label">Số điện thoại:</span>
                        <span class="preview-value" id="lblPhone">N/A</span>
                    </div>
                </div>
                <button type="button" id="btnConfirmAndSendOtp" class="btn-submit">Xác nhận & Gửi mã OTP</button>
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="#" onclick="window.location.reload();" style="font-size: 0.85rem; color: #64748b; text-decoration: none;">Nhập lại Email khác</a>
                </div>
            </div>

            <form id="newPasswordForm" class="login-form hidden-step">
                <div class="form-group-row" style="display: flex; gap: 1rem; flex-direction: column;">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
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
                    
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <div class="input-wrapper">
                            <span class="material-symbols-outlined input-icon-left">password</span>
                            <input type="password" name="repassword" id="repassword" placeholder="••••••••" class="form-input" required>
                            <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('repassword', this)">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" id="btnSubmitPassword" class="btn-submit" style="margin-top: 1.5rem;">Cập nhật mật khẩu</button>
            </form>
        </div>
    </div>

    <?php include_once __DIR__ . '../../layouts/alert.php'; ?>
    <?php include_once 'otp-handler.php'; ?>

    <script>
        const emailForm = document.getElementById('emailForm');
        const accountConfirmStep = document.getElementById('accountConfirmStep');
        const newPasswordForm = document.getElementById('newPasswordForm');
        
        const btnSubmitEmail = document.getElementById('btnSubmitEmail');
        const btnConfirmAndSendOtp = document.getElementById('btnConfirmAndSendOtp');
        const btnSubmitPassword = document.getElementById('btnSubmitPassword');
        
        const passwordInput = document.getElementById('password');
        const strengthMeterBar = document.getElementById('strengthMeterBar');
        const strengthText = document.getElementById('strengthText');

        let savedEmail = '';
        let isStrongPassword = false;
        
        // Đặt biến lưu Username gốc ở đây để dùng chung giữa các bước
        let forgotRawUsername = '';

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

        // Đánh giá thời gian thực độ mạnh mật khẩu mới
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

        // HÀM XỬ LÝ BƯỚC 1: Submit tìm kiếm Email liên kết
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            savedEmail = document.getElementById('resetEmail').value.trim();

            btnSubmitEmail.disabled = true;
            btnSubmitEmail.innerHTML = `Đang tìm kiếm...`;

            const formData = new FormData();
            formData.append('action', 'validate_email');
            formData.append('email', savedEmail);

            fetch('', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                btnSubmitEmail.disabled = false;
                btnSubmitEmail.innerText = 'Kiểm tra tài khoản';

                if (data.status === 'success') {
                    // Hiển thị dữ liệu dạng mặt nạ ẩn danh hóa bảo mật
                    document.getElementById('lblUsername').textContent = data.data.username;
                    document.getElementById('lblPhone').textContent = data.data.phone;
                    
                    // LƯU TÊN TÀI KHOẢN GỐC VÀO BIẾN TOÀN CỤC ĐỂ CHUẨN BỊ TRUYỀN CHO OTP MODAL
                    forgotRawUsername = data.data.raw_username; 
                    
                    emailForm.classList.add('hidden-step');
                    accountConfirmStep.classList.remove('hidden-step');
                } else {
                    showAlert(data.message, 'warning');
                }
            })
            .catch(err => {
                btnSubmitEmail.disabled = false;
                btnSubmitEmail.innerText = 'Kiểm tra tài khoản';
                showAlert('Hệ thống xử lý gián đoạn.', 'warning');
            });
        });

        // HÀM XỬ LÝ BƯỚC 2: Xác nhận thông tin tài khoản đúng và kích hoạt gửi OTP vào DB
        btnConfirmAndSendOtp.addEventListener('click', function() {
            btnConfirmAndSendOtp.disabled = true;
            btnConfirmAndSendOtp.innerHTML = `Đang khởi tạo mã & gửi Mail...`;

            const formData = new FormData();
            formData.append('action', 'generate_and_send_otp');
            formData.append('email', savedEmail);

            fetch('', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(otpResult => {
                btnConfirmAndSendOtp.disabled = false;
                btnConfirmAndSendOtp.innerText = 'Xác nhận & Gửi mã OTP';

                if (otpResult.status === 'success') {
                    // TRUYỀN USERNAME THẬT VÀO HÀM ĐỂ MODAL ĐỐI CHIẾU CHÍNH XÁC
                    openOtpModal(forgotRawUsername); 
                    showAlert(otpResult.message, 'success');
                } else {
                    showAlert(otpResult.message, 'warning');
                }
            })
            .catch(err => {
                btnConfirmAndSendOtp.disabled = false;
                btnConfirmAndSendOtp.innerText = 'Xác nhận & Gửi mã OTP';
                showAlert('Không thể kết nối đến máy chủ OTP.', 'warning');
            });
        });

        // LẮNG NGHE SỰ KIỆN TỪ BƯỚC 3: Khi nhập mã OTP chính xác từ Modal gửi ra ngoài
        document.addEventListener('otpSuccess', function(e) {
            showAlert('Xác thực mã OTP thành công! Vui lòng thiết lập mật khẩu mới.', 'success');
            
            // Ẩn bảng thông tin bước 2, bật hiển thị Form đổi mật khẩu mới (Bước 4)
            accountConfirmStep.classList.add('hidden-step');
            newPasswordForm.classList.remove('hidden-step');
        });

        // HÀM XỬ LÝ BƯỚC 4: Submit cập nhật mật khẩu mới hoàn chỉnh
        newPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const pwd = passwordInput.value;
            const repwd = document.getElementById('repassword').value;

            if (!isStrongPassword) {
                showAlert('Mật khẩu yếu! Cần tối thiểu 8 ký tự gồm chữ hoa, chữ thường, số và ký tự đặc biệt.', 'warning');
                passwordInput.focus();
                return;
            }

            if (pwd !== repwd) {
                showAlert('Mật khẩu xác nhận không trùng khớp.', 'warning'); 
                return;
            }

            btnSubmitPassword.disabled = true;
            btnSubmitPassword.innerHTML = `Đang lưu mật khẩu...`;

            const finalData = new FormData(newPasswordForm);
            finalData.append('action', 'final_reset_password');

            fetch('', { method: 'POST', body: finalData })
            .then(res => res.json())
            .then(data => {
                btnSubmitPassword.disabled = false;
                btnSubmitPassword.innerText = 'Cập nhật mật khẩu';

                if (data.status === 'success') {
                    showAlert(data.message + ' Đang chuyển hướng về trang đăng nhập...', 'success');
                    setTimeout(() => { window.location.href = 'login.php'; }, 2000);
                } else {
                    showAlert(data.message, 'warning');
                }
            })
            .catch(err => {
                btnSubmitPassword.disabled = false;
                btnSubmitPassword.innerText = 'Cập nhật mật khẩu';
                showAlert('Gặp lỗi hệ thống khi đồng bộ dữ liệu mật khẩu mới.', 'warning');
            });
        });
    </script>
</body>
</html>