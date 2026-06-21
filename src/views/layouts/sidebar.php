<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

$isLoggedIn   = isset($_SESSION['user_id']);
$currentRole  = $_SESSION['user_role'] ?? '';
$rootRole     = $_SESSION['root_role'] ?? $_SESSION['user_role'] ?? '';

$isWorkspaceMode = isset($isWorkspaceMode) ? $isWorkspaceMode : false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    
    if (!$isLoggedIn || !$isWorkspaceMode) {
        http_response_code(403);
        exit('Thao tác không hợp lệ hoặc bạn chưa đăng nhập.');
    }

    $allowedRolesToSwitch = ['admin', 'bac-si', 'le-tan', 'dieu-duong', 'duoc-si', 'ky-thuat-vien'];
    $targetRole = $_POST['role'];

    if ($rootRole !== 'admin') {
        http_response_code(403);
        exit('Hành động bị từ chối: Bạn không có quyền quản trị để thực hiện đóng vai.');
    }

    if (in_array($targetRole, $allowedRolesToSwitch)) {
        if ($targetRole === $rootRole) {
            // Reset về role gốc → xóa view_role
            unset($_SESSION['view_role']);
        } else {
            $_SESSION['view_role'] = $targetRole;
        }
    }

    // 5. Chuyển hướng an toàn sau khi cập nhật Session để tránh trùng lặp dữ liệu POST
    $nextPage = $_POST['page'] ?? 'home';
    header("Location: index.php?workspace=1&page=" . urlencode($nextPage));
    exit;   
}

$isAdmin      = ($rootRole === 'admin');
$activeView   = ($isAdmin && !empty($_SESSION['view_role'])) ? $_SESSION['view_role'] : $currentRole;
$isPreview    = $isAdmin && !empty($_SESSION['view_role']) && $_SESSION['view_role'] !== $rootRole;

// Menu sidebar render theo role hiệu lực
$sidebar_menu = $menus_by_role[$activeView] ?? [];
$p = isset($_GET['page']) ? $_GET['page'] : 'home';

// Danh sách role có thể switch (chỉ dùng trong dropdown admin)
$switchable_roles = [
    'admin'         => ['label' => 'Quản trị viên', 'icon' => 'admin_panel_settings'],
    'bac-si'        => ['label' => 'Bác sĩ',         'icon' => 'stethoscope'],
    'le-tan'        => ['label' => 'Lễ tân',          'icon' => 'badge'],
    'dieu-duong'    => ['label' => 'Điều dưỡng',      'icon' => 'health_and_safety'],
    'duoc-si'       => ['label' => 'Dược sĩ',         'icon' => 'medication'],
    'ky-thuat-vien' => ['label' => 'KTV',             'icon' => 'biotech'],
];

