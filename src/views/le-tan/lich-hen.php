<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../../src/helpers/format.php';
?>
<link rel="stylesheet" href="public/assets/css/LeTan/lich-hen.css" />
<body>
<div class="tn-page">

    <!-- Page Header -->
    <div class="tn-page-header">
        <div>
            <h1>Tiếp nhận bệnh nhân</h1>
            <p>Tra cứu hồ sơ bệnh nhân, tạo phiếu khám & tiếp nhận phiếu đặt trước</p>
        </div>
        <div class="tn-badge-today" id="badge-today">
            <span id="today-label">Hôm nay</span>
        </div>
    </div>

    <div class="tn-two-col">

        <!-- ===================== LEFT PANEL: DANH SÁCH BỆNH NHÂN ===================== -->
        <div class="tn-panel">
            <div class="tn-panel-header">
                <div class="tn-panel-title">
                    <div class="icon-wrap icon-wrap-blue">
                        <span class="material-symbols-outlined" style="font-size:18px;">group</span>
                    </div>
                    <div>
                        <h2>Danh sách bệnh nhân:
                            <span class="tn-panel-count" id="bn-count">0</span>
                        </h2>
                        <span class="sub">Tra cứu, thêm mới & tạo phiếu khám trực tiếp</span>
                    </div>
                </div>
                <button class="btn-primary" onclick="openModalAddPatient()">
                    <span class="material-symbols-outlined" style="font-size:16px;">person_add</span> Thêm bệnh nhân
                </button>
            </div>
            <div class="tn-search-bar">
                <div class="tn-search-wrap">
                    <span class="material-symbols-outlined s-icon">search</span>
                    <input id="bn-search" type="text" class="tn-input" placeholder="Tìm theo CCCD hoặc Mã BN (BNxxxxx)..." oninput="applyBNFilter()" />
                </div>
            </div>
            <div class="tn-table-scroll" id="bn-table-scroll">
                <table class="tn-table">
                    <thead>
                        <tr>
                            <th>Mã BN</th>
                            <th>Họ tên</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>CCCD/CMND</th>
                            <th style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="bn-table-body">
                        <tr><td colspan="6" class="tn-empty">
                            <span class="tn-loading"></span> Đang tải danh sách bệnh nhân...
                        </td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tn-pagination">
                <div class="pg-summary" id="bn-pg-summary">0 bệnh nhân</div>
                <div class="pg-links" id="bn-pg-links"></div>
            </div>
        </div>

        <!-- ===================== RIGHT PANEL: PHIẾU KHÁM ĐẶT TRƯỚC (MaTrangThai=9) ===================== -->
        <div class="tn-panel">
            <div class="tn-panel-header">
                <div class="tn-panel-title">
                    <div class="icon-wrap icon-wrap-green">
                        <span class="material-symbols-outlined" style="font-size:18px;">receipt_long</span>
                    </div>
                    <div>
                        <h2>Phiếu khám đặt trước
                            <span class="tn-panel-count green" id="pk-count">0</span>
                        </h2>
                        <span class="sub">Tiếp nhận bệnh nhân đặt lịch trước có STT</span>
                    </div>
                </div>
                <button class="btn-secondary" onclick="fetchPhieuKhamDatTruoc()" title="Tải lại danh sách">
                    <span class="material-symbols-outlined" style="font-size:16px;">refresh</span>
                </button>
            </div>
            <div class="tn-search-bar">
                <div class="tn-search-wrap" style="flex:1;">
                    <span class="material-symbols-outlined s-icon">search</span>
                    <input id="pk-search" type="text" class="tn-input" placeholder="Tên BN, mã phiếu, mã BN..." oninput="applyPKFilter()" />
                </div>
            </div>
            <div class="tn-table-scroll" id="pk-table-scroll">
                <table class="tn-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã phiếu</th>
                            <th>Bệnh nhân</th>
                            <th>Giờ đặt</th>
                            <th style="text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="pk-table-body">
                        <tr><td colspan="5" class="tn-empty">
                            <span class="tn-loading"></span> Đang tải danh sách phiếu khám...
                        </td></tr>
                    </tbody>
                </table>
            </div>
            <div class="tn-pagination">
                <div class="pg-summary" id="pk-pg-summary">0 phiếu</div>
                <div class="pg-links" id="pk-pg-links"></div>
            </div>
        </div>

    </div>
</div>


