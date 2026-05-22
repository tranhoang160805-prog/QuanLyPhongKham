 <?php
// Giả lập dữ liệu từ Database hoặc API để đổ vào Dashboard
$username = "Quản trị viên";
$last_update = "09:42 AM";

$kpi = [
    'doanh_thu' => [
        'value' => '45,200,000',
        'trend' => '+12%'
    ],
    'luot_kham' => [
        'value' => 128,
        'vs_yesterday' => 112
    ],
    'lap_day' => [
        'value' => 85
    ]
];

// Dữ liệu biểu đồ doanh thu 7 ngày (tỷ lệ phần trăm chiều cao cột)
$chart_data = [
    'Th 2' => 40,
    'Th 3' => 55,
    'Th 4' => 45,
    'Th 5' => 70,
    'Th 6' => 65,
    'Th 7' => 85,
    'CN'   => 95 // Ngày hiện tại active
];

// Danh sách hoạt động gần đây
$activities = [
    ['time' => '09:30 AM', 'content' => 'Thanh toán hóa đơn: BN-2023-0045', 'staff' => 'Lê Thị Mai (Kế toán)', 'status' => 'success', 'status_text' => 'Hoàn tất'],
    ['time' => '09:15 AM', 'content' => 'Cập nhật hồ sơ bệnh án: Nguyễn Văn An', 'staff' => 'BS. Trần Hùng', 'status' => 'info', 'status_text' => 'Đã lưu'],
    ['time' => '08:45 AM', 'content' => 'Đăng ký khám mới: Phạm Thu Thảo', 'staff' => 'Lễ tân - Sảnh A', 'status' => 'success', 'status_text' => 'Tiếp nhận']
];
?>

