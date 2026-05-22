<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Thiết lập cứng role gốc đăng nhập để test (Thực tế lấy từ Database)
$_SESSION['user_role'] = 'admin'; 
$real_role = $_SESSION['user_role'];

// 2. Lấy quyền hiển thị hiện tại (Phục vụ tính năng Admin chuyển đổi vai trò)
// Nếu chưa chuyển bao giờ, mặc định lấy theo quyền gốc vừa đăng nhập
$menu_role = isset($_SESSION['current_view_role']) ? $_SESSION['current_view_role'] : $real_role;

// Bảo mật: Nếu tài khoản gốc không phải admin thì tuyệt đối không cho dùng quyền ảo
if ($real_role !== 'admin') {
    $menu_role = $real_role;
}

// 3. Lấy trang hiện tại từ URL, mặc định là 'dashboard'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

/* ==========================================================================
   4. CẤU HÌNH ĐƯỜNG DẪN FILE & PHÂN QUYỀN TRUY CẬP (Khớp 100% với Navbar)
   ========================================================================== */
$allowed_pages = [
    // --- PHÂN HỆ ADMIN ---
    'dashboard'       => ['file' => '../src/views/quan-ly/dashboard.php',       'roles' => ['admin']],
    'nhan-su'         => ['file' => '../src/views/quan-ly/nhan-su.php',         'roles' => ['admin']],
    'bao-cao'         => ['file' => '../src/views/quan-ly/bao-cao.php',         'roles' => ['admin']],
    'danh-muc'        => ['file' => '../src/views/quan-ly/danh-muc.php',        'roles' => ['admin']],
    'cai-dat'         => ['file' => '../src/views/quan-ly/cai-dat-he-thong.php',         'roles' => ['admin']],

    // --- PHÂN HỆ BỆNH NHÂN ---
    'dat-lich'        => ['file' => '../src/views/benh-nhan/dat-lich.php',       'roles' => ['benh-nhan']],
    'lich-hen'        => ['file' => '../src/views/benh-nhan/lich-hen.php',       'roles' => ['benh-nhan']],
    'lich-su-kham'    => ['file' => '../src/views/benh-nhan/lich-su-kham.php',   'roles' => ['benh-nhan']],
    'don-thuoc'       => ['file' => '../src/views/benh-nhan/don-thuoc.php',      'roles' => ['benh-nhan']],
    'xet-nghiem'      => ['file' => '../src/views/benh-nhan/xet-nghiem.php',     'roles' => ['benh-nhan']],
    'hoa-don-bn'      => ['file' => '../src/views/benh-nhan/hoa-don.php',         'roles' => ['benh-nhan']],

    // --- PHÂN HỆ LỄ TÂN ---
    'ds-benh-nhan'    => ['file' => '../src/views/le-tan/danh-sach-benh-nhan.php',       'roles' => ['le-tan', 'dieu-duong', 'bac-si', 'ky-thuat-vien', 'duoc-si']],
    'phieu-kham'      => ['file' => '../src/views/le-tan/phieu-kham.php',       'roles' => ['le-tan']],
    'lich-hen-lt'     => ['file' => '../src/views/le-tan/lich-hen.php',         'roles' => ['le-tan']],
    'hoa-don-lt'      => ['file' => '../src/views/le-tan/hoa-don.php',          'roles' => ['le-tan']],

    // --- PHÂN HỆ ĐIỀU DƯỠNG ---
    'so-kham'         => ['file' => '../src/views/dieu-duong/so-kham.php',      'roles' => ['dieu-duong']],
    'ls-so-kham'      => ['file' => '../src/views/dieu-duong/ls-so-kham.php',   'roles' => ['dieu-duong']],

    // --- PHÂN HỆ BÁC SĨ ---
    'kham-benh'       => ['file' => '../src/views/bac-si/kham-benh.php',        'roles' => ['bac-si']],
    'chi-dinh'        => ['file' => '../src/views/bac-si/chi-dinh-cls.php',         'roles' => ['bac-si']],
    'cap-thuoc-bs'    => ['file' => '../src/views/bac-si/cap-thuoc.php',        'roles' => ['bac-si']],

    // --- PHÂN HỆ KỸ THUẬT VIÊN ---
    'xet-nghiem-ktv'  => ['file' => '../src/views/ky-thuat-vien/xet-nghiem.php', 'roles' => ['ky-thuat-vien']],
    'ls-xet-nghiem'   => ['file' => '../src/views/ky-thuat-vien/lich-su.php',    'roles' => ['ky-thuat-vien']],

    // --- PHÂN HỆ DƯỢC SĨ ---
    'kho-thuoc'       => ['file' => '../src/views/duoc-si/kho-thuoc.php',       'roles' => ['duoc-si']],
    'cap-phat'        => ['file' => '../src/views/duoc-si/cap-phat.php',        'roles' => ['duoc-si']]
];

// 5. XỬ LÝ ĐIỀU HƯỚNG VÀ BẢO MẬT TRUY CẬP
if (array_key_exists($page, $allowed_pages)) {
    // Kiểm tra xem Quyền hiện tại đang xem ($menu_role) có được phép truy cập trang này không
    if (in_array($menu_role, $allowed_pages[$page]['roles'])) {
        $content_file = $allowed_pages[$page]['file'];
    } else {
        // Có tồn tại trang nhưng vai trò hiện tại không được phép vào
        $content_file = 'pages/403_forbidden.php'; 
    }
} else {
    // Trang không tồn tại trong hệ thống
    $content_file = 'pages/error404.php';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MedPrecision Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    colors: { 
                        primary: '#0284c7', 
                        'surface': '#ffffff', 
                        'surface-container-low': '#f8fafc', 
                        'surface-container-lowest': '#ffffff', 
                        'surface-container-high': '#f1f5f9', 
                        'on-surface-variant': '#64748b', 
                        'outline-variant': '#cbd5e1' 
                    } 
                } 
            } 
        }
    </script>
</head>
<body class="bg-slate-50 antialiased">

    <!-- Gọi thanh điều hướng Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Vùng hiển thị nội dung động của từng trang -->
    <main class="ml-[240px] pt-[60px] min-h-screen p-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <?php 
            // Kiểm tra xem file view có thực sự tồn tại trên ổ đĩa không trước khi nhúng nhằm tránh lỗi crash hệ thống
            if (file_exists($content_file)) {
                include $content_file; 
            } else {
                echo "<div class='p-4 text-red-500 bg-red-50 rounded-xl border border-red-100'>
                        <strong>Lỗi hệ thống:</strong> Không tìm thấy tệp tin giao diện cấu hình tại mục <code>$content_file</code>. Vui lòng tạo file này hoặc kiểm tra lại đường dẫn!
                      </div>";
            }
            ?>
        </div>
    </main>

</body>
</html>