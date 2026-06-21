<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    exit(); 
}
?>
<link rel="stylesheet" href="public/assets/css/BenhNhan/dat-lich.css">

<div class="dl-container">
    <button>
        <a href="index.php?page=home" class="btn-profile--dark">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
    </button>
    <div class="dl-header-box">
        <div class="header-info">
            <h1>Đặt lịch khám bệnh</h1>
            <p>Mô tả vấn đề sức khỏe và xác nhận — chúng tôi sẽ sắp xếp bác sĩ phù hợp cho bạn.</p>
        </div>
        <div class="tab-navigation">
            <button class="tab-btn active" id="tab-pk" onclick="switchView('pk')">
                Phiếu khám của tôi
            </button>
            <button class="tab-btn" id="tab-form" onclick="switchView('form')">
                Đặt lịch mới
            </button>
        </div>
    </div>

    <section class="dl-card" id="appointment-pk-section">
        <div class="card-header">
            <h2>
                <span class="material-symbols-outlined text-blue">receipt_long</span>
                Danh sách phiếu khám đã hẹn
            </h2>
            <span class="counter-badge" id="pk-counter">0 phiếu</span>
        </div>
        <div id="pk-list-container">
            <div style="padding:32px 0; text-align:center; color:#94a3b8;">Đang tải danh sách phiếu khám...</div>
        </div>
    </section>

    <section class="dl-card hidden" id="booking-form-section">
        <div class="dl-card-body-form">

            <div class="wz-stepper" id="wz-stepper">
                <div class="wz-step active" id="wz-step-1">
                    <div class="wz-circle">
                        <span class="wz-num">1</span>
                        <span class="material-symbols-outlined wz-tick" style="font-size:18px;">check</span>
                    </div>
                    <span class="wz-label">Nhập vấn đề sức khỏe</span>
                </div>
                <div class="wz-connector" id="wz-conn-1"></div>
                <div class="wz-step" id="wz-step-2">
                    <div class="wz-circle">
                        <span class="wz-num">2</span>
                        <span class="material-symbols-outlined wz-tick" style="font-size:18px;">check</span>
                    </div>
                    <span class="wz-label">Xác nhận đặt lịch</span>
                </div>
            </div>

            <div class="wz-current-title" id="wz-current-title">Mô tả vấn đề sức khỏe của bạn</div>

            <div class="wizard-content-wrapper">

                <div id="step-1-content">
                    <div class="health-input-area">
                        <label for="health-issue">
                            <span class="material-symbols-outlined" style="color:#0284c7;">healing</span>
                            Mô tả vấn đề sức khỏe / triệu chứng:
                        </label>
                        <textarea 
                            class="dl-textarea" 
                            id="health-issue" 
                            rows="5" 
                            maxlength="500"
                            placeholder="Ví dụ: Tôi bị đau đầu liên tục 3 ngày nay, kèm theo sốt nhẹ và mệt mỏi. Đã uống thuốc hạ sốt nhưng không đỡ nhiều..."
                            oninput="updateCharCounter(this)"
                        ></textarea>
                        <div class="char-counter" id="char-counter">0 / 500 ký tự</div>
                        <div class="health-hint">
                            <span class="material-symbols-outlined">info</span>
                            Mô tả càng chi tiết giúp bác sĩ chuẩn bị tốt hơn cho buổi khám của bạn.
                        </div>
                    </div>
                </div>

                <div class="hidden" id="step-2-content">
                    <div class="confirm-box-dl">
                        <div class="confirm-row" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px 14px; margin-bottom:4px;">
                            <span class="lbl" style="color:#166534;">
                                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;margin-right:4px;">check_circle</span>
                                Thông tin được xác nhận
                            </span>
                        </div>
                        <div class="confirm-row" style="margin-top:12px;">
                            <span class="lbl">Ngày đặt lịch:</span>
                            <span class="val highlight" id="confirm-date">-</span>
                        </div>
                        <div class="confirm-row">
                            <span class="lbl">Trạng thái:</span>
                            <span class="val" style="color:#0284c7; font-weight:600;">Chờ phân công bác sĩ</span>
                        </div>
                        <div class="confirm-note-block" style="margin-top:12px;">
                            <span class="lbl-block">Vấn đề sức khỏe khai báo:</span>
                            <p class="note-p-preview" id="confirm-health-issue">-</p>
                        </div>
                        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:10px 14px; margin-top:12px; font-size:12px; color:#0369a1; display:flex; align-items:flex-start; gap:8px;">
                            <span class="material-symbols-outlined" style="font-size:16px;flex-shrink:0;margin-top:1px;">info</span>
                            <span>Sau khi xác nhận, phòng khám sẽ tự động tạo phiếu khám và phân công bác sĩ phù hợp với tình trạng của bạn.</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="wizard-footer-actions">
                <button class="btn-nav-action prev hidden" id="prev-btn" onclick="prevStep()">
                    <span class="material-symbols-outlined">arrow_back</span> Quay lại
                </button>
                <button class="btn-nav-action next hidden" id="next-btn" onclick="nextStep()">
                    Xem lại & Xác nhận <span class="material-symbols-outlined">arrow_forward</span>
                </button>
                <button class="btn-nav-action submit hidden" id="submit-btn" onclick="completeBooking()">
                    <span class="material-symbols-outlined">check_circle</span> Xác nhận đặt lịch
                </button>
            </div>

        </div>
    </section>
