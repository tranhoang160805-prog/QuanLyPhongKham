<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$maPhieuKhamURL = isset($_GET['ma_phieu']) ? (int)$_GET['ma_phieu'] : 0;
$patientData = null;

if ($maPhieuKhamURL > 0) {
    try {
        $sqlPatient = "SELECT pk.MaPhieuKham, pk.ChanDoan, pk.ChanDoanSoBo, bn.MaBenhNhanCode, bn.TenBenhNhan, bn.GioiTinh, 
                              (YEAR(NOW()) - YEAR(bn.NgaySinh)) AS Tuoi, bn.DiUng
                       FROM PHIEUKHAM pk
                       JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan
                       WHERE pk.MaPhieuKham = ?";
        $stmtPatient = $pdo->prepare($sqlPatient);
        $stmtPatient->execute([$maPhieuKhamURL]);
        $patientData = $stmtPatient->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $patientData = null;
    }
}
?>
<link rel="stylesheet" href="public/assets/css/BacSi/cap-thuoc.css">
<body>

<main class="ct-container">
    
    <?php if ($patientData): ?>
    <div class="ct-box-patient">
        <div class="ct-patient-info">
            <div class="ct-patient-avatar">
                <span class="material-symbols-outlined text-primary">account_circle</span>
            </div>
            <div>
                <h1 class="ct-patient-name"><?= htmlspecialchars($patientData['TenBenhNhan']) ?></h1>
                <p class="ct-patient-meta">
                    <?= htmlspecialchars($patientData['GioiTinh']) ?>, <?= (int)$patientData['Tuoi'] ?> tuổi • ID: <?= htmlspecialchars($patientData['MaBenhNhanCode'] ?? '') ?> • Mã phiếu: #<span id="lbl-ma-phieu"><?= $maPhieuKhamURL ?></span>
                </p>
            </div>
        </div>
        <div class="ct-patient-vitals">
            <div class="ct-vitals-label">Chẩn đoán:</div>
            <div class="ct-vitals-value"><?= htmlspecialchars($patientData['ChanDoan'] ? $patientData['ChanDoan'] : ($patientData['ChanDoanSoBo'] ? $patientData['ChanDoanSoBo'] : 'Chưa nhập chẩn đoán')) ?></div>
            <div class="ct-vitals-label">Dị ứng:</div>
            <div class="ct-vitals-value <?= !empty($patientData['DiUng']) && strtolower($patientData['DiUng']) !== 'không' ? 'text-error' : '' ?>">
                <?= htmlspecialchars($patientData['DiUng'] ? $patientData['DiUng'] : 'Không') ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div id="error-badge-url" class="ct-error-badge">
        <span class="material-symbols-outlined text-error">warning</span>
        <div>
            <h1>Hệ thống đang kiểm tra phiên làm việc ca bệnh...</h1>
            <p>Mã phiếu khám đang được đồng bộ tự động từ bộ nhớ đệm phân hệ Khám bệnh.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="ct-grid">
        
        <div class="ct-box-medicine">
            <h3 class="ct-box-title">
                <span class="material-symbols-outlined text-primary">medication</span>
                DANH MỤC THUỐC
            </h3>
            
            <div class="ct-search-wrapper">
                <input id="input-search-thuoc" oninput="fetchThuocFromAPI(1)" class="ct-search-input" placeholder="Tìm tên thuốc, hoạt chất..." type="text"/>
                <span class="material-symbols-outlined ct-search-icon">search</span>
            </div>

            <div id="search-results-area" class="ct-med-list">
                <p class="text-center italic ct-loading-text">Đang nạp dữ liệu kho thuốc...</p>
            </div>

            <div id="med-pagination">
                <button id="btn-med-prev" onclick="handleMedPageChange(-1)">◀ Trước</button>
                <span id="lbl-med-page">Trang 1 / 1</span>
                <button id="btn-med-next" onclick="handleMedPageChange(1)">Tiếp ▶</button>
            </div>
        </div>

        <div>
            <div class="ct-box-prescription">
                <div class="ct-presc-header">
                    <h3 class="ct-box-title">
                        <span class="material-symbols-outlined text-primary">description</span>
                        Toa thuốc chỉ định cấp phát
                    </h3>
                    <button onclick="clearPrescription()" class="ct-btn-text-danger">
                        <span class="material-symbols-outlined">delete</span> Làm mới đơn
                    </button>
                </div>

                <div class="ct-table-responsive">
                    <table class="ct-table">
                        <thead>
                            <tr>
                                <th class="ct-th-name">Tên thuốc / Hàm lượng</th>
                                <th class="text-center ct-th-qty">Số lượng</th>
                                <th class="text-right ct-th-price">Đơn giá</th>
                                <th class="ct-th-usage">Cách dùng / Tần suất</th>
                                <th class="text-right ct-th-total">Thành tiền</th>
                                <th class="ct-th-action"></th>
                            </tr>
                        </thead>
                        <tbody id="prescription-body"></tbody>
                    </table>

                    <div id="presc-empty-state" class="ct-empty-state">
                        <span class="material-symbols-outlined">medication_liquid</span>
                        <p>Chưa có thuốc nào trong đơn. Bấm (+) bên kho để thêm qua.</p>
                    </div>
                </div>

                <div class="ct-box-instruction">
                    <label>Lời dặn của Bác sĩ điều trị</label>
                    <textarea id="txt-loi-dan-presc" class="ct-textarea" placeholder="Nhập lời dặn bác sĩ (Ví dụ: Uống thuốc sau ăn, tái khám sau khi hết đơn thuốc...)" rows="3"></textarea>
                </div>
            </div>

            <div class="ct-box-footer">
                <div class="ct-footer-left">
                    <div class="ct-footer-meta">
                        <span>Mã phiếu khám đang xử lý:</span>
                        <span id="lbl-ma-phieu-hien-thi" class="text-primary UI-bold">#---</span>
                    </div>
                    <div class="ct-footer-meta">
                        <span>Tổng giá tiền đơn thuốc:</span>
                        <span id="lbl-total-presc-price" class="ct-total-price">0đ</span>
                    </div>
                </div>
                
                <div class="ct-footer-actions">
                    <button type="button" onclick="quayLaiPhanHeKham();" class="ct-btn ct-btn-outline">
                        Quay lại
                    </button>
                    <button id="btn-submit-prescription" onclick="submitPrescription()" disabled class="ct-btn ct-btn-primary">
                        <span class="material-symbols-outlined">task_alt</span> Xác nhận cấp thuốc
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
let currentMaPhieuKham = 0;
let currentMedPage = 1;
let currentMedFilter = '';
let searchKeyWord = '';
const medLimit = 6;