if ($isLoggedIn && $isWorkspaceMode):
?>
<link rel="stylesheet" href="public/assets/css/LayOuts/sidebar.css">
<header class="main-header">
    <div class="header-left-area">
    </div>

    <div class="header-right-actions">
        <button type="button" class="btn-icon-action" title="Thông báo">
            <span class="material-symbols-outlined">notifications</span>
            <span class="notification-badge"></span>
        </button>

        <button type="button" class="btn-icon-action" title="Trợ giúp">
            <span class="material-symbols-outlined">help</span>
        </button>

        <button type="button" class="btn-icon-action" title="Cài đặt hệ thống">
            <span class="material-symbols-outlined">settings</span>
        </button>

        <div class="vertical-divider"></div>

        <div class="user-profile-wrapper">

            <div class="user-info-text">
                <p class="user-name">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                </p>
                <p class="user-role-sub">
                    <?php if ($isPreview): ?>
                        <span class="badge-preview-mode">
                            <span class="material-symbols-outlined">visibility</span>
                            <?= htmlspecialchars($switchable_roles[$activeView]['label'] ?? $activeView) ?>
                        </span>
                    <?php else: ?>
                        <span class="text-role-name">
                            <?= htmlspecialchars($switchable_roles[$currentRole]['label'] ?? $currentRole) ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($isAdmin): ?>
                        <span class="material-symbols-outlined icon-expand">expand_more</span>
                    <?php endif; ?>
                </p>
            </div>
                    
            <img alt="User Avatar" 
                 class="user-avatar <?= $isPreview ? 'avatar-preview-active' : '' ?>"
                 src="<?= htmlspecialchars($_SESSION['avatar'] ?? 'public/assets/img/avatar.png') ?>">

            <?php if ($isAdmin): ?>
            <div class="role-dropdown-menu">

                <div class="dropdown-header">
                    <p class="dropdown-title">Xem giao diện theo vai trò</p>
                    <p class="dropdown-subtitle">Role thật của bạn vẫn là Admin</p>
                </div>

                <?php foreach ($switchable_roles as $roleKey => $roleMeta): ?>
                <form method="POST" action="">
                    <input type="hidden" name="role" value="<?= $roleKey ?>">
                    <input type="hidden" name="page" value="<?= htmlspecialchars($p) ?>">
                    
                    <button type="submit" class="btn-role-item <?= $activeView === $roleKey ? 'role-item-active' : 'role-item-normal' ?>">
                        <span class="material-symbols-outlined"><?= $roleMeta['icon'] ?></span>
                        <?= htmlspecialchars($roleMeta['label']) ?>
                        
                        <?php if ($activeView === $roleKey): ?>
                            <span class="material-symbols-outlined icon-checked">check_circle</span>
                        <?php endif; ?>
                    </button>
                </form>
                <?php endforeach; ?>

                <?php if ($isPreview): ?>
                <div class="dropdown-footer-action">
                    <form method="POST" action="">
                        <input type="hidden" name="role" value="<?= htmlspecialchars($rootRole) ?>">
                        <input type="hidden" name="page" value="<?= htmlspecialchars($p) ?>">
                        
                        <button type="submit" class="btn-undo-view">
                            <span class="material-symbols-outlined">undo</span>
                            Quay về Admin view
                        </button>
                    </form>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>
</header>

