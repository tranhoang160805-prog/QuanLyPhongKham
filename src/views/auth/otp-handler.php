<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../phpmailer/Exception.php';
require_once __DIR__ . '/../../../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailerException;

function sendOTPEmail($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tranhoang160805@gmail.com'; 
        $mail->Password   = 'zqai hxpd xemg oaux';   
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->setFrom('tranhoang160805@gmail.com', 'Hương Sơn');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Mã xác thực tài khoản Hương Sơn';
        $mail->Body    = "Mã xác thực OTP của bạn là: <strong style='font-size: 18px; color: #1b61b5;'>$otp</strong>. Mã có hiệu lực 5 phút.";
        $mail->send();
        return true;
    } catch (Exception $e) { return false; } 
      catch (MailerException $e) { return false; }
}

// BƯỚC 1b: Xử lý gửi duy nhất email khi được dang-ky.php gọi sang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_only_email') {
    ob_start();
    header('Content-Type: application/json');
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    if (sendOTPEmail($email, $otp)) {
        ob_clean();
        echo json_encode(['status' => 'success', 'message' => 'Mã OTP đã được gửi đến email của bạn!']);
    } else {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Không thể gửi email xác thực.']);
    }
    exit(); // Đảm bảo ngắt kết nối hoàn toàn, không lọt xuống HTML
}

// BƯỚC 2: Kiểm tra đối chiếu OTP trực tiếp với DB (Dùng chung cho cả Đăng ký & Quên mật khẩu)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify_otp_code') {
    ob_start();
    header('Content-Type: application/json');
    $otp_input = trim($_POST['otp'] ?? '');
    $username = trim($_POST['username'] ?? '');

    try {
        // Bỏ điều kiện "AND is_verified = 0" để tài khoản đang hoạt động (Quên pass) vẫn tìm thấy được
        $stmt = $pdo->prepare("SELECT MaTaiKhoan, otp_code, otp_expires_at, is_verified FROM taikhoan WHERE TenDangNhap = ?");
        $stmt->execute([$username]);
        $account = $stmt->fetch();

        if (!$account) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Tài khoản không tồn tại trên hệ thống.']);
            exit();
        }

        // Kiểm tra xem tài khoản này có thực sự đang có mã OTP chờ xác nhận hay không
        if (empty($account['otp_code'])) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy yêu cầu xác thực OTP nào cho tài khoản này.']);
            exit();
        }

        // Kiểm tra thời gian hết hạn 5 phút
        if (strtotime($account['otp_expires_at']) < time()) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Mã OTP đã hết hiệu lực (quá 5 phút). Vui lòng gửi lại mã.']);
            exit();
        }

        // Kiểm tra khớp mã OTP
        if ($account['otp_code'] !== $otp_input) {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Mã xác thực OTP không chính xác.']);
            exit();
        }

        // Nếu mọi thứ đều đúng -> Trả về kết quả thành công
        ob_clean();
        echo json_encode(['status' => 'success', 'message' => 'Mã OTP chính xác!']);
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống OTP: ' . $e->getMessage()]);
    }
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' || !isset($_POST['action'])) {
?>
<link rel="stylesheet" href="../../../public/assets/css/LayOuts/otp.css">
<div id="otpModal" class="modal-overlay hidden-otp">
    <div class="modal-content-card">
        <button type="button" onclick="closeOtpModal()" class="modal-btn-close">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="modal-header-section">
            <div class="modal-icon-badge">
                <span class="material-symbols-outlined">mark_email_read</span>
            </div>
            <h4 class="modal-main-title">Xác thực OTP</h4>
            <p class="modal-sub-desc">Vui lòng nhập mã gồm 6 chữ số vừa được gửi tới Email của bạn để tiếp tục.</p>
        </div>

        <div id="otp-alert-box" class="hidden-otp"></div>

        <form id="otpForm" data-username="">
            <div class="otp-inputs-row" id="otp-inputs-container">
                <input type="number" min="0" max="9" class="otp-box-item" required>
                <input type="number" min="0" max="9" class="otp-box-item" disabled required>
                <input type="number" min="0" max="9" class="otp-box-item" disabled required>
                <input type="number" min="0" max="9" class="otp-box-item" disabled required>
                <input type="number" min="0" max="9" class="otp-box-item" disabled required>
                <input type="number" min="0" max="9" class="otp-box-item" disabled required>
            </div>

            <button type="submit" id="btnVerifyOTP" class="btn-submit">
                Xác nhận mã kích hoạt
            </button>
        </form>

        <div class="modal-footer-note">
            <p>Mã có hiệu lực trong vòng 5 phút.</p>
        </div>
    </div>
