<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../src/helpers/format.php';

$status_filter = (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') ? (int)$_GET['status_filter'] : null;

// Danh sách nhân viên
$users = [];
try {
    // Sử dụng LEFT JOIN để đồng bộ với API và tránh nuốt mất dữ liệu nhân viên mới tạo
    $table_source = "TAIKHOAN tk 
                     LEFT JOIN NHANVIEN nv ON tk.MaTaiKhoan = nv.MaTaiKhoan 
                     LEFT JOIN TAIKHOAN_VAITRO tkvt ON tk.MaTaiKhoan = tkvt.MaTaiKhoan 
                     LEFT JOIN VAITRO vt ON tkvt.MaVaiTro = vt.MaVaiTro";

    $where = "WHERE vt.TenVaiTro != 'BENH_NHAN' OR vt.TenVaiTro IS NULL";

    if (isset($pdo)) {
        // Đã bỏ tính năng phân trang, truy vấn trực tiếp toàn bộ dữ liệu
        $sql = "SELECT
            nv.MaNhanVien AS maNhanVien,
            nv.HoTen AS hoTen,
            nv.NgaySinh AS ngaySinh,
            nv.GioiTinh AS gioiTinh,
            nv.CCCD AS cccd,
            nv.SoDienThoai AS soDienThoai,
            nv.Email AS email,
            nv.DiaChi AS diaChi,
            nv.AnhThe AS anhThe,
            nv.MaChuyenMon AS maChuyenMon,
            nv.BangCap AS bangCap,
            nv.SoChungChi AS soChungChi,
            nv.NgayVaoLam AS ngayVaoLam,
            tk.MaTaiKhoan AS maTaiKhoan,
            tk.TenDangNhap AS username,
            tk.DangHoatDong AS is_active,
            tk.NgayTao AS created_at,
            vt.TenVaiTro AS role_name,
            vt.MoTa AS role_description
        FROM $table_source
        $where
        ORDER BY tk.NgayTao DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $raw_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($raw_data as $row) {
            $initials = strtoupper(substr($row['username'], 0, 2));
            $status = ($row['is_active'] == 1) ? 'active' : 'inactive';

            $role_class = 'role-staff';
            if ($row['role_name'] === 'QUAN_LY' || $row['role_name'] === 'IT_ADMIN') {
                $role_class = 'role-admin';
            } elseif ($row['role_name'] === 'BAC_SI') {
                $role_class = 'role-doctor';
            } elseif ($row['role_name'] === 'DIEU_DUONG' || $row['role_name'] === 'KY_THUAT_VIEN') {
                $role_class = 'role-nurse';
            }

            $formatted_date = date('d/m/Y', strtotime($row['created_at']));

            $users[] = [
                'id'               => $row['maTaiKhoan'],
                'staff_id'         => $row['maNhanVien'],
                'initials'         => $initials,
                'name'             => $row['hoTen'],
                'avatar'           => $row['anhThe'],
                'username'         => $row['username'],
                'phone'            => $row['soDienThoai'] ?? 'Chưa cập nhật',
                'email'            => $row['email'] ?? 'Chưa cập nhật',
                'birthdate'        => $row['ngaySinh'],
                'gender'           => $row['gioiTinh'],
                'id_card'          => $row['cccd'],
                'address'          => $row['diaChi'],
                'qualification'    => $row['bangCap'],
                'license_number'   => $row['soChungChi'],
                'hire_date'        => $row['ngayVaoLam'],
                'role'             => $row['role_description'] ?? 'Chưa phân quyền',
                'role_name'        => $row['role_name'],
                'role_class'       => $role_class,
                'created_at'       => $formatted_date,
                'status'           => $status
            ];
        }
    }
} catch (PDOException $e) {
    error_log("Lỗi lấy danh sách nhân viên: " . $e->getMessage());
}

// Tổng nhân viên
$total_staff = 0;
$new_staff_this_month = 0;

try {
    if (isset($pdo)) {
        $sqlTotal = "SELECT COUNT(DISTINCT tk.MaTaiKhoan) AS total 
                     FROM TAIKHOAN tk
                     LEFT JOIN TAIKHOAN_VAITRO tkvt ON tk.MaTaiKhoan = tkvt.MaTaiKhoan
                     LEFT JOIN VAITRO vt ON tkvt.MaVaiTro = vt.MaVaiTro
                     WHERE vt.TenVaiTro != 'BENH_NHAN' OR vt.TenVaiTro IS NULL";
        $stmtTotal = $pdo->query($sqlTotal);
        $total_staff = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $sqlMonth = "SELECT COUNT(DISTINCT tk.MaTaiKhoan) AS total_month 
                     FROM TAIKHOAN tk
                     LEFT JOIN TAIKHOAN_VAITRO tkvt ON tk.MaTaiKhoan = tkvt.MaTaiKhoan
                     LEFT JOIN VAITRO vt ON tkvt.MaVaiTro = vt.MaVaiTro
                     WHERE (vt.TenVaiTro != 'BENH_NHAN' OR vt.TenVaiTro IS NULL)
                       AND MONTH(tk.NgayTao) = MONTH(CURRENT_DATE())
                       AND YEAR(tk.NgayTao) = YEAR(CURRENT_DATE())";
        $stmtMonth = $pdo->query($sqlMonth);
        $new_staff_this_month = $stmtMonth->fetch(PDO::FETCH_ASSOC)['total_month'] ?? 0;
    }
} catch (PDOException $e) {
    error_log("Lỗi đếm số liệu thống kê: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản người dùng</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..40,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="public/assets/css/QuanLy/nhan-vien.css">
</head>
<body>

<main class="manager-container">
    <section class="stats-overview">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-symbols-outlined">badge</span>
            </div>
            <div class="stat-info">
                <h4>Tổng nhân sự hệ thống</h4>
                <p><?= number_format($total_staff); ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                <span class="material-symbols-outlined">group_add</span>
            </div>
            <div class="stat-info">
                <h4>Nhân sự mới tháng này</h4>
                <p><?= number_format($new_staff_this_month); ?></p>
            </div>
        </div>
    </section>

    <section class="filters-panel">
        <div class="filter-field field-role">
            <label>Vai trò chức năng</label>
            <select id="filter-role">
                <option value="all">Tất cả phòng ban</option>
                <?php
                try {
                    if (isset($pdo)) {
                        $sqlRoles = "SELECT MaVaiTro, TenVaiTro, MoTa FROM VAITRO WHERE TenVaiTro != 'BENH_NHAN'";
                        $stmtRoles = $pdo->query($sqlRoles);
                        while ($role = $stmtRoles->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . htmlspecialchars($role['MaVaiTro']) . '">' . htmlspecialchars($role['MoTa']) . '</option>';
                        }
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Lỗi tải dữ liệu vai trò</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="filter-field field-status">
            <label>Trạng thái</label>
            <select id="filter-status">
                <option value="all">Tất cả trạng thái</option>
                <option value="active">Đang hoạt động</option>
                <option value="locked">Đã khóa</option>
            </select>
        </div>

        <div class="filter-field field-search">
            <label>Tìm kiếm theo tên hoặc email</label>
            <div class="search-box">
                <span class="material-symbols-outlined">search</span>
                <input id="search-employee" type="text" placeholder="Nhập tên hoặc email nhân viên..."/>
            </div>
        </div>

        <button class="btn btn-primary" onclick="openAddUserModal()">
            <span class="material-symbols-outlined">person_add</span> Thêm nhân sự
        </button>
    </section>

    <div class="bento-content">
        <section class="employee-grid">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): 
                    $is_active = ($user['status'] === 'active');
                    $status_color = $is_active ? '#10b981' : '#f59e0b';
                ?>
                    <div class="employee-card">
                        <div class="card-body">
                            <div class="card-header">
                                <div class="avatar-wrapper">
                                    <img src="public/assets/img/<?= htmlspecialchars($user['avatar'] ?? ''); ?>" onerror="this.src='https://via.placeholder.com/150'" alt="Avatar">
                                    <span class="status-dot" style="background: <?= $status_color; ?>" title="<?= $is_active ? 'Đang hoạt động' : 'Đã khóa' ?>"></span>
                                </div>
                                <span class="role-badge <?= htmlspecialchars($user['role_class']); ?>">
                                    <?= htmlspecialchars($user['role']); ?>
                                endspan>
                            </div>
                            <h3 class="emp-name"><?= htmlspecialchars($user['name']); ?></h3>
                            <p class="emp-email"><?= htmlspecialchars($user['email']); ?></p>
                            <div class="emp-contact">
                                <span class="material-symbols-outlined" style="font-size: 18px;">call</span>
                                <span><?= htmlspecialchars($user['phone']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- ĐỔI TẠI ĐÂY: Truyền ID tài khoản (user['id']) thay vì staff_id vào hàm sửa để API dễ truy vấn đồng bộ -->
                            <button class="btn-grid" title="Sửa thông tin" onclick="openEditUserModal('<?= htmlspecialchars($user['id']); ?>')">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btn-grid" title="<?= $is_active ? 'Khóa tài khoản' : 'Mở khóa tài khoản' ?>" onclick="toggleUserStatus('<?= $user['id']; ?>', '<?= $is_active ? 'lock' : 'unlock'; ?>', this)">
                                <span class="material-symbols-outlined"><?= $is_active ? 'lock' : 'lock_open'; ?></span>
                            </button>
                            <button class="btn-grid" title="Đặt lại mật khẩu" onclick="resetUserPassword('<?= $user['id']; ?>')">
                                <span class="material-symbols-outlined">lock_reset</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                    Không tìm thấy nhân viên nào phù hợp.
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Modal Form -->
    <div class="modal-backdrop hidden" id="modal-add-employee">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="modal-employee-title">Thêm nhân viên mới</h3>
                <button type="button" class="btn-close" onclick="toggleModal('modal-add-employee', false)">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="form-add-employee" method="POST" enctype="multipart/form-data" action="src/controllers/NhanVienController.php">
                <input type="hidden" name="emp_id" id="emp-id" value="" />
                <input type="hidden" name="account_id" id="account-id" value="" />

                <div class="modal-body">
                    <section class="avatar-upload-container">
                        <div class="avatar-preview-box">
                            <img id="emp-avatar-preview" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>" alt="Preview Avatar" />
                        </div>
                        <div style="text-align: center;">
                            <label for="emp-avatar-input" class="btn btn-secondary" style="height:36px; padding:0 12px; font-size:13px; line-height:36px;">Chọn ảnh thẻ</label>
                            <input type="file" name="avatar" id="emp-avatar-input" accept="image/png, image/jpeg, image/jpg" style="display:none;" />
                            <div style="margin-top: 4px;"><small style="color:var(--color-text-muted);">Kích thước 3x4 hoặc 4x6 (Dưới 2MB).</small></div>
                        </div>
                    </section>

                    <h4 style="margin: 0 0 12px 0; font-size: 14px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px; color: var(--color-primary);">Thông tin cá nhân</h4>
                    <section class="profile-info-grid" style="margin-bottom: 20px;">
                        <div class="form-group full-width">
                            <label>Họ tên nhân viên</label>
                            <input type="text" name="fullname" id="emp-fullname" placeholder="Nhập đầy đủ họ và tên" required />
                        </div>
                        <div class="form-group">
                            <label>Số CCCD / Định danh</label>
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
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" id="emp-phone" placeholder="Nhập số điện thoại liên lạc" required />
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ Email</label>
                            <input type="email" name="email" id="emp-email-input" placeholder="example@clinic.com" required />
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ thường trú</label>
                            <input type="text" name="address" id="emp-address" placeholder="Nhập địa chỉ chi tiết" />
                        </div>
                    </section>

                    <h4 style="margin: 0 0 12px 0; font-size: 14px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px; color: var(--color-primary);">Hồ sơ chuyên môn & Công tác</h4>
                    <section class="profile-info-grid" style="margin-bottom: 20px;">
                        <div class="form-group">
                            <label>Bằng cấp cao nhất</label>
                            <input type="text" name="qualification" id="emp-qualification" placeholder="Ví dụ: Thạc sĩ, Bác sĩ CKI..." />
                        </div>
                        <div class="form-group">
                            <label>Số chứng chỉ hành nghề (nếu có)</label>
                            <input type="text" name="license_number" id="emp-license" placeholder="Nhập số CCHN" />
                        </div>
                        <div class="form-group">
                            <label>Ngày bắt đầu vào làm</label>
                            <input type="date" name="hire_date" id="emp-hiredate" required />
                        </div>
                        <div class="form-group">
                            <label>Phân quyền Hệ thống (Vai trò)</label>
                            <select name="role_id" id="emp-role-select" required>
                                <option value="">-- Chọn vai trò xử lý --</option>
                                <?php
                                try {
                                    if (isset($pdo)) {
                                        $stmtRolesModal = $pdo->query("SELECT MaVaiTro, TenVaiTro, MoTa FROM VAITRO WHERE TenVaiTro != 'BENH_NHAN'");
                                        while ($r = $stmtRolesModal->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($r['MaVaiTro']) . '">' . htmlspecialchars($r['MoTa']) . '</option>';
                                        }
                                    }
                                } catch (PDOException $e) {}
                                ?>
                            </select>
                        </div>
                    </section>

                    <h4 style="margin: 0 0 12px 0; font-size: 14px; border-bottom: 1px solid var(--color-border); padding-bottom: 6px; color: var(--color-primary);">Tài khoản đăng nhập</h4>
                    <section class="profile-info-grid">
                        <div class="form-group full-width">
                            <label>Tên đăng nhập (Username)</label>
                            <input type="text" name="username" id="emp-username" placeholder="Nhập tên tài khoản duy nhất" required />
                        </div>
                        
                        <div class="form-group">
                            <label>Mật khẩu đăng nhập</label>
                            <div class="password-input-wrapper">
                                <input type="password" name="password" id="emp-password" placeholder="••••••••" />
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('emp-password', this)">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            <small id="help-password" style="color: #64748b; display:none; margin-top: 4px;">(Để trống nếu không đổi mật khẩu)</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Nhập lại mật khẩu</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="emp-confirm-password" placeholder="••••••••" />
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('emp-confirm-password', this)">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
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
</main>

<script>
// =========================================================================
// 1. CÁC HÀM TIỆN ÍCH UI & MODAL
// =========================================================================
function toggleModal(modalId, shouldShow) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    if (shouldShow) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex'; 
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        modal.style.display = 'none'; 
        document.body.style.overflow = '';
        
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            const avatarPreview = document.getElementById('emp-avatar-preview');
            if (avatarPreview) {
                avatarPreview.src = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
            }
        }
    }
}

function togglePasswordVisibility(inputId, buttonEl) {
    const input = document.getElementById(inputId);
    const icon = buttonEl.querySelector('.material-symbols-outlined');
    if (!input || !icon) return;
    input.type = (input.type === 'password') ? 'text' : 'password';
    icon.textContent = (input.type === 'text') ? 'visibility_off' : 'visibility';
}

function openAddUserModal() {
    const form = document.getElementById('form-add-employee');
    if (form) form.reset();

    const title = document.getElementById('modal-employee-title');
    const btn = document.getElementById('btn-submit-employee');
    
    if (title) title.innerText = "Thêm nhân viên mới";
    if (btn) btn.innerText = "Thêm nhân viên";
    if (form) form.action = "src/controllers/NhanVienController.php";

    const passwordInput = document.getElementById('emp-password');
    if (passwordInput) {
        passwordInput.required = true;
        const passwordWrapper = passwordInput.closest('.form-group');
        if (passwordWrapper) passwordWrapper.style.display = 'flex';
    }
    
    const confirmPasswordInput = document.getElementById('emp-confirm-password');
    if (confirmPasswordInput) {
        confirmPasswordInput.required = true;
        const confirmWrapper = confirmPasswordInput.closest('.form-group');
        if (confirmWrapper) confirmWrapper.style.display = 'flex';
    }

    const username = document.getElementById('emp-username');
    if (username) {
        username.readOnly = false;
        username.style.backgroundColor = '#fff';
    }
    
    document.getElementById('emp-id').value = "";
    document.getElementById('account-id').value = "";
    
    toggleModal('modal-add-employee', true);
}

function openEditUserModal(accountId) {
    const form = document.getElementById('form-add-employee');
    if (!form) return;
    
    form.reset();

    const modalTitle = document.getElementById('modal-employee-title');
    const submitBtn = document.getElementById('btn-submit-employee');
    if (modalTitle) modalTitle.innerText = "Cập nhật thông tin nhân viên";
    if (submitBtn) submitBtn.innerText = "Cập nhật";

    // Ẩn trường mật khẩu khi sửa
    const passwordInput = document.getElementById('emp-password');
    if (passwordInput) {
        passwordInput.required = false;
        const passwordWrapper = passwordInput.closest('.form-group');
        if (passwordWrapper) passwordWrapper.style.display = 'none';
    }
    const confirmPasswordInput = document.getElementById('emp-confirm-password');
    if (confirmPasswordInput) {
        confirmPasswordInput.required = false;
        const confirmWrapper = confirmPasswordInput.closest('.form-group');
        if (confirmWrapper) confirmWrapper.style.display = 'none';
    }

    // Khóa trường username
    const usernameInput = document.getElementById('emp-username');
    if (usernameInput) {
        usernameInput.readOnly = true;
        usernameInput.style.backgroundColor = '#f1f3f5';
    }

    // Gọi API lấy dữ liệu nhân viên
    fetch(`src/api/getNhanVien.php?id=${accountId}`)
        .then(response => response.json())
        .then(res => {
            if (res.success && res.data) {
                const emp = res.data;

                // GIẢI PHÁP ĐỔ DATA AN TOÀN TUYỆT ĐỐI BẰNG form.elements
                form.elements['emp_id'].value = emp.maNhanVien || emp.MaNhanVien || "";
                form.elements['account_id'].value = emp.maTaiKhoan || emp.MaTaiKhoan || "";
                form.elements['fullname'].value = emp.hoTen || emp.HoTen || "";
                form.elements['id_card'].value = emp.cccd || emp.CCCD || "";
                form.elements['birthdate'].value = emp.ngaySinh || emp.NgaySinh || "";
                form.elements['gender'].value = emp.gioiTinh || emp.GioiTinh || "M";
                form.elements['phone'].value = emp.soDienThoai || emp.SoDienThoai || "";
                form.elements['email'].value = emp.email || emp.Email || "";
                form.elements['address'].value = emp.diaChi || emp.DiaChi || "";
                form.elements['qualification'].value = emp.bangCap || emp.BangCap || "";
                form.elements['license_number'].value = emp.soChungChi || emp.SoChungChi || "";
                form.elements['hire_date'].value = emp.ngayVaoLam || emp.NgayVaoLam || "";
                form.elements['username'].value = emp.username || emp.TenDangNhap || "";
                form.elements['role_id'].value = emp.maVaiTro || emp.MaVaiTro || "";

                // Đổ ảnh xem trước
                const previewImg = document.getElementById('emp-avatar-preview');
                const avatarFile = emp.anhThe || emp.AnhThe;
                if (previewImg) {
                    if (avatarFile) {
                        previewImg.src = `public/assets/img/${avatarFile}`;
                    } else {
                        previewImg.src = "public/assets/img/avatar.png";
                    }
                }

                toggleModal('modal-add-employee', true);
            } else {
                showAlert("Không thể tải dữ liệu: " + (res.message || "Không có phản hồi hợp lệ"));
            }
        })
        .catch(err => {
            console.error("Lỗi fetch chi tiết nhân sự:", err);
            showAlert("Có lỗi kết nối hệ thống khi lấy thông tin.");
        });
}

// =========================================================================
// 2. LOGIC XỬ LÝ LỌC VÀ THAO TÁC AJAX
// =========================================================================
function escapeHtml(text) {
    if (!text) return '';
    const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
}

function fetchFilteredEmployees() {
    const filterRoleEl = document.getElementById('filter-role');
    const filterStatusEl = document.getElementById('filter-status');
    const searchEmployeeEl = document.getElementById('search-employee');

    if (!filterRoleEl || !filterStatusEl || !searchEmployeeEl) return;

    const role = filterRoleEl.value;
    const status = filterStatusEl.value;
    const search = searchEmployeeEl.value;

    const url = `src/api/getNhanVienList.php?role=${role}&status=${status}&search=${encodeURIComponent(search)}`;

    fetch(url)
        .then(response => response.json())
        .then(result => {
            const gridSection = document.querySelector('.employee-grid');
            if (!gridSection) return;

            gridSection.innerHTML = ''; 

            if (result.success && result.data.length > 0) {
                result.data.forEach(user => {
                    const is_active = (user.status === 'active');
                    const status_color = is_active ? '#10b981' : '#f59e0b';
                    
                    let rClass = 'role-staff';
                    if (user.role_name === 'QUAN_LY' || user.role_name === 'IT_ADMIN') rClass = 'role-admin';
                    else if (user.role_name === 'BAC_SI') rClass = 'role-doctor';
                    else if (user.role_name === 'DIEU_DUONG' || user.role_name === 'KY_THUAT_VIEN') rClass = 'role-nurse';

                    const card = document.createElement('div');
                    card.className = 'employee-card';
                    card.innerHTML = `
                        <div class="card-body">
                            <div class="card-header">
                                <div class="avatar-wrapper">
                                    <img src="public/assets/img/${escapeHtml(user.avatar)}" onerror="this.src='public/assets/img/avatar.png'" alt="Avatar">
                                    <span class="status-dot" style="background: ${status_color};"></span>
                                </div>
                                <span class="role-badge ${rClass}">${escapeHtml(user.role)}</span>
                            </div>
                            
                            <h3 class="emp-name">${escapeHtml(user.name)}</h3>
                            <p class="emp-email">${escapeHtml(user.email || 'Chưa cập nhật')}</p>
                            <div class="emp-contact">
                                <span class="material-symbols-outlined" style="font-size: 18px;">call</span>
                                <span>${escapeHtml(user.phone || 'Chưa cập nhật')}</span>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn-grid" title="Sửa thông tin" onclick="openEditUserModal('${user.id}')">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btn-grid" title="${is_active ? 'Khóa tài khoản' : 'Mở khóa'}" onclick="toggleUserStatus('${user.id}', '${is_active ? 'lock' : 'unlock'}', this)">
                                <span class="material-symbols-outlined">${is_active ? 'lock' : 'lock_open'}</span> 
                            </button>
                            <button class="btn-grid" title="Đặt lại mật khẩu" onclick="resetUserPassword('${user.id}')">
                                <span class="material-symbols-outlined">lock_reset</span>
                            </button>
                        </div>
                    `;
                    gridSection.appendChild(card);
                });
            } else {
                gridSection.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                        Không tìm thấy nhân viên nào phù hợp.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải bộ lọc nhân viên:', error);
        });
}

function initEmployeePageEvents() {
    const filterRole = document.getElementById('filter-role');
    const filterStatus = document.getElementById('filter-status');
    const searchEmployee = document.getElementById('search-employee');

    if (filterRole) filterRole.addEventListener('change', fetchFilteredEmployees);
    if (filterStatus) filterStatus.addEventListener('change', fetchFilteredEmployees);
    if (searchEmployee) searchEmployee.addEventListener('input', fetchFilteredEmployees);
}

window.initEmployeePageEvents = initEmployeePageEvents;

function toggleUserStatus(id, action, btnElement) {
    const message = (action === 'unlock') 
        ? "Bạn có chắc chắn muốn KÍCH HOẠT lại tài khoản này không?" 
        : "Bạn có chắc chắn muốn KHÓA tài khoản của nhân viên này không?";

    if (!confirm(message)) return; 

    const activeValue = (action === 'unlock') ? 1 : 0;
    const url = 'src/api/getTrangThaiTaiKhoan.php';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('is_active', activeValue);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showAlert('Đã cập nhật trạng thái thành công!');
            fetchFilteredEmployees();
        } else {
            showAlert('Lỗi từ hệ thống: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối API trạng thái:', error);
        showAlert('Không thể kết nối tới máy chủ xử lý.');
    });
}

function resetUserPassword(employeeId) {
    if (!confirm("Bạn có chắc chắn muốn đặt lại mật khẩu mặc định cho nhân viên này không?")) return;

    fetch(`src/api/getNhanVien.php?id=${employeeId}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showAlert(`Đặt lại mật khẩu thành công! Mật khẩu mặc định mới là: ${result.new_password || '123456'}`);
                fetchFilteredEmployees();
            } else {
                showAlert('Lỗi: ' + result.message);
            }
        })
        .catch(err => console.error('Lỗi kết nối đặt lại mật khẩu:', err));
}

// =========================================================================
// 3. KHỞI CHẠY LẬP TỨC
// =========================================================================
initEmployeePageEvents();
fetchFilteredEmployees();

document.addEventListener('DOMContentLoaded', function() {
    initEmployeePageEvents();
    fetchFilteredEmployees();

    const addEmployeeForm = document.getElementById('form-add-employee');
    if (addEmployeeForm) {
        // Thay đổi đoạn kiểm tra submit cũ thành đoạn này:
addEmployeeForm.addEventListener('submit', function(e) {
    e.preventDefault(); 

    const staffId = document.getElementById('emp-id').value;
    const pass = document.getElementById('emp-password')?.value;
    const confirmPass = document.getElementById('emp-confirm-password')?.value;
    
    // CHỈ BẮT BUỘC KIỂM TRA MẬT KHẨU KHI THÊM MỚI (Mã nhân viên rỗng)
    if (!staffId) { 
        if (!pass) {
            showAlert("Vui lòng nhập mật khẩu khi thêm nhân viên mới!");
            return;
        }
        if (pass !== confirmPass) {
            showAlert("Lỗi: Nhập lại mật khẩu không trùng khớp!");
            return;
        }
    }

    const formData = new FormData(this);

    fetch('src/controllers/NhanVienController.php', {
        method: 'POST',
        body: formData
    })
    // ... các đoạn xử lý .then() phía sau giữ nguyên ...
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message);
                    toggleModal('modal-add-employee', false);
                    this.reset();
                    fetchFilteredEmployees(); 
                } else {
                    showAlert('Thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra trong quá trình gửi dữ liệu!');
            });
        });
    }

    const avatarInput = document.getElementById('emp-avatar-input');
    avatarInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => document.getElementById('emp-avatar-preview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleModal('modal-add-employee', false);
        }
    });
});
</script>
</body>
</html>