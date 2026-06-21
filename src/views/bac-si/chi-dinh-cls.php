<?php
// Đường dẫn: chi-dinh-cls.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$maPhieuKhamURL = isset($_GET['ma_phieu']) ? (int)$_GET['ma_phieu'] : 0;
$patientData = null;

if ($maPhieuKhamURL > 0) {
    try {
        // Lấy thông tin hành chính bệnh nhân giống cap-thuoc.php
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

try {
    $stmtCLS = $pdo->query("SELECT MaLoaiCLS, TenLoaiCLS, DonGia, MoTa FROM LOAICLSn WHERE TrangThai = 1 ORDER BY TenLoaiCLS ASC");
    $listLoaiCLS = $stmtCLS->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $listLoaiCLS = [];
}
?>
<link rel="stylesheet" href="public/assets/css/BacSi/cap-thuoc.css">
<main class="ct-container">
    
    <?php if ($patientData): ?>
    <div class="ct-box-patient">
        <div class="ct-patient-info">
            <div class="ct-patient-avatar">
                <span class="material-symbols-outlined text-primary" style="font-size: 36px;">account_circle</span>
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
        <span class="material-symbols-outlined text-error" style="font-size: 32px;">warning</span>
        <div>
            <h1 style="font-size:16px; font-weight:bold; margin:0;">Hệ thống đang kiểm tra phiên làm việc ca bệnh...</h1>
            <p style="font-size:13px; margin:4px 0 0 0; opacity:0.9;">Mã phiếu khám đang được đồng bộ tự động từ bộ nhớ đệm phân hệ Khám bệnh.</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="ct-grid">
        
        <div class="ct-box-medicine">
            <h3 class="ct-box-title">
                <span class="material-symbols-outlined text-primary"></span>
                Danh mục dịch vụ kỹ thuật
            </h3>
            
            <div class="ct-search-wrapper">
                <input id="input-search-cls" oninput="filterCLSLocal()" class="ct-search-input" placeholder="Tìm tên dịch vụ, kỹ thuật, siêu âm, xét nghiệm..." type="text"/>
                <span class="material-symbols-outlined ct-search-icon">search</span>
            </div>

            <div class="ct-filter-tags">
                <button id="btn-filter-all" onclick="changeCLSFilterLocal('')" class="ct-tag active">TẤT CẢ DỊCH VỤ</button>
                <button id="btn-filter-active" onclick="changeCLSFilterLocal('pho-bien')" class="ct-tag">DỊCH VỤ PHỔ BIẾN</button>
            </div>

            <div id="search-results-area" class="ct-med-list">
            </div>

            <div id="cls-pagination" style="display:none; justify-content:space-between; margin-top:16px; padding-top:12px; border-top:1px solid var(--color-border);">
                <button id="btn-cls-prev" onclick="handleCLSPageChangeLocal(-1)" style="background:none; border:none; color:var(--color-primary); font-size:12px; font-weight:bold; cursor:pointer;">◀ Trước</button>
                <span id="lbl-cls-page" style="font-size:12px; color:var(--color-text-muted);">Trang 1 / 1</span>
                <button id="btn-cls-next" onclick="handleCLSPageChangeLocal(1)" style="background:none; border:none; color:var(--color-primary); font-size:12px; font-weight:bold; cursor:pointer;">Tiếp ▶</button>
            </div>
        </div>

        <div>
            <div class="ct-box-prescription">
                <div class="ct-presc-header">
                    <h3 class="ct-box-title" style="margin:0;">
                        <span class="material-symbols-outlined text-primary">description</span>
                        Phiếu chỉ định kỹ thuật yêu cầu
                    </h3>
                    <button onclick="clearCLSQueue()" class="ct-btn-text-danger">
                        <span class="material-symbols-outlined" style="font-size:18px;">delete</span> Làm mới phiếu
                    </button>
                </div>

                <div class="ct-table-responsive">
                    <table class="ct-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Tên dịch vụ</th>
                                <th class="text-center" style="width: 15%;">Số lượng</th>
                                <th class="text-right" style="width: 20%;">Đơn giá</th>
                                <th style="width: 40%;">Yêu cầu</th>
                                <th style="width: 30px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cls-queue-body">
                        </tbody>
                    </table>

                    <div id="cls-empty-state" class="ct-empty-state">
                        <span class="material-symbols-outlined" style="font-size:48px; color:#cbd5e1; margin-bottom:8px;">clinical_notes</span>
                        <p style="margin:0; font-size:14px; color:#94a3b8;">Chưa có dịch vụ nào được chỉ định. Bấm (+) bên danh mục để thêm qua.</p>
                    </div>
                </div>

                <div class="ct-box-instruction">
                    <label style="font-size:13px; font-weight:600; color:var(--color-text-muted);">Ghi chú tổng quát của Bác sĩ chỉ định</label>
                    <textarea id="txt-ghi-chu-cls" class="ct-textarea" placeholder="Nhập lời dặn chung hoặc lý do chỉ định tổng quát (Ví dụ: Nhịn ăn sáng trước khi xét nghiệm máu, uống nhiều nước trước khi siêu âm...)" rows="3"></textarea>
                </div>
            </div>

            <div class="ct-box-footer">
                <div class="ct-footer-left">
                    <div class="ct-footer-meta">
                        <span>Mã phiếu khám đang xử lý:</span>
                        <span id="lbl-ma-phieu-hien-thi" class="text-primary" style="font-weight:bold;">#---</span>
                    </div>
                    <div class="ct-footer-meta">
                        <span>Tổng chi phí tạm tính:</span>
                        <span id="lbl-total-cls-price" class="ct-total-price">0đ</span>
                    </div>
                </div>
                
                <div class="ct-footer-actions">
                    <button type="button" onclick="quayLaiPhanHeKham();" class="ct-btn ct-btn-outline">
                        Quay lại
                    </button>
                    <button id="btn-submit-cls" onclick="submitAllChiDinh()" disabled class="ct-btn ct-btn-primary">
                        <span class="material-symbols-outlined" style="font-size:20px;">send</span> Xác nhận & Chuyển phòng CLS
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
// Nhúng mảng dữ liệu đã câu từ Database bằng PHP thẳng vào biến JavaScript toàn cục
const serverCLSList = <?= json_encode($listLoaiCLS, JSON_UNESCAPED_UNICODE) ?>;

let currentMaPhieuKham = 0;
let currentCLSPage = 1;
let currentCLSFilter = '';
let searchKeyWord = '';
const clsLimit = 6; // Số lượng dịch vụ trên mỗi trang ở cột trái

// Giỏ hàng lưu trữ các dịch vụ được chọn sang bên phải
let selectedClsQueue = [];

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
            window.history.replaceState({}, '', `chi-dinh-cls.php?ma_phieu=${currentMaPhieuKham}`);
        }
    } else {
        if (lblFooter) {
            lblFooter.innerText = "Chưa chọn ca bệnh!";
            lblFooter.style.color = "var(--color-error)";
        }
        if (typeof window.showAlert === 'function') {
            window.showAlert("Hệ thống chưa tìm thấy thông tin ca bệnh chuyển qua.", 'warning');
        }
    }

    // Tiến hành render danh sách dịch vụ luôn từ dữ liệu đã có sẵn
    filterCLSLocal();
    renderCLSQueueCart();
}