</div>

<script>
    const otpModal = document.getElementById('otpModal');
    const otpForm = document.getElementById('otpForm');
    const otpAlertBox = document.getElementById('otp-alert-box');
    const btnVerifyOTP = document.getElementById('btnVerifyOTP');
    const otpInputs = document.querySelectorAll('#otp-inputs-container input');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value;
            if (value.length > 1) e.target.value = value.slice(-1);
            
            if (e.target.value !== "" && index < otpInputs.length - 1) {
                otpInputs[index + 1].removeAttribute('disabled');
                otpInputs[index + 1].focus();
            }
            checkAndAutoSubmitOtp();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && e.target.value === "" && index > 0) {
                otpInputs[index].setAttribute('disabled', true);
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = "";
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim();
            if (/^\d{6}$/.test(pasteData)) {
                pasteData.split('').forEach((char, i) => {
                    otpInputs[i].removeAttribute('disabled');
                    otpInputs[i].value = char;
                });
                otpInputs[5].focus();
                checkAndAutoSubmitOtp();
            }
        });
    });

    function openOtpModal(username) {
        if (!username) {
            showAlert('Thiếu thông tin tài khoản để xác thực OTP!');
            return;
        }
        // Gán username vào form để dùng khi submit
        document.getElementById('otpForm').setAttribute('data-username', username);
        
        otpModal.classList.remove('hidden-otp');
        resetOtpInputs();
    }

    function closeOtpModal() {
        otpModal.classList.add('hidden-otp');
    }

    function resetOtpInputs() {
        if(otpAlertBox) otpAlertBox.classList.add('hidden-otp');
        otpInputs.forEach((input, index) => {
            input.value = '';
            if (index > 0) input.setAttribute('disabled', true);
        });
        setTimeout(() => otpInputs[0].focus(), 100);
    }

    function checkAndAutoSubmitOtp() {
        let otpCombined = "";
        otpInputs.forEach(input => otpCombined += input.value);
        if (otpCombined.length === 6) {
            otpForm.requestSubmit(); 
        }
    }

    otpForm.addEventListener('submit', function(e) {
        e.preventDefault();

        let otpCombined = "";
        otpInputs.forEach(input => otpCombined += input.value);

        if (otpCombined.length !== 6) {
            showAlert('Vui lòng nhập đầy đủ 6 chữ số mã xác thực.', 'warning');
            return;
        }

        btnVerifyOTP.disabled = true;
        btnVerifyOTP.innerHTML = `Đang xác thực...`;

        // LẤY DỮ LIỆU ĐA NĂNG: Đọc trực tiếp từ form ra
        const currentUsername = otpForm.getAttribute('data-username');

        const formData = new FormData();
        formData.append('action', 'verify_otp_code');
        formData.append('otp', otpCombined);
        formData.append('username', currentUsername);

        fetch('otp-handler.php', { 
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btnVerifyOTP.disabled = false;
            btnVerifyOTP.innerText = 'Xác nhận mã kích hoạt';

            if (data.status === 'success') {
                closeOtpModal();
                // BẮN SỰ KIỆN: Trả kèm username ra ngoài để file cha xử lý tác vụ riêng biệt
                const event = new CustomEvent('otpSuccess', { 
                    detail: { otp: otpCombined, username: currentUsername } 
                });
                document.dispatchEvent(event);
            } else {
                showAlert(data.message, 'warning');
                resetOtpInputs();
            }
        })
        .catch(err => {
            btnVerifyOTP.disabled = false;
            btnVerifyOTP.innerText = 'Xác nhận mã kích hoạt';
            showAlert('Lỗi kết nối hệ thống OTP.', 'warning');
            resetOtpInputs();
        });
    });
</script>
<?php 
} 
?>