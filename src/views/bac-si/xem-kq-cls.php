<link rel="stylesheet" href="public/assets/css/BacSi/xem-kq-cls.css">
<div id="list-panel" class="cls-panel">
    <div class="cls-panel-header">
        <h3 class="cls-panel-header-title">
            <span class="material-symbols-outlined">pending_actions</span>
            Danh sách kết quả ôm nay
        </h3>
        
        <div class="cls-search-wrapper">
            <input type="text" id="instant-search" placeholder="Gõ tên BN, loại CLS, mã phiếu..." class="cls-search-input">
            <span class="material-symbols-outlined cls-search-icon">search</span>
        </div>
    </div>
    
    <div class="cls-table-responsive">
        <table class="cls-table" id="test-table">
            <thead class="cls-thead">
                <tr>
                    <th class="cls-th cls-th-stt">STT</th>
                    <th class="cls-th cls-th-code">Mã Phiếu</th>
                    <th class="cls-th cls-th-patient">Bệnh nhân</th>
                    <th class="cls-th">Các dịch vụ chỉ định</th>
                    <th class="cls-th cls-th-time">Thời gian chỉ định</th>
                    <th class="cls-th cls-th-action">Hành động</th>
                </tr>
            </thead>
            <tbody id="table-content" class="cls-tbody">
                <tr id="loading-row">
                    <td colspan="6" class="cls-table-status-cell">
                        Đang tải và xử lý đồng bộ danh sách chỉ định...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="diagnosis-panel" class="hidden cls-panel cls-panel-mt">
    <div class="cls-panel-header">
        <h3 class="cls-panel-header-title">
            <span class="material-symbols-outlined">edit_note</span>
            Nhập Chẩn Đoán Cận Lâm Sàng
        </h3>
    </div>
    
    <div class="cls-diagnosis-body">
        <div class="cls-patient-info-grid">
            <div><span class="cls-info-label">Bệnh nhân:</span> <strong id="diag-patient-name" class="cls-info-value">-</strong></div>
            <div><span class="cls-info-label">Ngày sinh:</span> <strong id="diag-patient-dob" class="cls-info-value">-</strong></div>
            <div><span class="cls-info-label">Mã Phiếu Khám:</span> <strong id="diag-ticket-code" class="cls-info-value cls-info-value-code">-</strong></div>
            <div><span class="cls-info-label">Người thực hiện</span> <strong id="diag-patient-nv" class="cls-info-value">-</strong></div>
        </div>

        <div style="margin-bottom: 24px;">
            <label class="cls-section-label">
                <span class="material-symbols-outlined cls-section-label-icon">segment</span>
                Chọn dịch vụ CLS đã hoàn thành để xem kết quả:
            </label>
            <div id="cls-horizontal-tabs" class="cls-tabs-container"></div>

            <div id="cls-detail-viewer" class="cls-detail-viewer-box hidden">
                <div class="cls-detail-grid">
                    <div class="cls-detail-text-col">
                        <div>
                            <span class="cls-sub-title-text">Kết quả chi tiết:</span>
                            <div id="view-cls-text" class="cls-text-container cls-text-result-box">-</div>
                        </div>
                        <div>
                            <span class="cls-sub-title-conclusion">Kết luận chuyên khoa:</span>
                            <div id="view-cls-conclusion" class="cls-text-container cls-text-conclusion-box">-</div>
                        </div>
                    </div>
                    
                    <div class="cls-detail-image-col" id="view-cls-image-wrapper">
                        <span class="cls-sub-title-text" style="margin-bottom: 4px;">Hình ảnh đính kèm (Bấm vào để phóng to):</span>
                        <div onclick="zoomImage()" class="cls-image-preview-container">
                            <img id="view-cls-image" src="" alt="Hình ảnh kết quả CLS" class="cls-image-preview-element">
                            <div class="cls-image-overlay">
                                <span class="material-symbols-outlined cls-image-overlay-icon">zoom_in</span> Xem ảnh lớn
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="cls-empty-alert" class="cls-empty-alert-box hidden">
                Chưa có dịch vụ cận lâm sàng nào trong phiếu khám này hoàn tất xét nghiệm.
            </div>
        </div>

        <div id="diagnosis-form-area" class="cls-form-area">
            <div class="cls-form-group">
                <label class="cls-form-label">Kết luận / Chẩn đoán của Bác sĩ:</label>
                <textarea id="doctor-conclusion" rows="4" class="cls-textarea" placeholder="Nhập kết luận chẩn đoán lâm sàng tổng hợp từ các kết quả xét nghiệm..."></textarea>
            </div>
            
            <div class="cls-action-btn-group" id="action-buttons-group"></div>
        </div>
    </div>