let prescriptionCart = [];

function initPage() {
    const urlParams = new URLSearchParams(window.location.search);
    let maPhieu = urlParams.get('ma_phieu') || urlParams.get('target_ma_phieu');
    if (!maPhieu) {
        maPhieu = sessionStorage.getItem('auto_select_ma_phieu');
    }
    
    currentMaPhieuKham = parseInt(maPhieu) || 0;
    
    const lblUrl = document.getElementById('lbl-ma-phieu');
    const lblFooter = document.getElementById('lbl-ma-phieu-hien-thi');
    const errorBadge = document.getElementById('error-badge-url');
    
    if (currentMaPhieuKham > 0) {
        if (lblUrl) lblUrl.innerText = currentMaPhieuKham;
        if (lblFooter) lblFooter.innerText = "#" + currentMaPhieuKham;
        if (errorBadge) errorBadge.classList.add('hidden');
        
        if (!urlParams.get('ma_phieu')) {
            window.history.replaceState({}, '', `cap-thuoc.php?ma_phieu=${currentMaPhieuKham}`);
        }
    } else {
        if (lblFooter) {
            lblFooter.innerText = "Chưa chọn ca bệnh!";
            lblFooter.classList.add('text-error');
        }
        showAlert("Hệ thống chưa tìm thấy thông tin ca bệnh chuyển qua từ phân hệ khám bệnh.", 'warning');
    }

    fetchThuocFromAPI(1);
    renderPrescriptionCart();
}

function fetchThuocFromAPI(page = 1) {
    currentMedPage = page;
    const searchInput = document.getElementById('input-search-thuoc');
    searchKeyWord = searchInput ? searchInput.value.trim() : '';
    const resultsArea = document.getElementById('search-results-area');
    
    let url = `src/api/getDSThuoc.php?p=${currentMedPage}&limit=${medLimit}&filter_status=${currentMedFilter}&search=${encodeURIComponent(searchKeyWord)}`;
    
    fetch(url)
        .then(res => res.json())
        .then(resData => {
            if (resData.success && resData.data) {
                renderMedicationList(resData.data);
                renderMedPagination(resData.pagination);
            } else {
                resultsArea.innerHTML = `<p class="text-center text-error ct-med-msg">${resData.message || 'Lỗi khi tải danh sách thuốc.'}</p>`;
            }
        })
        .catch(() => {
            resultsArea.innerHTML = `<p class="text-center text-error ct-med-msg">Lỗi kết nối máy chủ không thể đọc API getDSThuoc.php</p>`;
        });
}

function changeMedicationFilter(filterType) {
    currentMedFilter = filterType;
    const btnAll = document.getElementById('btn-filter-all');
    const btnLow = document.getElementById('btn-filter-low');
    
    if (filterType === 'low_stock') {
        btnLow.classList.add('active');
        btnAll.classList.remove('active');
    } else {
        btnAll.classList.add('active');
        btnLow.classList.remove('active');
    }
    fetchThuocFromAPI(1);
}

