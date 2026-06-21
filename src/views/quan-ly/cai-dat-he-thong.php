<link rel="stylesheet" href="public/assets/css/QuanLy/cai-dat-he-thong.css">

<section class="ql-page ql-settings" data-settings-page>
    <div class="ql-page-header">
        <div>
            <p class="ql-eyebrow">Cấu hình hệ thống</p>
            <h1>Cài đặt phòng khám</h1>
            <p>Quản lý các giá trị trong bảng <code>cauhinhhethong</code> và áp dụng ngay cho hệ thống.</p>
        </div>
        <div class="ql-header-actions">
            <button class="ql-btn ql-btn-secondary" type="button" id="settings-reload">
                <span class="material-symbols-outlined">refresh</span>
                Tải lại
            </button>
            <button class="ql-btn ql-btn-primary" type="submit" form="settings-form">
                <span class="material-symbols-outlined">save</span>
                Lưu cấu hình
            </button>
        </div>
    </div>

    <div class="ql-alert" id="settings-alert" hidden></div>

    <form class="ql-settings-grid" id="settings-form">
        <section class="ql-panel ql-panel-main">
            <div class="ql-panel-header">
                <div>
                    <h2>Thông tin phòng khám</h2>
                    <p>Dữ liệu hiển thị trên trang công khai và các biểu mẫu.</p>
                </div>
            </div>

            <div class="ql-form-grid">
                <label class="ql-field ql-field-full">
                    <span>Tên phòng khám</span>
                    <input type="text" name="ten_phong_kham" required>
                </label>
                <label class="ql-field">
                    <span>Số điện thoại</span>
                    <input type="text" name="so_dien_thoai">
                </label>
                <label class="ql-field">
                    <span>Email</span>
                    <input type="email" name="email">
                </label>
                <label class="ql-field ql-field-full">
                    <span>Địa chỉ</span>
                    <textarea name="dia_chi" rows="3"></textarea>
                </label>
                <label class="ql-field ql-field-full">
                    <span>Đường dẫn logo</span>
                    <input type="text" name="logo_url" placeholder="public/assets/img/icon.png">
                </label>
            </div>
        </section>

        <aside class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Trạng thái hệ thống</h2>
                    <p>Bật/tắt chế độ bảo trì.</p>
                </div>
            </div>
            <label class="ql-switch-row">
                <input type="checkbox" name="bao_tri" value="1">
                <span></span>
                <div>
                    <strong>Chế độ bảo trì</strong>
                    <p>Khi bật, người dùng sẽ được chuyển đến trang bảo trì.</p>
                </div>
            </label>
            <div class="ql-last-update" id="settings-last-update">Đang tải lịch sử cập nhật...</div>
        </aside>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Thanh toán</h2>
                    <p>Cấu hình thông tin tài khoản ngân hàng.</p>
                </div>
            </div>
            <div class="ql-form-grid">
                <label class="ql-field">
                    <span>Ngân hàng</span>
                    <input type="text" name="ngan_hang">
                </label>
                <label class="ql-field">
                    <span>Số tài khoản</span>
                    <input type="text" name="stk">
                </label>
                <label class="ql-field ql-field-full">
                    <span>Chủ tài khoản</span>
                    <input type="text" name="ctk">
                </label>
            </div>
        </section>

        <section class="ql-panel">
            <div class="ql-panel-header">
                <div>
                    <h2>Quy tắc mã</h2>
                    <p>Tiền tố sinh mã bệnh nhân, phiếu khám và hóa đơn.</p>
                </div>
            </div>
            <div class="ql-code-grid">
                <label class="ql-field">
                    <span>Bệnh nhân</span>
                    <input type="text" name="tien_to_benh_nhan" maxlength="10">
                </label>
                <label class="ql-field">
                    <span>Phiếu khám</span>
                    <input type="text" name="tien_to_phieu_kham" maxlength="10">
                </label>
                <label class="ql-field">
                    <span>Hóa đơn</span>
                    <input type="text" name="tien_to_hoa_don" maxlength="10">
                </label>
            </div>
        </section>

        <section class="ql-panel ql-panel-main">
            <div class="ql-panel-header">
                <div>
                    <h2>Giờ mở cửa</h2>
                    <p>Lịch này được lưu dạng JSON trong cấu hình <code>gio_mo_cua</code>.</p>
                </div>
            </div>
            <div class="ql-schedule" id="settings-schedule"></div>
        </section>
    </form>
</section>

