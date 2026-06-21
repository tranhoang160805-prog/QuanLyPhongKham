

<link rel="stylesheet" href="public/assets/css/LeTan/thanh-toan-hd.css">

<body>
<main class="invoice-wrapper">
    <div id="loading-state" class="state-loading">
        <span class="material-symbols-outlined spin-icon">progress_activity</span>
        <p>Đang tải thông tin hóa đơn...</p>
    </div>

    <div id="invoice-content" class="hidden">
        
        <div class="print-clinic-header">
            <?php if(!empty($site['logo_url'])): ?>
                <img src="<?= $site['logo_url'] ?>" alt="Logo" class="print-clinic-logo">
            <?php endif; ?>
            <div class="print-clinic-info">
                <h2><?= $site['ten_phong_kham'] ?></h2>
                <p><strong>Địa chỉ:</strong> <?= $site['dia_chi'] ?></p>
                <p><strong>Điện thoại:</strong> <?= $site['so_dien_thoai'] ?> | <strong>Email:</strong> <?= $site['email'] ?></p>
            </div>
        </div>

        <div class="print-invoice-title">
            <h1>HÓA ĐƠN THANH TOÁN VÀ CHI PHÍ ĐIỀU TRỊ</h1>
            <p>Ngày lập: <?= date('d/m/Y H:i') ?></p>
        </div>

        <div class="patient-summary-box">
            <div class="info-left">
                <div class="avatar-holder">
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
                <div>
                    <h3 id="patient-name">...</h3>
                    <div class="meta-details">
                        <span>Mã PK: <strong id="pk-code">...</strong></span>
                        <span>Mã BN: <span id="patient-id">...</span></span>
                    </div>
                </div>
            </div>
            <div class="info-right">
                <p class="label">Ngày lập hóa đơn</p>
                <p class="date"><?= date('d/m/Y') ?></p>
            </div>
        </div>

        <div class="step-wizard">
            <div class="step-item active" id="step-header-1">
                <span class="step-number">1</span>
                <span class="step-text">Thông tin hóa đơn</span>
            </div>
            <div class="step-item" id="step-header-2">
                <span class="step-number">2</span>
                <span class="step-text">Phương thức thanh toán</span>
            </div>
            <div class="step-item" id="step-header-3">
                <span class="step-number">3</span>
                <span class="step-text">Xác nhận &amp; Hoàn tất</span>
            </div>
        </div>

        <div id="step-1" class="wizard-step active">
            <div class="invoice-grid-layout">
                <div class="col-left">
                    <div class="billing-data-card">
                        <div class="card-header">
                            <h4><span class="material-symbols-outlined">biotech</span>Dịch vụ &amp; Xét nghiệm</h4>
                            <span id="badge-cls-count" class="badge">0 mục</span>
                        </div>
                        <table class="billing-table">
                            <thead>
                                <tr>
                                    <th>Tên dịch vụ</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="render-table-cls-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="col-right">
                    <div class="billing-data-card">
                        <div class="card-header">
                            <h4><span class="material-symbols-outlined" style="color:#00716d">medication</span>Đơn thuốc</h4>
                            <span id="badge-thuoc-count" class="badge badge-secondary">0 loại</span>
                        </div>
                        <table class="billing-table">
                            <thead>
                                <tr>
                                    <th>Tên thuốc</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-right">Đơn giá</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody class="render-table-thuoc-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="wizard-buttons">
                <div></div>
                <button type="button" class="btn-primary" onclick="goToStep(2)">
                    <span>Tiếp theo</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>

        <div id="step-2" class="wizard-step">
            <div class="invoice-grid-layout" style="grid-template-columns: 1fr;">
                <div class="payment-summary-card" style="max-width: 600px; margin: 0 auto; width: 100%;">
                    <h4>Chọn phương thức thanh toán</h4>
                    <div class="summary-row" style="margin-bottom: 1rem;">
                        <span>Tổng số tiền cần thanh toán:</span>
                        <span class="render-grand-total price" style="font-size: 20px; color: var(--color-error, red);">0đ</span>
                    </div>

                    <div class="form-group">
                        <label for="payment-method">Phương thức thanh toán</label>
                        <select id="payment-method" style="width: 100%; padding: 10px; font-size: 15px; font-weight: bold;">
                            <option value="tien-mat">Tiền mặt</option>
                            <option value="chuyen-khoan">Chuyển khoản QR</option>
                        </select>
                    </div>

                    <div id="dynamic-payment-fields">
                        <div class="form-group payment-dynamic-field">
                            <label for="cash-given">Số tiền khách đưa</label>
                            <input type="text" id="cash-given" class="form-control" style="width:100%; padding:10px; box-sizing:border-box; font-weight:bold; font-size:16px;" placeholder="Nhập số tiền...">
                        </div>
                        <div class="summary-row payment-dynamic-field" style="border-top:none; margin-top:0.5rem;">
                            <span>Tiền thừa trả khách:</span>
                            <span id="cash-return" class="text-success-custom" style="font-size:16px;">0đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-buttons">
                <button type="button" class="btn-secondary" onclick="goToStep(1)">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span>Quay lại</span>
                </button>
                <button type="button" id="btn-next-to-step3" class="btn-primary" onclick="goToStep(3)">
                    <span>Tiếp theo</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </div>

        <div id="step-3" class="wizard-step">
            <div class="invoice-grid-layout">
                
                <div class="col-left print-full-width">
                    <div class="billing-data-card print-no-height">
                        <div class="card-header">
                            <h4><span class="material-symbols-outlined">receipt_long</span>Chi tiết các hạng mục thanh toán</h4>
                        </div>
                        <div style="padding:15px;">
                            <h5 class="print-section-title">I. Dịch vụ &amp; Xét nghiệm</h5>
                            <table class="billing-table">
                                <thead>
                                    <tr>
                                        <th>Tên dịch vụ</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="render-table-cls-body"></tbody>
                            </table>

                            <h5 class="print-section-title" style="margin-top: 25px;">II. Đơn thuốc</h5>
                            <table class="billing-table">
                                <thead>
                                    <tr>
                                        <th>Tên thuốc</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-right">Đơn giá</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="render-table-thuoc-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-right print-full-width">
                    <div class="payment-summary-card">
                        <h4>Tóm tắt chi phí hóa đơn</h4>
                        
                        <div class="summary-row">
                            <span>Tổng tiền dịch vụ</span>
                            <span id="summary-cls-total" class="bold">0đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Tổng tiền thuốc</span>
                            <span id="summary-thuoc-total" class="bold">0đ</span>
                        </div>
                        <div class="divider"></div>
                        <div class="summary-row">
                            <span>Tạm tính</span>
                            <span id="summary-subtotal" class="bold">0đ</span>
                        </div>
                        
                        <div class="grand-total-box">
                            <p class="title">Tổng cộng cuối cùng</p>
                            <p class="render-grand-total price">0đ</p>
                        </div>

                        <div class="divider"></div>
                        
                        <div class="final-method-box">
                            <p style="margin-bottom:5px;"><strong>Hình thức thanh toán:</strong> <span id="final-payment-method">...</span></p>
                            <p id="final-cash-details" style="margin:0;"><strong>Số tiền khách đưa:</strong> <span id="final-cash-given">0đ</span> | <strong>Trả khách:</strong> <span id="final-cash-return" class="text-success-custom">0đ</span></p>
                        </div>

                        <div id="final-qr-layout" class="qr-flex-layout">
                            <div class="qr-left">
                                <img id="final-qr-img" src="" alt="Mã VietQR" class="qr-image">
                                <p class="qr-hint">Quét mã để chuyển khoản</p>
                            </div>
                            <div class="qr-right">
                                <p style="font-weight: bold; color:var(--color-primary); margin-bottom: 5px; font-size:14px;">THÔNG TIN TÀI KHOẢN PHÒNG KHÁM</p>
                                <p><strong>Ngân hàng:</strong> <?= $site['ngan_hang'] ?></p>
                                <p><strong>Số tài khoản:</strong> <span style="font-size:15px; font-weight:bold; color:#000;"><?= $site['stk'] ?></span></p>
                                <p><strong>Chủ tài khoản:</strong> <?= $site['ctk'] ?></p>
                                <p><strong>Số tiền:</strong> <span class="text-success-custom render-grand-total">0đ</span></p>
                            </div>
                        </div>

                        <div class="print-signatures">
                            <div class="sig-box">
                                <p class="sig-title">Người lập hóa đơn</p>
                                <p class="sig-sub">(Ký và ghi rõ họ tên)</p>
                            </div>
                            <div class="sig-box">
                                <p class="sig-title">Người thanh toán</p>
                                <p class="sig-sub">(Ký và ghi rõ họ tên)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-buttons">
                <button type="button" class="btn-secondary" onclick="goToStep(2)">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span>Quay lại</span>
                </button>
                
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-success" onclick="window.print()">
                        <span class="material-symbols-outlined">print</span>
                        <span>In hóa đơn</span>
                    </button>

                    <button type="button" id="btn-submit-billing" onclick="submitThanhToan()" class="submit-btn" style="margin: 0;">
                        <span class="material-symbols-outlined">check_circle</span>
                        <span>Xác nhận thanh toán</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
