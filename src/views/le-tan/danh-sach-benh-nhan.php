<?php
// ==========================================================================
// 1. CẤU HÌNH KHỞI TẠO SESSION & KẾT NỐI DATABASE
// ==========================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/database.php';

// Kiểm tra nếu biến kết nối chưa được khởi tạo từ file config
if (!isset($pdo)) {
    die("Lỗi hệ thống: Không tìm thấy kết nối Cơ sở dữ liệu.");
}

// Giả định tên bác sĩ đang đăng nhập (Thực tế sẽ lấy từ $_SESSION['User'])
$doctor_name = $_SESSION['doctor_name'] ?? "BS. Nguyễn Văn A";

$STATUS_CONFIG = [
    0 => [
        'label' => 'Đang chờ',
        'class' => 'status-waiting',
        'icon'  => 'hourglass_empty'
    ],
    1 => [
        'label' => 'Đang khám',
        'class' => 'status-examining',
        'icon'  => 'stethoscope'
    ],
    2 => [
        'label' => 'Đã hoàn thành',
        'class' => 'status-done',
        'icon'  => 'check_circle'
    ],
    3 => [
        'label' => 'Chờ kết quả',
        'class' => 'status-pending-result',
        'icon'  => 'biotech'
    ],
    4 => [
        'label' => 'Đã hủy ca',
        'class' => 'status-cancelled',
        'icon'  => 'cancel'
    ]
];

// ==========================================================================
// 2. TRUY VẤN DỮ LIỆU THỰC TẾ TỪ DATABASE (BẢNG BENHNHAN)
// ==========================================================================
try {
    // 2.1. Đếm số lượng thống kê động của ngày hôm nay (0: Đang chờ, 1: Đang khám, 2: Đã hoàn thành)
    $sql_stats = "SELECT 
                    COUNT(CASE WHEN TrangThai = 0 THEN 1 END) as waiting,
                    COUNT(CASE WHEN TrangThai = 1 THEN 1 END) as examining,
                    COUNT(CASE WHEN TrangThai = 2 THEN 1 END) as done,
                    COUNT(MaBenhNhan) as total
                  FROM BENHNHAN 
                  WHERE DATE(NgayTao) = CURRENT_DATE()";
                  
    $stmt_stats = $pdo->query($sql_stats);
    $db_stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

    $waiting_count   = $db_stats['waiting'] ?? 0;
    $examining_count = $db_stats['examining'] ?? 0;
    $done_count      = $db_stats['done'] ?? 0;
    $total_count     = $db_stats['total'] ?? 0;

    // 2.2. Lấy danh sách bệnh nhân cần xử lý hôm nay 
    // FIX: Lấy các trạng thái cần xử lý (0: Chờ, 1: Khám, 3: Chờ kết quả) thay vì chỉ lấy 0 và 1
    // Sắp xếp thông minh bằng FIELD: Người đang khám (1) lên đầu, đến Chờ kết quả (3), rồi đến Đang chờ (0)
    $sql_list = "SELECT MaBenhNhan, MaBN, HoTen, DiUng, TrangThai, NgayTao 
                 FROM BENHNHAN 
                 WHERE TrangThai IN (0, 1, 3) AND DATE(NgayTao) = CURRENT_DATE()
                 ORDER BY FIELD(TrangThai, 1, 3, 0), NgayTao ASC";
                 
    $stmt_list = $pdo->query($sql_list);
    $waiting_list = $stmt_list->fetchAll(PDO::FETCH_ASSOC);

    // 2.3. Tự động tìm ca khám tiếp theo (Là người có trạng thái = 0 xếp hàng sớm nhất)
    $sql_next = "SELECT MaBenhNhan, MaBN, HoTen, DiUng, NgayTao 
                 FROM BENHNHAN 
                 WHERE TrangThai = 0 AND DATE(NgayTao) = CURRENT_DATE()
                 ORDER BY NgayTao ASC 
                 LIMIT 1";
                 
    $stmt_next = $pdo->query($sql_next);
    $next_appointment = $stmt_next->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
}