<script>
(function () {
    const apiUrl = 'src/api/quanLyCaiDat.php';
    const form = document.getElementById('settings-form');
    const alertBox = document.getElementById('settings-alert');
    const scheduleTarget = document.getElementById('settings-schedule');
    const lastUpdate = document.getElementById('settings-last-update');
    const dayNames = {
        Monday: 'Thứ hai',
        Tuesday: 'Thứ ba',
        Wednesday: 'Thứ tư',
        Thursday: 'Thứ năm',
        Friday: 'Thứ sáu',
        Saturday: 'Thứ bảy',
        Sunday: 'Chủ nhật'
    };

    function showAlert(message, type = 'error') {
        alertBox.hidden = false;
        alertBox.className = `ql-alert ql-alert-${type}`;
        alertBox.textContent = message;
    }

    function clearAlert() {
        alertBox.hidden = true;
        alertBox.textContent = '';
    }

    function setValue(name, value) {
        const input = form.elements[name];
        if (!input) return;
        if (input.type === 'checkbox') {
            input.checked = String(value) === '1' || value === true;
            return;
        }
        input.value = value || '';
    }

    function renderSchedule(schedule) {
        scheduleTarget.innerHTML = Object.keys(dayNames).map(day => {
            const row = schedule[day] || { start: '08:00', end: '17:00', open: false };
            return `
                <div class="ql-schedule-row" data-day="${day}">
                    <label class="ql-check">
                        <input type="checkbox" data-schedule-open ${row.open ? 'checked' : ''}>
                        <span></span>
                    </label>
                    <strong>${dayNames[day]}</strong>
                    <input type="time" data-schedule-start value="${row.start || '08:00'}" ${row.open ? '' : 'disabled'}>
                    <span class="ql-time-sep">đến</span>
                    <input type="time" data-schedule-end value="${row.end || '17:00'}" ${row.open ? '' : 'disabled'}>
                    <em>${row.open ? 'Mở cửa' : 'Nghỉ'}</em>
                </div>
            `;
        }).join('');

        scheduleTarget.querySelectorAll('[data-schedule-open]').forEach(input => {
            input.addEventListener('change', () => {
                const row = input.closest('.ql-schedule-row');
                const isOpen = input.checked;
                row.querySelector('[data-schedule-start]').disabled = !isOpen;
                row.querySelector('[data-schedule-end]').disabled = !isOpen;
                row.querySelector('em').textContent = isOpen ? 'Mở cửa' : 'Nghỉ';
            });
        });
    }

    function collectSchedule() {
        const schedule = {};
        scheduleTarget.querySelectorAll('.ql-schedule-row').forEach(row => {
            const day = row.dataset.day;
            schedule[day] = {
                open: row.querySelector('[data-schedule-open]').checked,
                start: row.querySelector('[data-schedule-start]').value || '08:00',
                end: row.querySelector('[data-schedule-end]').value || '17:00'
            };
        });
        return schedule;
    }

    async function loadSettings() {
        clearAlert();
        document.querySelector('[data-settings-page]').classList.add('is-loading');
        try {
            const response = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Không tải được cấu hình.');

            const configs = result.data.configs || {};
            Object.keys(configs).forEach(key => {
                if (key !== 'gio_mo_cua') setValue(key, configs[key]);
            });
            renderSchedule(configs.gio_mo_cua || {});

            const log = result.data.last_update;
            lastUpdate.textContent = log
                ? `Cập nhật gần nhất: ${log.ThoiGianCapNhat} bởi ${log.TenDangNhap || 'Hệ thống'}`
                : 'Chưa có lịch sử cập nhật.';
        } catch (error) {
            showAlert(error.message);
        } finally {
            document.querySelector('[data-settings-page]').classList.remove('is-loading');
        }
    }

    async function saveSettings(event) {
        event.preventDefault();
        clearAlert();

        const payload = Object.fromEntries(new FormData(form).entries());
        payload.bao_tri = form.elements.bao_tri.checked ? 1 : 0;
        payload.gio_mo_cua = collectSchedule();

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Không lưu được cấu hình.');
            showAlert(result.message || 'Đã lưu cấu hình.', 'success');
            if (result.data) {
                const configs = result.data.configs || {};
                renderSchedule(configs.gio_mo_cua || {});
            }
        } catch (error) {
            showAlert(error.message);
        }
    }

    form.addEventListener('submit', saveSettings);
    document.getElementById('settings-reload').addEventListener('click', loadSettings);
    loadSettings();
})();
</script>