// Giữ nguyên logic xử lý dữ liệu, kiểm tra số tiền mặt và tạo mã VietQR động
const CONFIG_BANK = "<?= $site['ngan_hang'] ?? '' ?>";          
const CONFIG_STK = "<?= $site['stk'] ?? '' ?>";   
const CONFIG_TEN_CTK = "<?= $site['ctk'] ?? '' ?>"; 

let currentGrandTotal = 0; 
let currentTotalCls = 0;
let currentTotalThuoc = 0;
let isCashValid = false;



document.addEventListener("DOMContentLoaded", function () {
    const maPhieuKham = sessionStorage.getItem('auto_select_ma_phieu');
    if (!maPhieuKham) {
        document.getElementById('loading-state').innerHTML = `
            <span class="material-symbols-outlined" style="font-size:48px;color:var(--color-error)">warning</span>
            <p style="color:var(--color-error);font-weight:bold;margin-top:0.5rem">Không tìm thấy mã phiếu khám cần thanh toán!</p>
            <a href="index.php?page=ds-benh-nhan" style="color:var(--color-primary);text-decoration:underline;font-size:14px;margin-top:0.5rem;display:inline-block">Quay lại danh sách</a>
        `;
        return;
    }

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    fetch(`src/api/getChiTietHoaDon.php?maphieukham=${maPhieuKham}`)
        .then(response => response.json())
        .then(res => {
            if (res.status === 'error') { showAlert(res.message); return; }
            const data = res.data;
            document.getElementById('pk-code').textContent = data.MaPhieuKhamCode || 'N/A';
            document.getElementById('patient-id').textContent = data.MaBenhNhan || 'N/A';
            document.getElementById('patient-name').textContent = "Bệnh nhân: " + data.HoTen;

            let clsHtml = '', thuocHtml = '', countCls = 0, countThuoc = 0;
            currentTotalCls = 0;
            currentTotalThuoc = 0;
            const items = data.ChiTietMục || [];

            items.forEach(item => {
                const sl = parseInt(item.SoLuong) || 0;
                const gia = parseFloat(item.DonGia) || 0;
                const thanhTien = sl * gia;

                if (item.LoaiMuc === 'CLS') {
                    countCls++; currentTotalCls += thanhTien;
                    clsHtml += `<tr><td>${item.TenMuc}</td><td class="text-center">${sl}</td><td class="text-right">${formatVND(gia)}</td><td class="text-right total-cell">${formatVND(thanhTien)}</td></tr>`;
                } else if (item.LoaiMuc === 'Thuoc') {
                    countThuoc++; currentTotalThuoc += thanhTien;
                    thuocHtml += `<tr><td style="font-weight:600">${item.TenMuc}</td><td class="text-center">${sl}</td><td class="text-right">${formatVND(gia)}</td><td class="text-right total-cell">${formatVND(thanhTien)}</td></tr>`;
                }
            });

            const emptyRow = `<tr><td colspan="4" style="text-align:center;color:var(--color-text-muted);font-style:italic;padding:1.5rem 0">Không có dữ liệu trong mục này</td></tr>`;
            
            document.querySelectorAll('.render-table-cls-body').forEach(el => el.innerHTML = clsHtml || emptyRow);
            document.querySelectorAll('.render-table-thuoc-body').forEach(el => el.innerHTML = thuocHtml || emptyRow);

            document.getElementById('badge-cls-count').textContent = `${String(countCls).padStart(2, '0')} mục`;
            document.getElementById('badge-thuoc-count').textContent = `${String(countThuoc).padStart(2, '0')} loại`;
            
            document.getElementById('summary-cls-total').textContent = formatVND(currentTotalCls);
            document.getElementById('summary-thuoc-total').textContent = formatVND(currentTotalThuoc);
            
            currentGrandTotal = currentTotalCls + currentTotalThuoc;
            document.getElementById('summary-subtotal').textContent = formatVND(currentGrandTotal);
            
            document.querySelectorAll('.render-grand-total').forEach(el => el.textContent = formatVND(currentGrandTotal));

            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('invoice-content').classList.remove('hidden');

            initPaymentEventListeners(maPhieuKham);
        })
        .catch(err => {
            console.error(err);
            showAlert("Không thể tải thông tin hóa đơn do lỗi mạng.");
        });
});

