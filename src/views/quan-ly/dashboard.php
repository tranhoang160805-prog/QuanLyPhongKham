<link rel="stylesheet" href="public/assets/css/QuanLy/dashboard.css">

<section class="ql-page ql-dashboard" data-dashboard-page>
    <div class="ql-page-header">
        <div>
            <p class="ql-eyebrow">Tổng quan vận hành</p>
            <h1>Dashboard quản lý</h1>
            <p>Theo dõi nhanh tình hình khám bệnh, doanh thu, lịch hẹn và cảnh báo kho thuốc.</p>
        </div>
        <button class="ql-btn ql-btn-primary" type="button" id="dashboard-refresh">
            <span class="material-symbols-outlined">refresh</span>
            Tải lại
        </button>
    </div>

    <div class="ql-alert" id="dashboard-alert" hidden></div>

    <div class="ql-stat-grid">
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">payments</span>
            <p>Doanh thu hôm nay</p>
            <strong id="stat-revenue-today">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">account_balance_wallet</span>
            <p>Doanh thu tháng</p>
            <strong id="stat-revenue-month">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">clinical_notes</span>
            <p>Phiếu khám hôm nay</p>
            <strong id="stat-checkups-today">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">event_available</span>
            <p>Lịch hẹn hôm nay</p>
            <strong id="stat-appointments-today">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">groups</span>
            <p>Tổng bệnh nhân</p>
            <strong id="stat-patients-total">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">badge</span>
            <p>Nhân viên đang hoạt động</p>
            <strong id="stat-staff-active">--</strong>
        </article>
                <article class="ql-stat-card ql-stat-warn">
            <span class="material-symbols-outlined">biotech</span>
            <p>Thuốc đang còn sử dụng</p>
            <strong id="stat-cls-waiting">--</strong>
        </article>
        <article class="ql-stat-card ql-stat-warn">
            <span class="material-symbols-outlined">inventory_2</span>
            <p>Thuốc cảnh báo tồn</p>
            <strong id="stat-low-stock">--</strong>
        </article>

    </div>

    <div class="ql-dashboard-grid">
        <section class="ql-panel ql-panel-wide">
            <div class="ql-panel-header">
                <div>
                    <h2>Doanh thu 7 ngày gần nhất</h2>
                    <p>Dữ liệu tính từ bảng thanh toán.</p>
                </div>
            </div>
            <div class="ql-bars" id="dashboard-revenue-bars"></div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Trạng thái phiếu khám</h2>
                    <p>Thống kê 30 ngày gần nhất.</p>
                </div>
            </div>
            <div class="ql-list" id="dashboard-status-list"></div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Phiếu khám hôm nay</h2>
                    <p>Các phiếu vừa được tạo/cập nhật.</p>
                </div>
            </div>
            <div class="ql-table-wrap">
                <table class="ql-table">
                    <thead>
                        <tr>
                            <th>Mã phiếu</th>
                            <th>Bệnh nhân</th>
                            <th>Bác sĩ</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="dashboard-checkups"></tbody>
                </table>
            </div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Cảnh báo kho thuốc</h2>
                    <p>Thuốc sắp hết, hết hạn hoặc sắp hết hạn.</p>
                </div>
            </div>
            <div class="ql-list" id="dashboard-medicine-alerts"></div>
        </section>
    </div>
</section>

