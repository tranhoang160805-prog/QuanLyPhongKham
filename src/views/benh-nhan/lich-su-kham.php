<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    
    exit(); 
}
?>
<link rel="stylesheet" href="public/assets/css/BenhNhan/ls-kham.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<body>

<main>
    <div class="header-section">
    <div class="header-content">
        <h2>Lịch sử khám bệnh</h2>
        <p class="text-variant">Xem lại chi tiết các lần thăm khám và chẩn đoán của bạn.</p>
    </div>
    <a href="index.php?page=benh-an" class="btn-back-home">
        <span class="material-symbols-outlined">arrow_back</span>
        Quay lại
    </a>
</div>

    <div class="summary-header">
        <div class="summary-title-block">
            <div class="summary-icon">
                <span class="material-symbols-outlined style-fill" style="font-size: 28px; font-variation-settings: 'FILL' 1;">patient_list</span>
            </div>
            <div>
                <h3>Tổng quan bệnh án</h3>
                <p class="stat-label" id="txt-last-update">Cập nhật lần cuối: --/--/----</p>
            </div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-stats">
            <div>
                <p class="stat-label">Mã số bệnh nhân</p>
                <p class="stat-value" id="txt-patient-id">--</p>
            </div>
            <div>
                <p class="stat-label">Tổng số lượt khám</p>
                <p class="stat-value primary-color" id="txt-total-visits">0 lượt</p>
            </div>
            <div>
                <p class="stat-label">Bác sĩ khám gần nhất</p>
                <p class="stat-value" id="txt-last-doctor">--</p>
            </div>
        </div>
    </div>

    <div class="timeline-line" id="timeline-container">
        <div class="loading-text" id="loading-status">
            Đang tải lịch sử khám bệnh...
        </div>
    </div>
</main>

<div id="detail-modal" class="modal-overlay hidden">
    <div class="modal-content">
        <button onclick="closeModal()" class="btn-close-modal">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="modal-title" id="modal-title">Chi tiết phiếu khám</h3>
        
        <div class="modal-body">
            <div class="modal-grid-2">
                <div>
                    <p class="stat-label">Bác sĩ đảm nhiệm</p>
                    <p style="font-weight: 500;" id="modal-doctor">--</p>
                </div>
                <div>
                    <p class="stat-label">Chuyên khoa</p>
                    <p style="font-weight: 500; color: var(--primary);" id="modal-department">--</p>
                </div>
            </div>

            <div>
                <p class="bold-label">Lý do khám:</p>
                <p id="modal-reason">--</p>
            </div>
            <div>
                <p class="bold-label">Triệu chứng lâm sàng:</p>
                <p id="modal-symptoms">--</p>
            </div>
            <div>
                <p class="bold-label">Tiền sử bệnh:</p>
                <p id="modal-history">--</p>
            </div>

            <div>
                <p class="bold-label" style="margin-bottom: 0.5rem;">Thông số sinh tồn:</p>
                <div class="vitals-grid" id="modal-vitals"></div>
            </div>

            <div class="diagnosis-box">
                <p>Chẩn đoán cuối cùng:</p>
                <p id="modal-diagnosis">--</p>
            </div>
            <div>
                <p class="bold-label">Ghi chú từ bác sĩ:</p>
                <p class="text-notes" id="modal-notes">--</p>
            </div>
        </div>
    </div>
</div>

<script>
let globalRecords = [];

function formatDate(dateStr) {
    if(!dateStr) return '';
    const parts = dateStr.split('-');
    return {
        day: parts[2],
        monthYear: `Tháng ${parts[1]}, ${parts[0]}`
    };
}