<!-- ============================= MODAL: THÊM / SỬA BỆNH NHÂN ============================= -->
<div id="modal-patient" class="tn-modal-backdrop hidden">
    <div class="tn-modal">
        <div class="tn-modal-header">
            <div>
                <h2 id="modal-patient-title">Thêm bệnh nhân mới</h2>
                <p id="modal-patient-sub">Điền thông tin cá nhân để tạo hồ sơ bệnh nhân mới trong hệ thống</p>
            </div>
            <button class="btn-cancel-modal" onclick="closeModalPatient()" style="margin-left:16px;flex-shrink:0;">✕ Đóng</button>
        </div>
        <form id="form-patient" onsubmit="handleSavePatient(event)">
            <input type="hidden" id="hidden-edit-ma-benh-nhan" value="" />
            <input type="hidden" id="hidden-form-mode" value="add" />
            <input type="hidden" id="full_address" value="" />
            <div class="tn-modal-body">
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                        Thông tin bệnh nhân
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input id="p-ho-ten" required class="fg-input" type="text" placeholder="Nguyễn Văn A" />
                        </div>
                        <div class="form-group">
                            <label>Số CCCD *</label>
                            <input id="p-cccd" required class="fg-input" type="text" placeholder="012345678901" />
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input id="p-sdt" required class="fg-input" type="tel" placeholder="0912 345 678" />
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh *</label>
                            <input id="p-ngay-sinh" required class="fg-input" type="date" />
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label>
                            <div class="gender-row">
                                <label><input type="radio" name="gender" value="M" id="gender-nam" checked /><span>Nam</span></label>
                                <label><input type="radio" name="gender" value="F" id="gender-nu" /><span>Nữ</span></label>
                                <label><input type="radio" name="gender" value="O" id="gender-khac" /><span>Khác</span></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nhóm máu</label>
                            <select id="p-nhom-mau" class="fg-select">
                                <option value="">-- Không xác định --</option>
                                <option value="A+">A+</option><option value="A-">A-</option>
                                <option value="B+">B+</option><option value="B-">B-</option>
                                <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                <option value="O+">O+</option><option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group col-span-2">
                            <label>Số thẻ BHYT (nếu có)</label>
                            <input id="p-bhyt" class="fg-input" type="text" placeholder="15 ký tự số thẻ bảo hiểm y tế" />
                        </div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size:16px;">location_on</span>
                        Địa chỉ thường trú
                    </div>
                    <div class="address-grid">
                        <div class="form-group">
                            <label>Tỉnh / Thành phố *</label>
                            <select id="province" required class="fg-select">
                                <option value="">-- Chọn Tỉnh/Thành --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Xã / Phường *</label>
                            <select id="ward" required disabled class="fg-select">
                                <option value="">-- Chọn Xã/Phường --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Số nhà, tên đường *</label>
                        <input id="detail_address" required class="fg-input" type="text" placeholder="VD: 45 Phan Bội Châu" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeModalPatient()">Hủy bỏ</button>
                <button type="submit" id="btn-submit-patient" class="btn-save-modal">
                    <span class="material-symbols-outlined" style="font-size:14px;">save</span> Lưu hồ sơ
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ============================= MODAL: TẠO PHIẾU KHÁM TRỰC TIẾP ============================= -->
<div id="modal-create-phieu" class="tn-modal-backdrop hidden">
    <div class="tn-modal" style="max-width:480px;">
        <div class="tn-modal-header">
            <div>
                <h2>Tạo phiếu khám</h2>
                <p id="modal-phieu-sub">Tạo phiếu khám cho bệnh nhân đã có hồ sơ trong hệ thống</p>
            </div>
            <button class="btn-cancel-modal" onclick="closeModalPhieu()" style="margin-left:16px;flex-shrink:0;">✕ Đóng</button>
        </div>
        <input type="hidden" id="phieu-ma-benh-nhan" value="" />
        <div class="tn-modal-body" id="modal-phieu-body">
            <!-- Bước 1: Nhập thông tin -->
            <div id="phieu-step-input">
                <div class="form-section">
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size:16px;">assignment</span>
                        Thông tin bệnh nhân
                    </div>
                    <div class="form-group" style="display:flex;flex-direction:column;gap:8px;margin-bottom:15px;">
                        <div class="info-bn"><span class="cell-name-pk">Mã bệnh nhân:</span><span class="cell-value" id="lbl-phieu-ma-bn">--</span></div>
                        <div class="info-bn"><span class="cell-name-pk">Họ và tên:</span><span class="cell-value" id="lbl-phieu-ho-ten">--</span></div>
                        <div class="info-bn"><span class="cell-name-pk">Ngày sinh:</span><span class="cell-value" id="lbl-phieu-ngay-sinh">--</span></div>
                        <div class="info-bn"><span class="cell-name-pk">Giới tính:</span><span class="cell-value" id="lbl-phieu-gioi-tinh">--</span></div>
                        <div class="info-bn"><span class="cell-name-pk">CCCD:</span><span class="cell-value" id="lbl-phieu-cccd">--</span></div>
                        <div class="info-bn"><span class="cell-name-pk">SĐT:</span><span class="cell-value" id="lbl-phieu-sdt">--</span></div>
                        <div class="info-bn" style="border-bottom:none"><span class="cell-name-pk">Địa chỉ:</span><span class="cell-value" id="lbl-phieu-dia-chi">--</span></div>
                        <hr style="border:0;border-top:1px dashed #e2e8f0;margin:8px 0;">
                        <label style="font-weight:500;margin-bottom:4px;">Ghi chú / Lý do khám</label>
                        <input id="phieu-ghi-chu" class="fg-input" type="text" placeholder="Triệu chứng, lý do đến khám..." />
                    </div>
                </div>
                <div class="info-banner">
                    <span class="material-symbols-outlined">info</span>
                    <span>STT tự động sinh theo ngày, reset về 001 mỗi ngày mới. Bác sĩ sẽ được phân công ở bước tiếp theo.</span>
                </div>
            </div>
            <!-- Bước 2: Hiển thị kết quả phiếu khám -->
            <div id="phieu-step-result" style="display:none;">
                <div id="phieu-result-card-area"></div>
            </div>
        </div>
        <div class="modal-footer" id="modal-phieu-footer">
            <button type="button" class="btn-cancel-modal" onclick="closeModalPhieu()">Hủy bỏ</button>
            <button type="button" class="btn-create-phieu" id="btn-create-phieu" onclick="handleCreatePhieu()">
                <span class="material-symbols-outlined" style="font-size:14px;">add_circle</span> Tạo phiếu khám
            </button>
        </div>
    </div>
</div>


<!-- ============================= MODAL: TIẾP NHẬN PHIẾU KHÁM ĐẶT TRƯỚC ============================= -->
<div id="modal-tiepnhan-pk" class="tn-modal-backdrop hidden">
    <div class="tn-modal" style="max-width:540px;">
        <div class="tn-modal-header">
            <div>
                <h2 id="modal-tnpk-title">Tiếp nhận phiếu khám đặt trước</h2>
                <p id="modal-tnpk-sub">Xác nhận thông tin bệnh nhân trước khi tiếp nhận</p>
            </div>
            <button class="btn-cancel-modal" onclick="closeModalTiepNhanPK()" style="margin-left:16px;flex-shrink:0;">✕ Đóng</button>
        </div>
        <input type="hidden" id="tnpk-ma-phieu-kham" value="" />
        <input type="hidden" id="tnpk-ma-benh-nhan"  value="" />
        <div class="tn-modal-body" id="modal-tnpk-body">

            <!-- Bước 1: Thông tin BN có thể chỉnh sửa + thông tin PK -->
            <div id="tnpk-step-input">
                <!-- Banner thông tin phiếu -->
                <div class="pk-info-banner" style="margin-bottom:16px;" id="tnpk-pk-banner">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span id="tnpk-pk-banner-text">--</span>
                </div>

                <div class="form-section">
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size:16px;">person</span>
                        Thông tin bệnh nhân (có thể chỉnh sửa)
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input id="tnpk-ho-ten" required class="fg-input" type="text" />
                        </div>
                        <div class="form-group">
                            <label>Số CCCD *</label>
                            <input id="tnpk-cccd" required class="fg-input" type="text" />
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input id="tnpk-sdt" required class="fg-input" type="tel" />
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh *</label>
                            <input id="tnpk-ngay-sinh" required class="fg-input" type="date" />
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label>
                            <div class="gender-row">
                                <label><input type="radio" name="tnpk-gender" value="M" id="tnpk-gender-nam" checked /><span>Nam</span></label>
                                <label><input type="radio" name="tnpk-gender" value="F" id="tnpk-gender-nu" /><span>Nữ</span></label>
                                <label><input type="radio" name="tnpk-gender" value="O" id="tnpk-gender-khac" /><span>Khác</span></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nhóm máu</label>
                            <select id="tnpk-nhom-mau" class="fg-select">
                                <option value="">-- Không xác định --</option>
                                <option value="A+">A+</option><option value="A-">A-</option>
                                <option value="B+">B+</option><option value="B-">B-</option>
                                <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                <option value="O+">O+</option><option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bước 2: Kết quả sau tiếp nhận -->
            <div id="tnpk-step-result" style="display:none;">
                <div id="tnpk-result-card-area"></div>
            </div>
        </div>
        <div class="modal-footer" id="modal-tnpk-footer">
            <button type="button" class="btn-cancel-modal" onclick="closeModalTiepNhanPK()">Hủy bỏ</button>
            <button type="button" class="btn-save-modal" id="btn-confirm-tiepnhan" onclick="handleConfirmTiepNhanPK()">
                <span class="material-symbols-outlined" style="font-size:14px;">how_to_reg</span> Xác nhận tiếp nhận
            </button>
        </div>
    </div>
