<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <style>
        /* --- ĐỊNH NGHĨA BIẾN TOÀN CỤC (PALETTE MÀU CỦA BẠN) --- */
        :root {
            --surface: #f9f9ff;
            --on-surface: #151c27;
            --on-surface-variant: #414752;
            --primary: #00569f;
            --primary-fixed: #d4e3ff;
            --outline-variant: #c1c6d4;
            --outline: #717783;
            --error: #ba1a1a;
            --secondary: #006a66;
            --surface-container-low: #f0f3ff;
            --background-alert: #E8F4FD;
        }

        /* --- CSS RESET & CƠ BẢN --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: var(--surface);
            color: var(--on-surface);
            line-height: 1.5;
        }
        main {
            padding: 1.5rem;
            min-height: calc(100vh - 60px);
            max-width: 1200px;
            margin: 0 auto;
        }

        /* --- CẬP NHẬT HEADER SECTION --- */
.header-section {
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between; /* Đẩy chữ sang trái, nút sang phải */
    align-items: center;           /* Căn giữa chữ và nút theo chiều dọc */
    gap: 1rem;                     /* Khoảng cách an toàn khi màn hình thu nhỏ */
}

.header-content h2 {
    font-size: 32px;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin-bottom: 0.25rem;
}

/* --- CSS CHO NÚT QUAY LẠI HOME --- */
.btn-back-home {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background-color: #fff;
    border: 1px solid var(--outline-variant);
    color: var(--on-surface-variant);
    font-size: 14px;
    font-weight: 500;
    border-radius: 0.5rem;
    text-decoration: none; /* Bỏ gạch chân của thẻ a */
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-back-home:hover {
    background-color: var(--surface-container-low);
    color: var(--primary);
    border-color: var(--primary);
}

/* --- TỐI ƯU RESPONSIVE TRÊN ĐIỆN THOẠI NHỎ --- */
@media (max-width: 480px) {
    .header-section {
        flex-direction: column;    /* Chuyển thành hàng dọc trên màn hình quá nhỏ */
        align-items: flex-start;   /* Căn lề trái */
    }
    .btn-back-home {
        width: 100%;               /* Nút kéo dài hết màn hình trên điện thoại */
        justify-content: center;
    }
}
        .text-variant {
            color: var(--on-surface-variant);
            font-size: 14px;
        }

        /* --- TỔNG QUAN BỆNH ÁN (SUMMARY HEADER) --- */
        .summary-header {
            background-color: var(--background-alert);
            border: 1px solid rgba(0, 86, 159, 0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: center;
            margin-bottom: 2rem;
        }
        .summary-title-block {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .summary-icon {
            width: 3rem;
            height: 3rem;
            background-color: rgba(0, 86, 159, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        .summary-title-block h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
        }
        .summary-divider {
            height: 2.5rem;
            width: 1px;
            background-color: rgba(0, 86, 159, 0.1);
        }
        .summary-stats {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
        }
        .stat-label {
            font-size: 12px;
            color: var(--on-surface-variant);
            margin-bottom: 2px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: 600;
        }
        .stat-value.primary-color {
            color: var(--primary);
        }

        /* --- TRỤC THỜI GIAN TIMELINE --- */
        .timeline-line {
            position: relative;
        }
        .timeline-line::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #E2E8F0;
            z-index: 0;
        }
        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 1.5rem;
        }
        .timeline-dot {
            position: absolute;
            left: 13px;
            top: 1rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            z-index: 10;
        }
        .timeline-dot.active { background-color: var(--primary); }
        .timeline-dot.inactive { background-color: var(--outline-variant); }

        /* --- THẺ PHIẾU KHÁM (TIMELINE CARD) --- */
        .timeline-card {
            background-color: #fff;
            border: 1px solid var(--outline-variant);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }
        .timeline-card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        }
        .card-flex {
            display: flex;
            flex-direction: column;
        }
        .card-date-side {
            background-color: var(--surface-container-low);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .card-date-side .day {
            color: var(--primary);
            font-size: 24px;
            font-weight: 600;
            line-height: 1;
        }
        .card-date-side .month {
            color: var(--on-surface-variant);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }
        .card-date-side .time {
            font-size: 12px;
            color: var(--on-surface-variant);
            margin-top: 0.5rem;
        }
        .card-body-side {
            flex: 1;
            padding: 1.25rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .doctor-info-block {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .avatar-text {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--primary-fixed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
        }
        .doctor-name {
            font-size: 13px;
            font-weight: 600;
        }
        .doctor-dept {
            font-size: 12px;
            color: var(--primary);
        }
        .diagnosis-text {
            font-size: 14px;
            font-weight: 500;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .status-badge {
            display: inline-flex;
            margin-top: 0.5rem;
            align-items: center;
            padding: 0.125rem 0.625rem;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
            background-color: rgba(0, 106, 102, 0.15);
            color: var(--secondary);
        }
        .btn-view-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        .btn-detail {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            background-color: #fff;
            border: 1px solid var(--primary);
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-detail:hover {
            background-color: var(--primary);
            color: #fff;
        }

        /* --- ĐIỀU CHỈNH GIAO DIỆN MÀN HÌNH LỚN (PC) --- */
        @media (min-width: 768px) {
            .card-flex { flex-direction: row; }
            .card-date-side { width: 14rem; border-right: 1px solid var(--outline-variant); }
            .card-body-side { grid-template-columns: repeat(3, 1fr); }
            .btn-view-container { justify-content: flex-end; }
            .summary-divider { display: block; }
        }
        @media (max-width: 1023px) {
            .summary-divider { display: none; }
        }

        /* --- MODAL (HỘP THOẠI CHI TIẾT) --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .modal-overlay.hidden { display: none; }
        .modal-content {
            background-color: #fff;
            border-radius: 0.75rem;
            width: 100%;
            max-width: 42rem;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
            position: relative;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .btn-close-modal {
            position: absolute;
            right: 1rem;
            top: 1rem;
            background: none;
            border: none;
            color: var(--outline);
            cursor: pointer;
        }
        .btn-close-modal:hover { color: var(--on-surface); }
        .modal-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary);
            border-b: 1px solid #edf2f7;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
        }
        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            font-size: 14px;
        }
        .modal-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background-color: var(--surface-container-low);
            padding: 0.75rem;
            border-radius: 0.5rem;
        }
        .bold-label {
            font-size: 12px;
            color: var(--on-surface-variant);
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            text-align: center;
        }
        .vital-box {
            background-color: var(--surface);
            padding: 0.5rem;
            border: 1px solid var(--outline-variant);
            border-radius: 0.25rem;
        }
        .vital-box p:first-child {
            text-transform: capitalize;
        }
        .diagnosis-box {
            background-color: rgba(0, 86, 159, 0.05);
            border: 1px solid rgba(0, 86, 159, 0.2);
            padding: 1rem;
            border-radius: 0.5rem;
        }
        .diagnosis-box p:first-child {
            color: var(--primary);
            font-weight: 700;
            font-size: 12px;
        }
        .diagnosis-box p:last-child {
            font-size: 20px;
            font-weight: 600;
            margin-top: 0.25rem;
        }
        .text-notes {
            font-style: italic;
            margin-top: 0.25rem;
        }
        
        /* TRẠNG THÁI STATUS TRÊN TRANG */
        .loading-text { text-align: center; padding: 2rem 0; color: var(--on-surface-variant); }
        .error-text { text-align: center; padding: 3rem 0; color: var(--error); font-weight: 500; }
        .error-text span { display: block; font-size: 48px; margin-bottom: 0.5rem; }
    </style>
</head>
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