// Hàm bổ trợ sinh Avatar chữ từ Họ và Tên của bệnh nhân
function getAvatarText($fullName) {
    $parts = explode(' ', trim($fullName));
    if (count($parts) >= 2) {
        $first = mb_substr($parts[count($parts) - 2], 0, 1, 'UTF-8');
        $last = mb_substr($parts[count($parts) - 1], 0, 1, 'UTF-8');
        return mb_strtoupper($first . $last, 'UTF-8');
    }
    return mb_strtoupper(mb_substr($fullName, 0, 2, 'UTF-8'), 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điều khiển bác sĩ</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">

    <style>
        :root {
            --primary: #005fb8;
            --on-primary: #ffffff;
            --primary-container: #d6e3ff;
            --on-primary-container: #001b3e;
            --secondary: #006874;
            --tertiary: #166534;
            --surface: #ffffff;
            --bg-body: #f8f9fc;
            --on-surface: #1a1c1e;
            --on-surface-variant: #43474e;
            --outline-variant: #c3c7cf;
            --surface-variant-low: #f0f4f9;
            --surface-variant-high: #e0e4e9;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --shadow-surgical: 0 4px 20px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: var(--on-surface);
            line-height: 1.5;
        }

        .main-content {
            min-height: 100vh;
        }

        .container {
            max-width: 1440px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Welcome Header */
        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .welcome-section h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--on-surface);
        }

        .welcome-section p {
            color: var(--on-surface-variant);
            font-size: 0.95rem;
        }

        /* Bento Grid Thống kê */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        .stat-card {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            padding: 1.25rem 1.5rem;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow-surgical);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-total .stat-icon { background: rgba(0, 95, 184, 0.1); color: var(--primary); }
        .stat-processing .stat-icon { background: rgba(0, 104, 116, 0.1); color: var(--secondary); }
        .stat-done .stat-icon { background: rgba(22, 101, 52, 0.1); color: var(--tertiary); }

        .stat-info p {
            font-size: 0.85rem;
            color: var(--on-surface-variant);
            margin-bottom: 0.15rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        /* Giao diện chính 2 cột */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* Cột bên trái: Danh sách chờ */
        .card-table-container {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-surgical);
            overflow: hidden;
        }

        .card-table-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--outline-variant);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-table-header h3 {
            font-size: 1.15rem;
            font-weight: 600;
        }

        .badge-count {
            background-color: var(--surface-variant-high);
            color: var(--on-surface-variant);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Bảng hiển thị bệnh nhân */
        .responsive-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            background-color: var(--surface-variant-low);
            padding: 0.85rem 1.5rem;
            color: var(--on-surface-variant);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--outline-variant);
            font-size: 0.9rem;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background-color: var(--surface-variant-low);
        }

        .patient-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .patient-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
            background-color: #e8f4fd;
            color: var(--primary);
        }

        .patient-name {
            font-weight: 600;
        }

        .text-variant {
            color: var(--on-surface-variant);
        }

        /* Trạng thái khám */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            gap: 4px;
        }

        .status-badge .material-symbols-outlined {
            font-size: 0.95rem;
        }

        .status-badge.status-waiting {
            background-color: rgba(0, 95, 184, 0.08);
            color: var(--primary);
        }

        .status-badge.status-examining {
            background-color: rgba(0, 104, 116, 0.08);
            color: var(--secondary);
        }

        .status-badge.status-done {
            background-color: rgba(22, 101, 52, 0.08);
            color: var(--tertiary);
        }

        .status-badge.status-pending-result {
            background-color: rgba(183, 129, 3, 0.08);
            color: #b78103;
        }

        .status-badge.status-cancelled {
            background-color: rgba(180, 35, 24, 0.08);
            color: #b42318;
        }

        .table-footer-action {
            padding: 1rem;
            background-color: var(--surface-variant-low);
            border-top: 1px solid var(--outline-variant);
            text-align: center;
        }

        /* Cột bên phải: Tiện ích nổi bật */
        .sidebar-space {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Ca khám tiếp theo widget */
        .appointment-hero-card {
            background-color: var(--primary-container);
            color: var(--on-primary-container);
            padding: 1.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-surgical);
            position: relative;
            overflow: hidden;
        }

        .appointment-tag {
            background: rgba(255, 255, 255, 0.25);
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appointment-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 1rem;
        }

        .appointment-meta h4 {
            font-size: 1.35rem;
            font-weight: 600;
        }

        .appointment-meta p {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .appointment-icon-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .appointment-details {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .detail-item .material-symbols-outlined {
            font-size: 1.15rem;
        }

        /* Hộp thông tin khoa khám */
        .info-panel {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-surgical);
            overflow: hidden;
        }

        .info-panel-header {
            padding: 1rem;
            background-color: #e8f4fd;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .info-panel-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .info-row span:first-child {
            color: var(--on-surface-variant);
            font-size: 0.85rem;
        }

        .info-row span:last-child {
            font-weight: 600;
        }

        /* Nhật ký hoạt động */
        .activity-card {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-xl);
            padding: 1.25rem;
            box-shadow: var(--shadow-surgical);
        }

        .activity-card h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .activity-feed {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .dot-primary { background-color: var(--primary); }
        .dot-secondary { background-color: var(--secondary); }

        .activity-text p {
            font-size: 0.85rem;
        }

        .activity-text span {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--on-surface-variant);
            text-transform: uppercase;
        }

        /* Nút bấm hệ thống */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--on-primary);
            padding: 0.6rem 1.25rem;
            box-shadow: var(--shadow-surgical);
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.8rem;
        }

        .btn-outline {
            border: 1px solid var(--outline-variant);
            color: var(--on-surface-variant);
            background-color: var(--surface-variant-low);
        }

        .btn-outline:hover {
            background-color: var(--surface-variant-high);
        }

        .btn-full {
            width: 100%;
            padding: 0.75rem;
            margin-top: 1.25rem;
            background-color: #ffffff;
            color: var(--primary);
        }

        .btn-full:hover {
            background-color: var(--surface-variant-low);
        }

        .btn-link {
            background: transparent;
            color: var(--primary);
            font-size: 0.9rem;
        }

        .btn-link:hover {
            text-decoration: underline;
        }

        .deco-circle-1 { position: absolute; top: -20px; right: -20px; width: 128px; height: 128px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(24px); }
        .deco-circle-2 { position: absolute; bottom: -20px; left: -20px; width: 96px; height: 96px; background: rgba(0,0,0,0.04); border-radius: 50%; filter: blur(16px); }

        @media (max-width: 1024px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .main-content { padding-left: 0; }
            .welcome-section { flex-direction: column; align-items: flex-start; }
            .welcome-section .btn { width: 100%; }
            .stats-grid { grid-template-columns: 1fr; gap: 1rem; }
        }
    </style>
