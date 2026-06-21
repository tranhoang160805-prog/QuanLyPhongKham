<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/routing.php';
require_once 'config/database.php';
require_once 'config/site.php';
require_once 'config/status.php';

$isLoggedInEarly = isset($_SESSION['user_id']);
$rootRoleEarly   = $_SESSION['root_role'] ?? $_SESSION['user_role'] ?? '';

if (isset($_SESSION['user_id']) && isset($_SESSION['login_time'])) {
    $seconds_in_3_days = 3 * 24 * 60 * 60;
    if ((time() - $_SESSION['login_time']) > $seconds_in_3_days) {
        session_unset();
        session_destroy();
        header("Location: index.php?page=home"); 
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $wsCheck = isset($_GET['workspace']) && $_GET['workspace'] === '1';
    if ($isLoggedInEarly && $wsCheck && $rootRoleEarly === 'admin') {
        $allowedRolesToSwitch = ['admin', 'bac-si', 'le-tan', 'dieu-duong', 'duoc-si', 'ky-thuat-vien'];
        $targetRole = $_POST['role'] ?? '';
        if (in_array($targetRole, $allowedRolesToSwitch)) {
            if ($targetRole === $rootRoleEarly) {
                unset($_SESSION['view_role']);
            } else {
                $_SESSION['view_role'] = $targetRole;
            }
        }
    }
    
    // TỰ ĐỘNG ĐỊNH HƯỚNG TRANG ĐÍCH KHI ĐỔI VAI TRÒ
    $targetRoleView = $_SESSION['view_role'] ?? $rootRoleEarly;
    if ($targetRoleView === 'admin') {
        $nextPage = 'home';
    } else {
        $nextPage = 'ds-benh-nhan';
    }
    
    header("Location: index.php?workspace=1&page=" . urlencode($nextPage));
    exit;
}

$faviconPath = $site['logo_url'];

// XỬ LÝ TRANG MẶC ĐỊNH KHI TRUY CẬP VÀO WORKSPACE
$isWorkspace = isset($_GET['workspace']) && $_GET['workspace'] === '1';
$currentRoleEarly = $_SESSION['view_role'] ?? $rootRoleEarly;

if (!isset($_GET['page']) && $isWorkspace) {
    // Nếu vào chế độ làm việc mà không truyền tham số page cụ thể
    if ($currentRoleEarly === 'admin') {
        $page = 'home';
    } else {
        $page = 'ds-benh-nhan';
    }
} else {
    $page = $_GET['page'] ?? 'home';
}

// Logic kiểm tra chế độ bảo trì
$isMaintenanceMode = isset($site['bao_tri']) && (int)$site['bao_tri'] === 1;
$isAdmin = ($isLoggedInEarly && $rootRoleEarly === 'admin');

if ($isMaintenanceMode) {
    if (!$isAdmin && $page !== '503') {
        header("Location: index.php?page=503");
        exit;
    }
} else {
    if ($page === '503') {
        if ($isLoggedInEarly && $rootRoleEarly !== 'benh-nhan') {
            $next = ($currentRoleEarly === 'admin') ? 'home' : 'ds-benh-nhan';
            header("Location: index.php?workspace=1&page=" . $next);
        } else {
            header("Location: index.php?page=home");
        }
        exit;
    }
}

$isLoggedIn  = isset($_SESSION['user_id']);

if (!array_key_exists($page, $allowed_pages)) {
    $page = ($isWorkspace && $currentRoleEarly !== 'admin') ? 'ds-benh-nhan' : 'home';
}

$content_file = $allowed_pages[$page];
$page_title   = $page_titles[$page] ?? 'Phòng khám Hương Sơn';

$currentRole = $_SESSION['user_role'] ?? '';
$rootRole    = $_SESSION['root_role'] ?? $_SESSION['user_role'] ?? '';

$isStaff         = $isLoggedIn && !empty($rootRole) && $rootRole !== 'benh-nhan';
$isWorkspaceMode = $isStaff && $isWorkspace;

if ($isWorkspaceMode) {
    $sidebarStatus = $_COOKIE['sidebarStatus'] ?? 'expanded';
    $layoutPaddingClass = ($sidebarStatus === 'collapsed') ? 'md:pl-[72px]' : 'md:pl-64';
} else {
    $layoutPaddingClass = 'pl-0';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="shortcut icon" href="<?= htmlspecialchars($faviconPath); ?>" />
    <link rel="stylesheet" href="public/assets/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .layout-transition {
            transition: padding-left 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .no-transition { transition: none !important; }
    </style>

    <?php if ($isWorkspaceMode): ?>
    <script>
    (function () {
        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
            return match ? decodeURIComponent(match[1]) : null;
        }
        var status = getCookie('sidebarStatus') || localStorage.getItem('sidebarStatus') || 'expanded';
        window.__sidebarStatus = status;
        document.documentElement.setAttribute('data-sidebar', status === 'collapsed' ? 'collapsed' : 'expanded');
    })();
    </script>
    <style>
        html[data-sidebar="collapsed"] #main-content-layout  { margin-left: 72px; }
        html[data-sidebar="expanded"]  #main-content-layout  { margin-left: 256px; }
    </style>
    <?php endif; ?>
</head>
<body class="bg-slate-50/50 text-slate-800 min-h-screen">
<?php include_once __DIR__ . '/src/views/layouts/alert.php'; ?>
<?php if ($page === '503' || $page === '403'): ?>
    <?php if ($page === '403') { http_response_code(403); } ?>
    
    <div class="w-full min-h-screen flex items-center justify-center">
        <?php
            if (file_exists($content_file)) {
                include $content_file;
            } else {
                echo "
                <div class='p-8 text-center max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm font-sans mx-4'>
                    <span class='material-symbols-outlined text-4xl text-amber-500 mb-2 block'>warning</span>
                    <h4 class='font-bold text-lg text-slate-800 mb-1'>Nội dung không khả dụng</h4>
                    <p class='text-sm text-slate-500 mb-2'>Tệp giao diện chức năng hiện tại đang thiếu hoặc cấu hình sai đường dẫn.</p>
                    <code class='text-xs font-mono bg-slate-100 p-2 rounded block truncate'>$content_file</code>
                </div>";
            }
        ?>
    </div>

<?php elseif (!$isWorkspaceMode): ?>
    <?php include 'src/views/layouts/header.php'; ?>

    <main id="main-content-layout" class="layout-transition w-full min-h-screen <?= $layoutPaddingClass ?>">
        <div class="p-10 md:p-6 mx-auto">
            <?php
                if (file_exists($content_file)) {
                    include $content_file;
                } else {
                    echo "
                    <div class='p-6 bg-red-50 border border-red-200 text-red-700 rounded-xl max-w-2xl mx-auto my-8 shadow-sm flex items-start gap-3'>
                        <span class='material-symbols-outlined text-error mt-0.5'>error</span>
                        <div>
                            <h4 class='font-bold text-base mb-1'>Lỗi cấu trúc hệ thống</h4>
                            <p class='text-sm text-slate-600 mb-2'>Không tìm thấy tệp tin phân hệ chức năng theo đường dẫn định nghĩa.</p>
                            <code class='text-xs font-mono bg-white px-2 py-1 rounded border border-slate-200 block w-full truncate'>Đường dẫn lỗi: $content_file</code>
                        </div>
                    </div>";
                }
            ?>
        </div>
    </main>

    <?php include 'src/views/layouts/footer.php'; ?>

<?php else: ?>
    <?php include 'src/views/layouts/sidebar.php'; ?>

    <main id="main-content-layout" class="w-full <?= $layoutPaddingClass ?>">
        <div id="ajax-content" class="main-content-workspace">
            <?php
                if (file_exists($content_file)) {
                    include $content_file;
                } else {
                    echo "
                    <div class='p-6 bg-red-50 border border-red-200 text-red-700 rounded-xl max-w-2xl mx-auto my-8 shadow-sm flex items-start gap-3'>
                        <span class='material-symbols-outlined text-error mt-0.5'>error</span>
                        <div>
                            <h4 class='font-bold text-base mb-1'>Lỗi cấu trúc hệ thống</h4>
                            <p class='text-sm text-slate-600 mb-2'>Không tìm thấy tệp tin phân hệ chức năng theo đường dẫn định nghĩa.</p>
                            <code class='text-xs font-mono bg-white px-2 py-1 rounded border border-slate-200 block w-full truncate'>Đường dẫn lỗi: $content_file</code>
                        </div>
                    </div>";
                }
            ?>
        </div>
    </main>

<?php endif; ?>

<script>
(function () {
    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            var layout = document.getElementById('main-content-layout');
            if (layout) {
                layout.classList.add('layout-transition');
            }
        });
    });
})();
</script>
</body>
</html>