<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../src/models/NhanVienModel.php';
// require_once __DIR__ . '/../../../src/models/ThongBaoModel.php';

// danh sách nhân viên
$users = [];
try {
    if (isset($pdo)) {
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
                FROM TAIKHOAN tk
                INNER JOIN NHANVIEN nv ON tk.MaTaiKhoan = nv.MaTaiKhoan
                LEFT JOIN TAIKHOAN_VAITRO tkvt ON tk.MaTaiKhoan = tkvt.MaTaiKhoan
                LEFT JOIN VAITRO vt ON tkvt.MaVaiTro = vt.MaVaiTro
                ORDER BY tk.NgayTao DESC";

        $stmt = $pdo->query($sql);
        $raw_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // BƯỚC 4: Chuyển đổi dữ liệu từ tên cột DB sang đúng các key mà thẻ <tbody> của bạn đang gọi
        foreach ($raw_data as $row) {
            
            // Tạo chữ viết tắt Avatar (Ví dụ: tên lanh.nguyen -> lấy chữ 'LA')
            $initials = strtoupper(substr($row['username'], 0, 2));

            // Đổi giá trị 1/0 trong DB thành chữ 'active' / 'inactive' khớp với class CSS của bạn
            $status = ($row['is_active'] == 1) ? 'active' : 'inactive';

            // Định dạng class màu sắc cho Badge tương ứng với từng chức vụ
            $role_class = 'role-staff';
            if ($row['role_name'] === 'QUAN_LY' || $row['role_name'] === 'IT_ADMIN') {
                $role_class = 'role-admin';
            } elseif ($row['role_name'] === 'BAC_SI') {
                $role_class = 'role-doctor';
            } elseif ($row['role_name'] === 'DIEU_DUONG' || $row['role_name'] === 'KY_THUAT_VIEN') {
                $role_class = 'role-nurse';
            }

            // Định dạng lại ngày tạo cho dễ nhìn (Ngày/Tháng/Năm)
            $formatted_date = date('d/m/Y', strtotime($row['created_at']));

            // Đẩy phần tử này vào mảng $users chung
            $users[] = [
                'id'               => $row['maTaiKhoan'],
                'staff_id'         => $row['maNhanVien'],
                'initials'         => $initials,
                'name'             => $row['hoTen'],
                'avatar'           => $row['anhValue'] ?? $row['anhThe'],
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
    // Nếu có lỗi truy vấn, hệ thống ghi nhận log âm thầm để bảo mật thông tin
    error_log("Lỗi lấy danh sách nhân viên: " . $e->getMessage());
}

// tổng nhân viên
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
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ajax.js"></script>
    <style>
        /* ==========================================================================
   SEMANTIC STRUCTURAL STYLESHEET
   ========================================================================== */

/* Thiết lập biến hệ thống (CSS Custom Properties) */
:root {
    --color-background: #f8f9fa;
    --color-surface: #fdfdfd;
    --color-surface-container: #f0f0f2;
    --color-surface-container-lowest: #ffffff;
    --color-surface-container-highest: #e6e1e5;
    
    --color-primary: #0066cc;
    --color-on-primary: #ffffff;
    --color-primary-container: #0052a3;
    
    --color-on-surface: #1d1b20;
    --color-on-surface-variant: #49454f;
    --color-outline: #79747e;
    --color-outline-variant: #cac4d0;
    --color-secondary-fixed: #7df6ef;
    
    --margin-page: 24px;
    --container-gap: 24px;
    --gutter: 16px;
    
    --text-h2-size: 24px;
    --text-h2-weight: 600;
}

body {
    margin: 0;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background-color: var(--color-background);
    color: var(--color-on-surface);
}

/* Toàn bộ khung Layout sườn bên phải */
.account-manager-layout {
    min-height: 100vh;
    padding: var(--margin-page);
    box-sizing: border-box;
}
.manager-container {
    max-width: 100vw;
    margin: 0 auto;
}

/* Header khu vực tiêu đề trang */
.account-header {
    margin-bottom: var(--container-gap);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 16px;
}
@media (min-width: 768px) {
    .account-header { flex-direction: row; align-items: center; }
}
.header-title h2 {
    font-size: var(--text-h2-size);
    font-weight: var(--text-h2-weight);
    color: var(--color-on-surface);
    margin: 0;
}
.header-title p {
    font-size: 14px;
    color: var(--color-on-surface-variant);
    margin: 4px 0 0 0;
}

/* Hệ thống Buttons dùng chung */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn .material-symbols-outlined { font-size: 18px; }
.btn:active { transform: scale(0.97); }

.btn-primary {
    background-color: var(--color-primary);
    color: var(--color-on-primary);
}
.btn-primary:hover { background-color: var(--color-primary-container); }

.btn-outline {
    background-color: transparent;
    border-color: var(--color-outline);
    color: var(--color-on-surface-variant);
}
.btn-outline:hover { background-color: rgba(0, 0, 0, 0.04); }
.btn-full { width: 100%; justify-content: center; }

/* Bảng điều khiển bộ lọc (Filters Panel) */
.filters-panel {
    background: var(--color-surface-container-lowest);
    border: 1px solid var(--color-outline-variant);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: var(--container-gap);
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: var(--gutter);
}
.filter-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.filter-field label {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-on-surface-variant);
}
.filter-field select, .filter-field input {
    width: 100%;
    background: var(--color-surface);
    border: 1px solid var(--color-outline-variant);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}
.filter-field select:focus, .filter-field input:focus {
    border-color: var(--color-primary);
}
.field-role, .field-status { flex: 1 1 200px; min-width: 200px; }
.field-search { flex: 2 1 240px; min-width: 240px; }

.search-box { position: relative; }
.search-box .material-symbols-outlined {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: var(--color-on-surface-variant);
    font-size: 18px; pointer-events: none;
}
.search-box input { padding-left: 40px; }

/* Bento Grid Layout chính */
.bento-content { display: grid; grid-template-columns: 1fr; gap: var(--container-gap); }
@media (min-width: 992px) {
    .bento-content { grid-template-columns: 3fr 9fr; }
}

/* Sidebar phụ bên trái */
.stats-sidebar { display: flex; flex-direction: column; gap: var(--container-gap); }
.card {
    background: var(--color-surface-container-lowest);
    border: 1px solid var(--color-outline-variant);
    border-radius: 12px;
    padding: 24px;
}
.card-stats-total {
    background-color: var(--color-primary);
    color: var(--color-on-primary);
    border: none;
}
.card-label { opacity: 0.8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; }
.card-metrics { display: flex; align-items: baseline; gap: 8px; margin-top: 8px; }
.card-metrics h3 { font-size: 36px; font-weight: 700; margin: 0; }
.badge-trend { font-size: 12px; }
.card-footer {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 12px;
}
.online-indicator {
    width: 8px; height: 8px; border-radius: 50%;
    background-color: var(--color-secondary-fixed);
    box-shadow: 0 0 8px rgba(125, 246, 239, 0.6);
}

.security-header { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
.security-header h4 { font-size: 16px; margin: 0; color: var(--color-on-surface); }
.icon-wrapper {
    width: 40px; height: 40px; border-radius: 8px;
    background: rgba(0, 102, 204, 0.05);
    display: flex; align-items: center; justify-content: center;
    color: var(--color-primary);
}
.security-desc { font-size: 13px; color: var(--color-on-surface-variant); margin: 0 0 16px 0; line-height: 1.4; }

/* Khu vực hiển thị bảng dữ liệu */
.table-card {
    background: var(--color-surface-container-lowest);
    border: 1px solid var(--color-outline-variant);
    border-radius: 12px;
    overflow: hidden;
}
.table-header {
    padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;
    background: rgba(244, 244, 246, 0.4); border-bottom: 1px solid var(--color-outline-variant);
}
.table-header h3 { font-size: 16px; margin: 0; color: var(--color-on-surface); }
.table-tools { display: flex; gap: 4px; }
.btn-icon {
    background: none; border: none; padding: 8px; border-radius: 8px; cursor: pointer;
    color: var(--color-on-surface-variant); display: inline-flex;
}
.btn-icon:hover { background: var(--color-surface-container); }

.table-responsive { width: 100%; overflow-x: auto; }
.user-table { width: 100%; border-collapse: collapse; text-align: left; }
.user-table th { padding: 16px 24px; font-size: 13px; font-weight: 500; color: var(--color-on-surface-variant); border-bottom: 1px solid var(--color-outline-variant); }
.user-table td { padding: 16px 24px; vertical-align: middle; border-bottom: 1px solid var(--color-outline-variant); }
.user-table tbody tr { transition: background 0.15s; }
.user-table tbody tr:hover { background: rgba(0, 102, 204, 0.02); }
.user-table tbody tr:last-child td { border-bottom: none; }

/* Cấu trúc phần tử bên trong hàng của bảng */
.user-profile-cell { display: flex; align-items: center; gap: 12px; }
.user-profile-cell .avatar {
    width: 32px; height: 32px; border-radius: 50%; font-weight: bold;
    background: var(--color-surface-container-highest);
    color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 11px;
}
.user-profile-cell .info p { margin: 0; }
.user-profile-cell .info .name { font-size: 14px; font-weight: 500; color: var(--color-on-surface); }
.user-profile-cell .info .email { font-size: 12px; color: var(--color-on-surface-variant); }



/* Nhãn trạng thái tài khoản */
.badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-status .dot { width: 6px; height: 6px; border-radius: 50%; }
.status-active { background: rgba(54, 181, 107, 0.12); color: #1c7c42; }
.status-active .dot { background: #1c7c42; }
.status-locked { background: rgba(186, 26, 26, 0.12); color: #ba1a1a; }
.status-locked .dot { background: #ba1a1a; }

.date-cell { font-size: 14px; color: var(--color-on-surface-variant); }
.actions-cell { display: flex; align-items: center; justify-content: flex-end; gap: 16px; }
.btn-action-icon { background: none; border: none; cursor: pointer; padding: 0; color: var(--color-on-surface-variant); display: inline-flex; }
.btn-action-icon:hover { color: var(--color-primary); }

/* Thanh phân trang dưới chân bảng */
.table-pagination {
    padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;
    background: rgba(244, 244, 246, 0.15); border-top: 1px solid var(--color-outline-variant);
}
.pagination-info { font-size: 13px; color: var(--color-on-surface-variant); }
.pagination-nav { display: flex; gap: 6px; }
.btn-page, .btn-nav {
    width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
    border-radius: 6px; background: none; border: 1px solid transparent; cursor: pointer; font-size: 13px; transition: 0.2s;
}
.btn-nav { border-color: var(--color-outline-variant); color: var(--color-on-surface-variant); }
.btn-nav .material-symbols-outlined { font-size: 18px; }
.btn-page.active { background: var(--color-primary); color: white; font-weight: 500; }
.btn-page:not(.active):hover, .btn-nav:hover { background: var(--color-surface-container); }

/* Banner điều hướng / Hỗ trợ kỹ thuật */
.help-banner {
    margin-top: var(--container-gap); padding: 20px 24px; border-radius: 12px;
    background: var(--color-surface-container);
    display: flex; flex-direction: column; justify-content: space-between; align-items: center; gap: 16px;
}
@media (min-width: 768px) { .help-banner { flex-direction: row; } }
.banner-body { display: flex; align-items: center; gap: 16px; }
.icon-info { font-size: 32px; color: var(--color-primary); }
.banner-text h5 { margin: 0; font-size: 14px; font-weight: 500; color: var(--color-on-surface); }
.banner-text p { margin: 4px 0 0 0; font-size: 12px; color: var(--color-on-surface-variant); }
.btn-text {
    background: none; border: none; cursor: pointer; padding: 0; color: var(--color-primary);
    font-size: 14px; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;
}
.btn-text .material-symbols-outlined { font-size: 16px; }
.btn-text:hover { text-decoration: underline; }

/* Các Helper Selector căn chỉnh nhanh */
.text-center { text-align: center; }
.text-right { text-align: right; }
.center-wrapper { display: flex; justify-content: center; align-items: center; }
    </style>
</head>
<body>

<main class="account-manager-layout">
    <div class="manager-container">
        
        <!-- Page Header -->
        <header class="account-header">
            <div class="header-title">
                <h2>Quản lý tài khoản người dùng</h2>
                <p>Quản lý quyền truy cập và bảo mật cho đội ngũ nhân viên phòng khám.</p>
            </div>
            <button class="btn btn-primary" onclick="openAddUserModal()">
                <span class="material-symbols-outlined">person_add</span>
                Thêm nhân viên mới
            </button>
        </header>

        <!-- Filters Section -->
        <section class="filters-panel">
            <div class="filter-field field-role">
               <label>Vai trò</label>
               <select id="filter-role">
                   <option value="all">Tất cả vai trò</option>
                   <?php
                   try {
                       // Sử dụng trực tiếp biến kết nối từ file config (thay $pdo bằng $conn nếu file config của bạn dùng tên đó)
                       if (isset($pdo)) {
                           // Truy vấn lấy MaVaiTro và MoTa (Ẩn vai trò BENH_NHAN nếu đây là trang nhân sự)
                           $stmt = $pdo->query("SELECT MaVaiTro, MoTa FROM VAITRO WHERE TenVaiTro != 'BENH_NHAN' AND TenVaiTro != 'QUAN_LY'");
                           $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                           
                           foreach ($roles as $role) {
                               echo '<option value="' . htmlspecialchars($role['MaVaiTro']) . '">' . htmlspecialchars($role['MoTa']) . '</option>';
                           }
                       } else {
                           echo '<option value="">Không tìm thấy kết nối DB</option>';
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
            <div class="filter-actions">
                <button class="btn btn-outline">
                    <span class="material-symbols-outlined">filter_list</span>
                    Lọc nâng cao
                </button>
            </div>
        </section>

        <!-- Bento Layout -->
        <div class="bento-content">
            
            <!-- Quick Stats & Security Status -->
            <aside class="stats-sidebar">
                <div class="card card-stats-total">
                    <p class="card-label">Tổng nhân sự</p>
                    <div class="card-metrics">
                        <h3><?= number_format($total_staff); ?></h3>

                        <span class="badge-trend">+<?= number_format($new_staff_this_month); ?> tháng này</span>
                    </div>
                    <div class="card-footer">
                        <span>Hệ thống quản lý</span>
                        <span class="online-indicator"></span>
                    </div>
                </div>
                
                <div class="card card-security">
                    <div class="security-header">
                        <div class="icon-wrapper">
                            <span class="material-symbols-outlined">security</span>
                        </div>
                        <h4>Bảo mật</h4>
                    </div>
                    <p class="security-desc">Lần cuối quét bảo mật hệ thống: 2 giờ trước. Không phát hiện rủi ro.</p>
                    <button class="btn btn-outline btn-full" onclick="viewSystemLogs()">
                        Xem nhật ký hệ thống
                    </button>
                </div>
            </aside>

            <!-- Main Table Area -->
            <section class="table-area">
                <div class="table-card">
                    <div class="table-header">
                        <h3>Danh sách tài khoản</h3>
                        <div class="table-tools">
                            <button class="btn-icon" title="Tải xuống CSV"><span class="material-symbols-outlined">download</span></button>
                            <button class="btn-icon"><span class="material-symbols-outlined">more_vert</span></button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>NHÂN VIÊN</th>
                                    <th>VAI TRÒ</th>
                                    <th>NGÀY TẠO</th>
                                    <th class="text-center">TRẠNG THÁI</th>
                                    <th class="text-right">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                    <tr data-id="<?= $user['id']; ?>">
                                        <td>
                                            <div class="user-profile-cell">
                                                <!-- <div class="avatar"><?= htmlspecialchars($user['initials']); ?></div> -->
                                                <div class="info">
                                                    <p class="name"><?= htmlspecialchars($user['username']); ?></p>
                                                    <p class="email"><?= htmlspecialchars($user['email']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-role <?= htmlspecialchars($user['role_class']); ?>">
                                                <?= htmlspecialchars($user['role']); ?>
                                            </span>
                                        </td>
                                        <td class="date-cell"><?= htmlspecialchars($user['created_at']); ?></td>
                                        <td>
                                            <div class="center-wrapper">
                                                <span class="badge-status status-<?= htmlspecialchars($user['status']); ?>">
                                                    <span class="dot"></span> <?= ucfirst(htmlspecialchars($user['status'])); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <button class="btn-action-icon" title="Sửa thông tin nhân viên" onclick="openEditUserModal(<?= $user['staff_id']; ?>)">
                                                    <span class="material-symbols-outlined">edit</span>
                                                </button>
                                                                            
                                                <?php if ($user['status'] === 'active'): ?>
                                                    <button class="btn-action-icon btn-lock" title="Khóa tài khoản" onclick="toggleUserStatus(<?= $user['staff_id']; ?>, 'lock', this)">
                                                        <span class="material-symbols-outlined" style="color: var(--color-error, #ba1a1a);">lock</span>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn-action-icon btn-unlock" title="Kích hoạt tài khoản" onclick="toggleUserStatus(<?= $user['staff_id']; ?>, 'unlock', this)">
                                                        <span class="material-symbols-outlined" style="color: var(--color-success, #2e7d32);">lock_open</span>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button class="btn-action-icon" title="Đặt lại mật khẩu" onclick="resetUserPassword(<?= $user['staff_id']; ?>)">
                                                    <span class="material-symbols-outlined">lock_reset</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding: 32px; color: var(--color-on-surface-variant);">
                                            Không có dữ liệu người dùng.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <footer class="table-pagination">
                        <span class="pagination-info">Hiển thị 4 trên 48 kết quả</span>
                        <div class="pagination-nav">
                            <button class="btn-nav"><span class="material-symbols-outlined">chevron_left</span></button>
                            <button class="btn-page active">1</button>
                            <button class="btn-page">2</button>
                            <button class="btn-page">3</button>
                            <button class="btn-nav"><span class="material-symbols-outlined">chevron_right</span></button>
                        </div>
                    </footer>
                </div>
            </section>
        </div>
    </div>
</main>
</body>
</html>
