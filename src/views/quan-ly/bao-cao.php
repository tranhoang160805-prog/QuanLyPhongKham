<link rel="stylesheet" href="public/assets/css/QuanLy/bao-cao.css">

<section class="ql-page ql-report" data-report-page>
    <div class="ql-page-header">
        <div>
            <p class="ql-eyebrow">Thống kê tài chính</p>
            <h1>Báo cáo doanh thu</h1>
            <p>Theo dõi doanh thu, phương thức thanh toán, chuyên khoa và giao dịch chi tiết.</p>
        </div>
        <button class="ql-btn ql-btn-secondary" type="button" id="report-export">
            <span class="material-symbols-outlined">download</span>
            Xuất CSV
        </button>
    </div>

    <div class="ql-alert" id="report-alert" hidden></div>

    <form class="ql-filter-bar" id="report-filter">
        <label>
            <span>Từ ngày</span>
            <input type="date" name="start">
        </label>
        <label>
            <span>Đến ngày</span>
            <input type="date" name="end">
        </label>
        <label>
            <span>Phương thức</span>
            <select name="payment_method" id="report-payment-method">
                <option value="">Tất cả</option>
            </select>
        </label>
        <label class="ql-filter-search">
            <span>Tìm kiếm</span>
            <input type="search" name="search" placeholder="Hóa đơn, phiếu khám, bệnh nhân...">
        </label>
        <button class="ql-btn ql-btn-primary" type="submit">
            <span class="material-symbols-outlined">filter_alt</span>
            Áp dụng
        </button>
    </form>

    <div class="ql-stat-grid">
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">payments</span>
            <p>Tổng doanh thu</p>
            <strong id="report-total-revenue">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">receipt_long</span>
            <p>Giao dịch</p>
            <strong id="report-total-transactions">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">groups</span>
            <p>Bệnh nhân đã thu</p>
            <strong id="report-total-patients">--</strong>
        </article>
        <article class="ql-stat-card">
            <span class="material-symbols-outlined">query_stats</span>
            <p>Trung bình/giao dịch</p>
            <strong id="report-avg-payment">--</strong>
        </article>
    </div>

    <div class="ql-report-grid">
        <section class="ql-panel ql-panel-wide">
            <div class="ql-panel-header">
                <div>
                    <h2>Doanh thu theo ngày</h2>
                    <p>Biểu đồ cột được tạo bằng CSS từ dữ liệu thanh toán.</p>
                </div>
            </div>
            <div class="ql-bars" id="report-daily-bars"></div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Cơ cấu thanh toán</h2>
                    <p>Tỷ trọng theo phương thức.</p>
                </div>
            </div>
            <div class="ql-donut" id="report-donut">
                <div><strong id="report-donut-total">0%</strong><span>dữ liệu</span></div>
            </div>
            <div class="ql-list" id="report-payment-list"></div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Doanh thu chuyên khoa</h2>
                    <p>Top chuyên khoa theo số tiền thu.</p>
                </div>
            </div>
            <div class="ql-list" id="report-specialty-list"></div>
        </section>
    </div>

    <section class="ql-panel">
        <div class="ql-panel-header ql-table-header">
            <div>
                <h2>Chi tiết giao dịch</h2>
                <p id="report-pagination-info">Đang tải...</p>
            </div>
            <div class="ql-pagination">
                <button class="ql-icon-btn" type="button" id="report-prev" title="Trang trước">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <span id="report-page-label">1/1</span>
                <button class="ql-icon-btn" type="button" id="report-next" title="Trang sau">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
        <div class="ql-table-wrap">
            <table class="ql-table">
                <thead>
                    <tr>
                        <th>Hóa đơn</th>
                        <th>Ngày thu</th>
                        <th>Bệnh nhân</th>
                        <th>Chuyên khoa</th>
                        <th>Phương thức</th>
                        <th class="ql-text-right">Số tiền</th>
                    </tr>
                </thead>
                <tbody id="report-transactions"></tbody>
            </table>
        </div>
    </section>