function handleMedPageChange(direction) {
    let targetPage = currentMedPage + direction;
    if (targetPage < 1) return;
    fetchThuocFromAPI(targetPage);
}

function renderMedicationList(thuocArray) {
    const resultsArea = document.getElementById('search-results-area');
    if (thuocArray.length === 0) {
        resultsArea.innerHTML = `<p class="text-center italic ct-med-empty-msg">Không tìm thấy loại thuốc phù hợp</p>`;
        return;
    }

    let html = '';
    thuocArray.forEach(thuoc => {
        const isOutOfStock = thuoc.SoLuongTon <= 0;
        html += `
            <div onclick="${isOutOfStock ? '' : `addThuocToPrescription(${JSON.stringify(thuoc).replace(/"/g, '&quot;')})`}" 
                 class="ct-med-item ${isOutOfStock ? 'disabled' : ''}">
                <div class="ct-med-item-layout">
                    <div>
                        <p class="ct-med-item-name">${escapeHtml(thuoc.TenThuoc)} ${escapeHtml(thuoc.HamLuong || '')}</p>
                        <p class="ct-med-item-chemical">Hoạt chất: ${escapeHtml(thuoc.TenHoatChat || 'Chưa cập nhật')}</p>
                        <p class="ct-med-item-stock">Kho: <strong>${thuoc.SoLuongTon}</strong> ${escapeHtml(thuoc.TenDonVi)} • Giá: <span class="ct-med-item-price">${thuoc.GiaBan.toLocaleString('vi-VN')}đ</span></p>
                    </div>
                    ${isOutOfStock ? 
                        `<span class="ct-badge-out-of-stock">HẾT KHO</span>` : 
                        `<span class="material-symbols-outlined text-primary">add_circle</span>`
                    }
                </div>
            </div>
        `;
    });
    resultsArea.innerHTML = html;
}

function renderMedPagination(paginObj) {
    const paginBox = document.getElementById('med-pagination');
    const lblPage = document.getElementById('lbl-med-page');
    const btnPrev = document.getElementById('btn-med-prev');
    const btnNext = document.getElementById('btn-med-next');

    if (!paginBox || !paginObj) return;
    if (paginObj.total_pages <= 1) {
        paginBox.classList.remove('active-pagination');
        return;
    }

    paginBox.classList.add('active-pagination');
    lblPage.innerText = `Trang ${paginObj.current_page} / ${paginObj.total_pages}`;
    btnPrev.disabled = (paginObj.current_page <= 1);
    btnNext.disabled = (paginObj.current_page >= paginObj.total_pages);
}

function addThuocToPrescription(thuoc) {
    if (currentMaPhieuKham <= 0) {
        showAlert('Vui lòng chọn hoặc định vị một ca khám bệnh cụ thể trước khi lập đơn thuốc!', 'warning');
        return;
    }

    const existingIndex = prescriptionCart.findIndex(item => item.MaThuoc === thuoc.MaThuoc);
    if (existingIndex !== -1) {
        if (prescriptionCart[existingIndex].SoLuong + 1 > thuoc.SoLuongTon) {
            showAlert(`Kho chỉ còn tối đa ${thuoc.SoLuongTon} ${thuoc.TenDonVi}. Không thể thêm thêm!`, 'warning');
            return;
        }
        prescriptionCart[existingIndex].SoLuong += 1;
    } else {
        prescriptionCart.push({
            MaThuoc: thuoc.MaThuoc,
            TenThuoc: thuoc.TenThuoc,
            HamLuong: thuoc.HamLuong,
            DangBaoChe: thuoc.DangBaoChe,
            GiaBan: thuoc.GiaBan,
            TenDonVi: thuoc.TenDonVi,
            SoLuongTon: thuoc.SoLuongTon,
            SoLuong: 1,
            CachDung: 'Ngày uống 2 lần, mỗi lần 1 viên sau khi ăn.'
        });
    }
    renderPrescriptionCart();
}

function removeRowFromPrescription(index) {
    prescriptionCart.splice(index, 1);
    renderPrescriptionCart();
}

function clearPrescription() {
    if (prescriptionCart.length === 0) return;
    prescriptionCart = [];
    renderPrescriptionCart();
}

function updateQuantity(index, val) {
    let qty = parseInt(val) || 1;
    const maxStock = prescriptionCart[index].SoLuongTon;

    if (qty < 1) qty = 1;
    if (qty > maxStock) {
        showAlert(`Số lượng vượt giới hạn tồn kho hiện tại (${maxStock} ${prescriptionCart[index].TenDonVi})`);
        qty = maxStock;
    }

    prescriptionCart[index].SoLuong = qty;
    calculateTotalPriceOnly();
}

function updateCachDung(index, text) {
    prescriptionCart[index].CachDung = text;
}

function renderPrescriptionCart() {
    const tbody = document.getElementById('prescription-body');
    const emptyState = document.getElementById('presc-empty-state');
    const btnSubmit = document.getElementById('btn-submit-prescription');

    if (prescriptionCart.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        if (btnSubmit) btnSubmit.disabled = true;
        document.getElementById('lbl-total-presc-price').innerText = "0đ";
        return;
    }

    emptyState.classList.add('hidden');
    if (btnSubmit) btnSubmit.disabled = false;

    let html = '';
    prescriptionCart.forEach((item, index) => {
        html += `
            <tr>
                <td>
                    <p class="ct-item-table-name">${escapeHtml(item.TenThuoc)} ${escapeHtml(item.HamLuong || '')}</p>
                    <p class="ct-item-table-type">${escapeHtml(item.DangBaoChe || 'Viên')}</p>
                </td>
                <td class="text-center">
                    <div class="ct-qty-input-wrapper">
                        <input onchange="updateQuantity(${index}, this.value)" class="ct-input-qty" type="number" min="1" max="${item.SoLuongTon}" value="${item.SoLuong}"/>
                        <span class="ct-qty-unit-lbl">${escapeHtml(item.TenDonVi)}</span>
                    </div>
                </td>
                <td class="text-right ct-item-table-price">
                    ${item.GiaBan.toLocaleString('vi-VN')}đ
                </td>
                <td>
                    <input oninput="updateCachDung(${index}, this.value)" class="ct-input-usage" type="text" value="${escapeHtml(item.CachDung)}"/>
                </td>
                <td class="text-right text-primary ct-item-table-total" id="total-price-cell-${index}">
                    ${(item.GiaBan * item.SoLuong).toLocaleString('vi-VN')}đ
                </td>
                <td class="text-center">
                    <span onclick="removeRowFromPrescription(${index})" class="material-symbols-outlined ct-btn-remove-item">close</span>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    calculateTotalPriceOnly();
}

function calculateTotalPriceOnly() {
    let grandTotal = 0;
    prescriptionCart.forEach((item, index) => {
        const lineTotal = item.GiaBan * item.SoLuong;
        grandTotal += lineTotal;
        const cell = document.getElementById(`total-price-cell-${index}`);
        if (cell) cell.innerText = lineTotal.toLocaleString('vi-VN') + "đ";
    });
    document.getElementById('lbl-total-presc-price').innerText = grandTotal.toLocaleString('vi-VN') + "đ";
}

function submitPrescription() {
    if (currentMaPhieuKham <= 0 || prescriptionCart.length === 0) return;

    if (!confirm('Xác nhận lưu đơn thuốc và hoàn tất hồ sơ cấp thuốc cho bệnh nhân này?')) {
        return;
    }

    const btnSubmit = document.getElementById('btn-submit-prescription');
    if (btnSubmit) btnSubmit.disabled = true;

    const txtLoiDan = document.getElementById('txt-loi-dan-presc');
    const loiDanText = txtLoiDan ? txtLoiDan.value.trim() : '';

    const itemsPayload = prescriptionCart.map(item => {
        return {
            ma_thuoc: item.MaThuoc,
            so_luong: item.SoLuong,
            don_gia: item.GiaBan,
            cach_dung: item.CachDung
        };
    });

    const payload = {
        ma_phieu_kham: currentMaPhieuKham,
        loi_dan: loiDanText,
        items: itemsPayload
    };

    fetch('src/api/saveDonThuoc.php?action=save_prescription', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json; charset=utf-8' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            showAlert(resData.message, 'success');
            sessionStorage.removeItem('auto_select_ma_phieu');
            prescriptionCart = [];
            renderPrescriptionCart();
            setTimeout(function() {
                quayLaiDanhSach();
            }, 1500);
        } else {
            showAlert('Thao tác thất bại: ' + resData.message, 'error');
            if (btnSubmit) btnSubmit.disabled = false;
        }
    })
    .catch(() => {
        showAlert('Lỗi: Mất kết nối đến hệ thống máy chủ cơ sở dữ liệu.', 'warning');
        if (btnSubmit) btnSubmit.disabled = false;
    });
}

function quayLaiPhanHeKham() {
        window.location.href = 'index.php?workspace=1&page=kham-benh';
}

function quayLaiDanhSach() {
        const backMenuLink = document.querySelector('a[data-page="ds-benh-nhan"]') || document.querySelector('[onclick*="ds-benh-nhan"]');
        if (backMenuLink) {
            backMenuLink.click();
        } else {
            window.location.href = 'index.php?workspace=1&page=ds-benh-nhan';
        }
    }


function escapeHtml(string) {
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '/': '&#x2F;', '=': '&#x3D;' }[s];
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage);
} else {
    initPage();
}
</script>
</body>
</html>