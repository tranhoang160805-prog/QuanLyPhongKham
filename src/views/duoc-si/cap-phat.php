<?php
// Đường dẫn: cap-phat.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Nạp mã phiếu khám từ URL
$maPhieuKhamURL = isset($_GET['ma_phieu']) ? (int)$_GET['ma_phieu'] : 0;
?>
<link rel="stylesheet" href="public/assets/css/DuocSi/cap-phat.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<body>

<main class="cp-container">
    
    <div class="cp-grid">
        
        <div>
            <div class="cp-box-patient-info">
                <div class="cp-info-header">
                    <span class="material-symbols-outlined cp-icon-header">account_box</span>
                    <h3 class="cp-box-title">Hồ sơ hành chính ca bệnh</h3>
                </div>

                <div id="patient-profile-details">
                    <p class="cp-profile-loading">Đang nạp hồ sơ bệnh nhân...</p>
                </div>
            </div>
        </div>

        <div>
            <div class="cp-box-prescription">
                <div class="cp-presc-header">
                    <h3 class="cp-box-title">
                        <span class="material-symbols-outlined cp-icon-title">description</span>
                        Toa thuốc chỉ định cấp phát
                    </h3>
                    <div id="lbl-ngay-ketoa" class="cp-lbl-date">---</div>
                </div>

                <div class="cp-table-responsive">
                    <table class="cp-table">
                        <thead>
                            <tr>
                                <th class="cp-th-name">Tên thuốc / Hàm lượng</th>
                                <th class="cp-th-qty">Số lượng</th>
                                <th class="cp-th-price">Đơn giá</th>
                                <th class="cp-th-usage">Cách dùng / Tần suất</th>
                                <th class="cp-th-total">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="prescription-detail-body"></tbody>
                    </table>

                    <div id="presc-empty-state" class="cp-empty-state">
                        <span class="material-symbols-outlined cp-icon-empty">medication_liquid</span>
                        <p class="cp-empty-p">Chưa có dữ liệu đơn thuốc cho ca bệnh này.</p>
                    </div>
                </div>

                <div class="cp-box-instruction">
                    <label class="cp-label-instruction">Lời dặn của Bác sĩ điều trị</label>
                    <div id="txt-loi-dan-view" class="cp-txt-instruction-view">
                        Chưa có lời dặn.
                    </div>
                </div>
            </div>

            <div class="cp-box-footer">
                <div class="cp-footer-left">
                    <div class="cp-footer-meta">
                        <span>Mã phiếu khám đang xử lý:</span>
                        <span id="lbl-ma-phieu-hien-thi" class="cp-lbl-ma-phieu">#---</span>
                    </div>
                    <div class="cp-footer-meta">
                        <span>Tổng tiền thu hộ đơn thuốc:</span>
                        <span id="lbl-total-presc-price" class="cp-total-price">0đ</span>
                    </div>
                </div>
                
                <div class="cp-footer-actions">
                    <button type="button" onclick="quayLaiPhanHeKham();" class="cp-btn cp-btn-outline">
                        Quay lại
                    </button>
                    <button id="btn-submit-cap-phat" disabled onclick="submitXacNhanBanThuoc()" class="cp-btn cp-btn-primary">
                        <span class="material-symbols-outlined cp-icon-btn">task_alt</span> Xác nhận cấp thuốc
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
let currentMaPhieuKham = <?= $maPhieuKhamURL ?>;
let activePrescriptionData = null;

