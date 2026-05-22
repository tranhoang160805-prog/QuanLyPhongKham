<?php
// 1. Khởi động session nếu file index.php chưa khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Giả lập role gốc đăng nhập (Khi làm thật, biến này lấy từ DB sau khi đăng nhập thành công)
if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = 'admin'; // Giả sử user gốc là Admin
}

$real_role = $_SESSION['user_role']; // Quyền gốc thực sự của tài khoản

/* 
  3. Xử lý logic CHUYỂN QUYỀN (Chỉ áp dụng nếu quyền gốc là admin)
  Nếu có lệnh chuyển quyền từ URL (?switch_role=...) và user gốc là admin thì mới cho chuyển
*/
if (isset($_GET['switch_role']) && $real_role === 'admin') {
    $_SESSION['current_view_role'] = $_GET['switch_role'];
    
    // Chuyển hướng về trang dashboard để làm sạch URL và tải lại menu mới
    header("Location: index.php?page=dashboard");
    exit();
}

// Quyền hiện tại dùng để hiển thị Menu (Mặc định bằng quyền gốc nếu chưa chuyển)
$menu_role = isset($_SESSION['current_view_role']) ? $_SESSION['current_view_role'] : $real_role;

// Nếu tài khoản không phải admin thì tuyệt đối không cho dùng quyền ảo
if ($real_role !== 'admin') {
    $menu_role = $real_role;
}


// 4. Hàm kiểm tra Active Menu
function getMenuClass($pageParam, $currentPage) {
    return ($pageParam === $currentPage) 
        ? 'menu-item menu-item-active' 
        : 'menu-item menu-item-default';
}

/* ==========================================================================
   5. ĐỊNH NGHĨA MENU TÁCH BIỆT HOÀN TOÀN CHO TỪNG ROLE
   ========================================================================== */
$menus_by_role = [
    'admin' => [
        ['id' => 'dashboard',    'label' => 'Dashboard Admin',    'icon' => 'dashboard'],
        ['id' => 'nhan-su',      'label' => 'Quản lý Nhân sự',    'icon' => 'badge'],
        ['id' => 'bao-cao',      'label' => 'Báo cáo & Thống kê', 'icon' => 'assessment'],
        ['id' => 'danh-muc',     'label' => 'Quản lý Danh mục',   'icon' => 'category'],
        ['id' => 'cai-dat',      'label' => 'Cài đặt hệ thống',   'icon' => 'settings_suggest']
    ],
    'benh-nhan' => [
        ['id' => 'dat-lich',     'label' => 'Đặt lịch hẹn',       'icon' => 'add_task'],
        ['id' => 'lich-hen',     'label' => 'Lịch hẹn của tôi',   'icon' => 'event_note'],
        ['id' => 'lich-su-kham', 'label' => 'Lịch sử khám',       'icon' => 'history'],
        ['id' => 'don-thuoc',    'label' => 'Đơn thuốc',          'icon' => 'prescriptions'],
        ['id' => 'xet-nghiem',   'label' => 'Kết quả xét nghiệm', 'icon' => 'mic'],
        ['id' => 'hoa-don-bn',   'label' => 'Hóa đơn',            'icon' => 'receipt_long']
    ],
    'le-tan' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Danh sách bệnh nhân', 'icon' => 'groups'],
        ['id' => 'phieu-kham',   'label' => 'Phiếu khám',         'icon' => 'assignment'],
        ['id' => 'lich-hen-lt',  'label' => 'Lịch hẹn',           'icon' => 'calendar_month'],
        ['id' => 'hoa-don-lt',   'label' => 'Hóa đơn',            'icon' => 'payments']
    ],
    'dieu-duong' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Danh sách bệnh nhân', 'icon' => 'groups'],
        ['id' => 'so-kham',      'label' => 'Sơ khám',            'icon' => 'monitor_heart'],
        ['id' => 'ls-so-kham',   'label' => 'Lịch sử sơ khám',    'icon' => 'manage_search']
    ],
    'bac-si' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Danh sách bệnh nhân', 'icon' => 'groups'],
        ['id' => 'kham-benh',    'label' => 'Khám bệnh',          'icon' => 'stethoscope'],
        ['id' => 'chi-dinh',     'label' => 'Chỉ định lâm sàn',   'icon' => 'biotech'],
        ['id' => 'cap-thuoc-bs', 'label' => 'Cấp thuốc',          'icon' => 'medication']
    ],
    'ky-thuat-vien' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Danh sách bệnh nhân', 'icon' => 'groups'],
        ['id' => 'xet-nghiem-ktv','label' => 'Xét nghiệm',         'icon' => 'science'],
        ['id' => 'ls-xet-nghiem', 'label' => 'Lịch sử xét nghiệm', 'icon' => 'lab_profile']
    ],
    'duoc-si' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Danh sách bệnh nhân', 'icon' => 'groups'],
        ['id' => 'kho-thuoc',    'label' => 'Kho thuốc',          'icon' => 'inventory_2'],
        ['id' => 'cap-phat',     'label' => 'Cấp phát thuốc',     'icon' => 'vaccines']
    ]
];

