<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $_SESSION['user_name'] ?? '';
$userCode   = $_SESSION['user_code'] ?? '';

// Lưu root_role một lần duy nhất khi đăng nhập
$rootRole = $_SESSION['root_role'] ?? $_SESSION['user_role'] ?? '';
if (!isset($_SESSION['root_role']) && isset($_SESSION['user_role'])) {
    $_SESSION['root_role'] = $_SESSION['user_role'];
}

// Xử lý switch_role (chỉ dùng trong workspace mode, giữ nguyên logic cũ)
if (isset($_GET['switch_role'])) {
    $switchedRole = $_GET['switch_role'];
    if ($rootRole === 'admin') {
        $_SESSION['user_role'] = $switchedRole;
    } elseif ($rootRole === 'bac-si' && in_array($switchedRole, ['bac-si', 'benh-nhan'])) {
        $_SESSION['user_role'] = $switchedRole;
    }
    header("Location: index.php?page=home");
    exit;
}

$currentRole = $_SESSION['user_role'] ?? '';

$roleLabels = [
    'admin'         => 'Quản lý',
    'bac-si'        => 'Bác sĩ',
    'benh-nhan'     => 'Bệnh nhân',
    'le-tan'        => 'Lễ tân',
    'dieu-duong'    => 'Điều dưỡng',
    'ky-thuat-vien' => 'Kỹ thuật viên',
    'duoc-si'       => 'Dược sĩ'
];

if (!function_exists('getAvatarInitial')) {
    function getAvatarInitial($name) {
        if (empty($name)) return 'U';
        $parts    = explode(' ', trim($name));
        $lastPart = end($parts);
        return mb_strtoupper(mb_substr($lastPart, 0, 1, 'UTF-8'), 'UTF-8');
    }
}