<script>
(function () {
    const apiUrl = 'src/api/quanLyDashboard.php';
    const alertBox = document.getElementById('dashboard-alert');

    const money = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        maximumFractionDigits: 0
    });

    function text(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    function showAlert(message, type = 'error') {
        alertBox.hidden = false;
        alertBox.className = `ql-alert ql-alert-${type}`;
        alertBox.textContent = message;
    }

    function clearAlert() {
        alertBox.hidden = true;
        alertBox.textContent = '';
    }

    function safe(value, fallback = 'Chưa có') {
        return value === null || value === undefined || value === '' ? fallback : value;
    }

    function renderBars(items) {
        const target = document.getElementById('dashboard-revenue-bars');
        const max = Math.max(...items.map(item => Number(item.value) || 0), 1);
        target.innerHTML = items.map(item => {
            const percent = Math.max(4, Math.round(((Number(item.value) || 0) / max) * 100));
            return `
                <div class="ql-bar-item">
                    <div class="ql-bar-value">${money.format(Number(item.value) || 0)}</div>
                    <div class="ql-bar-track"><span style="height:${percent}%"></span></div>
                    <div class="ql-bar-label">${item.label}</div>
                </div>
            `;
        }).join('');
    }

    function renderStatus(items) {
        const target = document.getElementById('dashboard-status-list');
        if (!items.length) {
            target.innerHTML = '<p class="ql-empty">Chưa có dữ liệu trạng thái.</p>';
            return;
        }
        const max = Math.max(...items.map(item => Number(item.SoLuong) || 0), 1);
        target.innerHTML = items.map(item => {
            const percent = Math.round(((Number(item.SoLuong) || 0) / max) * 100);
            return `
                <div class="ql-progress-row">
                    <div><strong>${safe(item.TenTrangThai)}</strong><span>${Number(item.SoLuong) || 0} phiếu</span></div>
                    <div class="ql-progress"><span style="width:${percent}%"></span></div>
                </div>
            `;
        }).join('');
    }

    // function renderAppointments(items) {
    //     const target = document.getElementById('dashboard-appointments');
    //     if (!items.length) {
    //         target.innerHTML = '<p class="ql-empty">Không có lịch hẹn sắp tới.</p>';
    //         return;
    //     }
    //     target.innerHTML = items.map(item => `
    //         <div class="ql-list-row">
    //             <span class="material-symbols-outlined">event</span>
    //             <div>
    //                 <strong>${safe(item.HoTen)}</strong>
    //                 <p>${safe(item.MaBN)} - ${safe(item.SoDienThoai)} - ${safe(item.NgayHen)} ${safe(item.GioHen, '')}</p>
    //             </div>
    //             <em>${safe(item.TrangThai)}</em>
    //         </div>
    //     `).join('');
    // }

    function renderCheckups(items) {
        const target = document.getElementById('dashboard-checkups');
        if (!items.length) {
            target.innerHTML = '<tr><td colspan="4" class="ql-empty-cell">Chưa có phiếu khám.</td></tr>';
            return;
        }
        target.innerHTML = items.map(item => `
            <tr>
                <td><strong>${safe(item.MaPhieuKhamCode)}</strong><span>${safe(item.NgayKham)} ${safe(item.GioKham, '')}</span></td>
                <td>${safe(item.TenBenhNhan)}<span>${safe(item.MaBN)}</span></td>
                <td>${safe(item.TenBacSi, 'Chưa gán')}</td>
                <td><span class="ql-badge">${safe(item.TrangThai)}</span></td>
            </tr>
        `).join('');
    }

    function renderMedicine(items) {
        const target = document.getElementById('dashboard-medicine-alerts');
        if (!items.length) {
            target.innerHTML = '<p class="ql-empty">Kho thuốc đang ổn định.</p>';
            return;
        }
        target.innerHTML = items.map(item => `
            <div class="ql-list-row ql-list-row-warning">
                <span class="material-symbols-outlined">warning</span>
                <div>
                    <strong>${safe(item.TenThuoc)}</strong>
                    <p>${safe(item.MaThuocCode)} - Tồn ${Number(item.SoLuongTon) || 0}/${Number(item.TonToiThieu) || 0} - HSD ${safe(item.HanSuDung)}</p>
                </div>
            </div>
        `).join('');
    }

    async function loadDashboard() {
        clearAlert();
        document.querySelector('[data-dashboard-page]').classList.add('is-loading');
        try {
            const response = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Không tải được dashboard.');

            const data = result.data;
            const summary = data.summary || {};
            text('stat-revenue-today', money.format(Number(summary.revenue_today) || 0));
            text('stat-revenue-month', money.format(Number(summary.revenue_month) || 0));
            text('stat-checkups-today', Number(summary.checkups_today) || 0);
            text('stat-appointments-today', Number(summary.appointments_today) || 0);
            text('stat-patients-total', Number(summary.patients_total) || 0);
            text('stat-staff-active', Number(summary.staff_active) || 0);
            text('stat-low-stock', Number(summary.low_stock) || 0);
            text('stat-cls-waiting', Number(summary.cls_waiting) || 0);

            renderBars(data.revenue_last_7_days || []);
            renderStatus(data.status_stats || []);
            // renderAppointments(data.upcoming_appointments || []);
            renderCheckups(data.recent_checkups || []);
            renderMedicine(data.medicine_alerts || []);
        } catch (error) {
            showAlert(error.message);
        } finally {
            document.querySelector('[data-dashboard-page]').classList.remove('is-loading');
        }
    }

    document.getElementById('dashboard-refresh').addEventListener('click', loadDashboard);
    loadDashboard();
})();
</script>