</div>

<div id="image-lightbox" class="fixed cls-lightbox hidden" onclick="closeZoomImage()">

    <button class="cls-lightbox-close-btn" onclick="closeZoomImage()">
        <span class="material-symbols-outlined class=cls-lightbox-close-icon">close</span>
    </button>
    <div class="cls-lightbox-content" onclick="event.stopPropagation()">
        <img id="lightbox-target-img" src="" alt="Ảnh cận lâm sàng phóng lớn" class="cls-lightbox-img">
    </div>
</div>

<script>
(function initKqClsComponent() {
    const tableContent = document.getElementById('table-content');
    const searchInput = document.getElementById('instant-search');
    
    let localGroupedData = {};

    function formatDateTime(dateStr) {
        if(!dateStr) return '';
        const d = new Date(dateStr);
        return `${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')} ${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth()+1).padStart(2, '0')}/${d.getFullYear()}`;
    }

    function formatDate(dateStr) {
        if(!dateStr) return '';
        const d = new Date(dateStr);
        return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth()+1).padStart(2, '0')}/${d.getFullYear()}`;
    }

    function groupDataByPhieuKham(rawData) {
        const groups = {};
        rawData.forEach(item => {
            const key = item.MaPhieuKham;
            if (!groups[key]) {
                groups[key] = {
                    MaPhieuKham: item.MaPhieuKham,
                    MaPhieuKhamCode: item.MaPhieuKhamCode,
                    HoTen: item.HoTen,
                    MaBN: item.MaBN,
                    NgaySinh: item.NgaySinh,
                    NgayChiDinh: item.NgayChiDinh,
                    ChanDoan: item.ChanDoan || '', 
                    DichVus: []
                };
            }
            groups[key].DichVus.push({
                MaChiDinh: item.MaChiDinh,
                TenLoaiCLS: item.TenLoaiCLS,
                MaLoaiCLS: item.MaLoaiCLS,
                TrangThaiChiDinh: parseInt(item.TrangThaiChiDinh), 
                MoTaChiDinh: item.MoTaChiDinh,
                HasKetQua: item.MaKetQua ? true : false,
                KetQuaText: item.KetQuaText || 'Chưa có dữ liệu chi tiết',
                KetLuan: item.KetLuan || 'Chưa có kết luận từ phòng CLS',
                FileKetQua: item.FileKetQua || '', 
                TenNhanVienThucHien: item.TenNhanVienThucHien || 'Chưa rõ'
            });
        });
        localGroupedData = groups; 
        return Object.values(groups);
    }

    function renderTable(data) {
        if (!tableContent) return;
        if (data.length === 0) {
            tableContent.innerHTML = `
                <tr>
                    <td colspan="6" class="cls-table-status-cell">
                        Không có phiếu khám nào đang ở trạng thái xét nghiệm hôm nay.
                    </td>
                </tr>`;
            return;
        }

        let html = '';
        data.forEach((group, index) => {
            let clsBadgesHtml = '<div class="cls-badge-container">';
            let textSearchDichVu = ''; 
            
            group.DichVus.forEach(cls => {
                textSearchDichVu += ' ' + cls.TenLoaiCLS;
                const isDone = (cls.TrangThaiChiDinh === 2);
                const bgClass = isDone 
                    ? 'cls-badge-done' 
                    : 'cls-badge-pending';
                
                const icon = isDone ? 'check_circle' : 'hourglass_empty';
                clsBadgesHtml += `
                    <span class="cls-badge ${bgClass}" title="${cls.MoTaChiDinh ? 'Ghi chú: ' + cls.MoTaChiDinh : 'Không có ghi chú'}">
                        <span class="material-symbols-outlined cls-badge-icon">${icon}</span>
                        ${cls.TenLoaiCLS}
                    </span>`;
            });
            clsBadgesHtml += '</div>';

            html += `
                <tr class="data-row">
                    <td class="cls-td cls-td-stt stt-cell">${index + 1}</td>
                    <td class="cls-td cls-td-code search-target">#${group.MaPhieuKhamCode || ''}</td>
                    <td class="cls-td">
                        <span class="cls-patient-name search-target">${group.HoTen || ''}</span>
                        <span class="cls-patient-sub">NS: ${formatDate(group.NgaySinh)} | Mã BN: ${group.MaBN || ''}</span>
                    </td>
                    <td class="cls-td">
                        ${clsBadgesHtml}
                        <span class="hidden search-target">${textSearchDichVu}</span>
                    </td>
                    <td class="cls-td cls-td-time">${formatDateTime(group.NgayChiDinh)}</td>
                    <td class="cls-td cls-td-action">
                        <button onclick="openDiagnosisWorkspace('${group.MaPhieuKham}')" class="cls-btn-primary">
                            <span class="material-symbols-outlined" style="font-size: 14px;">edit_note</span> Chẩn đoán
                        </button>
                    </td>
                </tr>`;
        });
        
        html += `<tr id="no-search-results" class="hidden"><td colspan="6" class="cls-table-status-cell">Không tìm thấy kết quả phù hợp.</td></tr>`;
        tableContent.innerHTML = html;
    }

    function loadData() {
        fetch('src/api/getDSKetQuaCLS.php')
            .then(res => res.json())
            .then(resData => {
                if (resData.status === 'success') {
                    const groupedData = groupDataByPhieuKham(resData.data);
                    renderTable(groupedData);
                } else {
                    tableContent.innerHTML = `<tr><td colspan="6" class="cls-table-error-cell">${resData.message}</td></tr>`;
                }
            })
            .catch(err => {
                tableContent.innerHTML = `<tr><td colspan="6" class="cls-table-error-cell">Không thể kết nối tới máy chủ API.</td></tr>`;
            });
    }

    function switchClsDetailView(clsItem, activeBtn) {
        const viewer = document.getElementById('cls-detail-viewer');
        if(!clsItem) {
            viewer.classList.add('hidden');
            return;
        }

        const buttons = document.querySelectorAll('.cls-tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('cls-tab-btn-active');
        });
        if(activeBtn) {
            activeBtn.classList.add('cls-tab-btn-active');
        }

        document.getElementById('view-cls-text').textContent = clsItem.KetQuaText;
        document.getElementById('view-cls-conclusion').textContent = clsItem.KetLuan;

        const imgWrapper = document.getElementById('view-cls-image-wrapper');
        const imgTag = document.getElementById('view-cls-image');

        if(clsItem.FileKetQua.trim() !== '') {
            imgTag.src = `uploads/ket-qua-cls/${clsItem.FileKetQua}`;
            imgWrapper.classList.remove('hidden');
        } else {
            imgTag.src = '';
            imgWrapper.classList.add('hidden');
        }

        viewer.classList.remove('hidden');
    }

    window.zoomImage = function() {
        const currentSrc = document.getElementById('view-cls-image').src;
        if(!currentSrc) return;
        document.getElementById('lightbox-target-img').src = currentSrc;
        document.getElementById('image-lightbox').classList.remove('hidden');
    };

    window.closeZoomImage = function() {
        document.getElementById('image-lightbox').classList.add('hidden');
        document.getElementById('lightbox-target-img').src = '';
    };

    window.openDiagnosisWorkspace = function(maPhieuKham) {
        const targetData = localGroupedData[maPhieuKham];
        if(!targetData) return;

        document.getElementById('diag-patient-name').textContent = targetData.HoTen;
        document.getElementById('diag-ticket-code').textContent = targetData.MaPhieuKhamCode;
        document.getElementById('diag-patient-dob').textContent = formatDate(targetData.NgaySinh);
        document.getElementById('diag-patient-nv').textContent = targetData.TenNhanVienThucHien;
        document.getElementById('doctor-conclusion').value = targetData.ChanDoan; 

        const completedCLS = targetData.DichVus.filter(d => d.TrangThaiChiDinh === 2);
        const totalCLSCount = targetData.DichVus.length;

        const tabsContainer = document.getElementById('cls-horizontal-tabs');
        const emptyAlert = document.getElementById('cls-empty-alert');
        
        tabsContainer.innerHTML = '';
        
        if(completedCLS.length === 0) {
            tabsContainer.classList.add('hidden');
            emptyAlert.classList.remove('hidden');
            switchClsDetailView(null, null);
        } else {
            tabsContainer.classList.remove('hidden');
            emptyAlert.classList.add('hidden');

            completedCLS.forEach((cls, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = "cls-tab-btn";
                btn.innerHTML = `<span class="material-symbols-outlined" style="font-size: 14px;">done</span> ${cls.TenLoaiCLS}`;
                
                btn.onclick = function() {
                    switchClsDetailView(cls, btn);
                };
                tabsContainer.appendChild(btn);
            });

            switchClsDetailView(completedCLS[0], tabsContainer.firstElementChild);
        }

        const isAllDone = (completedCLS.length === totalCLSCount);
        const actionBtnGroup = document.getElementById('action-buttons-group');
        
        let buttonsHtml = `
            <button onclick="backToLeveL1()" class="cls-btn-back">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span> Quay lại
            </button>
        `;

        if (isAllDone && totalCLSCount > 0) {
            buttonsHtml += `
                <button onclick="submitDiagnosisAndPrescribe('${maPhieuKham}')" class="cls-btn-primary cls-btn-pulse">
                    <span class="material-symbols-outlined" style="font-size: 16px;">medical_information</span> Kê Đơn Thuốc <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span>
                </button>
            `;
        } else {
            buttonsHtml += `
                <div class="cls-info-text-status">
                    <span class="material-symbols-outlined cls-info-text-icon">info</span> Cần đợi hoàn thành đủ ${totalCLSCount} chỉ định để mở chức năng Kê đơn.
                </div>
            `;
        }
        actionBtnGroup.innerHTML = buttonsHtml;

        document.getElementById('list-panel').classList.add('hidden');
        document.getElementById('diagnosis-panel').classList.remove('hidden');
    };

    // TÁI SỬ DỤNG API saveSoKham.php ĐỂ UPDATE CHẨN ĐOÁN
    window.submitDiagnosisAndPrescribe = function(maPhieuKham) {
        const textChanDoan = document.getElementById('doctor-conclusion').value.trim();
        
        if(!textChanDoan) {
            if(typeof showAlert === 'function') {
                showAlert('Vui lòng nhập kết luận / chẩn đoán trước khi tiến hành kê đơn thuốc!', 'error');
            } else {
                showAlert('Vui lòng nhập kết luận / chẩn đoán trước khi tiến hành kê đơn thuốc!');
            }
            return;
        }

        fetch('src/api/saveSoKham.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                target_ma_phieu: maPhieuKham,
                chan_doan: textChanDoan
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success === true) {
                if(typeof showAlert === 'function') {
                    showAlert('Cập nhật chẩn đoán thành công. Hệ thống đang chuyển hướng!', 'success');
                } else {
                    showAlert('Cập nhật chẩn đoán thành công!');
                }
                
                setTimeout(() => {
                    window.location.href = `index.php?workspace=1&page=cap-thuoc-bs&ma_phieu=${maPhieuKham}`;
                }, 1000);
            } else {
                if(typeof showAlert === 'function') {
                    showAlert('Lỗi: ' + data.message, 'error');
                } else {
                    showAlert('Có lỗi xảy ra: ' + data.message);
                }
            }
        })
        .catch(err => {
            console.error(err);
            if(typeof showAlert === 'function') {
                showAlert('Không thể kết nối đến máy chủ để lưu chẩn đoán!', 'error');
            } else {
                showAlert('Không thể kết nối đến máy chủ để lưu chẩn đoán!');
            }
        });
    };

    window.backToLeveL1 = function() {
        document.getElementById('diagnosis-panel').classList.add('hidden');
        document.getElementById('list-panel').classList.remove('hidden');
        document.getElementById('doctor-conclusion').value = ''; 
        switchClsDetailView(null, null);
        loadData(); 
    };

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const rows = document.querySelectorAll('#test-table .data-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const targets = row.querySelectorAll('.search-target');
                let match = false;
                
                targets.forEach(target => {
                    const text = target.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (text.includes(keyword)) {
                        match = true;
                    }
                });

                if (match) {
                    row.classList.remove('hidden');
                    visibleCount++;
                    row.querySelector('.stt-cell').textContent = visibleCount; 
                } else {
                    row.classList.add('hidden');
                }
            });

            const noResultsRow = document.getElementById('no-search-results');
            if (noResultsRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.classList.remove('hidden');
                } else {
                    noResultsRow.classList.add('hidden');
                }
            }
        });
    }

    loadData();
})();
</script>