function initPage() {
    if (currentMaPhieuKham <= 0) {
        currentMaPhieuKham = parseInt(sessionStorage.getItem('auto_select_ma_phieu')) || 0;
    }
    
    const lblFooter = document.getElementById('lbl-ma-phieu-hien-thi');
    
    if (currentMaPhieuKham > 0) {
        lblFooter.className = "cp-lbl-ma-phieu";
        lblFooter.innerText = "#" + currentMaPhieuKham;
        if (!new URLSearchParams(window.location.search).get('ma_phieu')) {
    // Lấy lại toàn bộ tham số hiện tại (ví dụ: ?workspace=1&page=cap-phat)
    const currentParams = new URLSearchParams(window.location.search);
    // Gán thêm tham số ma_phieu vào
    currentParams.set('ma_phieu', currentMaPhieuKham);
    // Thay đổi URL giữ nguyên index.php giúp bấm F5 không bị lỗi 404
    window.history.replaceState({}, '', `index.php?${currentParams.toString()}`);
}
        
        loadDuLieuCapPhatThuoc(currentMaPhieuKham);
    } else {
        lblFooter.innerText = "Chưa chọn ca bệnh!";
        lblFooter.className = "cp-lbl-ma-phieu-error";
        document.getElementById('patient-profile-details').innerHTML = `
            <div class="cp-profile-error-wrapper">
                <span class="material-symbols-outlined cp-icon-warning">warning</span>
                <p class="cp-error-text">Không tìm thấy mã phiếu khám tương thích!</p>
            </div>
        `;
    }
}