// Hàm xử lý tìm kiếm và phân trang nội bộ trên mảng JS (Không cần gọi API)
function filterCLSLocal() {
    const searchInput = document.getElementById('input-search-cls');
    searchKeyWord = searchInput ? searchInput.value.trim().toLowerCase() : '';

    // 1. Thực hiện Lọc theo Từ khóa tìm kiếm
    let filtered = serverCLSList.filter(cls => {
        const matchesSearch = cls.TenLoaiCLS.toLowerCase().includes(searchKeyWord) || 
                              (cls.MoTa && cls.MoTa.toLowerCase().includes(searchKeyWord));
        return matchesSearch;
    });

    // 2. Thực hiện Lọc theo tag phổ biến (nếu có logic định nghĩa dịch vụ giá cao/phổ biến)
    if (currentCLSFilter === 'pho-bien') {
        filtered = filtered.filter(cls => parseFloat(cls.DonGia) >= 100000); // Ví dụ dịch vụ từ 100k trở lên là phổ biến
    }

    // 3. Xử lý logic phân trang nội bộ
    const totalItems = filtered.length;
    const totalPages = Math.ceil(totalItems / clsLimit) || 1;
    
    if (currentCLSPage > totalPages) currentCLSPage = totalPages;
    if (currentCLSPage < 1) currentCLSPage = 1;

    const startIndex = (currentCLSPage - 1) * clsLimit;
    const paginatedItems = filtered.slice(startIndex, startIndex + clsLimit);

    // Render dữ liệu ra giao diện cột trái
    renderCLSList(paginatedItems);

    // Cập nhật thanh phân trang giống hệt bên cap-thuoc
    renderCLSPaginationLocal(currentCLSPage, totalPages);
}

