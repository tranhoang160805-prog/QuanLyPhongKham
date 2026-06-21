<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$real_role = $_SESSION['user_role'] ?? 'le-tan';
$menu_role = $_SESSION['current_view_role'] ?? $real_role;
if ($real_role !== 'admin') {
    $menu_role = $real_role;
}

$STATUS_CONFIG = [
    1 => ['label'=>'Cấp cứu',         'class'=>'status-cc',   'icon'=>'emergency',     'role'=>'bac-si',        'btn_text'=>'Xử lý cấp cứu',  'next_status'=>1],
    2 => ['label'=>'Chờ sơ khám',     'class'=>'status-sk',   'icon'=>'monitor_heart', 'role'=>'dieu-duong',    'btn_text'=>'Sơ khám',        'next_status'=>2],
    3 => ['label'=>'Chờ khám bệnh',   'class'=>'status-kb',   'icon'=>'stethoscope',   'role'=>'bac-si',        'btn_text'=>'Khám bệnh',      'next_status'=>3],
    4 => ['label'=>'Chờ xét nghiệm',  'class'=>'status-xn',   'icon'=>'science',       'role'=>'ky-thuat-vien', 'btn_text'=>'Xét nghiệm',     'next_status'=>4],
    5 => ['label'=>'Đang xét nghiệm', 'class'=>'status-dxn',  'icon'=>'biotech',       'role'=>'ky-thuat-vien', 'btn_text'=>'Cập nhật KQ',    'next_status'=>5],
    6 => ['label'=>'Đã xét nghiệm',   'class'=>'status-dxn_ok','icon'=>'assignment_turned_in','role'=>'bac-si',  'btn_text'=>'Xem KQ & Kê đơn','next_status'=>3],
    7 => ['label'=>'Chờ cấp thuốc',   'class'=>'status-ct',   'icon'=>'medication',    'role'=>'duoc-si',       'btn_text'=>'Cấp thuốc',      'next_status'=>7],
    8 => ['label'=>'Chờ thanh toán',  'class'=>'status-tt',   'icon'=>'payments',      'role'=>'le-tan',        'btn_text'=>'Thanh toán',     'next_status'=>8],
    9 => ['label'=>'Hoàn thành',      'class'=>'status-ht',   'icon'=>'check_circle',  'role'=>'all',           'btn_text'=>'Xem bệnh án',    'next_status'=>9]
];

$ROLE_DEFAULT_STATUS = [
    'le-tan'        => 8,
    'dieu-duong'    => 2,
    'bac-si'        => 3,
    'ky-thuat-vien' => 4, 
    'duoc-si'       => 7,  
    'admin'         => '', 
];

