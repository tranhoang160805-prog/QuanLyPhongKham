<?php
require_once __DIR__ . '/../../config/database.php';
?>
<link rel="stylesheet" href="../public/assets/css/modal.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">

<div id="modal-add-employee" class="modal-backdrop hidden">
    <div class="modal-container">
        
        <div class="modal-header">
            <h2 class="modal-title" id="modal-employee-title">Thêm nhân viên mới</h2>
            <button class="btn-close" type="button" onclick="toggleModal('modal-add-employee', false)">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="form-add-employee" method="POST" action="../src/controllers/NhanVienController.php" enctype="multipart/form-data" onsubmit="return validateEmployeeForm()">
            <input type="hidden" name="employee_id" id="emp-id" value="">

            <div class="modal-body">
                
                <section class="form-section">
                    <div class="section-title">
                        <span class="material-symbols-outlined icon-primary">badge</span>
                        <h3>Thông tin nhân viên</h3>
                    </div>
                    
                    <div class="profile-layout-container">
                        <div class="avatar-upload-sidebar">
                            <div class="avatar-preview-box">
                                <img id="emp-avatar-preview" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>" alt="Xem trước ảnh">
                            </div>
                            <div class="avatar-input-box">
                                <label for="emp-avatar-input" class="custom-file-upload">Chọn ảnh thẻ</label>
                                <input type="file" name="avatar_file" id="emp-avatar-input" accept="image/png, image/jpeg, image/jpg" />
                                <small>Kích thước 3x4 hoặc 4x6 (Dưới 2MB).</small>
                            </div>
                        </div>

                        <div class="profile-info-grid">
                            <div class="form-group full-width">
                                <label>Họ tên</label>
                                <input type="text" name="fullname" id="emp-fullname" placeholder="Nhập đầy đủ họ và tên" required />
                            </div>
                            <div class="form-group full-width">
                                <label>CCCD</label>
                                <input type="text" name="id_card" id="emp-idcard" placeholder="Nhập số CCCD" required />
                            </div>
                            <div class="form-group">
                                <label>Ngày sinh</label>
                                <input type="date" name="birthdate" id="emp-birthdate" required />
                            </div>
                            <div class="form-group">
                                <label>Giới tính</label>
                                <select name="gender" id="emp-gender">
                                    <option value="M">Nam</option>
                                    <option value="F">Nữ</option>
                                    <option value="O">Khác</option>
                                </select>
                            </div>
                            <div class="form-group full-width">
                                <label>Địa chỉ</label>
                                <input type="text" name="address" id="emp-address" placeholder="Nhập địa chỉ thường trú" />
                            </div>
                            <div class="form-group">
                                <label>Bằng cấp</label>
                                <input type="text" name="qualification" id="emp-qualification" placeholder="Ví dụ: Thạc sĩ Y khoa" />
                            </div>
                            <div class="form-group">
                                <label>Chuyên khoa</label>
                                <select name="specialty" id="emp-specialty" required>
                                    <option value="">Chọn chuyên khoa</option>
                                    <?php
                                    try {
                                        if (isset($pdo)) {
                                            $stmt_ck = $pdo->query("SELECT MaChuyenKhoa, TenChuyenKhoa FROM CHUYENKHOA");
                                            $chuyenkhoas = $stmt_ck->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($chuyenkhoas as $ck) {
                                                echo '<option value="' . htmlspecialchars($ck['MaChuyenKhoa']) . '">' . htmlspecialchars($ck['TenChuyenKhoa']) . '</option>';
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option value="">Lỗi tải dữ liệu chuyên khoa...</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-title">
                        <span class="material-symbols-outlined icon-primary">admin_panel_settings</span>
                        <h3>Phân quyền hệ thống</h3>
                    </div>
                    <div class="form-group full-width">
                        <label>Vai trò nhân viên</label>
                        <select name="role_id" id="emp-role" required>
                            <option value="">Chọn vai trò nhân viên</option>
                            <?php
                            try {
                                if (isset($pdo)) {
                                    $stmt = $pdo->query("SELECT MaVaiTro, MoTa FROM VAITRO WHERE TenVaiTro != 'BENH_NHAN'");
                                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($roles as $role) {
                                        echo '<option value="' . htmlspecialchars($role['MaVaiTro']) . '">' . htmlspecialchars($role['MoTa']) . '</option>';
                                    }
                                }
                            } catch (PDOException $e) {
                                echo '<option value="">Lỗi tải dữ liệu...</option>';
                            }
                            ?>
                        </select>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-title">
                        <span class="material-symbols-outlined icon-primary">account_circle</span>
                        <h3>Thông tin tài khoản đăng nhập</h3>
                    </div>
                    <div class="form-grid grid-2-col">
                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" name="username" id="emp-username" placeholder="Ví dụ: lanh.nguyen" required />
                        </div>
                        <div class="form-group">
                            <label>Email liên hệ</label>
                            <input type="email" name="email" id="emp-email" placeholder="email@clinic.com" required />
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" id="emp-phone" placeholder="09xx xxx xxx" required />
                        </div>
                        
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="password" id="emp-password" placeholder="••••••••" required />
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('emp-password', this)">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            <small id="help-password" style="color: #666; display:none; margin-top: 4px;">(Để trống nếu không đổi mật khẩu)</small>
                        </div>

                        <div class="form-group">
                            <label>Nhập lại mật khẩu</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="emp-confirm-password" placeholder="••••••••" required />
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('emp-confirm-password', this)">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="toggleModal('modal-add-employee', false)">Hủy</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-employee">Thêm nhân viên</button>
            </div>
        </form>
    </div>
</div>

<script src="../public/assets/js/main.js"></script>