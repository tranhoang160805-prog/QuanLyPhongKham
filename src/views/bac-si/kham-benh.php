<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="public/assets/css/DieuDuong/so-kham.css">
<link rel="stylesheet" href="public/assets/css/BacSi/kham-benh.css">
<link rel="stylesheet" href="public/assets/css/BacSi/cap-thuoc.css">
<body>

<div class="main-container">
    
    <div class="left-panel-wrapper">
        
        <div class="card flex-card-main">
            <div class="card-header">
                <div class="card-title-group">
                    <span class="material-symbols-outlined icon-sky">account_box</span>
                    <h3 class="card-title">Hồ Sơ Hành Chính Bệnh Nhân</h3>
                </div>
            </div>
            
            <div class="info-body-container" id="patient-info-panel">
                <div class="empty-placeholder">
                    <span class="material-symbols-outlined kb-empty-icon">assignment_ind</span>
                    Chưa chọn dữ liệu bệnh nhân.<br>Vui lòng quay lại danh sách tổng.
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel-wrapper">
        <section class="card kb-card-full-height">
            <div class="card-body">

                <form id="form-kham-benh" onsubmit="event.preventDefault();">
                    <input type="hidden" id="target_ma_phieu" name="target_ma_phieu" value="0" />
                    <input type="hidden" id="action_type" name="action_type" value="tam_luu" />

                    <div class="kb-grid-two-cols">
                        <div>
                            <label class="form-label">Triệu chứng lâm sàng</label>
                            <textarea id="trieu_chung" name="trieu_chung" rows="4" class="form-textarea-full" placeholder="Ghi nhận các triệu chứng hiện tại của bệnh nhân..."></textarea>
                        </div>
                        <div>
                            <label class="form-label">Tiền sử bệnh lý</label>
                            <textarea id="tien_su_benh" name="tien_su_benh" rows="4" class="form-textarea-full" placeholder="Tiền sử bệnh mãn tính, dị ứng thuốc nếu có..."></textarea>
                        </div>
                    </div>

                    <div class="kb-form-group">
                        <label class="form-label kb-label-bold">Chẩn đoán sơ bộ</label>
                        <textarea id="cd_so_bo" name="cd_so_bo" rows="3" class="form-textarea-full kb-textarea-blue-border" placeholder="Chẩn đoán sơ bộ trước khi Chỉ định cận lân sàn"></textarea>
                    </div>

                    <div class="kb-form-group">
                        <label class="form-label kb-label-bold">Kết luận</label>
                        <textarea id="chan_doan" name="chan_doan" rows="3" class="form-textarea-full kb-textarea-blue-border" placeholder="Nhập kết luận bệnh trước khi cấp thuốc"></textarea>
                    </div>

                    <div class="kb-form-group-large-bottom">
                        <label class="form-label">Lời dặn & Hướng dẫn phác đồ điều trị</label>
                        <textarea id="loi_dan_bs" name="loi_dan_bs" rows="3" class="form-textarea-full" placeholder="Chế độ dinh dưỡng, dặn dò uống thuốc và hẹn lịch tái khám..."></textarea>
                    </div>

                    <div class="kb-form-actions">
                        <button type="button" onclick="quayLaiDanhSach()" class="btn btn-secondary kb-btn-flex-gap">
                            <span class="material-symbols-outlined kb-icon-back">arrow_back_ios</span>
                            Quay lại danh sách
                        </button>
                        <button type="button" onclick="submitKhamBenh('xet_nghiem')" class="btn btn-amber kb-btn-flex-gap">
                            <span class="material-symbols-outlined kb-btn-icon">science</span> Chỉ định Xét Nghiệm
                        </button>

                        <button type="button" onclick="submitKhamBenh('cap_thuoc')" class="btn btn-primary kb-btn-submit-primary">
                            <span class="material-symbols-outlined kb-btn-icon">medical_services</span> Hoàn Thành &amp; Kê Đơn
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<script>
(function() {
    let currentPatientData = null;

    function initSelectedPatient() {
        const urlParams = new URLSearchParams(window.location.search);
        const maPhieuKham = urlParams.get('ma_phieu') || sessionStorage.getItem('auto_select_ma_phieu');

        if (!maPhieuKham) {
            hienThiKhungTrong();
            return;
        }

        fetch(`src/api/getDSPhieuKham.php`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const patient = res.data.find(x => x.MaPhieuKham == maPhieuKham);
                    if (patient) {
                        currentPatientData = patient;
                        doDuLieuGiaoDien(patient);
                    } else {
                        hienThiKhungTrong("Không tìm thấy dữ liệu của mã phiếu khám này.");
                    }
                } else {
                    hienThiKhungTrong("Không tải được danh sách hồ sơ từ máy chủ.");
                }
            })
            .catch(() => {
                hienThiKhungTrong("Lỗi kết nối API lấy dữ liệu bệnh nhân.");
            });
    }

    // 2. ĐỔ DỮ LIỆU ĐỒNG THỜI VÀO KHUNG THÔNG TIN TRÁI VÀ FORM PHẢI
    function doDuLieuGiaoDien(patient) {
        // A. Điền thông tin vào Panel Trái (Hồ sơ hành chính sử dụng cho tất cả các role khác)
        const infoPanel = document.getElementById('patient-info-panel');
        const st = JSON.parse(patient.ThongSoSinhTon);
        if (infoPanel) {
            const sttFormatted = String(patient.STT).padStart(3, '0');
            const codeDisplay = patient.MaPhieuKhamCode || '#' + patient.MaPhieuKham;
            const gioiTinhText = patient.GioiTinh === 'F' ? 'Nữ' : 'Nam';

            infoPanel.innerHTML = `
                <div class="info-section-title">Thông tin phiếu khám</div>
                <div class="info-grid">                    
                    <div class="info-label">Trạng thái:</div>
                    <div><span class="status-badge-inline">Đang chờ khám</span></div>

                    <div class="info-label">Số thứ tự:</div>
                    <div class="info-value"><span class="kb-text-highlight-stt">${sttFormatted}</span></div>

                    <div class="info-label">Mã phiếu:</div>
                    <div class="info-value kb-text-primary-blue">${codeDisplay}</div>

                     <div class="info-label">Giờ tiếp nhận:</div>
                    <div class="info-value kb-text-primary-blue">${patient.GioTiepNhan}</div>

                </div>

                <div class="info-section-title kb-margin-top-10">THÔNG TIN BỆNH NHÂN</div>
                <div class="info-grid">
                    <div class="info-label">Mã bệnh nhân:</div>
                    <div class="info-value">${patient.MaBN || 'N/A'}</div>

                    <div class="info-label">Họ và tên:</div>
                    <div class="info-value kb-text-uppercase-dark">${patient.HoTen}</div>
                    
                    <div class="info-label">Ngày sinh:</div>
                    <div class="info-value">${patient.NgaySinh || 'N/A'}</div>

                    <div class="info-label">Giới tính:</div>
                    <div class="info-value">${gioiTinhText}</div>

                    <div class="info-label">Mã BHYT:</div>
                    <div class="info-value">${patient.MaBHYT || 'Không đăng ký'}</div>
                    
                </div>

                <div class="info-section-title kb-margin-top-10">THÔNG TIN KHÁM BỆNH</div>
                <div class="info-grid">
                    <div class="info-label">Chiều cao: </div>
                    <div class="info-value">${st.chieu_cao || '--'} cm</div>

                    <div class="info-label">Cân nặng: </div>
                    <div class="info-value">${st.can_nang || '--'} kg</div>

                    <div class="info-label">Huyết áp: </div>
                    <div class="info-value">${st.huyet_ap || '--'} mmHg</div>

                    <div class="info-label">Lý do khám:</div>
                    <div class="info-value">${patient.LyDoKham || '--'}</div>

                    <div class="info-label">Triệu chứng:</div>
                    <div class="info-value">${patient.TrieuChung || '--'}</div>

                    <div class="info-label">Tiền sử bệnh:</div>
                    <div class="info-value">${patient.TienSuBenh || '--'}</div>

                    <div class="info-label">Chuẩn đoán:</div>
                    <div class="info-value">${patient.ChanDoan || '--'}</div>

                    <div class="info-label">Lời dặn Bác sĩ:</div>
                    <div class="info-value">${patient.LoiDanBS || '--'}</div>

                    <div class="info-label">Ghi chú:</div>
                    <div class="info-value">${patient.GhiChu || '--'}</div>
                    
                    <div class="info-label">Tiền sử dị ứng:</div>
                    <div class="info-value kb-text-danger">${patient.DiUng || 'Chưa phát hiện'}</div>
                </div>
            `;
        }

        document.getElementById('trieu_chung').value = patient.TrieuChung || '';
        document.getElementById('tien_su_benh').value = patient.TienSuBenh || '';
        document.getElementById('chan_doan').value = patient.ChanDoan || '';
        document.getElementById('cd_so_bo').value = patient.ChanDoanSoBo || '';
        document.getElementById('loi_dan_bs').value = patient.LoiDanBS || '';

    }

    function hienThiKhungTrong(customMsg = "Vui lòng chọn một bệnh nhân từ danh sách tổng để hiển thị hồ sơ.") {
        const infoPanel = document.getElementById('patient-info-panel');
        if (infoPanel) {
            infoPanel.innerHTML = `
                <div class="empty-placeholder">
                    <span class="material-symbols-outlined kb-empty-icon">assignment_ind</span>
                    ${customMsg}
                </div>
            `;
        }
        clearSinhTonInputs();
    }

    function clearSinhTonInputs() {
        document.getElementById('nhiet_do').value = '';
        document.getElementById('huyet_ap').value = '';
        document.getElementById('mach').value = '';
        document.getElementById('nhip_tho').value = '';
    }

    // 3. ĐIỀU HƯỚNG QUAY TRỞ LẠI TRANG DANH SÁCH TỔNG (ĂN THEO URL ROUTER)
    function quayLaiDanhSach() {
        const backMenuLink = document.querySelector('a[data-page="ds-benh-nhan"]') || document.querySelector('[onclick*="ds-benh-nhan"]');
        if (backMenuLink) {
            backMenuLink.click();
        } else {
            window.location.href = 'index.php?workspace=1&page=ds-benh-nhan';
        }
    }

    // 4. GỬI DỮ LIỆU ĐẾN API LƯU BỆNH ÁN
    function submitKhamBenh(actionType) {
        const actionInput = document.getElementById('action_type');
        if (actionInput) actionInput.value = actionType;
    
        // 2. Lấy mã phiếu thông minh (Ưu tiên ô ẩn -> dự phòng tìm trong bộ nhớ tạm sessionStorage)
        let rawMaPhieu = document.getElementById('target_ma_phieu') ? document.getElementById('target_ma_phieu').value : "0";
        if (!rawMaPhieu || rawMaPhieu === "0") {
            rawMaPhieu = sessionStorage.getItem('auto_select_ma_phieu') || "0";
        }
    
        const maPhieu = parseInt(rawMaPhieu);
        
        // Kiểm tra dữ liệu hợp lệ
        if (!maPhieu || maPhieu <= 0) {
            showAlert('Hệ thống: Không tìm thấy mã phiếu khám hợp lệ để lưu! Vui lòng chọn một bệnh nhân từ danh sách bên trái.', 'error');
            return;
        }
    
        // 3. Bắt buộc nhập chẩn đoán đối với các hành động chuyển tiếp chuyên sâu
        if (actionType === 'cap_thuoc') {
            const chanDoanInput = document.getElementById('chan_doan');
            const cd = chanDoanInput ? chanDoanInput.value.trim() : "";
            if (!cd) {
                showAlert('Bác sĩ bắt buộc phải nhập Kết luận trước khi cấp thuốc!', 'warning');
                if (chanDoanInput) chanDoanInput.focus();
                return;
            }
        }

        if (actionType === 'xet_nghiem') {
            const CDSBInput = document.getElementById('cd_so_bo');
            const cdsb = CDSBInput ? CDSBInput.value.trim() : "";
            if (!cdsb) {
                showAlert('Bác sĩ vui lòng nhập Chẩn đoán sơ bộ!', 'warning');
                if (CDSBInput) CDSBInput.focus();
                return;
            }
        }
    
        // 4. Khóa nút bấm tạm thời chống gửi trùng lặp dữ liệu (Double click)
        const activeBtn = event ? event.currentTarget : null;
        if (activeBtn && activeBtn.tagName === 'BUTTON') activeBtn.disabled = true;
    
        // 5. Đóng gói payload JSON sạch gửi lên API xử lý
        const formData = {
            target_ma_phieu: maPhieu,
            action_type: actionType,
            nhiet_do: document.getElementById('nhiet_do') ? document.getElementById('nhiet_do').value : '',
            huyet_ap: document.getElementById('huyet_ap') ? document.getElementById('huyet_ap').value : '',
            mach: document.getElementById('mach') ? document.getElementById('mach').value : '',
            nhip_tho: document.getElementById('nhip_tho') ? document.getElementById('nhip_tho').value : '',
            trieu_chung: document.getElementById('trieu_chung') ? document.getElementById('trieu_chung').value : '',
            tien_su_benh: document.getElementById('tien_su_benh') ? document.getElementById('tien_su_benh').value : '',
            chan_doan: document.getElementById('chan_doan') ? document.getElementById('chan_doan').value : '',
            cd_so_bo: document.getElementById('cd_so_bo') ? document.getElementById('cd_so_bo').value : '',
            loi_dan_bs: document.getElementById('loi_dan_bs') ? document.getElementById('loi_dan_bs').value : ''
        };
    
        fetch('src/api/saveSoKham.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json; charset=utf-8' },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message || 'Cập nhật hồ sơ bệnh án thành công!', 'success');
                sessionStorage.setItem('auto_select_ma_phieu', maPhieu);

            // Xử lý điều hướng thông minh dựa trên Action Type sau 1.2 giây
                setTimeout(() => {
                    if (actionType === 'xet_nghiem') {
                        // Chuyển hướng sang trang chỉ định xét nghiệm chi tiết ctv
                        const navLink = document.querySelector('[data-page="chi-dinh"]') || document.querySelector('[onclick*="xet-nghiem-ctv"]');
                        if (navLink) {
                            navLink.click();
                        } else {
                            window.location.href = `index.php?workspace=1&page=chi-dinh&ma_phieu=${maPhieu}`;
                        }
                    } else if (actionType === 'cap_thuoc') {
                        // Chuyển hướng sang trang kê đơn/cấp thuốc
                        const navLink = document.querySelector('[data-page="cap-thuoc-bs"]') || document.querySelector('[onclick*="cap-thuoc"]');
                        if (navLink) {
                            navLink.click();
                        } else {
                            window.location.href = `index.php?workspace=1&page=cap-thuoc-bs&ma_phieu=${maPhieu}`;
                        }
                    } else {
                        // Nếu là tạm lưu thì chỉ cần quay lại màn hình danh sách ca bệnh chung
                        quayLaiDanhSach();
                    }
                }, 1500);

            } else {
                showAlert('Thao tác thất bại: ' + data.message, 'error');
                if (activeBtn) activeBtn.disabled = false;
            }
        })
        .catch(() => {
            showAlert('Lỗi: Không thể kết nối đến hệ thống xử lý API máy chủ.', 'error');
            if (activeBtn) activeBtn.disabled = false;
        });
    }
    // ĐĂNG KÝ HÀM TOÀN CỤC (GLOBAL SCOPE)
    window.initSelectedPatient  = initSelectedPatient;
    window.submitKhamBenh       = submitKhamBenh;
    window.quayLaiDanhSach      = quayLaiDanhSach;

    // TỰ ĐỘNG CHẠY KHI KHỞI TẠO FILE TRÊN ROUTER TẬP TRUNG
    if (document.getElementById('patient-info-panel')) {
        initSelectedPatient();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSelectedPatient);
    }

    const kbObserver = new MutationObserver(() => {
        const checkPanel = document.getElementById('patient-info-panel');
        if (checkPanel && !checkPanel.dataset.loaded) {
            checkPanel.dataset.loaded = '1';
            initSelectedPatient();
        }
    });
    kbObserver.observe(document.body, { childList: true, subtree: true });

})();
</script>
</body>