$status_filter_from_url = (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') ? (int)$_GET['status_filter'] : null;
$default_status_for_role = $ROLE_DEFAULT_STATUS[$menu_role] ?? '';
$status_filter = $status_filter_from_url !== null ? $status_filter_from_url : $default_status_for_role;
?>

<link rel="stylesheet" href="public/assets/css/LeTan/ds-benh-nhan.css">
<div class="ds-page-root" id="ds-page-root">

    <div class="stats-grid-container">
        <div class="stat-card" onclick="quickFilterStat(1)" id="sc-cc" title="Lọc: Cấp cứu">
            <div class="icon-wrapper"><span class="material-symbols-outlined">emergency</span></div>
            <div class="stat-info"><p>Cấp cứu</p><h4 id="stat-cc">0</h4></div>
        </div>
        <div class="stat-card" onclick="quickFilterStat(2)" id="sc-sk" title="Lọc: Chờ sơ khám">
            <div class="icon-wrapper"><span class="material-symbols-outlined">monitor_heart</span></div>
            <div class="stat-info"><p>Sơ khám</p><h4 id="stat-sk">0</h4></div>
        </div>
        <div class="stat-card" onclick="quickFilterStat(3)" id="sc-kb" title="Lọc: Chờ khám bệnh">
            <div class="icon-wrapper"><span class="material-symbols-outlined">stethoscope</span></div>
            <div class="stat-info"><p>Khám bệnh</p><h4 id="stat-kb">0</h4></div>
        </div>
        <div class="stat-card" onclick="quickFilterStat(4)" id="sc-xn" title="Lọc: Chờ xét nghiệm">
            <div class="icon-wrapper"><span class="material-symbols-outlined">science</span></div>
            <div class="stat-info"><p>Xét nghiệm</p><h4 id="stat-xn">0</h4></div>
        </div>
        <div class="stat-card" onclick="quickFilterStat(7)" id="sc-ct" title="Lọc: Chờ cấp thuốc">
            <div class="icon-wrapper"><span class="material-symbols-outlined">medication</span></div>
            <div class="stat-info"><p>Cấp thuốc</p><h4 id="stat-ct">0</h4></div>
        </div>
                <div class="stat-card" onclick="quickFilterStat(8)" id="sc-huy" title="Lọc: Chờ thanh toán / Đã hủy">
            <div class="icon-wrapper"><span class="material-symbols-outlined">cancel</span></div>
            <div class="stat-info"><p>Thanh toán</p><h4 id="stat-huy">0</h4></div>
        </div>
        <div class="stat-card" onclick="quickFilterStat(9)" id="sc-ht" title="Lọc: Hoàn thành">
            <div class="icon-wrapper"><span class="material-symbols-outlined">task_alt</span></div>
            <div class="stat-info"><p>Hoàn thành</p><h4 id="stat-ht">0</h4></div>
        </div>

    </div>

    <div class="main-card-container" id="main-card-container">

        <div class="main-card-header" id="main-card-header">
            <div class="title-area">
                <h3>Phiếu khám đăng ký hôm nay</h3>
                <span class="badge-pulse">
                    <span></span> Tự động cập nhật
                </span>
            </div>
            <div class="controls-area">
                <div class="search-box">
                    <span class="material-symbols-outlined">search</span>
                    <input id="ds-search-input" type="text" placeholder="Mã PK, Mã BN, Họ tên..." oninput="onSearchInput()" />
                </div>

                <select id="status-filter-select" onchange="filterByStatus(this.value)">
                    <option value="">Tất cả trạng thái</option>
                    <?php foreach ($STATUS_CONFIG as $id => $cfg): ?>
                        <option value="<?= $id ?>" <?= ((string)$status_filter === (string)$id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cfg['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <span class="role-badge">
                    <?= htmlspecialchars($menu_role) ?>
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 60px;">STT</th>
                        <th>Mã phiếu khám</th>
                        <th>Mã BN</th>
                        <th>Họ và tên</th>
                        <th>Dị ứng</th>
                        <th>Giờ tiếp nhận</th>
                        <th>Trạng thái</th>
                        <th style="text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="patient-table-body">
                    <tr>
                        <td colspan="8">
                            <div class="text-loading-box">
                                <span class="material-symbols-outlined animate-spin-custom">progress_activity</span>
                                <p style="margin: 0;">Đang tải dữ liệu hệ thống...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-bar" id="main-pagination-bar">
            <div class="info-text" id="pg-info-text">Hiển thị 0 bản ghi</div>
            <div class="pagination-buttons" id="pg-btn-container"></div>
        </div>
    </div>

</div>
<script>
(function () {
'use strict';

const STATUS_CONFIG  = <?= json_encode($STATUS_CONFIG) ?>;
const MENU_ROLE      = <?= json_encode($menu_role) ?>;
const ROLE_DEFAULT_STATUS = <?= json_encode((string)$status_filter) ?>;

let currentPage         = 1;
let limitPerPage        = 8;
let currentStatusFilter = ROLE_DEFAULT_STATUS;
let currentSearch       = '';
let searchTimer         = null;
let autoReloadTimer     = null;
let knownIds            = new Set();
const AUTO_RELOAD_MS    = 5000;

function onSearchInput() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentSearch = document.getElementById('ds-search-input').value.trim();
        loadPatientList(1);
    }, 350);
}

function filterByStatus(val) {
    currentStatusFilter = val;
    currentPage = 1;

    const sel = document.getElementById('status-filter-select');
    if (sel) sel.value = val;

    document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active-filter-card'));

    const statCardMap = { '1':'sc-cc','2':'sc-sk','3':'sc-kb','4':'sc-xn','7':'sc-ct','9':'sc-ht','8':'sc-huy' };
    if (val && statCardMap[val]) {
        document.getElementById(statCardMap[val])?.classList.add('active-filter-card');
    }

    loadPatientList(1);
}

function quickFilterStat(statusId) {
    const newVal = (String(currentStatusFilter) === String(statusId)) ? '' : String(statusId);
    filterByStatus(newVal);
}

function buildApiUrl(page) {
    let url = `src/api/getDSPhieuKham.php?p=${page}&limit=${limitPerPage}`;
    if (currentStatusFilter !== '') url += `&status_filter=${currentStatusFilter}`;
    if (currentSearch !== '')       url += `&search=${encodeURIComponent(currentSearch)}`;
    return url;
}

function loadPatientList(page = 1, silent = false) {
    currentPage = page;
    fetch(buildApiUrl(page))
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            updateStats(res.stats);
            renderTable(res.data, silent);
            renderPagination(res.pagination);
        })
        .catch(err => console.error('API error:', err));
}