<!-- Khởi tạo file CSS riêng cho Dashboard không dùng Tailwind -->
<style>
    :root {
        --color-primary: #0284c7;
        --color-primary-light: #e0f2fe;
        --color-secondary: #0f766e;
        --color-secondary-light: #ccfbf1;
        --color-text-main: #1e293b;
        --color-text-muted: #64748b;
        --color-border: #cbd5e1;
        --color-bg-card: #ffffff;
        --color-bg-sub: #f8fafc;
        --color-error: #dc2626;
        --color-error-bg: #fee2e2;
        --gap-container: 24px;
    }

    /* Layout chính */
    .dashboard-container {
        padding: var(--gap-container);
        min-height: 100vh;
        font-family: system-ui, -apple-system, sans-serif;
        color: var(--color-text-main);
    }

    /* Welcome Section */
    .welcome-section {
        margin-bottom: 32px;
    }
    .welcome-section h1 {
        font-size: 28px;
        font-weight: 700;
        color: var(--color-primary);
        margin: 0 0 4px 0;
    }
    .welcome-section p {
        font-size: 15px;
        color: var(--color-text-muted);
        margin: 0;
    }

    /* Bento KPI Grid */
    .section-title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }
    .update-time {
        font-size: 13px;
        color: var(--color-text-muted);
    }
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: var(--gap-container);
        margin-bottom: 32px;
    }
    @media (min-width: 768px) {
        .kpi-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }

    /* KPI Card Style */
    .kpi-card {
        background-color: var(--color-bg-card);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
    }
    .kpi-icon {
        padding: 8px;
        border-radius: 8px;
        display: inline-flex;
    }
    .kpi-icon.blue { background-color: var(--color-primary-light); color: var(--color-primary); }
    .kpi-icon.teal { background-color: var(--color-secondary-light); color: var(--color-secondary); }
    .kpi-icon.orange { background-color: #ffedd5; color: #ea580c; }
    
    .kpi-trend {
        display: flex;
        align-items: center;
        font-size: 13px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 9999px;
    }
    .kpi-trend.up { background-color: rgba(15, 118, 110, 0.1); color: var(--color-secondary); }
    .kpi-compare { font-size: 13px; color: var(--color-text-muted); }
    
    .kpi-label { font-size: 14px; color: var(--color-text-muted); margin: 0 0 4px 0; }
    .kpi-value { font-size: 24px; font-weight: 700; margin: 0; }
    .kpi-value span { font-size: 14px; font-weight: 400; color: var(--color-text-muted); }

    /* Progress bar cho lịch hẹn */
    .progress-bar-container { width: 64px; height: 6px; background-color: var(--color-border); border-radius: 9999px; margin-top: 12px; overflow: hidden; }
    .progress-fill { background-color: #ea580c; height: 100%; }

    /* Main Dashboard Layout Columns */
    .dashboard-columns {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: var(--gap-container);
    }
    @media (min-width: 1024px) {
        .dashboard-columns { grid-template-columns: repeat(12, minmax(0, 1fr)); }
        .col-left { grid-column: span 8 / span 8; }
        .col-right { grid-column: span 4 / span 4; }
    }

    .col-left, .col-right { display: flex; flex-direction: column; gap: var(--gap-container); }

    /* Toàn bộ Card chứa nội dung lớn */
    .content-card {
        background-color: var(--color-bg-card);
        border-radius: 12px;
        border: 1px solid var(--color-border);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 24px;
    }
    .content-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .content-card-title { font-size: 16px; font-weight: 600; margin: 0; }
    .card-action-btn { color: var(--color-primary); font-size: 14px; font-weight: 500; background: none; border: none; cursor: pointer; text-decoration: none; }
    .card-action-btn:hover { text-decoration: underline; }

    /* Biểu đồ hình cột phẳng */
    .chart-container {
        height: 240px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        padding: 0 16px;
    }
    .chart-column { display: flex; flex-direction: column; align-items: center; flex: 1; }
    .chart-bar { width: 100%; background-color: rgba(2, 132, 199, 0.2); border-radius: 8px 8px 0 0; transition: height 0.3s ease; }
    .chart-bar.highlight { background-color: rgba(2, 132, 199, 0.5); }
    .chart-bar.active { background-color: var(--color-primary); }
    .chart-label { margin-top: 8px; font-size: 13px; color: var(--color-text-muted); }
    .chart-label.active { color: var(--color-primary); font-weight: 700; }

    /* Bảng hoạt động gần đây */
    .table-card { padding: 0; overflow: hidden; }
    .table-header-box { padding: 16px 24px; border-b: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); }
    .responsive-table-wrapper { overflow-x: auto; width: 100%; }
    .custom-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 14px; }
    .custom-table thead { background-color: var(--color-bg-sub); color: var(--color-text-muted); }
    .custom-table th { padding: 12px 24px; font-weight: 500; }
    .custom-table td { padding: 16px 24px; border-bottom: 1px solid var(--color-border); }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .custom-table tbody tr:hover { background-color: var(--color-bg-sub); }
    .row-highlight { font-weight: 600; color: var(--color-text-main); }
    
    /* Trạng thái Badge */
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .badge.success { background-color: rgba(15, 118, 110, 0.1); color: var(--color-secondary); }
    .badge.info { background-color: rgba(2, 132, 199, 0.1); color: var(--color-primary); }

    /* Cột cảnh báo vận hành */
    .alert-title-group { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; }
    .alert-title-group .material-symbols-outlined { color: var(--color-error); }
    .alert-box-list { display: flex; flex-direction: column; gap: 24px; }
    
    /* Hộp cảnh báo khẩn cấp */
    .alert-box { padding: 16px; border-radius: 12px; border: 1px solid var(--color-border); }
    .alert-box.emergency { background-color: rgba(220, 38, 38, 0.03); border-color: rgba(220, 38, 38, 0.2); }
    .alert-box.normal { background-color: var(--color-bg-sub); border-color: var(--color-border); }
    
    .alert-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .alert-tag { font-size: 12px; font-weight: 600; text-transform: uppercase; display: flex; align-items: center; gap: 4px; }
    .alert-tag.error { color: var(--color-error); }
    .alert-tag.primary { color: var(--color-primary); }
    
    .badge-emergency { background-color: var(--color-error); color: #ffffff; font-size: 10px; padding: 2px 6px; border-radius: 9999px; font-weight: 700; }
    .alert-heading { font-size: 15px; font-weight: 700; margin: 0 0 4px 0; }
    .alert-desc { font-size: 13px; color: var(--color-text-muted); margin: 0 0 12px 0; }
    
    /* Buttons hành động */
    .btn-alert { width: 100%; py: 8px; border: none; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
    .btn-alert.danger { background-color: var(--color-error); color: white; }
    .btn-alert.outline { background-color: transparent; border: 1px solid var(--color-primary); color: var(--color-primary); }
    .btn-alert:hover { opacity: 0.9; }
    .btn-alert.outline:hover { background-color: rgba(2, 132, 199, 0.05); }

    /* Danh sách nhân sự nghỉ */
    .personnel-list { display: flex; flex-direction: column; gap: 12px; margin: 12px 0; }
    .personnel-item { display: flex; align-items: center; gap: 12px; }
    .avatar-text { width: 32px; height: 32px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; }
    .avatar-text.blue { background-color: var(--color-primary-light); color: var(--color-primary); }
    .avatar-text.teal { background-color: var(--color-secondary-light); color: var(--color-secondary); }
    .personnel-info p { font-size: 13px; font-weight: 600; margin: 0; }
    .personnel-info span { font-size: 11px; color: var(--color-text-muted); }

    /* Thống kê nhanh */
    .quick-summary-box { background-color: rgba(241, 245, 249, 0.6); padding: 16px; border-radius: 12px; }
    .summary-title { font-size: 11px; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 12px 0; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 13px; }
    .summary-row:last-child { margin-bottom: 0; }
    .summary-row .val-blue { font-weight: 700; color: var(--color-primary); }
    .summary-row .val-teal { font-weight: 700; color: var(--color-secondary); }
</style>

<div class="dashboard-container">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <h1>Chào buổi sáng, <?php echo htmlspecialchars($username); ?></h1>
        <p>Hệ thống MedPrecision đang hoạt động ổn định. Dưới đây là tóm tắt hoạt động hôm nay.</p>
    </section>

    <!-- KPI Bento Grid -->
    <section style="margin-bottom: 32px;">
        <div class="section-title-wrapper">
            <h3 class="section-title">Chỉ số KPI chính</h3>
            <span class="update-time">Cập nhật: <?php echo $last_update; ?></span>
        </div>
        
        <div class="kpi-grid">
            <!-- KPI Card 1 -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon blue">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <span class="kpi-trend up">
                        <span class="material-symbols-outlined" style="font-size: 16px; margin-right: 4px;">trending_up</span> 
                        <?php echo $kpi['doanh_thu']['trend']; ?>
                    </span>
                </div>
                <p class="kpi-label">Doanh thu hôm nay</p>
                <p class="kpi-value"><?php echo $kpi['doanh_thu']['value']; ?> VND</p>
            </div>

            <!-- KPI Card 2 -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon teal">
                        <span class="material-symbols-outlined">person_search</span>
                    </div>
                    <span class="kpi-compare">Vs. hôm qua: <?php echo $kpi['luot_kham']['vs_yesterday']; ?></span>
                </div>
                <p class="kpi-label">Lượt khám hôm nay</p>
                <p class="kpi-value"><?php echo $kpi['luot_kham']['value']; ?> <span>bệnh nhân</span></p>
            </div>

            <!-- KPI Card 3 -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <div class="kpi-icon orange">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-fill" style="width: <?php echo $kpi['lap_day']['value']; ?>%;"></div>
                    </div>
                </div>
                <p class="kpi-label">Tỷ lệ lấp đầy lịch hẹn</p>
                <p class="kpi-value"><?php echo $kpi['lap_day']['value']; ?>%</p>
            </div>
        </div>
    </section>

    <!-- Biểu đồ & Bảng chi tiết -->
    <div class="dashboard-columns">
        
        <!-- Cột trái: Biểu đồ & Bảng dữ liệu -->
        <div class="col-left">
            
            <!-- Revenue Trend Chart -->
            <div class="content-card">
                <div class="content-card-header">
                    <h3 class="content-card-title">Xu hướng doanh thu 7 ngày qua</h3>
                    <div style="display: flex; gap: 8px;">
                        <span style="display: flex; align-items: center; font-size: 13px; color: var(--color-text-muted);">
                            <span style="width: 12px; height: 12px; background-color: var(--color-primary); border-radius: 50%; margin-right: 8px; display: inline-block;"></span>
                            Doanh thu
                        </span>
                    </div>
                </div>
                
                <div class="chart-container">
                    <?php foreach ($chart_data as $day => $percentage): ?>
                        <?php 
                            // Phân tách trạng thái CSS cho ngày chủ nhật (Active) hoặc ngày thường
                            $bar_class = ($day === 'CN') ? 'chart-bar active' : (($percentage > 70) ? 'chart-bar highlight' : 'chart-bar');
                            $label_class = ($day === 'CN') ? 'chart-label active' : 'chart-label';
                        ?>
                        <div class="chart-column">
                            <div class="<?php echo $bar_class; ?>" style="height: <?php echo $percentage; ?>%;"></div>
                            <span class="<?php echo $label_class; ?>"><?php echo $day; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Activities Table -->
            <div class="content-card table-card">
                <div class="table-header-box">
                    <h3 class="content-card-title">Hoạt động gần đây</h3>
                    <button class="card-action-btn">Xem tất cả</button>
                </div>
                <div class="responsive-table-wrapper">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Nội dung</th>
                                <th>Nhân viên</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $act): ?>
                            <tr>
                                <td><?php echo $act['time']; ?></td>
                                <td class="row-highlight"><?php echo htmlspecialchars($act['content']); ?></td>
                                <td><?php echo htmlspecialchars($act['staff']); ?></td>
                                <td>
                                    <span class="badge <?php echo $act['status']; ?>">
                                        <?php echo $act['status_text']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cột phải: Cảnh báo vận hành -->
        <div class="col-right">
            <div class="content-card" style="height: 100%;">
                <div class="alert-title-group">
                    <span class="material-symbols-outlined">warning</span>
                    <h3 class="content-card-title">Cảnh báo vận hành</h3>
                </div>

                <div class="alert-box-list">
                    <!-- Inventory Alert -->
                    <div class="alert-box emergency">
                        <div class="alert-header">
                            <span class="alert-tag error">
                                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">inventory_2</span> Kho dược
                            </span>
                            <span class="badge-emergency">KHẨN CẤP</span>
                        </div>
                        <p class="alert-heading">15 mặt hàng sắp hết</p>
                        <p class="alert-desc">Paracetamol 500mg, Amoxicillin...</p>
                        <button class="btn-alert danger">Nhập kho ngay</button>
                    </div>

                    <!-- Personnel Alert -->
                    <div class="alert-box normal">
                        <div class="alert-header">
                            <span class="alert-tag primary">
                                <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">groups</span> Nhân sự
                            </span>
                        </div>
                        <p class="alert-heading">2 bác sĩ xin nghỉ phép hôm nay</p>
                        
                        <div class="personnel-list">
                            <div class="personnel-item">
                                <div class="avatar-text blue">TH</div>
                                <div class="personnel-info">
                                    <p>BS. Trần Hùng</p>
                                    <span>Nghỉ ốm (Đã phê duyệt)</span>
                                </div>
                            </div>
                            <div class="personnel-item">
                                <div class="avatar-text teal">NL</div>
                                <div class="personnel-info">
                                    <p>BS. Nguyễn Lan</p>
                                    <span>Việc riêng (Đang chờ)</span>
                                </div>
                            </div>
                        </div>
                        <button class="btn-alert outline">Điều phối lịch trực</button>
                    </div>

                    <!-- Quick Summary -->
                    <div class="quick-summary-box">
                        <p class="summary-title">Thống kê nhanh tuần này</p>
                        <div class="summary-row">
                            <span>Đánh giá khách hàng</span>
                            <span class="val-blue">4.8/5.0</span>
                        </div>
                        <div class="summary-row">
                            <span>Lỗi vận hành hệ thống</span>
                            <span class="val-teal">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