</div>

<div class="dl-modal-backdrop hidden" id="success-modal">
    <div class="dl-modal-box" style="max-width: 480px;">
        <div class="modal-header">
            <h3>
                <span class="material-symbols-outlined" style="color:#16a34a;" id="success-modal-icon">check_circle</span> 
                <span id="success-modal-title">Đặt lịch thành công!</span>
            </h3>
            <button class="modal-close-btn" onclick="closeModal()"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="modal-body" style="padding: 4px 0 0;">
            <div id="phieu-kham-result-area"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal secondary hidden" id="btn-go-pk" onclick="switchView('pk'); closeModal();">
                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">receipt_long</span>
                Xem tất cả phiếu khám
            </button>
            <button class="btn-modal primary" onclick="closeModal()">Đóng</button>
        </div>
    </div>
</div>

<div class="dl-modal-backdrop hidden" id="simple-success-modal">
    <div class="dl-modal-box size-small">
        <div class="success-modal-layout">
            <div class="success-icon-circle">
                <span class="material-symbols-outlined" style="font-size: 24px; font-weight: bold;">check_circle</span>
            </div>
            <h3 id="simple-modal-title">Thành công!</h3>
            <p id="simple-modal-message">Yêu cầu đã được xử lý thành công.</p>
            <button class="btn-success-close" onclick="closeSimpleModal()">Đóng thông báo</button>
        </div>
    </div>
</div>

<script>
// =========================================================================
// BIẾN TRẠNG THÁI TOÀN CỤC
// =========================================================================
let currentStep = 1;
let allPhieuKham = [];

// =========================================================================
// KHỞI TẠO
// =========================================================================
document.addEventListener("DOMContentLoaded", function() {
    switchView('pk');
});

// =========================================================================
// CHUYỂN TAB
// =========================================================================
function switchView(view) {
    const tabs    = { pk: 'tab-pk', form: 'tab-form' };
    const sections = {
        pk:   'appointment-pk-section',
        form: 'booking-form-section'
    };

    Object.keys(tabs).forEach(k => {
        document.getElementById(tabs[k]).className = (k === view) ? 'tab-btn active' : 'tab-btn';
        document.getElementById(sections[k]).classList.toggle('hidden', k !== view);
    });

    if (view === 'form') { resetForm(); }
    if (view === 'pk')   { loadMyPhieuKham(); }
}