function startAutoReload() {
    stopAutoReload();
    autoReloadTimer = setInterval(silentPoll, AUTO_RELOAD_MS);
}

function stopAutoReload() {
    if (autoReloadTimer) { clearInterval(autoReloadTimer); autoReloadTimer = null; }
}

function silentPoll() {
    fetch(`src/api/getDSPhieuKham.php?p=1&limit=20`)
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            updateStats(res.stats);
            const newItems = (res.data || []).filter(d => !knownIds.has(d.MaPhieuKham));
            if (newItems.length > 0) {
                newItems.forEach(d => knownIds.add(d.MaPhieuKham));
                loadPatientList(currentPage, true);
            }
        })
        .catch(() => console.log('Mất kết nối máy chủ dữ liệu'));
}

function updateStats(stats) {
    if (!stats) return;
    const map = {
        'stat-cc': stats.total_cc,  'stat-sk': stats.total_sk,
        'stat-kb': stats.total_kb,  'stat-xn': stats.total_xn,
        'stat-ct': stats.total_ct,  'stat-ht': stats.total_ht,
        'stat-huy':stats.total_huy
    };
    Object.entries(map).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = val ?? 0;
    });
}

function renderTable(data, silent = false) {
    const tbody = document.getElementById('patient-table-body');
    if (!tbody) return;

    if (!data || data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; font-style: italic; color: var(--color-on-surface-variant); padding: 3rem 1.5rem;">
            Không có dữ liệu phiếu khám nào trong danh mục này.
        </td></tr>`;
        return;
    }

    tbody.innerHTML = data.map(item => {
        const statusVal = parseInt(item.MaTrangThai);
        const cfg = STATUS_CONFIG[statusVal] || {
            label: 'Không rõ', class: '',
            icon: 'help', role: 'none', btn_text: 'Xem', next_status: null
        };

        knownIds.add(item.MaPhieuKham);

        let actionBtn;
        if (cfg.role === MENU_ROLE || MENU_ROLE === 'admin') {
            actionBtn = `<button class="btn-action-primary"
                onclick="processClinicWorkflow(${item.MaPhieuKham}, ${statusVal})">
                <span class="material-symbols-outlined">play_arrow</span>
                <span>${cfg.btn_text}</span>
            </button>`;
        } else {
            actionBtn = `<button class="btn-action-secondary"
                onclick="viewMedicalRecord(${item.MaPhieuKham})">
                <span class="material-symbols-outlined">visibility</span>
                <span>Chi tiết</span>
            </button>`;
        }

        const allergyHtml = (item.DiUng && item.DiUng !== 'Không')
            ? `<span class="allergy-warning">⚠ ${esc(item.DiUng)}</span>`
            : `<span style="color: var(--color-on-surface-variant)">Không</span>`;

        const timeHtml = item.GioTiepNhan
            ? `<span class="time-highlight">${esc(item.GioTiepNhan)}</span>`
            : `<span style="color: var(--color-outline-variant); font-style: italic; font-size: 12px;">Chưa nhận</span>`;

        return `<tr>
            <td style="text-align: center; font-family: monospace; color: var(--color-on-surface-variant);">${esc(item.STT) || '—'}</td>
            <td style="font-weight: 700; color: var(--color-primary);">${esc(item.MaPhieuKhamCode)}</td>
            <td style="font-weight: 500;">${esc(item.MaBN)}</td>
            <td style="font-weight: 600; color: var(--color-on-surface);">${esc(item.HoTen)}</td>
            <td>${allergyHtml}</td>
            <td>${timeHtml}</td>
            <td>
                <span class="status-badge ${cfg.class}">
                    <span class="material-symbols-outlined">${cfg.icon}</span>
                    ${cfg.label}
                </span>
            </td>
            <td style="text-align: right;">${actionBtn}</td>
        </tr>`;
    }).join('');
}

function renderPagination(pg) {
    const info = document.getElementById('pg-info-text');
    const btns = document.getElementById('pg-btn-container');
    if (!info || !btns) return;

    const start = pg.total_records > 0 ? (pg.current_page - 1) * pg.limit + 1 : 0;
    const end   = Math.min(pg.current_page * pg.limit, pg.total_records);
    info.innerHTML = `Hiển thị <strong class="text-primary">${start}–${end}</strong> / <strong>${pg.total_records}</strong> phiếu khám`;

    if (pg.total_pages <= 1) { btns.innerHTML = ''; return; }

    let html = '';
    
    html += `<button class="pg-btn" onclick="loadPatientList(${pg.current_page-1})" ${pg.current_page<=1?'disabled':''}>‹</button>`;

    for (let i = 1; i <= pg.total_pages; i++) {
        if (pg.total_pages > 7 && i!==1 && i!==pg.total_pages && Math.abs(i-pg.current_page)>2) {
            if (i===pg.current_page-3 || i===pg.current_page+3) {
                html += `<span class="pg-ellipsis">…</span>`;
            }
            continue;
        }
        const activeClass = i === pg.current_page ? "pg-btn-active" : "";
        html += `<button class="pg-btn ${activeClass}" onclick="loadPatientList(${i})">${i}</button>`;
    }

    html += `<button class="pg-btn" onclick="loadPatientList(${pg.current_page+1})" ${pg.current_page>=pg.total_pages?'disabled':''}>›</button>`;
    btns.innerHTML = html;
}

function processClinicWorkflow(maPhieuKham, currentStatus) {
    if (!maPhieuKham) return;
    sessionStorage.setItem('auto_select_ma_phieu', maPhieuKham);
    
    const pageMap = {
        1: 'cap-cuu',
        2: 'so-kham',
        3: 'kham-benh',
        4: 'xet-nghiem-ktv',
        5: 'kq-xet-nghiem',    
        6: 'kham-benh',
        7: 'cap-phat',
        8: 'thanh-toan'
    };
    
    const targetPage = pageMap[currentStatus] || 'ho-so-benh-an';
    
    if (targetPage === 'kq-xet-nghiem' || currentStatus === 5) {
        window.location.href = `index.php?workspace=1&page=kq-xet-nghiem&maphieukham=${maPhieuKham}`;
    } else if (targetPage === 'xet-nghiem-ktv' || currentStatus === 4) {
        window.location.href = `index.php?workspace=1&page=xet-nghiem-ktv&maphieukham=${maPhieuKham}`;
    } else {
        const link = document.querySelector(`a[data-page="${targetPage}"]`) || document.querySelector(`[onclick*="${targetPage}"]`);
        if (link) link.click();
        else window.location.href = `index.php?workspace=1&page=${targetPage}`;
    }
}

function viewMedicalRecord(maPhieuKham) {
    sessionStorage.setItem('auto_select_ma_phieu', maPhieuKham);
    const link = document.querySelector(`a[data-page="ho-so-benh-an"]`) || document.querySelector(`[onclick*="ho-so-benh-an"]`);
    if (link) link.click();
    else showAlert('Mã phiếu khám: ' + maPhieuKham);
}

function esc(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function initPage() {
    const container = document.getElementById('main-card-container');
    if (!container || container.dataset.dsInit === '1') return;
    container.dataset.dsInit = '1';

    filterByStatus(currentStatusFilter);
    startAutoReload();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage);
} else {
    initPage();
}

new MutationObserver(() => {
    if (document.getElementById('main-card-container')) initPage();
    else stopAutoReload();
}).observe(document.body, { childList: true, subtree: true });

window.filterByStatus        = filterByStatus;
window.quickFilterStat       = quickFilterStat;
window.loadPatientList       = loadPatientList;
window.onSearchInput         = onSearchInput;
window.processClinicWorkflow = processClinicWorkflow;
window.viewMedicalRecord     = viewMedicalRecord;

})();
</script>