function goToStep(stepNumber) {
    const pMethod = document.getElementById('payment-method').value;

    if (stepNumber === 3) {
        if (pMethod === 'tien-mat' && !isCashValid) {
            showAlert("Vui lòng nhập số tiền khách đưa hợp lệ trước khi tiếp tục!");
            return;
        }

        if (pMethod === 'tien-mat') {
            document.getElementById('final-payment-method').textContent = "Tiền mặt";
            document.getElementById('final-cash-details').style.display = "block";
            document.getElementById('final-cash-given').textContent = document.getElementById('cash-given').value + "đ";
            document.getElementById('final-cash-return').textContent = document.getElementById('cash-return').textContent;
            document.getElementById('final-qr-layout').style.display = "none";
        } else {
            document.getElementById('final-payment-method').textContent = "Chuyển khoản QR (VietQR)";
            document.getElementById('final-cash-details').style.display = "none";
            
            const maPhieuKham = sessionStorage.getItem('auto_select_ma_phieu');
            const addInfo = "Thanh toan PK " + maPhieuKham; 
            const qrUrl = `https://img.vietqr.io/image/${CONFIG_BANK}-${CONFIG_STK}-qr_only.png?amount=${currentGrandTotal}&addInfo=${encodeURIComponent(addInfo)}&accountName=${encodeURIComponent(CONFIG_TEN_CTK)}`;
            
            document.getElementById('final-qr-img').src = qrUrl;
            document.getElementById('final-qr-layout').style.display = "flex";
        }
    }

    document.querySelectorAll('.wizard-step').forEach(step => step.classList.remove('active'));
    document.querySelectorAll('.step-item').forEach(item => {
        item.classList.remove('active');
        item.classList.remove('completed');
    });

    document.getElementById(`step-${stepNumber}`).classList.add('active');
    
    for (let i = 1; i <= 3; i++) {
        const header = document.getElementById(`step-header-${i}`);
        if (i < stepNumber) {
            header.classList.add('completed');
        } else if (i === stepNumber) {
            header.classList.add('active');
        }
    }
}