<aside id="main-sidebar" class="sidebar">

    <div class="sidebar-header">
        <a href="index.php?page=home" class="sidebar-logo logo-dashboard" title="Về trang chủ">
            <img src="<?= $site['logo_url'] ?>" alt="">
            <span class="nav-text logo-text">Hương Sơn</span>
        </a>
    </div>

    <div class="sidebar-body">
        <nav class="sidebar-nav">
            <?php foreach ($sidebar_menu as $item):
                $isActive  = ($p === $item['id']);
                $itemClass = $isActive ? 'nav-link active' : 'nav-link';
            ?>
                <a href="index.php?workspace=1&page=<?= $item['id'] ?>"
                   class="<?= $itemClass ?>"
                   title="<?= $item['label'] ?>">
                    <span class="material-symbols-outlined nav-icon"><?= $item['icon'] ?></span>
                    <span class="nav-text"><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="sidebar-footer">

        <a href="/QuanLyPhongKham/src/views/auth/logout.php" class="nav-link nav-link-footer nav-link-exit-workspace">
            <span class="material-symbols-outlined nav-icon">logout</span>
            <span class="nav-text">Đăng xuất</span>
        </a>

        <a href="index.php?page=home"
           class="nav-link nav-link-footer nav-link-exit-workspace"
           title="Thoát không gian làm việc">
            <span class="material-symbols-outlined nav-icon">arrow_back</span>
            <span class="nav-text">Thoát làm việc</span>
        </a>
        
        <button id="sidebar-toggle-btn" class="nav-link  nav-link-footer" title="Thu gọn/Mở rộng menu">
            <span id="toggle-icon" class="material-symbols-outlined toggle-icon">menu_open</span>
            <span class="toggle-text">Thu gọn menu</span>
        </button>

    </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sidebar     = document.getElementById("main-sidebar");
    const toggleBtn   = document.getElementById("sidebar-toggle-btn");
    const toggleIcon  = document.getElementById("toggle-icon");
    const mainContent = document.getElementById("main-content-layout");

    // ─── 1. KHÔI PHỤC TRẠNG THÁI SIDEBAR ───
    var initialStatus = window.__sidebarStatus || 'expanded';
    if (initialStatus === 'collapsed') {
        sidebar.classList.add('sidebar-collapsed');
        toggleIcon.innerText = 'keyboard_double_arrow_right';
    }
    document.documentElement.removeAttribute('data-sidebar');

    // ─── 2. TOGGLE SIDEBAR ───────────────────────────────────────────────────────────────────────
    toggleBtn.addEventListener('click', function () {
        sidebar.classList.contains('sidebar-collapsed') ? disableCollapsed() : enableCollapsed();
    });

    function enableCollapsed() {
        sidebar.classList.add('sidebar-collapsed');
        if (mainContent) {
            mainContent.classList.remove('md:pl-64');
            mainContent.classList.add('md:pl-[72px]');
            mainContent.style.marginLeft = '';
        }
        toggleIcon.innerText = 'keyboard_double_arrow_right';
        localStorage.setItem('sidebarStatus', 'collapsed');
        document.cookie = 'sidebarStatus=collapsed; path=/; max-age=' + (30 * 24 * 60 * 60);
    }

    function disableCollapsed() {
        sidebar.classList.remove('sidebar-collapsed');
        if (mainContent) {
            mainContent.classList.remove('md:pl-[72px]');
            mainContent.classList.add('md:pl-64');
            mainContent.style.marginLeft = '';
        }
        toggleIcon.innerText = 'menu_open';
        localStorage.setItem('sidebarStatus', 'expanded');
        document.cookie = 'sidebarStatus=expanded; path=/; max-age=' + (30 * 24 * 60 * 60);
    }

    // ─── 3. AJAX NAVIGATION — không reload trang, chỉ swap nội dung <main> ──────────────────────
    // 3. THAY ĐỔI SỬ DỤNG :not(.nav-link-exit-workspace) ĐỂ KHÔNG CHẶN NÚT THOÁT VÀ ĐĂNG XUẤT
    const navLinks = sidebar.querySelectorAll('a.nav-link:not(.nav-link-exit-workspace)');

    navLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const url = link.getAttribute('href');
            navigateTo(url, link);
        });
    });

    // Xử lý nút back/forward của trình duyệt
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.workspace) {
            loadContent(e.state.url, e.state.page, false);
        }
    });

    function navigateTo(url, link) {
        setActiveLink(link);
        var pageParam = new URLSearchParams(url.split('?')[1] || '').get('page') || 'home';
        history.pushState({ workspace: true, url: url, page: pageParam }, '', url);
        loadContent(url, pageParam, true);
    }

    function loadContent(url, pageParam, animate) {
        var inner = mainContent.querySelector('#ajax-content');
        if (inner && animate) {
            inner.style.opacity = '0';
            inner.style.transition = 'opacity 150ms ease';
        }

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
        })
        .then(function (html) {
            var parser   = new DOMParser();
            var doc      = parser.parseFromString(html, 'text/html');
            var newInner = doc.getElementById('ajax-content');
            var newMain  = doc.getElementById('main-content-layout');
            var targetEl = mainContent.querySelector('#ajax-content');

            if (newInner && targetEl) {
                targetEl.innerHTML = newInner.innerHTML;
            } else if (newMain && targetEl) {
                targetEl.innerHTML = newMain.innerHTML;
            } else if (newMain) {
                mainContent.innerHTML = newMain.innerHTML;
            }

            var newTitle = doc.querySelector('title');
            if (newTitle) document.title = newTitle.textContent;

            var updatedInner = mainContent.querySelector('#ajax-content');
            if (updatedInner) {
                updatedInner.style.transition = 'opacity 150ms ease';
                updatedInner.style.opacity    = '0';
                requestAnimationFrame(function () {
                    updatedInner.style.opacity = '1';
                });
            }

            syncActiveLink(pageParam);
            rerunScripts(mainContent);
        })
        .catch(function (err) {
            console.error('[AJAX Nav] Lỗi tải trang:', err);
            window.location.href = url;
        });
    }

    function setActiveLink(activeLink) {
        navLinks.forEach(function (l) { l.classList.remove('active'); });
        if (activeLink) activeLink.classList.add('active');
    }

    function syncActiveLink(pageParam) {
        navLinks.forEach(function (l) {
            var href      = l.getAttribute('href') || '';
            var linkPage  = new URLSearchParams(href.split('?')[1] || '').get('page') || '';
            l.classList.toggle('active', linkPage === pageParam);
        });
    }

    function rerunScripts(el) {
        var scripts = el.querySelectorAll('script');
        scripts.forEach(function (oldScript) {
            var src = oldScript.getAttribute('src') || '';
            if (src.includes('tailwindcss') || src.includes('main.js') || src.includes('ajax.js')) {
                return; 
            }

            var newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(function (attr) {
                newScript.setAttribute(attr.name, attr.value);
            });
            newScript.textContent = oldScript.textContent;
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }
});
</script>
<?php endif; ?>