</head>
<body>

<main class="main-content">
    <div class="container">
        
        <section class="welcome-section">
            <div>
                <h2>Xin chào, <?php echo htmlspecialchars($doctor_name); ?></h2>
                <p>Hôm nay bạn có <?php echo $waiting_count; ?> bệnh nhân trong danh sách chờ.</p>
            </div>
            <button class="btn btn-primary">
                <span class="material-symbols-outlined">add</span> Thêm bệnh nhân
            </button>
        </section>

        <section class="stats-grid">
            <div class="stat-card stat-total">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <div class="stat-info">
                    <p>Tổng số BN hôm nay</p>
                    <h3><?php echo $total_count; ?></h3>
                </div>
            </div>
            
            <div class="stat-card stat-processing">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">hourglass_empty</span>
                </div>
                <div class="stat-info">
                    <p>BN đang khám</p>
                    <h3><?php echo sprintf("%02d", $examining_count); ?></h3>
                </div>
            </div>
            
            <div class="stat-card stat-done">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="stat-info">
                    <p>BN đã hoàn thành</p>
                    <h3><?php echo $done_count; ?></h3>
                </div>
            </div>
        </section>

        <div class="main-grid">
            
            <section class="card-table-container">
                <div class="card-table-header">
                    <h3>Danh sách xử lý khám</h3>
                    <span class="badge-count"><?php echo count($waiting_list); ?> người cần xử lý</span>
                </div>
                
                <div class="responsive-table-wrapper">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Mã BN</th>
                                <th>Bệnh nhân</th>
                                <th>Tiền sử dị ứng</th>
                                <th>Thời gian đến</th>
                                <th>Trạng thái</th>
                                <th style="text-align: right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($waiting_list)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--on-surface-variant);">
                                        Không có bệnh nhân nào trong danh sách đợi khám ngày hôm nay.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($waiting_list as $patient): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($patient['MaBN']); ?></strong></td>
                                        <td>
                                            <div class="patient-cell">
                                                <div class="patient-avatar">
                                                    <?php echo getAvatarText($patient['HoTen']); ?>
                                                </div>
                                                <span class="patient-name"><?php echo htmlspecialchars($patient['HoTen']); ?></span>
                                            </div>
                                        </td>
                                        <td class="text-variant">
                                            <?php echo !empty($patient['DiUng']) ? htmlspecialchars($patient['DiUng']) : '<em>Không có</em>'; ?>
                                        </td>
                                        <td class="text-variant"><?php echo date('H:i', strtotime($patient['NgayTao'])); ?></td>
                                        <td>
                                            <?php 
                                            // Lấy giá trị số trạng thái từ DB (Mặc định là 0 nếu trống)
                                            $status_val = $patient['TrangThai'] ?? 0; 
                                                                        
                                            // Ánh xạ vào mảng cấu hình $STATUS_CONFIG, dự phòng trường hợp ID lạ không tồn tại
                                            $current_status = $STATUS_CONFIG[$status_val] ?? [
                                                'label' => 'Không rõ',
                                                'class' => 'status-waiting',
                                                'icon'  => 'help'
                                            ];
                                            ?>
                                            <span class="status-badge <?php echo $current_status['class']; ?>">
                                                <span class="material-symbols-outlined"><?php echo $current_status['icon']; ?></span>
                                                <?php echo htmlspecialchars($current_status['label']); ?>
                                            </span>
                                        </td>
                                        <td style="text-align: right">
                                            <?php if ($status_val == 0): ?>
                                                <button class="btn btn-primary btn-sm" onclick="changeStatus(<?php echo $patient['MaBenhNhan']; ?>, 1)">Gọi vào khám</button>
                                            <?php elseif ($status_val == 1): ?>
                                                <button class="btn btn-outline btn-sm" onclick="changeStatus(<?php echo $patient['MaBenhNhan']; ?>, 3)">Chỉ định xét nghiệm</button>
                                            <?php elseif ($status_val == 3): ?>
                                                <button class="btn btn-primary btn-sm" style="background-color: var(--tertiary);" onclick="changeStatus(<?php echo $patient['MaBenhNhan']; ?>, 2)">Kết luận bệnh</button>
                                            <?php else: ?>
                                                <button class="btn btn-outline btn-sm" onclick="viewProfile(<?php echo $patient['MaBenhNhan']; ?>)">Xem hồ sơ</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer-action">
                    <button class="btn btn-link">Xem tất cả danh sách</button>
                </div>
            </section>

            <aside class="sidebar-space">
                
                <div class="appointment-hero-card">
                    <div class="deco-circle-1"></div>
                    <div class="deco-circle-2"></div>
                    
                    <span class="appointment-tag">Ca khám tiếp theo</span>
                    
                    <?php if ($next_appointment): ?>
                        <div class="appointment-meta">
                            <div>
                                <h4><?php echo htmlspecialchars($next_appointment['HoTen']); ?></h4>
                                <p>Mã BN: <?php echo htmlspecialchars($next_appointment['MaBN']); ?></p>
                            </div>
                            <div class="appointment-icon-box">
                                <span class="material-symbols-outlined" style="color: #fff">stethoscope</span>
                            </div>
                        </div>
                        
                        <div class="appointment-details">
                            <div class="detail-item">
                                <span class="material-symbols-outlined">schedule</span>
                                <span>Đến lúc: <?php echo date('H:i', strtotime($next_appointment['NgayTao'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="material-symbols-outlined">medical_services</span>
                                <span>Dị ứng: <?php echo !empty($next_appointment['DiUng']) ? htmlspecialchars($next_appointment['DiUng']) : 'Không có'; ?></span>
                            </div>
                        </div>
                        
                        <button class="btn btn-full" onclick="changeStatus(<?php echo $next_appointment['MaBenhNhan']; ?>, 1)">Bắt đầu ca khám</button>
                    <?php else: ?>
                        <div class="appointment-meta" style="margin-top: 1rem;">
                            <p>Hiện tại không có bệnh nhân nào đang đợi khám tiếp theo.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-panel">
                    <div class="info-panel-header">Thông tin khoa khám</div>
                    <div class="info-panel-body">
                        <div class="info-row">
                            <span>Phòng khám:</span>
                            <span>P.302 - Lầu 3</span>
                        </div>
                        <div class="info-row">
                            <span>Điều dưỡng hỗ trợ:</span>
                            <span>Lê Thị Mai</span>
                        </div>
                        <div class="info-row">
                            <span>Thời gian làm việc:</span>
                            <span>08:00 - 17:00</span>
                        </div>
                    </div>
                </div>

                <div class="activity-card">
                    <h4>Hoạt động gần đây</h4>
                    <div class="activity-feed">
                        <div class="activity-item">
                            <div class="dot dot-secondary"></div>
                            <div class="activity-text">
                                <p>Hoàn thành khám cho một bệnh nhân</p>
                                <span>Mới đây</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="dot dot-primary"></div>
                            <div class="activity-text">
                                <p>Cơ sở dữ liệu bệnh nhân vừa cập nhật</p>
                                <span>Hệ thống</span>
                            </div>
                        </div>
                    </div>
                </div>

            </aside>
        </div>

    </div>
</main>

<script>
    // Hàm gọi xử lý cập nhật trạng thái động thay cho hàm cũ
    function changeStatus(id, newStatus) {
        alert("Gửi lệnh cập nhật Bệnh nhân ID [ " + id + " ] sang mã trạng thái mới: " + newStatus);
        // Khi tích hợp logic backend thực tế:
        // window.location.href = "cap-nhat-trang-thai.php?id=" + id + "&status=" + newStatus;
    }

    function viewProfile(id) {
        alert("Đang hiển thị hồ sơ bệnh án chi tiết bệnh nhân ID: " + id);
    }
</script>

</body>
</html>