</div>


<script>
(function () {
    // =========================================================================
    // STATE
    // =========================================================================
    let rawBNList  = [];
    let filtBNList = [];
    let bnPage     = 1;
    const BN_PER_PAGE = 8;

    let rawPKList  = [];   // Phiếu khám đặt trước (MaTrangThai=9)
    let filtPKList = [];
    let pkPage     = 1;
    const PK_PER_PAGE = 8;

    const API_BASE_PROVINCE = 'https://provinces.open-api.vn/api/v2';

    // =========================================================================
    // INIT
    // =========================================================================
    async function init() {
        const today = new Date();
        const label = today.toLocaleDateString('vi-VN', { weekday:'long', day:'2-digit', month:'2-digit', year:'numeric' });
        const el = document.getElementById('today-label');
        if (el) el.textContent = label;

        await Promise.all([fetchBenhNhan(), fetchPhieuKhamDatTruoc(), initProvinces()]);
    }

    // =========================================================================
    // BỆNH NHÂN
    // =========================================================================
    function fetchBenhNhan() {
        return fetch('src/api/getBenhNhan.php')
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    rawBNList  = result.data || [];
                    filtBNList = [...rawBNList];
                    renderBNTable();
                } else {
                    renderBNError(result.message || 'Không tải được danh sách bệnh nhân.');
                }
            })
            .catch(() => renderBNError('Lỗi kết nối máy chủ.'));
    }

    function applyBNFilter() {
        const kw = (document.getElementById('bn-search')?.value || '').toLowerCase().trim();
        filtBNList = rawBNList.filter(bn => {
            if (!kw) return true;
            return (bn.MaBN && bn.MaBN.toLowerCase().includes(kw))
                || (bn.CCCD && bn.CCCD.includes(kw))
                || (bn.HoTen && bn.HoTen.toLowerCase().includes(kw))
                || (bn.SoDienThoai && bn.SoDienThoai.includes(kw));
        });
        bnPage = 1;
        renderBNTable();
    }

    function renderBNTable() {
        const tbody = document.getElementById('bn-table-body');
        if (!tbody) return;
        document.getElementById('bn-count').textContent = filtBNList.length;

        if (filtBNList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6"><div class="tn-empty">
                <span class="material-symbols-outlined e-icon">person_search</span>
                Không tìm thấy bệnh nhân phù hợp
            </div></td></tr>`;
            setupPagination('bn', 0, 0, 0);
            return;
        }

        const total = filtBNList.length;
        const start = (bnPage - 1) * BN_PER_PAGE;
        const end   = Math.min(start + BN_PER_PAGE, total);
        const slice = filtBNList.slice(start, end);

        tbody.innerHTML = '';
        slice.forEach(bn => {
            const dob = bn.NgaySinh ? bn.NgaySinh.split('-').reverse().join('/') : '---';
            let gt = bn.GioiTinh || '';
            if (gt === 'M') gt = 'Nam'; else if (gt === 'F') gt = 'Nữ'; else if (gt === 'O') gt = 'Khác';

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td><span class="cell-code">${esc(bn.MaBN)}</span></td>
                    <td>
                        <div class="cell-name">${esc(bn.HoTen)}</div>
                        <div class="cell-sub">${esc(bn.SoDienThoai || '')}</div>
                    </td>
                    <td><div class="cell-name">${dob}</div></td>
                    <td><div class="cell-name">${gt || '---'}</div></td>
                    <td><div class="cell-name">${esc(bn.CCCD || '---')}</div></td>
                    <td class="cell-action">
                        <button class="btn-view" onclick="openModalEditPatient(${bn.MaBenhNhan})">
                            <span class="material-symbols-outlined" style="font-size:14px;">edit</span> Sửa
                        </button>
                        <button class="btn-success" onclick="openModalPhieu(${bn.MaBenhNhan})">
                            <span class="material-symbols-outlined" style="font-size:14px;">add_circle</span> Tạo PK
                        </button>
                    </td>
                </tr>
            `);
        });

        setupPagination('bn', total, start + 1, end);
    }

    function renderBNError(msg) {
        const tbody = document.getElementById('bn-table-body');
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="tn-empty" style="color:#ef4444;">⚠️ ${esc(msg)}</td></tr>`;
    }

    // =========================================================================
    // PHIẾU KHÁM ĐẶT TRƯỚC (MaTrangThai = 9)
    // =========================================================================
    function fetchPhieuKhamDatTruoc() {
        const tbody = document.getElementById('pk-table-body');
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="tn-empty"><span class="tn-loading"></span> Đang tải...</td></tr>`;

        return fetch('src/api/getDSPhieuKham.php?status_filter=9')
            .then(r => r.json())
            .then(result => {
                if (result.success || result.status === 'success') {
                    rawPKList  = (result.data || []).filter(pk => parseInt(pk.MaTrangThai) === 9);
                    filtPKList = [...rawPKList];
                    renderPKTable();
                } else {
                    renderPKError(result.message || 'Không tải được danh sách phiếu khám.');
                }
            })
            .catch(() => renderPKError('Lỗi kết nối máy chủ.'));
    }

    function applyPKFilter() {
        const kw = (document.getElementById('pk-search')?.value || '').toLowerCase().trim();
        filtPKList = rawPKList.filter(pk => {
            if (!kw) return true;
            return (pk.HoTen && pk.HoTen.toLowerCase().includes(kw))
                || (pk.MaBN && pk.MaBN.toLowerCase().includes(kw))
                || (pk.MaPhieuKhamCode && pk.MaPhieuKhamCode.toLowerCase().includes(kw))
                || String(pk.STT).includes(kw);
        });
        pkPage = 1;
        renderPKTable();
    }

    function renderPKTable() {
        const tbody = document.getElementById('pk-table-body');
        if (!tbody) return;
        document.getElementById('pk-count').textContent = filtPKList.length;

        if (filtPKList.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5"><div class="tn-empty">
                <span class="material-symbols-outlined e-icon">inbox</span>
                Không có phiếu khám đặt trước nào đang chờ tiếp nhận
            </div></td></tr>`;
            setupPagination('pk', 0, 0, 0);
            return;
        }

        const total = filtPKList.length;
        const start = (pkPage - 1) * PK_PER_PAGE;
        const end   = Math.min(start + PK_PER_PAGE, total);
        const slice = filtPKList.slice(start, end);

        tbody.innerHTML = '';
        slice.forEach(pk => {
            const gio = pk.GioTiepNhan ? pk.GioTiepNhan.substring(0,5) : '---';
            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td><span class="pkr-stt-badge" style="
                        display:inline-flex;align-items:center;justify-content:center;
                        width:34px;height:34px;border-radius:50%;
                        background:#0284c7;color:#fff;font-weight:800;font-size:15px;">
                        ${esc(String(pk.STT))}
                    </span></td>
                    <td>
                        <div class="cell-code" style="font-size:11px;">${esc(pk.MaPhieuKhamCode || '#' + pk.MaPhieuKham)}</div>
                    </td>
                    <td>
                        <div class="cell-name">${esc(pk.HoTen || 'N/A')}</div>
                        <div class="cell-sub">${esc(pk.MaBN || '')}</div>
                    </td>
                    <td><div class="cell-name">${pk.NgayTao}</div></td>
                    <td class="cell-action">
                        <button class="btn-accept" onclick="openModalTiepNhanPK(${pk.MaPhieuKham})">
                            <span class="material-symbols-outlined" style="font-size:13px;">how_to_reg</span> Tiếp nhận
                        </button>
                    </td>
                </tr>
            `);
        });

        setupPagination('pk', total, start + 1, end);
    }

    function renderPKError(msg) {
        const tbody = document.getElementById('pk-table-body');
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="tn-empty" style="color:#ef4444;">⚠️ ${esc(msg)}</td></tr>`;
    }

    // =========================================================================
    // PAGINATION
    // =========================================================================
    function setupPagination(prefix, total, startRow, endRow) {
        const summary = document.getElementById(`${prefix}-pg-summary`);
        const links   = document.getElementById(`${prefix}-pg-links`);
        if (!summary || !links) return;

        const perPage = prefix === 'bn' ? BN_PER_PAGE : PK_PER_PAGE;
        const curPage = prefix === 'bn' ? bnPage : pkPage;
        const unit    = prefix === 'bn' ? 'bệnh nhân' : 'phiếu';

        summary.textContent = total > 0 ? `${startRow}–${endRow} / ${total} ${unit}` : `0 ${unit}`;
        links.innerHTML = '';

        const totalPages = Math.ceil(total / perPage);
        if (totalPages <= 1) return;

        const prev = document.createElement('button');
        prev.className = 'pg-btn'; prev.innerHTML = '‹'; prev.disabled = curPage === 1;
        prev.onclick = () => goPage(prefix, curPage - 1);
        links.appendChild(prev);

        for (let i = 1; i <= totalPages; i++) {
            if (totalPages > 7 && Math.abs(i - curPage) > 2 && i !== 1 && i !== totalPages) {
                if (i === curPage - 3 || i === curPage + 3) {
                    const dots = document.createElement('span');
                    dots.textContent = '…'; dots.style.cssText = 'padding:0 4px;color:#94a3b8;';
                    links.appendChild(dots);
                }
                continue;
            }
            const btn = document.createElement('button');
            btn.className = 'pg-btn' + (i === curPage ? ' active' : '');
            btn.textContent = i; btn.onclick = () => goPage(prefix, i);
            links.appendChild(btn);
        }

        const next = document.createElement('button');
        next.className = 'pg-btn'; next.innerHTML = '›'; next.disabled = curPage === totalPages;
        next.onclick = () => goPage(prefix, curPage + 1);
        links.appendChild(next);
    }

    function goPage(prefix, page) {
        if (prefix === 'bn') { bnPage = page; renderBNTable(); }
        else                  { pkPage = page; renderPKTable(); }
    }

    // =========================================================================
    // MODAL THÊM / SỬA BỆNH NHÂN
    // =========================================================================
    function openModalAddPatient() {
        resetPatientForm();
        setFormMode('add');
        document.getElementById('modal-patient-title').textContent = 'Thêm bệnh nhân mới';
        document.getElementById('modal-patient-sub').textContent   = 'Điền thông tin cá nhân để tạo hồ sơ bệnh nhân mới';
        document.getElementById('hidden-edit-ma-benh-nhan').value  = '';
        document.getElementById('btn-submit-patient').innerHTML    = '<span class="material-symbols-outlined" style="font-size:14px;">save</span> Lưu hồ sơ';
        setFormReadonly(false);
        document.getElementById('modal-patient').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openModalEditPatient(maBenhNhan) {
        const bn = rawBNList.find(x => x.MaBenhNhan == maBenhNhan);
        if (!bn) return;
        resetPatientForm();
        setFormMode('edit');
        document.getElementById('hidden-edit-ma-benh-nhan').value  = bn.MaBenhNhan;
        document.getElementById('modal-patient-title').textContent = `Sửa thông tin – ${bn.MaBN || '#' + bn.MaBenhNhan}`;
        document.getElementById('modal-patient-sub').textContent   = 'Cập nhật hồ sơ bệnh nhân. Thay đổi sẽ được lưu vào CSDL.';
        document.getElementById('btn-submit-patient').innerHTML    = '<span class="material-symbols-outlined" style="font-size:14px;">save</span> Cập nhật hồ sơ';
        fillFromPatient(bn);
        setFormReadonly(false);
        document.getElementById('modal-patient').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalPatient() {
        document.getElementById('modal-patient').classList.add('hidden');
        document.body.style.overflow = '';
        setFormReadonly(false);
        document.getElementById('btn-submit-patient').classList.remove('hidden');
        document.getElementById('ward').disabled = true;
    }

    function setFormReadonly(isReadonly) {
        const form = document.getElementById('form-patient');
        form.querySelectorAll('input, select, textarea').forEach(el => { el.disabled = isReadonly; });
        if (!isReadonly) {
            const wSel = document.getElementById('ward');
            const pSel = document.getElementById('province');
            if (pSel && pSel.selectedIndex <= 0) wSel.disabled = true;
        }
    }

    function setFormMode(mode) { document.getElementById('hidden-form-mode').value = mode; }

    function resetPatientForm() {
        const form = document.getElementById('form-patient');
        if (form) form.reset();
        document.getElementById('full_address').value             = '';
        document.getElementById('hidden-edit-ma-benh-nhan').value = '';
        document.getElementById('hidden-form-mode').value         = 'add';
        document.getElementById('ward').disabled                  = true;
        document.getElementById('gender-nam').checked             = true;
        document.getElementById('btn-submit-patient').classList.remove('hidden');
    }

    function fillFromPatient(bn) {
        document.getElementById('p-ho-ten').value    = bn.HoTen       || '';
        document.getElementById('p-cccd').value      = bn.CCCD        || '';
        document.getElementById('p-sdt').value       = bn.SoDienThoai || '';
        document.getElementById('p-ngay-sinh').value = bn.NgaySinh    || '';
        document.getElementById('p-bhyt').value      = bn.SoBHYT      || '';
        const nmSel = document.getElementById('p-nhom-mau');
        if (nmSel && bn.NhomMau) nmSel.value = bn.NhomMau;
        const gt = bn.GioiTinh || 'M';
        if (gt === 'M')      document.getElementById('gender-nam').checked  = true;
        else if (gt === 'F') document.getElementById('gender-nu').checked   = true;
        else                 document.getElementById('gender-khac').checked = true;
        fillAddress(bn.DiaChi || '');
    }

    // =========================================================================
    // SAVE PATIENT (Thêm mới / Sửa)
    // =========================================================================
    function handleSavePatient(event) {
        event.preventDefault();
        composeFullAddress();
        const pSel = document.getElementById('province');
        const wSel = document.getElementById('ward');
        if ((pSel && pSel.selectedIndex <= 0) || (wSel && wSel.selectedIndex <= 0)) {
            showAlert('Vui lòng chọn đầy đủ Tỉnh/Thành phố và Xã/Phường.');
            return;
        }

        const payload = {
            maBenhNhan: document.getElementById('hidden-edit-ma-benh-nhan').value || null,
            hoTen:      document.getElementById('p-ho-ten').value.trim(),
            cccd:       document.getElementById('p-cccd').value.trim(),
            sdt:        document.getElementById('p-sdt').value.trim(),
            ngaySinh:   document.getElementById('p-ngay-sinh').value,
            gioiTinh:   document.querySelector('input[name="gender"]:checked')?.value || 'M',
            BHYT:       document.getElementById('p-bhyt').value.trim(),
            nhomMau:    document.getElementById('p-nhom-mau').value,
            diaChi:     document.getElementById('full_address').value,
        };

        const btn = document.getElementById('btn-submit-patient');
        if (btn) { btn.disabled = true; btn.textContent = 'Đang lưu...'; }

        fetch('src/api/saveBenhNhanTiepNhan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                showAlert(payload.maBenhNhan ? '✅ Cập nhật hồ sơ thành công!' : '✅ Thêm bệnh nhân mới thành công!');
                closeModalPatient();
                fetchBenhNhan();
            } else {
                showAlert('❌ ' + (result.message || 'Lỗi không xác định.'));
            }
        })
        .catch(() => showAlert('❌ Không kết nối được máy chủ.'))
        .finally(() => {
            if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">save</span> Lưu hồ sơ'; }
        });
    }

    // =========================================================================
    // MODAL TẠO PHIẾU KHÁM TRỰC TIẾP
    // =========================================================================
    function openModalPhieu(maBenhNhan) {
        const bn = rawBNList.find(x => x.MaBenhNhan == maBenhNhan);
        if (!bn) { showAlert("Không tìm thấy thông tin bệnh nhân!"); return; }

        // Reset về bước nhập
        document.getElementById('phieu-step-input').style.display  = '';
        document.getElementById('phieu-step-result').style.display = 'none';
        document.getElementById('modal-phieu-footer').innerHTML = `
            <button type="button" class="btn-cancel-modal" onclick="closeModalPhieu()">Hủy bỏ</button>
            <button type="button" class="btn-create-phieu" id="btn-create-phieu" onclick="handleCreatePhieu()">
                <span class="material-symbols-outlined" style="font-size:14px;">add_circle</span> Tạo phiếu khám
            </button>`;

        document.getElementById('phieu-ma-benh-nhan').value      = bn.MaBenhNhan;
        document.getElementById('lbl-phieu-ma-bn').textContent   = bn.MaBN || 'Chưa cấp mã';
        document.getElementById('lbl-phieu-ho-ten').textContent  = bn.HoTen || 'N/A';
        document.getElementById('lbl-phieu-ngay-sinh').textContent = bn.NgaySinh || 'N/A';
        document.getElementById('lbl-phieu-cccd').textContent    = bn.CCCD || 'N/A';
        document.getElementById('lbl-phieu-sdt').textContent     = bn.SoDienThoai || 'N/A';
        document.getElementById('lbl-phieu-dia-chi').textContent = bn.DiaChi || 'N/A';
        let gtText = 'Khác';
        if (bn.GioiTinh === 'M' || bn.GioiTinh === 'Nam') gtText = 'Nam';
        else if (bn.GioiTinh === 'F' || bn.GioiTinh === 'Nữ') gtText = 'Nữ';
        document.getElementById('lbl-phieu-gioi-tinh').textContent = gtText;
        document.getElementById('phieu-ghi-chu').value = '';

        document.getElementById('modal-create-phieu').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function handleCreatePhieu() {
        const maBenhNhan = document.getElementById('phieu-ma-benh-nhan').value;
        const ghiChu     = document.getElementById('phieu-ghi-chu').value.trim();
        if (!maBenhNhan) { showAlert('Không tìm thấy mã bệnh nhân. Vui lòng thử lại!'); return; }

        const now = new Date();
        const gioTiepNhan = [
            String(now.getHours()).padStart(2,'0'),
            String(now.getMinutes()).padStart(2,'0'),
            String(now.getSeconds()).padStart(2,'0')
        ].join(':');

        const btn = document.getElementById('btn-create-phieu');
        if (btn) { btn.disabled = true; btn.textContent = 'Đang xử lý...'; }

        fetch('src/api/createPhieuKham.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ maBenhNhan, ghiChu, gioTiepNhan })
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                // Hiển thị card kết quả phiếu khám
                showPhieuResult('phieu-result-card-area', result.data, 'direct');
                document.getElementById('phieu-step-input').style.display  = 'none';
                document.getElementById('phieu-step-result').style.display = '';
                // Đổi footer thành nút In / Đóng
                document.getElementById('modal-phieu-footer').innerHTML = `
                    <button type="button" class="btn-dismiss-pk" onclick="closeModalPhieu()">
                        <span class="material-symbols-outlined" style="font-size:14px;">close</span> Đóng
                    </button>
                    <button type="button" class="btn-print-pk" onclick="printPhieu()">
                        <span class="material-symbols-outlined" style="font-size:14px;">print</span> In phiếu khám
                    </button>`;
                fetchPhieuKhamDatTruoc(); // Refresh panel phải
                if (typeof fetchBenhNhan === "function") fetchBenhNhan();
            } else {
                showAlert('❌ ' + (result.message || 'Lỗi không xác định.'));
                if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">add_circle</span> Tạo phiếu khám'; }
            }
        })
        .catch(() => {
            showAlert('❌ Không kết nối được máy chủ.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">add_circle</span> Tạo phiếu khám'; }
        });
    }

    function closeModalPhieu() {
        document.getElementById('modal-create-phieu').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // =========================================================================
    // MODAL TIẾP NHẬN PHIẾU KHÁM ĐẶT TRƯỚC
    // =========================================================================
    function openModalTiepNhanPK(maPhieuKham) {
    // 1. Tìm thông tin phiếu khám từ danh sách
    const pk = rawPKList.find(x => x.MaPhieuKham == maPhieuKham);
    if (!pk) { showAlert('Không tìm thấy thông tin phiếu khám!'); return; }

    // 2. Tìm thông tin bệnh nhân tương ứng trong danh sách hồ sơ (SỬA LỖI maBenhNhan)
    // Dùng dấu ? để phòng trường hợp rawBNList chưa tải kịp hoặc không tìm thấy
    const bn = rawBNList ? rawBNList.find(x => x.MaBenhNhan == pk.MaBenhNhan) : null;

    // Reset giao diện về bước nhập thông tin ban đầu
    document.getElementById('tnpk-step-input').style.display  = '';
    document.getElementById('tnpk-step-result').style.display = 'none';
    document.getElementById('modal-tnpk-footer').innerHTML = `
        <button type="button" class="btn-cancel-modal" onclick="closeModalTiepNhanPK()">Hủy bỏ</button>
        <button type="button" class="btn-save-modal" id="btn-confirm-tiepnhan" onclick="handleConfirmTiepNhanPK()">
            <span class="material-symbols-outlined" style="font-size:14px;">how_to_reg</span> Xác nhận tiếp nhận
        </button>`;

    // Gán các giá trị ID ẩn vào Form
    document.getElementById('tnpk-ma-phieu-kham').value = pk.MaPhieuKham;
    document.getElementById('tnpk-ma-benh-nhan').value  = pk.MaBenhNhan;

    // Hiển thị banner thông tin phiếu (SỬA: Ưu tiên hiển thị GioHen, nếu không có mới dùng GioTiepNhan)
    const gioHienThi = pk.GioHen ? pk.GioHen.substring(0,5) : (pk.GioTiepNhan ? pk.GioTiepNhan.substring(0,5) : 'N/A');
    document.getElementById('tnpk-pk-banner-text').textContent =
        `Phiếu ${esc(pk.MaPhieuKhamCode || '#' + pk.MaPhieuKham)} - STT: ${pk.STT} - Đặt lúc: ${pk.NgayTao}`;

    document.getElementById('modal-tnpk-title').textContent = `Tiếp nhận phiếu khám - STT ${pk.STT}`;

    // Đổ dữ liệu Bệnh nhân lên các ô Input
    document.getElementById('tnpk-ho-ten').value    = pk.HoTen || '';
    
    // SỬA: Lấy an toàn từ đối tượng bn (hoặc từ chính pk nếu API có trả về kèm)
    document.getElementById('tnpk-cccd').value      = (bn ? bn.CCCD : '') || pk.CCCD || '';
    document.getElementById('tnpk-sdt').value       = (bn ? bn.SoDienThoai : '') || pk.SoDienThoai || '';
    document.getElementById('tnpk-ngay-sinh').value = (bn ? bn.NgaySinh : '') || pk.NgaySinh || '';

    // Xử lý nhóm máu nếu form có trường này
    const nmSel = document.getElementById('tnpk-nhom-mau');
    if (nmSel) {
        nmSel.value = pk.NhomMau || (bn ? bn.NhomMau : '') || 'Chưa xác định';
    }

    // Xử lý Giới tính (M / F / Khác)
    const gt = pk.GioiTinh || (bn ? bn.GioiTinh : 'M');
    if (gt === 'M' || gt === 'Nam')      document.getElementById('tnpk-gender-nam').checked  = true;
    else if (gt === 'F' || gt === 'Nữ') document.getElementById('tnpk-gender-nu').checked   = true;
    else                                document.getElementById('tnpk-gender-khac').checked = true;

    // Hiển thị Modal và chặn cuộn trang nền
    document.getElementById('modal-tiepnhan-pk').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

    function handleConfirmTiepNhanPK() {
        const maPhieuKham = document.getElementById('tnpk-ma-phieu-kham').value;
        const maBenhNhan  = document.getElementById('tnpk-ma-benh-nhan').value;
        if (!maPhieuKham) { showAlert('Không tìm thấy mã phiếu khám!'); return; }

        // Thu thập thông tin BN đã có thể chỉnh sửa
        const payload = {
            maPhieuKham: maPhieuKham,
            maBenhNhan:  maBenhNhan,
            hoTen:       document.getElementById('tnpk-ho-ten').value.trim(),
            cccd:        document.getElementById('tnpk-cccd').value.trim(),
            sdt:         document.getElementById('tnpk-sdt').value.trim(),
            ngaySinh:    document.getElementById('tnpk-ngay-sinh').value,
            gioiTinh:    document.querySelector('input[name="tnpk-gender"]:checked')?.value || 'M',
            nhomMau:     document.getElementById('tnpk-nhom-mau').value,
        };

        if (!payload.hoTen || !payload.cccd || !payload.sdt) {
            showAlert('Vui lòng điền đầy đủ họ tên, CCCD và số điện thoại.');
            return;
        }

        const btn = document.getElementById('btn-confirm-tiepnhan');
        if (btn) { btn.disabled = true; btn.textContent = 'Đang xử lý...'; }

        // Gọi API cập nhật trạng thái phiếu khám → 2
        fetch('src/api/saveBenhNhanTiepNhan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(result => {
            if (result.success) {
                // Hiển thị card kết quả
                const pk = rawPKList.find(x => x.MaPhieuKham == maPhieuKham);
                const pkData = {
                    sttNgay:         pk ? String(pk.STT).padStart(3,'0') : '---',
                    maPhieuKhamCode: pk ? (pk.MaPhieuKhamCode || '#' + pk.MaPhieuKham) : '---',
                    maBenhNhan:      maBenhNhan
                };
                showPhieuResult('tnpk-result-card-area', pkData, 'tiepnhan');
                document.getElementById('tnpk-step-input').style.display  = 'none';
                document.getElementById('tnpk-step-result').style.display = '';
                // Đổi footer
                document.getElementById('modal-tnpk-footer').innerHTML = `
                    <button type="button" class="btn-dismiss-pk" onclick="closeModalTiepNhanPK()">
                        <span class="material-symbols-outlined" style="font-size:14px;">close</span> Đóng
                    </button>
                    <button type="button" class="btn-print-pk" onclick="printPhieu()">
                        <span class="material-symbols-outlined" style="font-size:14px;">print</span> In phiếu khám
                    </button>`;
                fetchPhieuKhamDatTruoc(); // Xóa khỏi danh sách chờ
            } else {
                showAlert('❌ ' + (result.message || 'Lỗi không xác định.'));
                if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">how_to_reg</span> Xác nhận tiếp nhận'; }
            }
        })
        .catch(() => {
            showAlert('❌ Không kết nối được máy chủ.');
            if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined" style="font-size:14px;">how_to_reg</span> Xác nhận tiếp nhận'; }
        });
    }

    function closeModalTiepNhanPK() {
        document.getElementById('modal-tiepnhan-pk').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // =========================================================================
    // HIỂN THỊ CARD KẾT QUẢ PHIẾU KHÁM (dùng chung)
    // =========================================================================
    function showPhieuResult(containerId, pkData, mode) {
        const today   = new Date();
        const dateStr = today.toLocaleDateString('vi-VN', { day:'2-digit', month:'2-digit', year:'numeric' });
        const timeStr = String(today.getHours()).padStart(2,'0') + ':' + String(today.getMinutes()).padStart(2,'0');
        const isNew   = mode === 'direct';

        const headerTitle = isNew ? 'Phiếu khám đã được tạo' : 'Tiếp nhận thành công';
        const headerSub   = isNew ? 'Vui lòng in và giao phiếu cho bệnh nhân' : 'Bệnh nhân đã được xác nhận vào hàng chờ';
        const notice      = isNew
            ? 'Chuyên khoa và bác sĩ sẽ được phân công ở bước tiếp theo tại quầy phân phòng.'
            : 'Bệnh nhân đã vào hàng chờ với STT bên dưới. Hãy in phiếu và hướng dẫn ngồi chờ theo số thứ tự.';

        document.getElementById(containerId).innerHTML = `
            <div class="pk-result-card">
                <div class="pkr-header">
                    <div class="pkr-icon">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <div class="pkr-title">
                        <h3>${headerTitle}</h3>
                        <p>${headerSub}</p>
                    </div>
                </div>

                <div class="pkr-stt">
                    <span class="stt-lbl">Số thứ tự hôm nay</span>
                    <span class="stt-num">${esc(pkData.sttNgay || '---')}</span>
                </div>

                <div class="pkr-code">
                    <span class="code-lbl">Mã phiếu khám</span>
                    <span class="code-val">${esc(pkData.maPhieuKhamCode || '---')}</span>
                </div>

                <div class="pkr-grid">
                    <div class="pkr-item">
                        <div class="pi-lbl">Ngày khám</div>
                        <div class="pi-val">${dateStr}</div>
                    </div>
                    <div class="pkr-item">
                        <div class="pi-lbl">Giờ tiếp nhận</div>
                        <div class="pi-val">${timeStr}</div>
                    </div>
                    <div class="pkr-item">
                        <div class="pi-lbl">Trạng thái</div>
                        <div class="pi-val" style="color:#0284c7;">Chờ phân công</div>
                    </div>
                    <div class="pkr-item">
                        <div class="pi-lbl">Mã bệnh nhân</div>
                        <div class="pi-val">#BN-${esc(String(pkData.maBenhNhan || '---'))}</div>
                    </div>
                </div>

                <div class="pkr-notice">
                    <span class="material-symbols-outlined">info</span>
                    <span>${notice}</span>
                </div>
            </div>
        `;
        // Lưu mã phiếu để hàm in dùng
        window._lastPKCode = pkData.maPhieuKhamCode;
        window._lastSTT    = pkData.sttNgay;
    }

    // =========================================================================
    // IN PHIẾU KHÁM (đơn giản — mở cửa sổ in)
    // =========================================================================
    function printPhieu() {
        const code = window._lastPKCode || '---';
        const stt  = window._lastSTT    || '---';
        const today = new Date().toLocaleDateString('vi-VN', { day:'2-digit', month:'2-digit', year:'numeric' });
        const win = window.open('', '_blank', 'width=400,height=500');
        win.document.write(`<!DOCTYPE html><html><head>
            <meta charset="UTF-8"><title>In phiếu khám</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
                h2 { font-size: 18px; margin-bottom: 4px; }
                .stt { font-size: 64px; font-weight: 900; color: #0284c7; margin: 12px 0; }
                .code { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
                .info { font-size: 12px; color: #555; }
                hr { border: 0; border-top: 1px dashed #ccc; margin: 14px 0; }
            </style>
        </head><body>
            <h2>PHIẾU KHÁM BỆNH</h2>
            <hr>
            <div class="info">Ngày: ${today}</div>
            <div class="stt">${stt}</div>
            <div class="code">Mã phiếu: ${code}</div>
            <hr>
            <div class="info">Vui lòng giữ phiếu này và chờ gọi số thứ tự</div>
        </body></html>`);
        win.document.close();
        win.focus();
        setTimeout(() => win.print(), 300);
    }

    // =========================================================================
    // PROVINCES
    // =========================================================================
    async function initProvinces() {
        const pSel = document.getElementById('province');
        const wSel = document.getElementById('ward');
        if (!pSel || !wSel) return;

        pSel.addEventListener('change', composeFullAddress);
        wSel.addEventListener('change', composeFullAddress);
        document.getElementById('detail_address')?.addEventListener('input', composeFullAddress);
        pSel.addEventListener('change', handleProvinceChange);

        try {
            const resp = await fetch(`${API_BASE_PROVINCE}/p/`);
            const list = await resp.json();
            list.sort((a, b) => a.name.localeCompare(b.name));
            pSel.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
            list.forEach(p => {
                const o = document.createElement('option');
                o.value = p.code; o.textContent = p.name;
                pSel.appendChild(o);
            });
        } catch (e) { console.error('Lỗi tải tỉnh:', e); }
    }

    async function handleProvinceChange(e) {
        const code = e.target.value;
        const wSel = document.getElementById('ward');
        wSel.innerHTML = '<option value="">-- Chọn Xã/Phường --</option>';
        wSel.disabled = true;
        if (!code) { composeFullAddress(); return; }
        try {
            const resp = await fetch(`${API_BASE_PROVINCE}/p/${code}?depth=2`);
            const data = await resp.json();
            const wards = (data.wards || []).sort((a, b) => a.name.localeCompare(b.name));
            wards.forEach(w => {
                const o = document.createElement('option');
                o.value = w.code; o.textContent = w.name;
                wSel.appendChild(o);
            });
            const pSel = document.getElementById('province');
            if (pSel && !pSel.hasAttribute('disabled')) wSel.disabled = false;
        } catch (e) { console.error('Lỗi tải xã:', e); }
        composeFullAddress();
    }

    async function loadWardsAsync(provinceCode, matchXaText) {
        const wSel = document.getElementById('ward');
        if (!wSel) return;
        try {
            const resp = await fetch(`${API_BASE_PROVINCE}/p/${provinceCode}?depth=2`);
            const data = await resp.json();
            const wards = (data.wards || []).sort((a, b) => a.name.localeCompare(b.name));
            wSel.innerHTML = '<option value="">-- Chọn Xã/Phường --</option>';
            wards.forEach(w => {
                const o = document.createElement('option');
                o.value = w.code; o.textContent = w.name;
                if (w.name.toLowerCase() === matchXaText.toLowerCase()) o.selected = true;
                wSel.appendChild(o);
            });
            const pSel = document.getElementById('province');
            if (pSel && !pSel.hasAttribute('disabled')) wSel.disabled = false;
        } catch (e) { console.error(e); }
        composeFullAddress();
    }

    function fillAddress(addr) {
        if (!addr || !addr.includes(',')) { document.getElementById('detail_address').value = addr; return; }
        const parts = addr.split(',');
        if (parts.length >= 3) {
            const tinhText = parts[parts.length - 1].trim();
            const xaText   = parts[parts.length - 2].trim();
            const duong    = parts.slice(0, parts.length - 2).join(',').trim();
            document.getElementById('detail_address').value = duong;
            const pSel = document.getElementById('province');
            if (pSel) {
                for (let opt of pSel.options) {
                    if (opt.text.toLowerCase() === tinhText.toLowerCase()) {
                        pSel.value = opt.value;
                        loadWardsAsync(opt.value, xaText);
                        break;
                    }
                }
            }
        } else {
            document.getElementById('detail_address').value = addr;
        }
    }

    function composeFullAddress() {
        const dInp = document.getElementById('detail_address');
        const pSel = document.getElementById('province');
        const wSel = document.getElementById('ward');
        const fHid = document.getElementById('full_address');
        if (!dInp || !pSel || !wSel || !fHid) return;
        const detail   = dInp.value.trim();
        const province = pSel.selectedIndex > 0 ? pSel.options[pSel.selectedIndex].text : '';
        const ward     = wSel.selectedIndex > 0 ? wSel.options[wSel.selectedIndex].text : '';
        fHid.value = (detail && ward && province) ? `${detail}, ${ward}, ${province}` : '';
    }

    // =========================================================================
    // UTILITY
    // =========================================================================
    function esc(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // =========================================================================
    // EXPOSE & START
    // =========================================================================
    window.applyBNFilter           = applyBNFilter;
    window.applyPKFilter           = applyPKFilter;
    window.openModalAddPatient     = openModalAddPatient;
    window.closeModalPatient       = closeModalPatient;
    window.handleSavePatient       = handleSavePatient;
    window.openModalPhieu          = openModalPhieu;
    window.closeModalPhieu         = closeModalPhieu;
    window.handleCreatePhieu       = handleCreatePhieu;
    window.openModalTiepNhanPK     = openModalTiepNhanPK;
    window.closeModalTiepNhanPK    = closeModalTiepNhanPK;
    window.handleConfirmTiepNhanPK = handleConfirmTiepNhanPK;
    window.fetchPhieuKhamDatTruoc  = fetchPhieuKhamDatTruoc;
    window.printPhieu              = printPhieu;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // SPA observer
    const observer = new MutationObserver(() => {
        if (document.getElementById('bn-table-body') && !document.getElementById('bn-table-body').dataset.init) {
            document.getElementById('bn-table-body').dataset.init = '1';
            init();
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });

})();
</script>
</body>
</html>