// Lấy đúng mảng menu của role hiện tại, nếu không có thì mặc định lấy của doctor hoặc mảng rỗng
$current_menu = isset($menus_by_role[$menu_role]) ? $menus_by_role[$menu_role] : [];
?>

<link rel="stylesheet" href="assets/css/navbar.css">

<!-- TopNavBar -->
<header class="navbar-header">
    <div class="brand-wrapper">
        <span class="brand-logo-text">MedPrecision Clinic</span>
        <?php if ($real_role === 'admin'): ?>
            <div class="switch-role-container" style="display: flex; align-items: center; gap: 8px; margin-left: 15px;">
                <span style="font-size: 12px; color: var(--color-text-muted);">Xem với quyền:</span>
                <select onchange="location = this.value;" style="padding: 4px 8px; border-radius: 6px; border: 1px solid var(--color-border); font-size: 13px; font-weight: bold; color: var(--color-primary); background-color: #fff; cursor: pointer;">
                    <option value="index.php?switch_role=admin" <?php echo $menu_role === 'admin' ? 'selected' : ''; ?>>QUẢN TRỊ VIÊN</option>
                    <option value="index.php?switch_role=bac-si" <?php echo $menu_role === 'bac-si' ? 'selected' : ''; ?>>BÁC SĨ</option>
                    <option value="index.php?switch_role=le-tan" <?php echo $menu_role === 'le-tan' ? 'selected' : ''; ?>>LỄ TÂN</option>
                    <option value="index.php?switch_role=ky-thuat-vien" <?php echo $menu_role === 'ky-thuat-vien' ? 'selected' : ''; ?>>KỸ THUẬT VIÊN</option>
                    <option value="index.php?switch_role=benh-nhan" <?php echo $menu_role === 'benh-nhan' ? 'selected' : ''; ?>>BỆNH NHÂN</option>
                    <option value="index.php?switch_role=dieu-duong" <?php echo $menu_role === 'dieu-duong' ? 'selected' : ''; ?>>ĐIỀU DƯỠNG</option>
                    <option value="index.php?switch_role=duoc-si" <?php echo $menu_role === 'duoc-si' ? 'selected' : ''; ?>>DƯỢC SĨ</option>
                </select>
            </div>
        <?php else: ?>
            <span class="role-badge">Role: <?php echo strtoupper($real_role); ?></span>
        <?php endif; ?>
    </div>

    <div class="header-actions">
        <div class="right-buttons">
            <div class="avatar-wrapper">
                <img alt="Avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCY0HWCGk6CjVrVPPzqQd-2-3zHO-Az16nQ_oR4y5epFXKudkjFvJMJeyM1HKEOoe7oSNBD5Rw58hNpVu9xT1KcSTbRiuEOfSQb7EC3q7Bobma1XhmdOOu3fIqhhRg1PCRlq75OcMjXqfqyPa4yzXR6HQx2AZyOglTPXcdR_pAZjnecRzBSap2Y4CJOB_UBql1lbDQQs7rdWNz9mUdF0S1_wq4ymb3yqLMA70HcCyQ3Nf8lrQUGq1pV-uWoLGoEaeIziEysi04nBGI"/>
            </div>
        </div>
    </div>
</header>

<!-- SideNavBar -->
<aside class="navbar-aside">
    <div class="aside-brand-section">
        <div class="aside-brand-box">
            <div class="aside-logo-icon">
                <span class="material-symbols-outlined">medical_services</span>
            </div>
            <div class="aside-title-text">
                <h2>MedPrecision</h2>
                <p>
                    <?php 
                    // Đổi tên phân hệ ở Menu tùy theo quyền đang xem để trực quan
                    if ($menu_role === 'admin') echo 'Hệ Thống Quản Trị';
                    if ($menu_role === 'doctor') echo 'Phân Hệ Bác Sĩ';
                    if ($menu_role === 'receptionist') echo 'Phân Hệ Lễ Tân';
                    ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- IN RA MENU ĐÃ ĐƯỢC LỌC RIÊNG BIỆT -->
    <nav class="menu-navigation">
        <?php foreach ($current_menu as $item): ?>
            <a class="<?php echo getMenuClass($item['id'], $page); ?>" href="index.php?page=<?php echo $item['id']; ?>">
                <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    
    <!-- KHU VỰC CHỨC NĂNG HỆ THỐNG (CÀI ĐẶT & ĐĂNG XUẤT) -->
    <div class="aside-footer-actions">
        <a class="footer-action-item" href="index.php?page=cai-dat">
            <span class="material-symbols-outlined">settings</span>
            <span>Cài đặt</span>
        </a>
        <a class="footer-action-item btn-logout" href="logout.php">
            <span class="material-symbols-outlined">logout</span>
            <span>Đăng xuất</span>
        </a>
    </div>
</aside>