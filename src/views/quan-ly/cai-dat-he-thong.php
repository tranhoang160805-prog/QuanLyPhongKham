<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../config/database.php';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 1. Truy vấn lấy toàn bộ cấu hình hệ thống
    $stmt = $pdo->query("SELECT KhoacCauHinh, GiaTri FROM CAUHINHHETHONG");
    
    /* 
       Sử dụng PDO::FETCH_KEY_PAIR để chuyển dữ liệu từ dạng danh sách hàng 
       thành dạng mảng Key => Value (ví dụ: ['ten_phong_kham' => 'Phòng Khám Đa Khoa'])
    */
    $configs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // 2. Gán dữ liệu thực tế từ DB vào biến (nếu DB trống thì lấy giá trị mặc định)
    $clinic_name = $configs['ten_phong_kham'] ?? 'ClinicCentral';
    $address     = $configs['dia_chi']        ?? 'Chưa cập nhật địa chỉ';
    $hotline     = $configs['so_dien_thoai']  ?? '1900 1234';
    $logo_url    = $configs['logo_url']       ?? 'https://lh3.googleusercontent.com/...';
    
    // Lấy thêm thông tin người cập nhật cuối cùng (nếu cần hiển thị ở footer)
    $stmt_log = $pdo->query("
        SELECT c.ThoiGianCapNhat, t.TenNguoiDung 
        FROM CAUHINHHETHONG c
        LEFT JOIN TAIKHOAN t ON c.NguoiCapNhat = t.MaTaiKhoan
        WHERE c.ThoiGianCapNhat IS NOT NULL
        ORDER BY c.ThoiGianCapNhat DESC LIMIT 1
    ");
    $last_log = $stmt_log->fetch();
    
    $last_update = $last_log ? date('d/m/Y lúc H:i', strtotime($last_log['ThoiGianCapNhat'])) : 'Chưa có cập nhật';
    $updated_by  = $last_log['TenNguoiDung'] ?? 'Hệ thống';

} catch (\PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

// Mảng cấu hình giờ làm việc: [Giờ mở, Giờ đóng, Trạng thái mở (true/false)]
$schedule = [
    "Thứ Hai"  => ["08:00", "20:00", true],
    "Thứ Ba"   => ["08:00", "20:00", true],
    "Thứ Tư"   => ["08:00", "20:00", true],
    "Thứ Năm"  => ["08:00", "20:00", true],
    "Thứ Sáu"  => ["08:00", "20:00", true],
    "Thứ Bảy"  => ["08:00", "17:00", true],
    "Chủ Nhật" => ["00:00", "00:00", false],
];

// Xử lý khi người dùng nhấn nút Save (gửi Form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ở đây bạn có thể viết code kết nối database để cập nhật dữ liệu
    // Ví dụ: $clinic_name = $_POST['clinic_name'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt Thông tin phòng khám</title>
    
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">

    <!-- ==========================================
         2. TOÀN BỘ GIAO DIỆN CSS THUẦN (Không Tailwind)
         ========================================== -->
    <style>
        :root {
            --primary: #005fb8;
            --primary-container: rgba(0, 95, 184, 0.1);
            --on-surface: #1a1c1e;
            --on-surface-variant: #43474e;
            --outline-variant: #c3c7cf;
            --surface: #ffffff;
            --surface-container: #f0f2f5;
            --error: #ba1a1a;
            --error-container: rgba(186, 26, 26, 0.05);
            --bg-body: #f8f9fc;
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

        /* Layout Sidebar Giả lập (ml-[240px]) */
        .main-content {
            min-height: 100vh;
        }

        .container {
            margin: 0 auto;
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
        }

        .header-title h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .header-title p {
            color: var(--on-surface-variant);
            font-size: 0.9rem;
        }

        /* Layout hai cột */
        .grid-layout {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 992px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
            .main-content {
                padding-left: 0;
            }
        }

        /* Card cấu trúc */
        .card {
            background: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--outline-variant);
            margin-bottom: 1.25rem;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .icon-primary {
            color: var(--primary);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--on-surface-variant);
            margin-bottom: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        input[type="time"] {
            width: 100%;
            height: 40px;
            padding: 0 1rem;
            border: 1px solid var(--outline-variant);
            border-radius: 8px;
            outline: none;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        textarea {
            height: 80px;
            padding: 0.6rem 1rem;
            resize: none;
        }

        input:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 95, 184, 0.15);
        }

        /* Input có icon bên trong */
        .input-relative {
            position: relative;
        }

        .input-relative .material-symbols-outlined {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--on-surface-variant);
            font-size: 1.2rem;
        }

        .input-relative textarea ~ .material-symbols-outlined {
            top: 20px;
            transform: none;
        }

        .input-relative input,
        .input-relative textarea {
            padding-left: 2.5rem;
        }

        /* Nhận diện thương hiệu */
        .branding-container {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .logo-box {
            width: 120px;
            height: 120px;
            border: 2px dashed var(--outline-variant);
            border-radius: 12px;
            background-color: var(--bg-body);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .logo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .logo-box:hover .upload-overlay {
            opacity: 1;
        }

        .upload-overlay span {
            color: #fff;
        }

        .branding-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .branding-info p {
            font-size: 0.8rem;
            color: var(--on-surface-variant);
            margin-bottom: 1rem;
        }

        /* Giờ làm việc */
        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .schedule-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border: 1px solid var(--outline-variant);
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .schedule-row:hover {
            background-color: var(--bg-body);
        }

        .schedule-row.closed-row {
            border-color: rgba(186, 26, 26, 0.2);
            background-color: var(--error-container);
        }

        .day-name {
            width: 90px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .schedule-row.closed-row .day-name {
            color: var(--error);
        }

        .time-box {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .time-box input {
            width: 95px;
            height: 32px;
            padding: 0 0.5rem;
            text-align: center;
        }

        .time-box .separator {
            color: var(--on-surface-variant);
        }

        .status-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .status-text {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--on-surface-variant);
            width: 30px;
        }

        .closed-row .status-text {
            color: var(--error);
        }

        /* Info box thông báo */
        .info-box {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            background-color: var(--primary-container);
            border-radius: 8px;
            margin-top: 1.5rem;
        }

        .info-box p {
            font-size: 0.8rem;
            color: var(--on-surface-variant);
        }

        /* Buttons chung */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #fff;
            padding: 0.6rem 1.5rem;
        }

        .btn-primary:hover {
            filter: brightness(1.1);
        }

        .btn-primary:active {
            transform: scale(0.97);
        }

        .btn-light {
            background-color: var(--primary-container);
            color: var(--primary);
            padding: 0.5rem 1rem;
        }

        .btn-light:hover {
            background-color: rgba(0, 95, 184, 0.2);
        }

        .btn-danger {
            background: transparent;
            color: var(--error);
            padding: 0.5rem 1rem;
        }

        .btn-danger:hover {
            background-color: var(--error-container);
        }

        .btn-text {
            background: transparent;
            color: var(--on-surface-variant);
            padding: 0.5rem 1rem;
        }

        .btn-text:hover {
            color: var(--on-surface);
        }

        /* Footer */
        .form-footer {
            margin-top: 1.5rem;
            padding: 1rem 1.5rem;
            background: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .history-log {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--on-surface-variant);
        }

        .btn-group {
            display: flex;
            gap: 0.75rem;
        }
    </style>
</head>
<body>

<main class="main-content">
    <div class="container">
        
        <!-- Header Section -->
        <header class="header-section">
            <div class="header-title">
                <h2>Cài đặt Thông tin phòng khám</h2>
                <p>Quản lý các thông tin định danh và vận hành cốt lõi của ClinicCentral.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="triggerSubmit()">
                <span class="material-symbols-outlined" style="font-size: 1.1rem">save</span> Lưu thay đổi
            </button>
        </header>

        <!-- Form chính gộp chung dữ liệu -->
        <form id="clinicForm" method="POST" action="">
            <div class="grid-layout">
                
                <!-- Cột trái: Định danh & Thương hiệu -->
                <div class="column-left">
                    
                    <!-- Thông tin định danh -->
                    <section class="card">
                        <div class="card-header">
                            <span class="material-symbols-outlined icon-primary">domain</span>
                            <h3>Thông tin định danh</h3>
                        </div>
                        
                        <div class="form-group">
                            <label>Tên phòng khám</label>
                            <input type="text" name="clinic_name" value="<?php echo htmlspecialchars($clinic_name); ?>"/>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Hotline chăm sóc khách hàng</label>
                                <div class="input-relative">
                                    <span class="material-symbols-outlined">call</span>
                                    <input type="text" name="hotline" value="<?php echo htmlspecialchars($hotline); ?>" placeholder="1900 1234"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email liên hệ</label>
                                <div class="input-relative">
                                    <span class="material-symbols-outlined">mail</span>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="contact@cliniccentral.vn"/>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Địa chỉ trụ sở chính</label>
                            <div class="input-relative">
                                <span class="material-symbols-outlined">location_on</span>
                                <textarea name="address"><?php echo htmlspecialchars($address); ?></textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Nhận diện thương hiệu -->
                    <section class="card">
                        <div class="card-header">
                            <span class="material-symbols-outlined icon-primary">palette</span>
                            <h3>Bộ nhận diện thương hiệu</h3>
                        </div>
                        <div class="branding-container">
                            <div class="logo-box">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7c2Y0elbJLRbOjTx12kaI-Lsj-dFatIAenwbYKLNipD8d9gCkHfbopFpTnVtkOCixKdsw_svm3RBXAdfi6tztwJpnlZaOUYDKgSsJOgxYbkMUBaHn5y_3f7Unp_i0YLX57CeuWAIEkL2rDwuvZsOyz_6AZKplE4Osomco0rQyneXj4POE-r0N1ukzvPvNODV8tjrlmbMfNJmFV5hfYOmk_72UPkGswqYkCg6gHTHXs7QuIkMbHR-zMg5TIsfVw2MV4iPXIL4-Ogc" alt="Clinic Logo"/>
                                <div class="upload-overlay">
                                    <span class="material-symbols-outlined">upload</span>
                                </div>
                            </div>
                            <div class="branding-info">
                                <h4>Logo phòng khám</h4>
                                <p>Tải lên logo chính thức của phòng khám. Định dạng hỗ trợ: PNG, SVG (tối đa 2MB). Kích thước khuyến nghị: 512x512px.</p>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light">Tải ảnh mới</button>
                                    <button type="button" class="btn btn-danger">Gỡ bỏ</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Cột phải: Giờ mở cửa -->
                <div class="column-right">
                    <section class="card" style="height: calc(100% - 1.5rem)">
                        <div class="card-header">
                            <span class="material-symbols-outlined icon-primary">schedule</span>
                            <h3>Giờ mở cửa</h3>
                        </div>
                        
                        <div class="schedule-list">
                            <?php foreach ($schedule as $day => $data): 
                                $isOpen = $data[2];
                                $rowClass = $isOpen ? "" : "closed-row";
                                $disabledAttr = $isOpen ? "" : "disabled";
                            ?>
                            <div class="schedule-row <?php echo $rowClass; ?>">
                                <span class="day-name"><?php echo $day; ?></span>
                                <div class="time-box">
                                    <input type="time" name="start_time[<?php echo $day; ?>]" value="<?php echo $data[0]; ?>" <?php echo $disabledAttr; ?>/>
                                    <span class="separator">—</span>
                                    <input type="time" name="end_time[<?php echo $day; ?>]" value="<?php echo $data[1]; ?>" <?php echo $disabledAttr; ?>/>
                                </div>
                                <label class="status-toggle">
                                    <input type="checkbox" class="toggle-checkbox" name="open_status[<?php echo $day; ?>]" <?php echo $isOpen ? 'checked' : ''; ?>/>
                                    <span class="status-text"><?php echo $isOpen ? 'Mở' : 'Nghỉ'; ?></span>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="info-box">
                            <span class="material-symbols-outlined icon-primary" style="font-size: 1.3rem">info</span>
                            <p>Lịch làm việc này sẽ được hiển thị công khai trên cổng đăng ký khám trực tuyến dành cho bệnh nhân.</p>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Footer Alert / Actions Bar -->
            <footer class="form-footer">
                <div class="history-log">
                    <span class="material-symbols-outlined" style="font-size: 1.2rem">history</span>
                    <span>Lần cập nhật cuối: <?php echo $last_update; ?> bởi <strong><?php echo $updated_by; ?></strong></span>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-text">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary">Lưu tất cả thay đổi</button>
                </div>
            </footer>
        </form>

    </div>
</main>

<!-- ==========================================
     3. TOÀN BỘ LOGIC JAVASCRIPT THUẦN
     ========================================== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.toggle-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('.schedule-row');
            const timeInputs = row.querySelectorAll('input[type="time"]');
            const statusText = row.querySelector('.status-text');

            if (this.checked) {
                row.classList.remove('closed-row');
                statusText.textContent = 'Mở';
                timeInputs.forEach(input => input.disabled = false);
            } else {
                row.classList.add('closed-row');
                statusText.textContent = 'Nghỉ';
                timeInputs.forEach(input => input.disabled = true);
            }
        });
    });
});

// Hàm để kích hoạt submit form từ nút trên header
function triggerSubmit() {
    document.getElementById('clinicForm').submit();
}
</script>

</body>
</html>