// Trang workspace mặc định sau khi nhấn "Vào không gian làm việc"
// Ưu tiên dashboard nếu tồn tại trong routing, fallback về home
$workspaceLandingPage = 'dashboard';
?>
<link rel="stylesheet" href="public/assets/css/LayOuts/header.css">
<header class="header">
    <div class="container">
        <div class="header-container">

            <div class="sidebar-header">
        <a href="index.php?page=home" class="sidebar-logo" title="Về trang chủ">
            <img src="<?= $site['logo_url'] ?>" alt="">
            <span class="nav-text logo-text">Hương Sơn</span>
        </a>
    </div>

            <nav class="md:flex gap-6 h-full items-center">
                <?php
                $p = isset($_GET['page']) ? $_GET['page'] : 'home';
                if (!function_exists('getLinkClass')) {
                    function getLinkClass($current_page, $target_page) {
                        return ($current_page === $target_page) ? 'link-menu-active' : 'link-menu';
                    }
                }
                ?>
                <a href="index.php?page=home"     class="<?= getLinkClass($p, 'home');    ?>">Trang chủ</a>
                <a href="index.php?page=doctors"  class="<?= getLinkClass($p, 'doctors'); ?>">Đội ngũ bác sĩ</a>
                <a href="index.php?page=services" class="<?= getLinkClass($p, 'services'); ?>">Dịch vụ y tế</a>
                <a href="index.php?page=news"     class="<?= getLinkClass($p, 'news');    ?>">Tin tức</a>
                <a href="index.php?page=contact"  class="<?= getLinkClass($p, 'contact'); ?>">Liên hệ</a>
            </nav>

            <div class="flex items-center gap-4 flex-shrink-0">

                <?php if (!$isLoggedIn): ?>
                    <!-- Chưa đăng nhập: hiện nút đăng nhập + đặt lịch -->
                    <a href="src/views/auth/login.php" class="btn-submit btn-login">Đăng nhập</a>
                    <a href="src/views/auth/login.php" class="btn-submit">Đặt lịch khám</a>

                <?php else: ?>
                    <!-- Đã đăng nhập: hiện avatar + dropdown tương ứng vai trò -->
                    <div class="user-profile">

                        <!-- <div class="user-profile-avatar">
                            <?= getAvatarInitial($userName) ?>
                        </div> -->

                        <div class="user-profile-info">
                            <span class="user-profile-name"><?= htmlspecialchars($userName) ?></span>

                            <div class="user-profile-meta">
                                <?php
                                $badgeModifiers = [
                                    'admin'     => 'status-cancelled',
                                    'bac-si'    => 'status-pending',
                                    'benh-nhan' => 'status-examining'
                                ];
                                $currentBadgeModifier = $badgeModifiers[$currentRole] ?? 'status-completed';
                                ?>
                                <span class="user-profile-badge <?= $currentBadgeModifier ?>">
                                    <?= $roleLabels[$currentRole] ?? $currentRole ?>
                                </span>
                                <span class="material-symbols-outlined user-profile-arrow-icon">keyboard_arrow_down</span>
                            </div>
                        </div>

                        <?php if ($rootRole === 'benh-nhan'): ?>
                            <!-- ===== DROPDOWN BỆNH NHÂN ===== -->
                            <!-- Hiển thị các tác vụ liên quan đến bệnh nhân -->
                            <div class="user-profile-dropdown user-profile-dropdown-patient">

                                <div class="user-profile-dropdown-header">
                                    <p class="user-profile-dropdown-title">Tài khoản của tôi</p>
                                </div>

                                <a href="index.php?page=profile" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">person</span>
                                    <span>Hồ sơ cá nhân</span>
                                </a>

                                <a href="index.php?page=dat-lich" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                    <span>Lịch hẹn của tôi</span>
                                </a>

                                <a href="index.php?page=benh-an" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">clinical_notes</span>
                                    <span>Bệnh án điện tử</span>
                                </a>

                                <!-- <a href="index.php?page=don-thuoc" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">lab_research</span>
                                    <span>Đơn thuốc</span>
                                </a>

                                <a href="index.php?page=hoa-don-bn" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">medication</span>
                                    <span>Hóa đơn</span>
                                </a>

                                <a href="index.php?page=danh-gia" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">medication</span>
                                    <span>Đánh giá</span>
                                </a> -->

                                <div class="user-profile-divider"></div>

                                <a href="src/views/auth/logout.php" class="user-profile-dropdown-item user-profile-dropdown-item-logout">
                                    <span class="material-symbols-outlined">logout</span>
                                    <span>Đăng xuất</span>
                                </a>

                            </div>

                        <?php else: ?>
                            <!-- ===== DROPDOWN ADMIN / NHÂN VIÊN ===== -->
                            <!-- Có nút chuyển sang trang làm việc (workspace layout) -->
                            <div class="user-profile-dropdown user-profile-dropdown-staff">

                                <div class="user-profile-dropdown-header">
                                    <p class="user-profile-dropdown-title">Xin chào, <?= htmlspecialchars($userName) ?></p>
                                </div>

                                <!-- Nút vào không gian làm việc → chuyển sang workspace layout -->
                                <a href="index.php?workspace=1&page=<?= $workspaceLandingPage ?>"
                                   class="user-profile-dropdown-item user-profile-dropdown-item-workspace">
                                    <span class="material-symbols-outlined">dashboard</span>
                                    <span>Dashboard làm việc</span>
                                </a>

                                <a href="index.php?page=staff-profile" class="user-profile-dropdown-item">
                                    <span class="material-symbols-outlined">manage_accounts</span>
                                    <span>Thông tin tài khoản</span>
                                </a>

                                <div class="user-profile-divider"></div>

                                <a href="src/views/auth/logout.php" class="user-profile-dropdown-item user-profile-dropdown-item-logout">
                                    <span class="material-symbols-outlined">logout</span>
                                    <span>Đăng xuất tài khoản</span>
                                </a>

                            </div>

                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</header>