function initPaymentEventListeners(maPhieuKham) {
    const paymentMethodSelect = document.getElementById('payment-method');
    const dynamicFields = document.getElementById('dynamic-payment-fields');
    const btnNext = document.getElementById('btn-next-to-step3');

    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'tien-mat') {
            isCashValid = false;
            btnNext.disabled = true;
            dynamicFields.innerHTML = `
                <div class="form-group payment-dynamic-field">
                    <label for="cash-given">Số tiền khách đưa</label>
                    <input type="text" id="cash-given" class="form-control" style="width:100%; padding:10px; box-sizing:border-box; font-weight:bold; font-size:16px;" placeholder="Nhập số tiền...">
                </div>
                <div class="summary-row payment-dynamic-field" style="border-top:none; margin-top:0.5rem;">
                    <span>Tiền thừa trả khách:</span>
                    <span id="cash-return" class="text-success-custom" style="font-size:16px;">0đ</span>
                </div>
            `;
            bindCashInputEvent();
        } else if (this.value === 'chuyen-khoan') {
            isCashValid = true;
            btnNext.disabled = false;
            
            const addInfo = "Thanh toan PK " + maPhieuKham; 
            const qrUrl = `https://img.vietqr.io/image/${CONFIG_BANK}-${CONFIG_STK}-qr_only.png?amount=${currentGrandTotal}&addInfo=${encodeURIComponent(addInfo)}&accountName=${encodeURIComponent(CONFIG_TEN_CTK)}`;

            dynamicFields.innerHTML = `
                <div class="qr-flex-layout">
                    <div class="qr-left">
                        <img src="${qrUrl}" alt="Mã VietQR" class="qr-image">
                    </div>
                    <div class="qr-right">
                        <p style="font-weight:bold; margin-bottom:5px; font-size:14px; color:var(--color-primary);">QUÉT MÃ VIETQR ĐỂ CHUYỂN KHOẢN</p>
                        <p><strong>Ngân hàng:</strong> ${CONFIG_BANK}</p>
                        <p><strong>STK:</strong> ${CONFIG_STK}</p>
                        <p><strong>CTK:</strong> ${CONFIG_TEN_CTK}</p>
                    </div>
                </div>
            `;
        }
    });

    btnNext.disabled = true;
    bindCashInputEvent();
}

