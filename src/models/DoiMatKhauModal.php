<div id="modal-change-password" class="modal-backdrop hidden" style="display: none;">
    <div class="modal-container">
        
        <div class="modal-header">
            <h2 class="modal-title">Đặt lại mật khẩu nhân viên</h2>
            <button class="btn-close" onclick="toggleModal('modal-change-password', false);" type="button">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="modal-body">
            <p style="color: #555; margin-bottom: 24px; font-size: 0.95rem; line-height: 1.5;">
                Thiết lập mật khẩu mới cho tài khoản nhân viên. Nhân viên sẽ được yêu cầu đổi mật khẩu trong lần đăng nhập tới để đảm bảo tính an toàn.
            </p>

            <div class="employee-info-card">
                <img id="modal_employee_avatar" class="employee-avatar" src="" alt="Avatar">
                <div class="employee-details">
                    <p id="modal_employee_name" class="employee-name">Đang tải...</p>
                    <p class="employee-username">
                        <span class="material-symbols-outlined" style="font-size: 18px;">account_circle</span>
                        <span id="modal_employee_username">...</span>
                    </p>
                </div>
            </div>

            <form id="form_reset_password" action="update-password.php" method="POST" class="form-grid">
                
                <input type="hidden" id="modal_employee_id" name="employee_id" value="">

                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="new_password" name="new_password" oninput="checkStrength(this.value)" placeholder="Nhập mật khẩu mới" required />
                        <button class="btn-toggle-password" onclick="toggleVisibility('new_password')" type="button">
                            <span class="material-symbols-outlined" id="eye_icon_new">visibility</span>
                        </button>
                    </div>
                    
                    <div class="strength-meter-container">
                        <div class="strength-text-row">
                            <span id="strength_text">Độ mạnh mật khẩu: <span style="font-weight: 500;">Chưa nhập</span></span>
                        </div>
                        <div class="strength-bars">
                            <div class="password-strength-bar bg-surface-variant" id="bar_1"></div>
                            <div class="password-strength-bar bg-surface-variant" id="bar_2"></div>
                            <div class="password-strength-bar bg-surface-variant" id="bar_3"></div>
                            <div class="password-strength-bar bg-surface-variant" id="bar_4"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required />
                        <button class="btn-toggle-password" onclick="toggleVisibility('confirm_password')" type="button">
                            <span class="material-symbols-outlined" id="eye_icon_confirm">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="force_change" name="force_change" value="1" checked />
                    <label for="force_change">Yêu cầu người dùng đổi mật khẩu khi đăng nhập lần đầu</label>
                </div>

                <div class="security-notice">
                    <span class="material-symbols-outlined">info</span>
                    <p>
                        <strong>Ghi chú bảo mật:</strong> Đảm bảo rằng mật khẩu mới tuân thủ chính sách bảo mật của bệnh viện (tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường và chữ số). Hành động này sẽ được ghi lại trong nhật ký hệ thống (Audit Log).
                    </p>
                </div>

                <div class="modal-footer-custom">
                    <button class="btn btn-secondary" type="button" onclick="toggleModal('modal-change-password', false);">Hủy bỏ</button>
                    <button class="btn btn-primary" type="submit">Xác nhận đặt lại mật khẩu</button>
                </div>

            </form>
        </div>

    </div>
</div>

<style>
    /* Các thành phần bổ trợ cho tính năng Mật khẩu & Thẻ nhân viên */
    .employee-info-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1px solid #eef0f2;
    }
    .employee-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .employee-details p { margin: 0; }
    .employee-name { font-weight: 600; color: #1a6fc4; font-size: 1.1rem; }
    .employee-username { display: flex; align-items: center; gap: 4px; color: #6c757d; font-size: 0.9rem; margin-top: 4px; }
    
    .strength-meter-container { margin-top: 10px; }
    .strength-text-row { font-size: 0.85rem; margin-bottom: 6px; color: #555; }
    .strength-bars { display: flex; gap: 4px; }
    .password-strength-bar { height: 6px; border-radius: 3px; flex: 1; transition: all 0.3s ease; }
    
    /* Màu trạng thái thanh độ mạnh mật khẩu */
    .bg-surface-variant { background-color: #e0e0e0; }
    .bg-error { background-color: #dc3545; }
    .bg-yellow-500 { background-color: #ffc107; }
    .bg-green-500 { background-color: #28a745; }
    .text-error { color: #dc3545; }
    .text-yellow-600 { color: #e0a800; }
    .text-green-600 { color: #218838; }

    .checkbox-group { display: flex; align-items: center; gap: 10px; margin-top: 8px; }
    .checkbox-group input { width: 18px; height: 18px; cursor: pointer; }
    .checkbox-group label { color: #555; cursor: pointer; font-size: 0.9rem; user-select: none; }
    
    .security-notice {
        margin-top: 20px;
        padding: 14px 16px;
        background: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 8px;
        color: #856404;
        display: flex;
        gap: 12px;
        font-size: 0.85rem;
    }
    .security-notice p { margin: 0; line-height: 1.4; }
    .modal-footer-custom { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #e0e0e0; }
</style>