// =========================================================================
// TIỆN ÍCH
// =========================================================================
function getTodayString() {
    const now = new Date();
    return `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    return dateStr.split('-').reverse().join('/');
}

function updateCharCounter(el) {
    const len  = el.value.length;
    const counter = document.getElementById('char-counter');
    counter.textContent = `${len} / 500 ký tự`;
    counter.className = len > 450 ? 'char-counter warn' : 'char-counter';

    const trimmed = el.value.trim();
    const nextBtn = document.getElementById('next-btn');
    if (trimmed.length >= 10) {
        nextBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.add('hidden');
    }
}

// =========================================================================
// LOAD PHIẾU KHÁM
// =========================================================================
function loadMyPhieuKham() {
    const container = document.getElementById('pk-list-container');
    container.innerHTML = '<div style="padding:24px 0; text-align:center; color:#94a3b8;">Đang tải danh sách phiếu khám...</div>';

    fetch('src/api/lichHen.php?action=get_my_phieu_kham')
        .then(r => r.json())
        .then(res => {
            if (res.status !== 'success' || !res.data || res.data.length === 0) {
                allPhieuKham = [];
                document.getElementById('pk-counter').innerText = '0 phiếu';
                container.innerHTML = '<div style="padding:32px 0; text-align:center; color:#94a3b8; font-weight:500;">Bạn chưa có phiếu khám nào.</div>';
                return;
            }
            
            allPhieuKham = res.data;
            document.getElementById('pk-counter').innerText = `${res.data.length} phiếu`;
            
            let html = `<table class="pk-history-table">
                <thead><tr>
                    <th>Mã phiếu</th>
                    <th>STT</th>
                    <th>Ngày đặt</th>
                    <th>Giờ tiếp nhận</th>
                    <th>Trạng thái</th>
                    <th>Lý do khám</th>
                    <th style="text-align:center;">Thao tác</th>
                </tr></thead><tbody>`;
                
            res.data.forEach(pk => {
                const lyDoKhamS = pk.LyDoKham
                    ? (pk.LyDoKham.length > 30 ? pk.LyDoKham.substring(0,30) + '...' : pk.LyDoKham)
                    : '<span style="color:#94a3b8;font-style:italic;">-</span>';
                
                let badgeClass = 'status-badge pending';
                if (pk.MaTrangThai == 3) badgeClass = 'status-badge success';
                if (pk.MaTrangThai == 4) badgeClass = 'status-badge canceled';

                html += `<tr>
                    <td style="font-weight:700;color:#c2410c;">${pk.MaPhieuKhamCode}</td>
                    <td style="font-weight:800;font-size:18px;color:#0284c7;">${pk.STT || '-'}</td>
                    <td>${pk.NgayTao}</td>
                    <td>${pk.GioTiepNhan ? pk.GioTiepNhan.substring(0,5) : '-'}</td>
                    <td><span class="${badgeClass}">${pk.TenTrangThai || 'Chờ phân công'}</span></td>
                    <td>${lyDoKhamS}</td>
                    <td style="text-align:center;">
                        <button class="btn-action-view-pk" onclick="viewPhieuKhamDetail('${pk.MaPhieuKhamCode}')">
                            <span class="material-symbols-outlined" style="font-size:14px;">visibility</span> Chi tiết
                        </button>
                    </td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(() => {
            container.innerHTML = '<div style="padding:32px 0;text-align:center;color:#dc2626;">Không thể tải dữ liệu phiếu khám.</div>';
        });
}

