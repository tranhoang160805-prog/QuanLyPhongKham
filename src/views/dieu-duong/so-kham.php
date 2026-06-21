<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="public/assets/css/DieuDuong/so-kham.css">

<body>

<div class="main-container">
    
    <div class="left-panel-wrapper">
        <div class="card flex-card-main">
            <div class="card-header">
                <div class="card-title-group">
                    <span class="material-symbols-outlined icon-sky">account_box</span>
                    <h3 class="card-title">Hồ Sơ Bệnh Nhân</h3>
                </div>
            </div>
            
            <div class="info-body-container" id="patient-info-panel">
                <div class="empty-placeholder">
                    <span class="material-symbols-outlined" style="font-size: 48px; margin-bottom: 10px; display: block;">assignment_ind</span>
                    Chưa chọn dữ liệu bệnh nhân.<br>Vui lòng quay lại danh sách tổng.
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel-wrapper">
        <section class="card" style="height: 100%;">

            <div class="card-body">

                <form id="form-vitals" onsubmit="event.preventDefault();">
                    <input type="hidden" id="target_ma_phieu" name="target_ma_phieu" value="0" />
                    
                    <div class="vital-grid">
                        <div class="input-group">
                            <label class="input-label" for="nhiet_do">Nhiệt độ (°C)</label>
                            <input type="number" id="nhiet_do" name="nhiet_do" step="0.1" min="30" max="45" placeholder="Ví dụ: 36.5" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label" for="huyet_ap">Huyết áp (mmHg)</label>
                            <input type="text" id="huyet_ap" name="huyet_ap" placeholder="Ví dụ: 120/80" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label" for="chieu_cao">Chiều cao (cm)</label>
                            <input type="number" id="chieu_cao" name="chieu_cao" step="0.1" min="50" max="250" placeholder="Ví dụ: 170" required>
                        </div>
                        
                        <div class="input-group">
                            <label class="input-label" for="can_nang">Cân nặng (kg)</label>
                            <input type="number" id="can_nang" name="can_nang" step="0.1" min="2" max="200" placeholder="Ví dụ: 60" required>
                        </div>
                    </div>

                    <div class="bmi-container">
                        <div class="bmi-header">
                            <span class="bmi-title">Chỉ số khối cơ thể (BMI)</span>
                            <span id="bmi-value" class="bmi-value">---</span>
                        </div>
                        
                        <div class="bmi-bar-wrapper">
                            <div class="bmi-bar">
                                <div class="bmi-segment bg-bmi-underweight"></div>
                                <div class="bmi-segment bg-bmi-normal"></div>
                                <div class="bmi-segment bg-bmi-overweight"></div>
                                <div class="bmi-segment bg-bmi-obese"></div>
                            </div>
                            <div id="bmi-pin" class="bmi-pin"></div>
                        </div>
                        
                        <div class="bmi-labels">
                            <span>&lt; 18.5<br>Gầy</span>
                            <span>18.5 - 22.9<br>Bình thường</span>
                            <span>23.0 - 24.9<br>Thừa cân</span>
                            <span>&ge; 25.0<br>Béo phì</span>
                        </div>
                        <div style="text-align: right; margin-top: 5px;">
                            <span id="bmi-status-label" class="bmi-status" style="display:inline-block; padding: 2px 8px; border-radius: 4px; font-size:12px; font-weight:700;"></span>
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <label class="input-label" for="ly_do_kham">Lý do khám bệnh</label>
                        <textarea id="ly_do_kham" name="ly_do_kham" rows="3" class="textara-input" placeholder="Nhập lý do bệnh nhân đến khám hoặc ghi chú đặc biệt..."></textarea>
                    </div>

                    <div style="margin-top: 20px;">
                        <label class="input-label" for="trieu_chung">Ghi chú</label>
                        <textarea id="ghi_chu" name="ghi_chu" rows="3" class="textara-input" placeholder="Nhập triệu chứng"></textarea>
                    </div>

                    <div class="form-actions btn-group" >
                        <button type="button" class="btn btn-secondary" onclick="quayLaiDanhSach()">Quay lại</button>
                        <button type="submit" class="btn btn-primary">Xác nhận &amp; Chuyển phòng khám</button>
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
        let maPhieuKham = urlParams.get('ma_phieu') || sessionStorage.getItem('auto_select_ma_phieu');

        if (!maPhieuKham || maPhieuKham === "0") {
            hienThiKhungTrong("Vui lòng quay lại danh sách tổng để chọn bệnh nhân.");
            return;
        }

        document.getElementById('target_ma_phieu').value = maPhieuKham;

        fetch(`src/api/getDSPhieuKham.php?status_filter=2&limit=100`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const patient = res.data.find(x => x.MaPhieuKham == maPhieuKham);
                    if (patient) {
                        currentPatientData = patient;
                        doDuLieuGiaoDien(patient);
                    } else {
                        fetch(`src/api/getDSPhieuKham.php?limit=150`)
                            .then(r => r.json())
                            .then(allRes => {
                                const backupPatient = allRes.data?.find(x => x.MaPhieuKham == maPhieuKham);
                                if (backupPatient) {
                                    currentPatientData = backupPatient;
                                    doDuLieuGiaoDien(backupPatient);
                                } else {
                                    hienThiKhungTrong(`Mã phiếu #${maPhieuKham} không tồn tại hoặc đã khám xong.`);
                                }
                            });
                    }
                } else {
                    hienThiKhungTrong("Không tải được thông tin từ máy chủ.");
                }
            })
            .catch(() => {
                hienThiKhungTrong("Lỗi kết nối cơ sở dữ liệu.");
            });
    }

    // 2. ĐỔ DỮ LIỆU ĐỒNG THỜI VÀO KHUNG THÔNG TIN VÀ FORM SƠ KHÁM
    function doDuLieuGiaoDien(patient) {
        // A. Điền thông tin vào Panel Trái
        const infoPanel = document.getElementById('patient-info-panel');
        
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
                    <div class="info-value"><span style="color: #0284c7; font-weight: 900;">${sttFormatted}</span></div>

                    <div class="info-label">Mã phiếu:</div>
                    <div class="info-value" style="color: #0284c7;">${codeDisplay}</div>

                     <div class="info-label">Giờ tiếp nhận:</div>
                    <div class="info-value" style="color: #0284c7;">${patient.GioTiepNhan}</div>
                </div>

                <div class="info-section-title" style="margin-top: 10px;">Hành chính bệnh nhân</div>
                <div class="info-grid">
                    <div class="info-label">Mã bệnh nhân:</div>
                    <div class="info-value">${patient.MaBN || 'N/A'}</div>

                    <div class="info-label">Họ và tên:</div>
                    <div class="info-value" style="text-transform: uppercase; color: #0f172a;">${patient.HoTen}</div>
                    
                    <div class="info-label">Ngày sinh:</div>
                    <div class="info-value">${patient.NgaySinh || 'N/A'}</div>

                    <div class="info-label">Giới tính:</div>
                    <div class="info-value">${gioiTinhText}</div>

                    <div class="info-label">Mã BHYT:</div>
                    <div class="info-value">${patient.MaBHYT || 'Không đăng ký'}</div>
                </div>

                <div class="info-section-title" style="margin-top: 10px;">Cảnh báo & Bảo hiểm</div>
                <div class="info-grid">

                    <div class="info-label">Lý do khám:</div>
                    <div class="info-value">${patient.LyDoKham || '--'}</div>

                    <div class="info-label">Triệu chứng:</div>
                    <div class="info-value">${patient.TrieuChung || '--'}</div>

                    <div class="info-label">Tiền sử bệnh:</div>
                    <div class="info-value">${patient.TienSuBenh || '--'}</div>

                    <div class="info-label">Ghi chú:</div>
                    <div class="info-value">${patient.GhiChu || '--'}</div>
                    
                    <div class="info-label">Tiền sử dị ứng:</div>
                    <div class="info-value" style="color: #ef4444;">${patient.DiUng || 'Chưa phát hiện'}</div>
                </div>
            `;
        }

        document.getElementById('ly_do_kham').value = patient.LyDoKham || '';
        document.getElementById('ghi_chu').value = patient.GhiChu || '';

        if (patient.ThongSoSinhTon) {
            try {
                const st = JSON.parse(patient.ThongSoSinhTon);
                document.getElementById('nhiet_do').value = st.nhiet_do || '';
                document.getElementById('huyet_ap').value = st.huyet_ap || '';
                // document.getElementById('mach').value = st.mach || '';
                // document.getElementById('nhip_tho').value = st.nhip_tho || '';
                document.getElementById('chieu_cao').value = st.chieu_cao || '';
                document.getElementById('can_nang').value = st.can_nang || '';
                const event = new Event('input');
                document.getElementById('chieu_cao').dispatchEvent(event);
            } catch(e) {
                clearFormInputs();
            }
        } else {
            clearFormInputs();
        }
    }

    function hienThiKhungTrong(customMsg = "Vui lòng chọn một bệnh nhân từ danh sách tổng để hiển thị hồ sơ sơ khám.") {
        const infoPanel = document.getElementById('patient-info-panel');
        if (infoPanel) {
            infoPanel.innerHTML = `
                <div class="empty-placeholder">
                    <span class="material-symbols-outlined" style="font-size: 48px; margin-bottom: 10px; display: block;">assignment_ind</span>
                    ${customMsg}
                </div>
            `;
        }
        clearFormInputs();
    }

    function clearFormInputs() {
        document.getElementById('form-vitals').reset();
        document.getElementById('bmi-value').innerText = '---';
        document.getElementById('bmi-pin').style.left = '0%';
        const bLabel = document.getElementById('bmi-status-label');
        if (bLabel) { bLabel.innerText = ''; bLabel.className = ''; }
    }

    function quayLaiDanhSach() {
        const backMenuLink = document.querySelector('a[data-page="ds-benh-nhan"]') || document.querySelector('[onclick*="ds-benh-nhan"]');
        if (backMenuLink) {
            backMenuLink.click();
        } else {
            window.location.href = 'index.php?workspace=1&page=ds-benh-nhan';
        }
    }

    // 5. KHỞI TẠO LOGIC TÍNH TOÁN BMI TỰ ĐỘNG
    function initSoKhamModule() {
        const hInp = document.getElementById("chieu_cao");
        const wInp = document.getElementById("can_nang");
        const vBmi = document.getElementById("bmi-value");
        const pPin = document.getElementById("bmi-pin");
        const bLabel = document.getElementById("bmi-status-label");

        if (hInp && wInp && vBmi && pPin) {
            function calcBMI() {
                const h = parseFloat(hInp.value) / 100;
                const w = parseFloat(wInp.value);
                
                if (!h || !w || h <= 0 || w <= 0) {
                    vBmi.innerText = "---";
                    pPin.style.left = "0%";
                    if (bLabel) { bLabel.innerText = ""; bLabel.className = ""; }
                    return;
                }
                
                const bmi = w / (h * h);
                vBmi.innerText = bmi.toFixed(1);
                
                // Tính toán tọa độ thanh phần trăm thước đo BMI
                let pct = 0;
                if (bmi < 18.5) {
                    pct = (bmi / 18.5) * 25;
                } else if (bmi >= 18.5 && bmi < 23.0) {
                    pct = 25 + ((bmi - 18.5) / 4.5) * 25;
                } else if (bmi >= 23.0 && bmi < 25.0) {
                    pct = 50 + ((bmi - 23.0) / 2.0) * 25;
                } else {
                    pct = 75 + Math.min(((bmi - 25.0) / 10.0) * 25, 25);
                }
                pPin.style.left = `${pct}%`;
                
                // Gán màu sắc trạng thái nhãn
                if (bLabel) {
                    if (bmi < 18.5) {
                        bLabel.innerText = "Gầy"; bLabel.className = "bmi-status bg-bmi-underweight";
                        pPin.style.backgroundColor = "#0284c7";
                    } else if (bmi >= 18.5 && bmi < 23.0) {
                        bLabel.innerText = "Bình thường"; bLabel.className = "bmi-status bg-bmi-normal";
                        pPin.style.backgroundColor = "#15803d";
                    } else if (bmi >= 23.0 && bmi < 25.0) {
                        bLabel.innerText = "Thừa cân"; bLabel.className = "bmi-status bg-bmi-overweight";
                        pPin.style.backgroundColor = "#c2410c";
                    } else {
                        bLabel.innerText = "Béo phì"; bLabel.className = "bmi-status bg-bmi-obese";
                        pPin.style.backgroundColor = "#b91c1c";
                    }
                }
            }
            hInp.addEventListener("input", calcBMI);
            wInp.addEventListener("input", calcBMI);
        }

        const formVitals = document.getElementById('form-vitals');
        if (formVitals) {
            formVitals.onsubmit = null; 

            formVitals.onsubmit = function(e) {
                e.preventDefault();

                const maPhieu = parseInt(document.getElementById('target_ma_phieu').value);
                if (!maPhieu || maPhieu <= 0) {
                    showAlert('Chưa chọn bệnh nhân hợp lệ!', 'error');
                    return;
                }

                const btnSubmit = formVitals.querySelector('button[type="submit"]');
                if (btnSubmit) btnSubmit.disabled = true;

                const bodyPayload = new URLSearchParams();
                bodyPayload.append('target_ma_phieu', maPhieu);
                bodyPayload.append('nhiet_do', document.getElementById('nhiet_do').value);
                bodyPayload.append('huyet_ap', document.getElementById('huyet_ap').value);
                // bodyPayload.append('mach', document.getElementById('mach').value);
                // bodyPayload.append('nhip_tho', document.getElementById('nhip_tho').value);
                bodyPayload.append('chieu_cao', document.getElementById('chieu_cao').value);
                bodyPayload.append('can_nang', document.getElementById('can_nang').value);
                bodyPayload.append('ly_do_kham', document.getElementById('ly_do_kham').value);
                bodyPayload.append('ghi_chu', document.getElementById('ghi_chu').value);

                fetch('src/api/saveSoKham.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyPayload.toString()
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        sessionStorage.removeItem('auto_select_ma_phieu');

                        setTimeout(() => {
                            quayLaiDanhSach();
                        }, 1500);
                    } else {
                        showAlert('Lỗi: ' + data.message, 'error');
                        if (btnSubmit) btnSubmit.disabled = false; // Mở khóa nút nếu lỗi
                    }
                })
                .catch(() => {
                    showAlert('Lỗi kết nối máy chủ.', 'error');
                    if (btnSubmit) btnSubmit.disabled = false; // Mở khóa nút nếu lỗi
                });
            };
        }
    }

    // EXPOSE FUNCTIONS TO WINDOW SCOPE
    window.quayLaiDanhSach = quayLaiDanhSach;
    window.initSelectedPatient = initSelectedPatient;

    // TỰ ĐỘNG KÍCH HOẠT KHI CHẠY TRONG ROUTER TẬP TRUNG (SPA HOẶC DIRECT LOAD)
    if (document.getElementById('patient-info-panel')) {
        initSelectedPatient();
        initSoKhamModule();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initSelectedPatient();
            initSoKhamModule();
        });
    }

    const skObserver = new MutationObserver(() => {
        const checkPanel = document.getElementById('patient-info-panel');
        if (checkPanel && !checkPanel.dataset.loaded) {
            checkPanel.dataset.loaded = '1';
            initSelectedPatient();
            initSoKhamModule();
        }
    });
    skObserver.observe(document.body, { childList: true, subtree: true });

})();
</script>
</body>
</html>