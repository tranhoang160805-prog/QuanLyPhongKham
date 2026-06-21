<?php
// Đường dẫn: danh-sach-ghi-nhan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../src/helpers/format.php';; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống Ghi nhận Chỉ số Sinh tồn</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'surface-container-lowest': '#ffffff',
                        'surface-container-low': '#f8fafc',
                        'surface-container-highest': '#f1f5f9',
                        'on-surface': '#0f172a',
                        'on-surface-variant': '#64748b',
                        'outline-variant': '#e2e8f0',
                        'primary': '#0284c7',
                        'secondary': '#0ea5e9',
                        'tertiary': '#10b981',
                        'error': '#ef4444'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 p-6 text-on-surface">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <div class="flex justify-between items-start mb-3">
                <span class="text-on-surface-variant text-sm font-medium">Bệnh nhân hôm nay</span>
                <span class="material-symbols-outlined text-primary">groups</span>
            </div>
            <div class="text-3xl font-bold text-on-surface" id="stat-total-patients">--</div>
            <div class="text-emerald-600 text-xs flex items-center gap-1 mt-2 font-medium">
                <span class="material-symbols-outlined text-[16px]">trending_up</span> Ca đã ghi nhận sinh tồn
            </div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <div class="flex justify-between items-start mb-3">
                <span class="text-on-surface-variant text-sm font-medium">BMI Trung Bình</span>
                <span class="material-symbols-outlined text-tertiary">monitor_weight</span>
            </div>
            <div class="text-3xl font-bold text-on-surface">23.4</div>
            <div class="flex gap-1 items-end h-4 mt-3">
                <div class="w-2 h-[40%] bg-emerald-200 rounded-full"></div>
                <div class="w-2 h-[60%] bg-emerald-200 rounded-full"></div>
                <div class="w-2 h-[55%] bg-emerald-200 rounded-full"></div>
                <div class="w-2 h-[80%] bg-emerald-500 rounded-full"></div>
                <div class="w-2 h-[70%] bg-emerald-200 rounded-full"></div>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <div class="flex justify-between items-start mb-3">
                <span class="text-on-surface-variant text-sm font-medium">Cảnh báo huyết áp</span>
                <span class="material-symbols-outlined text-error">ecg</span>
            </div>
            <div class="text-3xl font-bold text-on-surface">02</div>
            <div class="text-error text-xs mt-2 font-semibold">Cần chú ý lâm sàng</div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant shadow-[0_1px_3px_rgba(0,0,0,0.08)]">
            <div class="flex justify-between items-start mb-3">
                <span class="text-on-surface-variant text-sm font-medium">Thời gian TB sơ khám</span>
                <span class="material-symbols-outlined text-amber-500">timer</span>
            </div>
            <div class="text-3xl font-bold text-on-surface">8.5m</div>
            <div class="text-on-surface-variant text-xs mt-2">Đạt tiêu chuẩn KPI hệ thống</div>
        </div>
    </div>

    <div class="table-container" id="vitals-card-container">
        
        <div class="table-header" id="vitals-card-header">
            <h3 class="font-bold text-base text-on-surface">Danh sách bản ghi gần đây</h3>
            <!-- <div class="flex gap-2">
                <button class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">filter_list</span>
                </button>
                <button class="p-2 hover:bg-surface-container-highest rounded-lg transition-colors text-on-surface-variant">
                    <span class="material-symbols-outlined">sort</span>
                </button>
            </div> -->
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead class="">
                    <tr>
                        <th class="px-6 py-3 w-28">Mã Phiếu</th>
                        <th class="px-6 py-3 w-56">BỆNH NHÂN</th>
                        <th class="px-6 py-3 text-center w-32">NHIỆT ĐỘ</th>
                        <th class="px-6 py-3 text-center w-40">HUYẾT ÁP (BP)</th>
                        <th class="px-6 py-3 text-center w-36">NHỊP TIM</th>
                        <th class="px-6 py-3">GHI CHÚ LINH SÀNG</th>
                        <th class="px-6 py-3 text-right w-32">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-white" id="vitals-table-body">
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-on-surface-variant text-sm">
                            <span class="animate-spin material-symbols-outlined text-2xl block mb-2 text-primary">sync</span>
                            Đang tải danh sách chỉ số sinh tồn từ hệ thống...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-bar" id="vitals-pagination-bar">
            <div class="pagination-info" id="vitals-pagination-info">
                Đang tính toán phân trang...
            </div>
            <div class="pagination-buttons" id="vitals-btn-container">
                </div>
        </div>
    </div>