function changeCLSFilterLocal(filterType) {
    currentCLSFilter = filterType;
    const btnAll = document.getElementById('btn-filter-all');
    const btnActive = document.getElementById('btn-filter-active');
    
    if (filterType === 'pho-bien') {
        btnActive.classList.add('active');
        btnAll.classList.remove('active');
    } else {
        btnAll.classList.add('active');
        btnActive.classList.remove('active');
    }
    currentCLSPage = 1;
    filterCLSLocal();
}

function handleCLSPageChangeLocal(direction) {
    currentCLSPage += direction;
    filterCLSLocal();
}

function renderCLSList(clsArray) {
    const resultsArea = document.getElementById('search-results-area');
    if (clsArray.length === 0) {
        resultsArea.innerHTML = `<p class="text-center italic" style="font-size:13px; color:var(--color-text-muted); padding:24px 0;">Không tìm thấy dịch vụ cận lâm sàng phù hợp</p>`;
        return;
    }

    let html = '';
    clsArray.forEach(cls => {
        html += `
            <div onclick="addCLSToQueue(${JSON.stringify(cls).replace(/"/g, '&quot;')})" class="ct-med-item">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="margin:0; font-weight:bold; color:var(--color-primary);">${escapeHtml(cls.TenLoaiCLS)}</p>
                        <p style="margin:2px 0; font-size:12px; color:var(--color-text-muted);">Mô tả: ${escapeHtml(cls.MoTa || 'Theo quy chuẩn phòng khám')}</p>
                        <p style="margin:0; font-size:12px; color:#64748b;">Giá dịch vụ: <span style="color:var(--color-text); font-weight:600;">${parseFloat(cls.DonGia).toLocaleString('vi-VN')}đ</span></p>
                    </div>
                    <span class="material-symbols-outlined text-primary">add_circle</span>
                </div>
            </div>
        `;
    });
    resultsArea.innerHTML = html;
}

function renderCLSPaginationLocal(currentPage, totalPages) {
    const paginBox = document.getElementById('cls-pagination');
    const lblPage = document.getElementById('lbl-cls-page');
    const btnPrev = document.getElementById('btn-cls-prev');
    const btnNext = document.getElementById('btn-cls-next');

    if (!paginBox) return;
    if (totalPages <= 1) {
        paginBox.style.display = 'none';
        return;
    }

    paginBox.style.display = 'flex';
    lblPage.innerText = `Trang ${currentPage} / ${totalPages}`;
    btnPrev.disabled = (currentPage <= 1);
    btnNext.disabled = (currentPage >= totalPages);
}

function addCLSToQueue(cls) {
    if (currentMaPhieuKham <= 0) {
        if (typeof window.showAlert === 'function') {
            window.showAlert('Vui lòng chọn một ca khám bệnh cụ thể trước khi lập phiếu chỉ định!', 'warning');
        } else {
            showAlert('Vui lòng chọn một ca khám bệnh cụ thể trước khi lập phiếu chỉ định!');
        }
        return;
    }

    const existingIndex = selectedClsQueue.findIndex(item => item.ma_loai_cls === parseInt(cls.MaLoaiCLS));
    if (existingIndex !== -1) {
        if (typeof window.showAlert === 'function') {
            window.showAlert(`Dịch vụ kỹ thuật "${cls.TenLoaiCLS}" đã được thêm trước đó.`, 'warning');
        } else {
            showAlert(`Dịch vụ kỹ thuật "${cls.TenLoaiCLS}" đã được thêm trước đó.`);
        }
        return;
    }

    selectedClsQueue.push({
        ma_loai_cls: parseInt(cls.MaLoaiCLS),
        ten_loai_cls: cls.TenLoaiCLS,
        don_gia: parseFloat(cls.DonGia) || 0,
        mo_ta_chi_dinh: cls.MoTa
    });
    
    renderCLSQueueCart();
}