// =========================================================================
// XEM CHI TIẾT PHIẾU KHÁM (GIỐNG MODAL ĐẶT THÀNH CÔNG)
// =========================================================================
function viewPhieuKhamDetail(code) {
    const pk = allPhieuKham.find(p => p.MaPhieuKhamCode === code);
    if (!pk) return;

    document.getElementById('success-modal-title').innerText = "Chi tiết phiếu khám";
    document.getElementById('success-modal-icon').style.color = "#0284c7";
    document.getElementById('success-modal-icon').innerText = "info";
    document.getElementById('btn-go-pk').classList.add('hidden');

    const todayStr = formatDate(pk.NgayTao);
    const timeStr = pk.GioTiepNhan ? pk.GioTiepNhan.substring(0,5) : '-';
    const sttNum = pk.STT || '-';
    const lyDoKhamS = pk.LyDoKham
                    ? (pk.LyDoKham.length > 30 ? pk.LyDoKham.substring(0,50) + '...' : pk.LyDoKham)
                    : '<span style="color:#94a3b8;font-style:italic;">-</span>';

    const html = `
        <div class="phieu-kham-result">
            <div class="pk-header">
                <div class="pk-icon">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <div class="pk-title">
                    <h3>Thông tin phiếu khám</h3>
                    <p>Mã quản lý hệ thống: ${code}</p>
                </div>
            </div>

            <div class="pk-stt-box">
                <span class="stt-label">Số thứ tự khám trong ngày</span>
                <span class="stt-number">${sttNum}</span>
            </div>

            <div class="pk-code-row">
                <span class="code-label">Mã phiếu khám</span>
                <span class="code-val">${code}</span>
            </div>

            <div class="pk-info-grid">
                <div class="pk-info-item">
                    <div class="pi-label">Ngày khám</div>
                    <div class="pi-val">${todayStr}</div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Giờ tiếp nhận</div>
                    <div class="pi-val">${timeStr}</div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Trạng thái</div>
                    <div class="pi-val" style="font-weight:700; color:${pk.MaTrangThai == 3 ? '#16a34a' : '#0284c7'};">
                        ${pk.TenTrangThai || 'Chờ phân công'}
                    </div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Mã bệnh nhân</div>
                    <div class="pi-val">#BN-${pk.MaBenhNhan || pk.MaBenhNhanCode || ''}</div>
                </div>
            </div>

            <div class="confirm-note-block" style="margin-bottom: 12px; background:#fff; padding:10px; border-radius:8px; border: 1px solid #e0f2fe;">
                <span class="lbl-block" style="font-size:11px; color:#64748b; font-weight:500;">Vấn đề sức khỏe khai báo:</span>
                <p class="note-p-preview" style="margin:4px 0 0; font-size:13px; color:#1e293b; line-height:1.4;">${lyDoKhamS || 'Không có.'}</p>
            </div>

            <div class="pk-notice">
                <span class="material-symbols-outlined">info</span>
                <span>Xuất trình mã phiếu này tại quầy tiếp đón để được hướng dẫn vào phòng khám chuyên khoa.</span>
            </div>
        </div>
    `;

    document.getElementById('phieu-kham-result-area').innerHTML = html;
    document.getElementById('success-modal').classList.remove('hidden');
}

// =========================================================================
// BƯỚC WIZARD ĐẶT LỊCH
// =========================================================================
function updateStepUI() {
    const titles = [
        "Mô tả vấn đề sức khỏe của bạn",
        "Xác nhận thông tin đặt lịch"
    ];

    for (let i = 1; i <= 2; i++) {
        document.getElementById(`step-${i}-content`).className = (i === currentStep) ? "animate-dl-fade" : "hidden";
    }

    document.getElementById('wz-current-title').innerText = titles[currentStep - 1];

    for (let i = 1; i <= 2; i++) {
        const el = document.getElementById(`wz-step-${i}`);
        if (i < currentStep)       el.className = "wz-step done";
        else if (i === currentStep) el.className = "wz-step active";
        else                        el.className = "wz-step";
    }

    const conn = document.getElementById('wz-conn-1');
    conn.className = (currentStep > 1) ? "wz-connector done" : "wz-connector";

    document.getElementById('prev-btn').classList.toggle('hidden', currentStep === 1);

    const nextBtn   = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    if (currentStep === 2) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        submitBtn.classList.add('hidden');
        const val = document.getElementById('health-issue').value.trim();
        if (val.length >= 10) nextBtn.classList.remove('hidden');
        else nextBtn.classList.add('hidden');
    }
}

function nextStep() {
    const val = document.getElementById('health-issue').value.trim();
    if (val.length < 10) {
        showAlert('Vui lòng mô tả vấn đề sức khỏe ít nhất 10 ký tự.');
        return;
    }
    document.getElementById('confirm-health-issue').innerText = val;
    document.getElementById('confirm-date').innerText = formatDate(getTodayString()) + ' (hôm nay)';

    currentStep = 2;
    updateStepUI();
}

function prevStep() {
    if (currentStep > 1) { currentStep--; updateStepUI(); }
}