function bindCashInputEvent() {
    const cashGivenInput = document.getElementById('cash-given');
    const btnNext = document.getElementById('btn-next-to-step3');
    if (!cashGivenInput) return;

    cashGivenInput.addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value === '') {
            document.getElementById('cash-return').textContent = "0đ";
            isCashValid = false;
            btnNext.disabled = true;
            return;
        }

        let cashGiven = parseFloat(value);
        this.value = new Intl.NumberFormat('vi-VN').format(cashGiven);

        let cashReturn = cashGiven - currentGrandTotal;
        if (cashReturn >= 0) {
            document.getElementById('cash-return').textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(cashReturn);
            document.getElementById('cash-return').style.color = "#2e7d32";
            isCashValid = true;
            btnNext.disabled = false;
        } else {
            document.getElementById('cash-return').textContent = "Còn thiếu " + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(Math.abs(cashReturn));
            document.getElementById('cash-return').style.color = "var(--color-error, red)";
            isCashValid = false;
            btnNext.disabled = true;
        }
    });
}

function submitThanhToan() {
    const maPhieuKham = sessionStorage.getItem('auto_select_ma_phieu');
    if (!maPhieuKham) {
        showAlert("Lỗi: Không tìm thấy mã phiếu khám!");
        return;
    }

    const pMethod = document.getElementById('payment-method').value;
    let cashGivenValue = 0;
    
    if (pMethod === 'tien-mat') {
        const cashInput = document.getElementById('cash-given');
        if (cashInput) {
            cashGivenValue = parseFloat(cashInput.value.replace(/[^\d]/g, '')) || 0;
        }
        if (!isCashValid || cashGivenValue < currentGrandTotal) {
            showAlert("Số tiền khách đưa không hợp lệ hoặc chưa đủ!");
            return;
        }
    } else {
        cashGivenValue = currentGrandTotal;
    }

    // Disable nút bấm để tránh double submit
    const btnSubmit = document.getElementById('btn-submit-billing');
    if (btnSubmit) btnSubmit.disabled = true;

    const invoiceData = {
        MaPhieuKham: maPhieuKham,
        TongTienCLS: currentTotalCls,
        TongTienThuoc: currentTotalThuoc,
        TongCong: currentGrandTotal,
        GiamGia: 0,
        TongThanhToan: currentGrandTotal,
        TrangThai: 1, 
        PhuongThucThanhToan: pMethod,
        SoTienKhachDua: cashGivenValue,
        SoTienTraKhach: pMethod === 'tien-mat' ? (cashGivenValue - currentGrandTotal) : 0
    };

    // Gửi dữ liệu qua API backend để thực thi câu lệnh SQL INSERT INTO hoadon
    fetch('src/api/saveHD.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(invoiceData)
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            showAlert("XÁC NHẬN HOÀN TẤT:\n" + (res.message || "Hệ thống đã lưu trạng thái hóa đơn thành công!"));
            sessionStorage.removeItem('auto_select_ma_phieu');
            window.location.href = "index.php?workspace=1&page=ds-benh-nhan";
        } else {
            showAlert("Lỗi hệ thống: " + res.message);
            if (btnSubmit) btnSubmit.disabled = false;
        }
    })
    .catch(err => {
        console.error(err);
        showAlert("Không thể kết nối máy chủ để lưu hóa đơn.");
        if (btnSubmit) btnSubmit.disabled = false;
    });
}
</script>
</body>