function loadDuLieuCapPhatThuoc(maPhieu) {
    const tbody = document.getElementById('prescription-detail-body');
    const emptyState = document.getElementById('presc-empty-state');
    const btnSubmit = document.getElementById('btn-submit-cap-phat');
    const profileBox = document.getElementById('patient-profile-details');

    fetch(`src/api/getChiTietDonThuoc.php?ma_phieu_kham=${maPhieu}`)
        .then(res => res.json())
        .then(resData => {
            if (resData.success && resData.info) {
                activePrescriptionData = resData;
                emptyState.classList.add('hidden');
                if (btnSubmit) btnSubmit.disabled = false;

                const pInfo = resData.info;
                const isAllergy = pInfo.DiUng && pInfo.DiUng.toLowerCase() !== 'không';
                const allergyClass = isAllergy ? 'cp-info-value-allergy-active' : 'cp-info-value-allergy';

                profileBox.innerHTML = `
                    <div class="cp-info-group">
                        <div class="cp-info-label">Họ và tên bệnh nhân</div>
                        <div class="cp-info-value-name">${escapeHtml(pInfo.HoTen)}</div>
                    </div>
                    <div class="cp-info-group">
                        <div class="cp-info-label">Mã số bệnh nhân (ID)</div>
                        <div class="cp-info-value-code">${escapeHtml(pInfo.MaBN || '---')}</div>
                    </div>
                    <div class="cp-info-group">
                        <div class="cp-info-label">Mã phiếu khám</div>
                        <div class="cp-info-value">#${maPhieu}</div>
                    </div>
                    <div class="cp-info-group">
                        <div class="cp-info-label">Chần đoán từ Bác sĩ</div>
                        <div class="cp-info-value-diagnosis">
                            ${escapeHtml(pInfo.ChanDoan || pInfo.ChanDoanSoBo || 'Đã kê đơn thuốc chỉ định')}
                        </div>
                    </div>
                    <div class="cp-info-group">
                        <div class="cp-info-label">Tiền sử dị ứng</div>
                        <div class="${allergyClass}">
                            ${escapeHtml(pInfo.DiUng || 'Không')}
                        </div>
                    </div>
                `;

                document.getElementById('lbl-ngay-ketoa').innerText = `Ngày kê đơn: ${pInfo.NgayKeToa}`;
                document.getElementById('txt-loi-dan-view').innerText = pInfo.LoiDan || 'Không có lời dặn.';

                let html = '';
                let grandTotal = 0;

                if (resData.thuoc_list && resData.thuoc_list.length > 0) {
                    resData.thuoc_list.forEach(item => {
                        let totalLine = item.DonGia * item.SoLuong;
                        grandTotal += totalLine;

                        html += `
                            <tr>
                                <td>
                                    <p class="cp-med-name-p">${escapeHtml(item.TenThuoc)} ${escapeHtml(item.HamLuong || '')}</p>
                                    <p class="cp-med-form-p">${escapeHtml(item.DangBaoChe || 'Viên')}</p>
                                </td>
                                <td class="cp-td-center">
                                    <span class="cp-qty-text">${item.SoLuong}</span> <span class="cp-unit-text">${escapeHtml(item.TenDonVi || 'Viên')}</span>
                                </td>
                                <td class="cp-td-right">
                                    <span class="cp-price-text">${item.DonGia.toLocaleString('vi-VN')}đ</span>
                                </td>
                                <td>
                                    <span class="cp-usage-text">${escapeHtml(item.CachDung)}</span>
                                </td>
                                <td class="cp-td-right">
                                    <span class="cp-total-line-text">${totalLine.toLocaleString('vi-VN')}đ</span>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = '';
                    emptyState.classList.remove('hidden');
                }
                
                document.getElementById('lbl-total-presc-price').innerText = grandTotal.toLocaleString('vi-VN') + "đ";

            } else {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                document.getElementById('lbl-total-presc-price').innerText = "0đ";
                if (btnSubmit) btnSubmit.disabled = true;
                
                profileBox.innerHTML = `
                    <div class="cp-profile-error-api">
                        <span class="material-symbols-outlined cp-icon-error-sm">error_text_sm</span>
                        <p class="cp-error-api-desc">Không tìm thấy thông tin đơn thuốc của phiếu #${maPhieu}</p>
                    </div>
                `;
            }
        })
        .catch(() => {
            showAlert('Lỗi kết nối API chi tiết đơn thuốc getChiTietDonThuoc.php');
        });
}

function submitXacNhanBanThuoc() {
    if (currentMaPhieuKham <= 0 || !activePrescriptionData || !activePrescriptionData.thuoc_list || activePrescriptionData.thuoc_list.length === 0) {
        if (typeof showAlert === 'function') {
            showAlert('Không tìm thấy dữ liệu toa thuốc hợp lệ để thực hiện cấp phát.', 'warning');
        } else {
            showAlert('Không tìm thấy dữ liệu toa thuốc hợp lệ để thực hiện cấp phát.');
        }
        return;
    }

    if (!confirm('Xác nhận hoàn tất quy trình xuất bán & cấp phát thuốc cho ca bệnh này?')) {
        return;
    }

    const btnSubmit = document.getElementById('btn-submit-cap-phat');
    if (btnSubmit) btnSubmit.disabled = true;

    const thuocPayload = activePrescriptionData.thuoc_list.map(item => {
        return {
            MaThuoc: item.MaThuoc,
            SoLuong: item.SoLuong,
            DonGia: item.DonGia
        };
    });

    const payload = {
        ma_phieu_kham: currentMaPhieuKham,
        thuoc_list: thuocPayload
    };

    fetch('src/api/saveCapThuoc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if (resData.success) {
            if (typeof showAlert === 'function') {
                showAlert(resData.message, 'success');
            } else {
                showAlert(resData.message);
            }
            
            setTimeout(function() {
                window.location.href = 'index.php?workspace=1&page=ds-benh-nhan';
            }, 1500);
        } else {
            if (typeof showAlert === 'function') {
                showAlert('Cấp phát thất bại: ' + resData.message, 'error');
            } else {
                showAlert('Cấp phát thất bại: ' + resData.message);
            }
            if (btnSubmit) btnSubmit.disabled = false;
        }
    })
    .catch(() => {
        if (typeof showAlert === 'function') {
            showAlert('Lỗi: Mất kết nối đến hệ thống máy chủ cơ sở dữ liệu khi lưu cấp phát.', 'warning');
        } else {
            showAlert('Lỗi: Mất kết nối đến hệ thống máy chủ cơ sở dữ liệu khi lưu cấp phát.');
        }
        if (btnSubmit) btnSubmit.disabled = false;
    });
}

function quayLaiPhanHeKham() {
    window.location.href = 'index.php?workspace=1&page=ds-benh-nhan';
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