// =========================================================================
// GỬI ĐẶT LỊCH + TẠO PHIẾU KHÁM
// =========================================================================
function completeBooking() {
    const ghiChu = document.getElementById('health-issue').value.trim();
    if (ghiChu.length < 10) {
        showAlert('Vấn đề sức khỏe không hợp lệ. Vui lòng quay lại bước 1.');
        currentStep = 1; updateStepUI(); return;
    }

    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="btn-spinner"></span> Đang xử lý...`;

    fetch('src/api/lichHen.php?action=create_booking', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ghi_chu: ghiChu })
    })
    .then(r => r.json())
    .then(lichHenResult => {
        if (lichHenResult.status !== 'success') {
            throw new Error(lichHenResult.message || 'Lỗi tạo lịch hẹn.');
        }
        const maBenhNhan = lichHenResult.maBenhNhan || 0;

        return fetch('src/api/createPhieuKham.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                maBenhNhan: maBenhNhan,
                ghiChu: ghiChu,
                maTrangThai: 9,
            })
        });
    })
    .then(r => r.json())
    .then(pkResult => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-outlined">check_circle</span> Xác nhận đặt lịch`;

        if (pkResult.success) {
            showBookingSuccessModal(pkResult.data, ghiChu);
            resetForm();
        } else {
            showSimpleSuccess(
                'Đặt lịch thành công!',
                'Lịch hẹn đã được ghi nhận. Phòng khám sẽ liên hệ xác nhận phiếu khám cho bạn sớm nhất.'
            );
            resetForm();
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-outlined">check_circle</span> Xác nhận đặt lịch`;
        showAlert('Đặt lịch thất bại: ' + err.message);
    });
}

// =========================================================================
// HIỂN THỊ MODAL THÀNH CÔNG SAU KHI ĐẶT MỚI
// =========================================================================
function showBookingSuccessModal(pkData, ghiChu) {
    document.getElementById('success-modal-title').innerText = "Đặt lịch thành công!";
    document.getElementById('success-modal-icon').style.color = "#16a34a";
    document.getElementById('success-modal-icon').innerText = "check_circle";
    document.getElementById('btn-go-pk').classList.remove('hidden');

    const today = formatDate(getTodayString());
    const now   = new Date();
    const timeStr = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');

    const html = `
        <div class="phieu-kham-result">
            <div class="pk-header">
                <div class="pk-icon">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <div class="pk-title">
                    <h3>Phiếu khám đã được tạo</h3>
                    <p>Vui lòng lưu thông tin phiếu khám bên dưới</p>
                </div>
            </div>

            <div class="pk-stt-box">
                <span class="stt-label">Số thứ tự khám hôm nay</span>
                <span class="stt-number">${pkData.sttNgay || pkData.stt || '-'}</span>
            </div>

            <div class="pk-code-row">
                <span class="code-label">Mã phiếu khám</span>
                <span class="code-val">${pkData.maPhieuKhamCode}</span>
            </div>

            <div class="pk-info-grid">
                <div class="pk-info-item">
                    <div class="pi-label">Ngày khám</div>
                    <div class="pi-val">${today}</div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Giờ tiếp nhận</div>
                    <div class="pi-val">${timeStr}</div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Trạng thái</div>
                    <div class="pi-val" style="color:#0284c7;">Chờ phân công</div>
                </div>
                <div class="pk-info-item">
                    <div class="pi-label">Mã bệnh nhân</div>
                    <div class="pi-val">#BN-${pkData.maBenhNhan}</div>
                </div>
            </div>

            <div class="pk-notice">
                <span class="material-symbols-outlined">warning</span>
                <span>Vui lòng đến quầy lễ tân và xuất trình <strong>mã phiếu khám</strong> để được tiếp nhận và phân công bác sĩ phù hợp.</span>
            </div>
        </div>
    `;

    document.getElementById('phieu-kham-result-area').innerHTML = html;
    document.getElementById('success-modal').classList.remove('hidden');
}

// =========================================================================
// MODAL HELPERS
// =========================================================================
function closeModal() {
    document.getElementById('success-modal').classList.add('hidden');
}

function showSimpleSuccess(title, msg) {
    document.getElementById('simple-modal-title').innerText = title;
    document.getElementById('simple-modal-message').innerText = msg;
    document.getElementById('simple-success-modal').classList.remove('hidden');
}
function closeSimpleModal() {
    document.getElementById('simple-success-modal').classList.add('hidden');
}

// =========================================================================
// RESET FORM VỀ BƯỚC 1
// =========================================================================
function resetForm() {
    currentStep = 1;
    document.getElementById('health-issue').value = '';
    document.getElementById('char-counter').textContent = '0 / 500 ký tự';
    document.getElementById('char-counter').className = 'char-counter';
    updateStepUI();
}
</script>