</section>

<script>
(function () {
    const apiUrl = 'src/api/quanLyBaoCao.php';
    const form = document.getElementById('report-filter');
    const alertBox = document.getElementById('report-alert');
    const paymentSelect = document.getElementById('report-payment-method');
    const state = { page: 1, totalPages: 1, transactions: [] };

    const money = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        maximumFractionDigits: 0
    });

    // Helper functions for dates
    function today() {
        return new Date().toISOString().slice(0, 10);
    }

    function monthStart() {
        const d = new Date();
        d.setDate(1);
        return d.toISOString().slice(0, 10);
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

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    function safe(value, fallback = 'Chưa có') {
        return value === null || value === undefined || value === '' ? fallback : value;
    }

    function params() {
        const data = new FormData(form);
        data.set('page', state.page);
        data.set('limit', 10);
        return new URLSearchParams(data).toString();
    }

    function renderPaymentMethods(methods) {
        const current = paymentSelect.value;
        paymentSelect.innerHTML = '<option value="">Tất cả</option>' + methods.map(method =>
            `<option value="${method.MaPhuongThuc}">${method.TenPhuongThuc}</option>`
        ).join('');
        paymentSelect.value = current;
    }

    function renderBars(items) {
        const target = document.getElementById('report-daily-bars');
        if (!items.length) {
            target.innerHTML = '<p class="ql-empty">Không có doanh thu trong khoảng ngày này.</p>';
            return;
        }
        const max = Math.max(...items.map(item => Number(item.revenue) || 0), 1);
        target.innerHTML = items.map(item => {
            const percent = Math.max(4, Math.round(((Number(item.revenue) || 0) / max) * 100));
            return `
                <div class="ql-bar-item">
                    <div class="ql-bar-value">${money.format(Number(item.revenue) || 0)}</div>
                    <div class="ql-bar-track"><span style="height:${percent}%"></span></div>
                    <div class="ql-bar-label">${item.date_label.slice(5).split('-').reverse().join('/')}</div>
                </div>
            `;
        }).join('');
    }

    function renderPaymentStats(items, total) {
        const target = document.getElementById('report-payment-list');
        if (!items.length || total <= 0) {
            document.getElementById('report-donut').style.background = '#eef2f7';
            setText('report-donut-total', '0%');
            target.innerHTML = '<p class="ql-empty">Chưa có giao dịch.</p>';
            return;
        }
        const colors = ['#0f6bff', '#10a37f', '#f59e0b', '#7c3aed', '#ef4444'];
        let cursor = 0;
        const stops = items.map((item, index) => {
            const value = Number(item.revenue) || 0;
            const start = cursor;
            cursor += (value / total) * 100;
            return `${colors[index % colors.length]} ${start}% ${cursor}%`;
        });
        document.getElementById('report-donut').style.background = `conic-gradient(${stops.join(', ')})`;
        setText('report-donut-total', '100%');
        target.innerHTML = items.map((item, index) => {
            const percent = total > 0 ? Math.round(((Number(item.revenue) || 0) / total) * 100) : 0;
            return `
                <div class="ql-list-row">
                    <i style="background:${colors[index % colors.length]}"></i>
                    <div>
                        <strong>${safe(item.method_name)}</strong>
                        <p>${Number(item.transactions) || 0} giao dịch - ${money.format(Number(item.revenue) || 0)}</p>
                    </div>
                    <em>${percent}%</em>
                </div>
            `;
        }).join('');
    }

    function renderSpecialties(items) {
        const target = document.getElementById('report-specialty-list');
        if (!items.length) {
            target.innerHTML = '<p class="ql-empty">Chưa có dữ liệu chuyên khoa.</p>';
            return;
        }
        const max = Math.max(...items.map(item => Number(item.revenue) || 0), 1);
        target.innerHTML = items.map(item => {
            const percent = Math.round(((Number(item.revenue) || 0) / max) * 100);
            return `
                <div class="ql-progress-row">
                    <div><strong>${safe(item.specialty)}</strong><span>${Number(item.transactions) || 0} giao dịch</span></div>
                    <div class="ql-progress"><span style="width:${percent}%"></span></div>
                    <em>${money.format(Number(item.revenue) || 0)}</em>
                </div>
            `;
        }).join('');
    }

    function renderTransactions(items) {
        const target = document.getElementById('report-transactions');
        state.transactions = items;
        if (!items.length) {
            target.innerHTML = '<tr><td colspan="6" class="ql-empty-cell">Không có giao dịch phù hợp.</td></tr>';
            return;
        }
        target.innerHTML = items.map(item => `
            <tr>
                <td><strong>${safe(item.SoHoaDon)}</strong><span>${safe(item.MaPhieuKhamCode)}</span></td>
                <td>${safe(item.NgayThanhToan)}</td>
                <td>${safe(item.TenBenhNhan)}<span>${safe(item.MaBN)}</span></td>
                <td>${safe(item.TenChuyenKhoa)}</td>
                <td><span class="ql-badge">${safe(item.PhuongThuc)}</span></td>
                <td class="ql-text-right"><strong>${money.format(Number(item.SoTien) || 0)}</strong></td>
            </tr>
        `).join('');
    }

    function exportCsv() {
        if (!state.transactions.length) {
            showAlert('Không có dữ liệu để xuất.', 'error');
            return;
        }
        const header = ['SoHoaDon', 'NgayThanhToan', 'MaBN', 'TenBenhNhan', 'TenChuyenKhoa', 'PhuongThuc', 'SoTien'];
        const rows = state.transactions.map(item => header.map(key => `"${String(item[key] ?? '').replace(/"/g, '""')}"`).join(','));
        const blob = new Blob([[header.join(','), ...rows].join('\n')], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `bao-cao-doanh-thu-${Date.now()}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }

    async function loadReport() {
        clearAlert();
        document.querySelector('[data-report-page]').classList.add('is-loading');
        try {
            const response = await fetch(`${apiUrl}?${params()}`, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Không tải được báo cáo.');

            const data = result.data;
            const summary = data.summary || {};
            const total = Number(summary.total_revenue) || 0;
            setText('report-total-revenue', money.format(total));
            setText('report-total-transactions', Number(summary.total_transactions) || 0);
            setText('report-total-patients', Number(summary.total_patients) || 0);
            setText('report-avg-payment', money.format(Number(summary.avg_payment) || 0));

            renderPaymentMethods(data.payment_methods || []);
            renderBars(data.daily_revenue || []);
            renderPaymentStats(data.payment_stats || [], total);
            renderSpecialties(data.specialty_stats || []);
            renderTransactions(data.transactions || []);

            state.totalPages = Math.max(1, Number(data.pagination.total_pages) || 1);
            state.page = Math.min(state.page, state.totalPages);
            setText('report-page-label', `${state.page}/${state.totalPages}`);
            setText('report-pagination-info', `Hiển thị ${data.transactions.length} / ${Number(data.pagination.total_records) || 0} giao dịch`);
            document.getElementById('report-prev').disabled = state.page <= 1;
            document.getElementById('report-next').disabled = state.page >= state.totalPages;
        } catch (error) {
            showAlert(error.message);
        } finally {
            document.querySelector('[data-report-page]').classList.remove('is-loading');
        }
    }

    form.elements.start.value = monthStart();
    form.elements.end.value = today();

    form.addEventListener('submit', event => {
        event.preventDefault();
        state.page = 1;
        loadReport();
    });
    document.getElementById('report-prev').addEventListener('click', () => {
        if (state.page > 1) {
            state.page -= 1;
            loadReport();
        }
    });
    document.getElementById('report-next').addEventListener('click', () => {
        if (state.page < state.totalPages) {
            state.page += 1;
            loadReport();
        }
    });
    document.getElementById('report-export').addEventListener('click', exportCsv);

    loadReport();
})();
</script>