function removeRowFromCLSQueue(index) {
    selectedClsQueue.splice(index, 1);
    renderCLSQueueCart();
}

function clearCLSQueue() {
    if (selectedClsQueue.length === 0) return;
    selectedClsQueue = [];
    renderCLSQueueCart();
}

function updateMoTaChiDinh(index, text) {
    selectedClsQueue[index].mo_ta_chi_dinh = text;
}

function renderCLSQueueCart() {
    const tbody = document.getElementById('cls-queue-body');
    const emptyState = document.getElementById('cls-empty-state');
    const btnSubmit = document.getElementById('btn-submit-cls');

    if (selectedClsQueue.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        if (btnSubmit) btnSubmit.disabled = true;
        document.getElementById('lbl-total-cls-price').innerText = "0đ";
        return;
    }

    emptyState.classList.add('hidden');
    if (btnSubmit) btnSubmit.disabled = false;

    let html = '';
    selectedClsQueue.forEach((item, index) => {
        html += `
            <tr>
                <td>
                    <p style="margin:0; font-weight:bold; color:var(--color-text);">${escapeHtml(item.ten_loai_cls)}</p>
                </td>
                <td class="text-center" style="color:var(--color-text-muted); font-size:13px;">
                    1 Lượt
                </td>
                <td class="text-right" style="color:var(--color-text-muted); font-weight:500;">
                    ${item.don_gia.toLocaleString('vi-VN')}đ
                </td>
                <td>
                    <input oninput="updateMoTaChiDinh(${index}, this.value)" class="ct-input-usage" type="text" value="${escapeHtml(item.mo_ta_chi_dinh)}"/>
                </td>
                <td class="text-center">
                    <span onclick="removeRowFromCLSQueue(${index})" class="material-symbols-outlined" style="color:var(--color-text-muted); cursor:pointer; font-size:18px;">close</span>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    calculateTotalPriceOnly();
}

function calculateTotalPriceOnly() {
    let grandTotal = 0;
    selectedClsQueue.forEach((item) => {
        grandTotal += item.don_gia;
    });
    document.getElementById('lbl-total-cls-price').innerText = grandTotal.toLocaleString('vi-VN') + "đ";
}

function submitAllChiDinh() {
    if (currentMaPhieuKham <= 0 || selectedClsQueue.length === 0) return;

    if (!confirm('Xác nhận hoàn tất lập phiếu chỉ định kỹ thuật và chuyển phòng thực hiện CLS?')) {
        return;
    }

    const btnSubmit = document.getElementById('btn-submit-cls');
    if (btnSubmit) btnSubmit.disabled = true;

    const txtGhiChu = document.getElementById('txt-ghi-chu-cls');
    const ghiChuText = txtGhiChu ? txtGhiChu.value.trim() : '';

    const payload = {
        ma_phieu_kham: currentMaPhieuKham,
        ghi_chu_tong_quat: ghiChuText,
        danh_sach_cls: selectedClsQueue
    };

    fetch('src/api/chiDinhCLS.php?action=save_chi_dinh', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json; charset=utf-8' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            if (typeof window.showAlert === 'function') {
                window.showAlert(resData.message, 'success');
            } else {
                showAlert(resData.message);
            }
            sessionStorage.removeItem('auto_select_ma_phieu');
            selectedClsQueue = [];
            renderCLSQueueCart();
            setTimeout(function() {
                quayLaiDanhSach();
            }, 1500);
        } else {
            if (typeof window.showAlert === 'function') {
                window.showAlert('Thao tác thất bại: ' + resData.message, 'error');
            } else {
                showAlert('Thao tác thất bại: ' + resData.message);
            }
            if (btnSubmit) btnSubmit.disabled = false;
        }
    })
    .catch(() => {
        if (typeof window.showAlert === 'function') {
            window.showAlert('Lỗi: Mất kết nối đến hệ thống máy chủ cơ sở dữ liệu.', 'warning');
        } else {
            showAlert('Lỗi: Mất kết nối hệ thống.');
        }
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