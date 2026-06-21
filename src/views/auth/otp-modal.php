<!-- ============================================================
     otp_modal.php
     Modal xác thực OTP — dùng chung cho mọi trang.

     Cách dùng:
       1. Thêm <?php require_once 'otp_modal.php'; ?> vào cuối <body>
          (trước thẻ đóng </body>).
       2. Từ JS của trang, gọi OTP.open() để hiện modal.
       3. Khi xác thực thành công, server trả về { redirect: '...' }
          và trang tự chuyển hướng — hoặc bạn override OTP.onSuccess(data).

     Tùy chỉnh:
       - OTP.actionUrl  : URL endpoint POST (mặc định: '' = trang hiện tại)
       - OTP.onSuccess  : callback(data) sau khi verify thành công
       - OTP.onClose    : callback khi đóng modal
     ============================================================ -->

<div id="otpModal" class="modal-overlay hidden-otp">
    <div class="modal-content-card">
        <button type="button" onclick="OTP.close()" class="modal-btn-close">
            <span class="material-symbols-outlined">close</span>
        </button>

        <div class="modal-header-section">
            <div class="modal-icon-badge">
                <span class="material-symbols-outlined">mark_email_read</span>
            </div>
            <h4 class="modal-main-title">Xác thực OTP</h4>
            <p class="modal-sub-desc">
                Vui lòng nhập mã gồm 6 chữ số vừa được gửi tới Email đăng ký của bạn để kích hoạt tài khoản.
            </p>
        </div>

        <div id="otp-alert-box" class="hidden-otp"></div>

        <form id="otpForm">
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
/**
 * OTP — namespace dùng chung cho modal xác thực OTP.
 *
 * API công khai:
 *   OTP.open()              — hiện modal và reset ô nhập
 *   OTP.close()             — đóng modal
 *   OTP.actionUrl           — URL nhận POST (mặc định: trang hiện tại)
 *   OTP.onSuccess(data)     — override để xử lý sau khi thành công
 *   OTP.onClose()           — override để xử lý khi đóng modal
 *
 * Ví dụ override từ trang cha:
 *   OTP.onSuccess = function(data) {
 *       showAlert('Xong! Chuyển về ' + data.redirect);
 *       window.location.href = data.redirect;
 *   };
 */
const OTP = (() => {
    const modal       = document.getElementById('otpModal');
    const form        = document.getElementById('otpForm');
    const alertBox    = document.getElementById('otp-alert-box');
    const btnVerify   = document.getElementById('btnVerifyOTP');
    const inputs      = document.querySelectorAll('#otp-inputs-container input');

    // ── helpers ──────────────────────────────────────────────────
    function showAlert(type, message) {
        alertBox.className = type === 'success' ? 'error-alert-box success-alert' : 'error-alert-box';
        const icon = type === 'success' ? 'check_circle' : 'error';
        alertBox.innerHTML = `<span class="material-symbols-outlined alert-icon">${icon}</span>
                              <span class="alert-text">${message}</span>`;
        alertBox.classList.remove('hidden-otp');
    }

    function reset() {
        alertBox.classList.add('hidden-otp');
        inputs.forEach((inp, i) => {
            inp.value = '';
            if (i > 0) inp.setAttribute('disabled', true);
        });
        setTimeout(() => inputs[0].focus(), 100);
    }

    // ── input navigation ─────────────────────────────────────────
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length > 1) e.target.value = e.target.value.slice(-1);
            if (e.target.value !== '' && index < inputs.length - 1) {
                inputs[index + 1].removeAttribute('disabled');
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index].setAttribute('disabled', true);
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').trim();
            if (/^\d{6}$/.test(pasted)) {
                pasted.split('').forEach((char, i) => {
                    inputs[i].removeAttribute('disabled');
                    inputs[i].value = char;
                });
                inputs[5].focus();
            }
        });
    });

    // ── submit verify ─────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        alertBox.classList.add('hidden-otp');

        const otpCombined = Array.from(inputs).map(i => i.value).join('');
        if (otpCombined.length !== 6) {
            showAlert('error', 'Vui lòng nhập đầy đủ 6 chữ số mã xác thực.');
            return;
        }

        btnVerify.disabled = true;
        btnVerify.textContent = 'Đang xác thực...';

        const formData = new FormData();
        formData.append('action', 'verify_otp');
        formData.append('otp', otpCombined);

        fetch(OTP.actionUrl || '', { method: 'POST', body: formData })
            .then(res => {
                if (!res.ok) throw new Error('Phản hồi server lỗi');
                return res.json();
            })
            .then(data => {
                btnVerify.disabled = false;
                btnVerify.textContent = 'Xác nhận mã kích hoạt';

                if (data.status === 'success') {
                    showAlert('success', data.message + ' Đang chuyển hướng...');
                    OTP.onSuccess(data);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(() => {
                btnVerify.disabled = false;
                btnVerify.textContent = 'Xác nhận mã kích hoạt';
                showAlert('error', 'Xác thực không thành công do lỗi hệ thống.');
            });
    });

    // ── public API ────────────────────────────────────────────────
    return {
        actionUrl: '',

        open() {
            modal.classList.remove('hidden-otp');
            reset();
        },

        close() {
            modal.classList.add('hidden-otp');
            if (typeof OTP.onClose === 'function') OTP.onClose();
        },

        /** Override này từ trang cha nếu muốn xử lý riêng */
        onSuccess(data) {
            setTimeout(() => {
                window.location.href = data.redirect || 'login.php';
            }, 2000);
        },

        onClose: null,
    };
})();
</script>