document.addEventListener("DOMContentLoaded", function() {
    const API_URL = 'src/api/getHoSoBenhAn.php'; 

    fetch(API_URL)
        .then(response => {
            if (!response.ok) throw new Error("Lỗi xác thực hoặc hệ thống (" + response.status + ")");
            return response.json();
        })
        .then(res => {
            if (res.success) {
                const data = res.data;
                globalRecords = data.DanhSachPhieuKham;

                document.getElementById('txt-patient-id').innerText = '#' + data.MaBenhNhan;
                document.getElementById('txt-total-visits').innerText = globalRecords.length + ' lượt';
                
                if (globalRecords.length > 0) {
                    const latestRecord = globalRecords[0];
                    document.getElementById('txt-last-doctor').innerText = latestRecord.TenBacSi;
                    document.getElementById('txt-last-update').innerText = `Cập nhật lần cuối: ${latestRecord.NgayKham}`;
                    renderTimeline(globalRecords);
                } else {
                    document.getElementById('timeline-container').innerHTML = `
                        <div class="loading-text">Bạn chưa có lịch sử khám bệnh nào trên hệ thống.</div>
                    `;
                }
            } else {
                showError(res.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError("Không thể kết nối với máy chủ hoặc phiên làm việc đã hết hạn.");
        });
});

function showError(msg) {
    document.getElementById('timeline-container').innerHTML = `
        <div class="error-text">
            <span class="material-symbols-outlined">error</span>
            ${msg}
        </div>
    `;
}

function renderTimeline(records) {
    const container = document.getElementById('timeline-container');
    container.innerHTML = ""; 

    records.forEach((record, index) => {
        const dateObj = formatDate(record.NgayKham);
        
        const entryHtml = `
            <div class="timeline-item">
                <div class="timeline-dot ${index === 0 ? 'active' : 'inactive'}"></div>
                <div class="timeline-card">
                    <div class="card-flex">
                        <div class="card-date-side">
                            <span class="day">${dateObj.day}</span>
                            <span class="month">${dateObj.monthYear}</span>
                            <span class="time">${record.GioKham}</span>
                        </div>
                        <div class="card-body-side">
                            <div>
                                <p class="stat-label" style="margin-bottom: 0.25rem;">Chuyên khoa & Bác sĩ</p>
                                <div class="doctor-info-block">
                                    <div class="avatar-text">
                                        ${record.TenBacSi ? record.TenBacSi.charAt(0) : 'B'}
                                    </div>
                                    <div>
                                        <p class="doctor-name">${record.TenBacSi}</p>
                                        <p class="doctor-dept">${record.TenChuyenKhoa}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="stat-label" style="margin-bottom: 0.25rem;">Chẩn đoán sơ bộ</p>
                                <p class="diagnosis-text">${record.ChanDoan || 'Chưa có chẩn đoán'}</p>
                                <span class="status-badge">${record.TenTrangThai}</span>
                            </div>
                            <div class="btn-view-container">
                                <button onclick="openModal('${record.MaPhieuKhamCode}')" class="btn-detail">
                                    <span class="material-symbols-outlined" style="font-size:18px;">description</span>
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += entryHtml;
    });
}

function openModal(code) {
    const record = globalRecords.find(r => r.MaPhieuKhamCode === code);
    if (!record) return;

    document.getElementById('modal-title').innerText = "Chi tiết phiếu khám: " + record.MaPhieuKhamCode;
    document.getElementById('modal-doctor').innerText = record.TenBacSi;
    document.getElementById('modal-department').innerText = record.TenChuyenKhoa;
    document.getElementById('modal-reason').innerText = record.LyDoKham || "Không ghi nhận";
    document.getElementById('modal-symptoms').innerText = record.TrieuChung || "Không ghi nhận";
    document.getElementById('modal-history').innerText = record.TienSuBenh || "Không ghi nhận";
    document.getElementById('modal-diagnosis').innerText = record.ChanDoan || "Chưa có chẩn đoán";
    document.getElementById('modal-notes').innerText = record.GhiChu || "Không có ghi chú thêm.";

    const vitalsContainer = document.getElementById('modal-vitals');
    vitalsContainer.innerHTML = "";
    
    if (record.ThongSoSinhTon && typeof record.ThongSoSinhTon === 'object') {
        for (const [key, value] of Object.entries(record.ThongSoSinhTon)) {
            let label = key.toUpperCase().replace('_', ' ');
            vitalsContainer.innerHTML += `
                <div class="vital-box">
                    <p class="stat-label">${label}</p>
                    <p style="font-weight: 700;">${value}</p>
                </div>
            `;
        }
    } else {
        vitalsContainer.innerHTML = `<p class="text-variant" style="font-style: italic; grid-column: span 3; text-align: left;">Không có dữ liệu sinh tồn.</p>`;
    }

    const modal = document.getElementById('detail-modal');
    modal.classList.remove('hidden');
    setTimeout(() => { modal.style.opacity = '1'; }, 10);
}

function closeModal() {
    const modal = document.getElementById('detail-modal');
    modal.style.opacity = '0';
    setTimeout(() => { modal.classList.add('hidden'); }, 300);
}
</script>
</body>
</html>