<script>
let currentPage = 1;
let limitPerPage = 7; // Chiều cao h-[560px] chứa cực đẹp khoảng 7 dòng dữ liệu h-[58px]

// Hàm tiện ích chống XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

// --- THUẬT TOÁN ENGINE: TẢI DỮ LIỆU QUA AJAX KHÔNG RELOAD TRANG ---
function loadVitalsList(page = 1) {
    currentPage = page;
    
    // Gọi trực tiếp đến API chung lấy dữ liệu bệnh nhân giống file ds-benh-nhan.php của bạn
    // Hệ thống sẽ chỉ lọc các ca đã qua bước sơ khám (có thông số sinh tồn) hoặc tùy chỉnh endpoint tùy backend của bạn
    let apiUrl = `src/api/getDSBenhNhan.php?p=${page}&limit=${limitPerPage}`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                // Cập nhật số liệu lên widget thống kê phía trên
                if(res.pagination && res.pagination.total_records) {
                    document.getElementById('stat-total-patients').innerText = res.pagination.total_records;
                }
                
                // Vẽ dữ liệu vào Table & vẽ lại các nút phân trang
                renderVitalsTable(res.data);
                renderPaginationUI(res.pagination);
            } else {
                console.error(res.message);
            }
        })
        .catch(err => {
            document.getElementById('vitals-table-body').innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-red-500 text-sm">
                        <span class="material-symbols-outlined text-4xl block mb-2">error</span>
                        Lỗi kết nối API lấy danh sách ghi nhận.<br><small class="text-slate-400">${err.message}</small>
                    </td>
                </tr>`;
        });
}

// --- HÀM IN DỮ LIỆU ĐỘNG VÀO TRONG BODY TABLE ---
function renderVitalsTable(data) {
    const tbody = document.getElementById('vitals-table-body');
    if (!data || data.length === 0) {
        tbody.innerHTML = `<tr>
            <td colspan="7" class="px-6 py-20 text-center text-on-surface-variant text-sm">
                <span class="material-symbols-outlined text-4xl block mb-2 text-slate-300">assignment_late</span>
                Chưa có bản ghi thông số sinh tồn nào trên hệ thống.
            </td>
        </tr>`;
        return;
    }

    tbody.innerHTML = data.map(item => {
        // Xử lý bóc tách JSON Chuỗi thông số sinh tồn giống code PHP cũ của bạn
        let vitals = {};
        try {
            vitals = item.ThongSoSinhTon ? (typeof item.ThongSoSinhTon === 'string' ? JSON.parse(item.ThongSoSinhTon) : item.ThongSoSinhTon) : {};
        } catch(e) { vitals = {}; }

        const nhiet_do = vitals.nhiet_do || '--';
        const huyet_ap = vitals.huyet_ap || '--/--';
        const nhip_tim  = vitals.nhip_tim ? vitals.nhip_tim + ' bpm' : '--';

        // Phân tích trạng thái cảnh báo huyết áp cao
        let isWarning = false;
        if (huyet_ap !== '--/--') {
            const bpParts = huyet_ap.split('/');
            if (bpParts[0] && parseInt(bpParts[0]) >= 140) {
                isWarning = true;
            }
        }

        // Tạo chuỗi HTML giao diện cho từng dòng tr
        return `
            <tr">
                <td class="px-6 py-2">
                    <div class="text-primary font-bold text-sm">${escapeHtml(item.MaPhieuKhamCode || '#' + item.MaPhieuKham)}</div>
                </td>
                <td class="px-6 py-2">
                    <div class="text-on-surface font-semibold text-sm truncate">${escapeHtml(item.HoTen)}</div>
                    <div class="text-on-surface-variant text-[11px]">Mã BN: ${escapeHtml(item.MaBN)}</div>
                </td>
                <td class="px-6 py-2 text-center">
                    ${parseFloat(nhiet_do) >= 38.0 
                        ? `<span class="bg-red-50 text-error px-2.5 py-0.5 rounded-full font-semibold text-xs border border-red-100">${nhiet_do}°C</span>`
                        : `<span class="bg-slate-100 px-2.5 py-0.5 rounded-full text-on-surface font-semibold text-xs">${nhiet_do}°C</span>`
                    }
                </td>
                <td class="px-6 py-2 text-center">
                    <div class="text-sm font-bold ${isWarning ? 'text-error' : 'text-on-surface'}">${escapeHtml(huyet_ap)}</div>
                    <div class="${isWarning ? 'text-error' : 'text-emerald-600'} text-[9px] font-bold uppercase tracking-wider leading-none">
                        ${isWarning ? 'Cao' : 'Bình thường'}
                    </div>
                </td>
                <td class="px-6 py-2 text-center">
                    <div class="text-on-surface font-semibold text-sm">${escapeHtml(nhip_tim)}</div>
                </td>
                <td class="px-6 py-2">
                    <div class="text-on-surface-variant text-xs truncate max-w-[280px]" title="${escapeHtml(item.GhiChu || '')}">
                        ${item.GhiChu ? escapeHtml(item.GhiChu) : '<span class="text-slate-300">Không có ghi chú</span>'}
                    </div>
                </td>
                <td class="px-6 py-2 text-right">
                    <button onclick="showAlert('Xem chi tiết ca: ' + ${item.MaPhieuKham})" class="text-primary hover:bg-sky-50 px-3 py-1.5 rounded-lg font-medium text-xs transition-all border border-transparent hover:border-outline-variant">
                        Chi tiết
                    </button>
                </td>
            </tr>`;
    }).join('');
}

// --- HÀM TỰ ĐỘNG SINH CÁC NÚT BẤM VÀ THANH PHÂN TRANG (Áp dụng thuật toán rút gọn ...) ---
function renderPaginationUI(pagination) {
    if(!pagination) return;
    
    const textInfo = document.getElementById('vitals-pagination-info');
    const btnContainer = document.getElementById('vitals-btn-container');

    const totalRecords = pagination.total_records;
    const limit = pagination.limit || limitPerPage;
    const currPage = pagination.current_page;
    const totalPages = pagination.total_pages;

    const startRecord = totalRecords > 0 ? (currPage - 1) * limit + 1 : 0;
    const endRecord = Math.min(currPage * limit, totalRecords);

    textInfo.innerHTML = `Hiển thị <strong>${startRecord}</strong> - <strong>${endRecord}</strong> trên tổng số <strong>${totalRecords}</strong> ca`;

    if (totalPages <= 1) {
        btnContainer.innerHTML = '';
        return;
    }

    // CSS đồng bộ cho nút phân trang của Tailwind, giống style mượt mà của file ds-benh-nhan
    const btnClass = "px-3 py-1.5 text-xs font-semibold rounded-lg border border-outline-variant bg-white text-on-surface-variant hover:bg-slate-100 transition-colors disabled:opacity-40 disabled:cursor-not-allowed";
    const activeClass = "px-3 py-1.5 text-xs font-bold rounded-lg border border-primary bg-primary text-white pointer-events-none";

    let btnHtml = '';
    
    // Nút Lùi (Trước)
    btnHtml += `<button class="${btnClass}" ${currPage <= 1 ? 'disabled' : ''} onclick="loadVitalsList(${currPage - 1})">&laquo;</button>`;

    const range = 1; 
    let renderPages = [];

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currPage - range && i <= currPage + range)) {
            renderPages.push(i);
        }
    }

    let lastPageAdded = null;
    for (let page of renderPages) {
        if (lastPageAdded !== null) {
            if (page - lastPageAdded === 2) {
                btnHtml += `<button class="${btnClass}" onclick="loadVitalsList(${lastPageAdded + 1})">${lastPageAdded + 1}</button>`;
            } else if (page - lastPageAdded > 2) {
                btnHtml += `<span class="px-1.5 py-1 text-xs text-on-surface-variant align-middle">...</span>`;
            }
        }

        if(page === currPage) {
            btnHtml += `<button class="${activeClass}">${page}</button>`;
        } else {
            btnHtml += `<button class="${btnClass}" onclick="loadVitalsList(${page})">${page}</button>`;
        }
        lastPageAdded = page;
    }

    // Nút Tiến (Tiếp)
    btnHtml += `<button class="${btnClass}" ${currPage >= totalPages ? 'disabled' : ''} onclick="loadVitalsList(${currPage + 1})">&raquo;</button>`;

    btnContainer.innerHTML = btnHtml;
}

// Khởi chạy ngầm ngay khi tải trang
document.addEventListener('DOMContentLoaded', () => {
    loadVitalsList(currentPage);
});
</script>
</body>
</html>