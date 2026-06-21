<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="public/assets/css/DuocSi/kho-thuoc.css">
<body>

<div>
    <main class="kt-main-layout">
        <div class="kt-header-section">
            <div>
                <h2 class="kt-title-h2">Quản lý kho thuốc</h2>
                <p id="txt-sub-total" class="kt-subtitle-md">Đang tải...</p>
            </div>
            
            <div class="kt-controls-group">
                <div class="kt-filter-tabs">
                    <button onclick="switchFilter('')" id="btn-filter-all" class="kt-btn-filter kt-btn-filter-active">Tất cả</button>
                    <button onclick="switchFilter('low_stock')" id="btn-filter-low_stock" class="kt-btn-filter">Sắp hết hàng</button>
                    <button onclick="switchFilter('expired')" id="btn-filter-expired" class="kt-btn-filter">Hạn sử dụng</button>
                    <button onclick="switchFilter('inactive')" id="btn-filter-inactive" class="kt-btn-filter">Đã ẩn</button>
                </div>

                <div class="kt-search-box">
                    <span class="material-symbols-outlined kt-search-icon">search</span>
                    <input type="text" id="input-search" oninput="handleSearch(this.value)" placeholder="Tìm tên, mã, hoạt chất..." class="kt-input-search">
                </div>

                <button onclick="openModalForm()" class="kt-btn-add">
                    <span class="material-symbols-outlined" style="font-size: 20px;">add_circle</span> Thêm thuốc mới
                </button>
            </div>
        </div>

        <div class="kt-stats-grid">
            <div onclick="switchFilter('')" class="kt-stat-card">
                <div class="kt-icon-circle kt-icon-primary"><span class="material-symbols-outlined">inventory_2</span></div>
                <div><p class="kt-stat-label">Tổng danh mục</p><p id="stat-total-types" class="kt-stat-number">0</p></div>
            </div>
            <div onclick="switchFilter('low_stock')" class="kt-stat-card">
                <div class="kt-icon-circle kt-icon-accent"><span class="material-symbols-outlined">trending_up</span></div>
                <div><p class="kt-stat-label">Sắp hết kho</p><p id="stat-low-stock" class="kt-stat-number">0</p></div>
            </div>
            <div onclick="switchFilter('expired')" class="kt-stat-card kt-stat-card-danger">
                <div class="kt-icon-circle kt-icon-danger"><span class="material-symbols-outlined">event_note</span></div>
                <div><p class="kt-stat-label">Sắp hết / Quá hạn</p><p id="stat-near-expired" class="kt-stat-number-danger">0</p></div>
            </div>
            <div class="kt-stat-card-static kt-stat-card">
                <div class="kt-icon-circle kt-icon-gray"><span class="material-symbols-outlined">event_busy</span></div>
                <div><p class="kt-stat-label">Đã quá hạn</p><p id="stat-expired" class="kt-stat-number">0</p></div>
            </div>
        </div>

        <div class="kt-table-container">
            <div class="kt-table-responsive">
                <table class="kt-table">
                    <thead>
                        <tr class="kt-thead-tr">
                            <th class="kt-th">Mã / Tên thuốc</th>
                            <th class="kt-th">Hoạt chất</th>
                            <th class="kt-th">Quy cách / Đơn vị</th>
                            <th class="kt-th kt-th-right">Giá bán</th>
                            <th class="kt-th kt-th-center">Tồn kho</th>
                            <th class="kt-th">Hạn sử dụng</th>
                            <th class="kt-th kt-th-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="kt-tbody"></tbody>
                </table>
            </div>
            
            <div class="kt-pagination-bar">
                <span id="txt-pagination-info" class="kt-pagination-info">Hiển thị 0 - 0 của 0 mặt hàng</span>
                <div id="pagination-controls" class="kt-pagination-controls"></div>
            </div>
        </div>
    </main>
</div>

<div id="modal-form" class="kt-modal-backdrop hidden opacity-0">
    <div class="kt-modal-content">
        <div class="kt-modal-header">
            <h3 id="modal-title" class="kt-modal-title">Thêm thuốc mới</h3>
            <button onclick="closeModalForm()" class="kt-btn-modal-close"><span class="material-symbols-outlined">close</span></button>
        </div>
        
        <form id="form-thuoc" onsubmit="handleSubmitForm(event)" class="kt-form-space">
            <input type="hidden" id="form-id" value="0">

            <div class="kt-form-grid-3">
                <div>
                    <label class="kt-form-label">Mã Thuoc Code *</label>
                    <input type="text" id="form-code" required placeholder="Ví dụ: MA-0012" class="kt-input-field">
                </div>
                <div class="kt-grid-span-2">
                    <label class="kt-form-label">Tên Thuốc *</label>
                    <input type="text" id="form-name" required placeholder="Nhập tên thuốc thương mại" class="kt-input-field">
                </div>
            </div>

            <div class="kt-form-grid-3">
                <div>
                    <label class="kt-form-label">Tên Hoạt Chất</label>
                    <input type="text" id="form-hoatchat" placeholder="Paracetamol, Amox..." class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Hàm Lượng</label>
                    <input type="text" id="form-hamluong" placeholder="500mg, 100ml..." class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Đơn Vị Tính *</label>
                    <select id="form-donvi" class="kt-input-field">
                        <option value="1">Viên</option>
                        <option value="2">Hộp</option>
                        <option value="3">Chai / Lọ</option>
                        <option value="4">Vỉ</option>
                    </select>
                </div>
            </div>

            <div class="kt-form-grid-3">
                <div>
                    <label class="kt-form-label">Dạng Bào Chế</label>
                    <input type="text" id="form-baoche" placeholder="Viên nén, siro..." class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Quy Cách Đóng Gói</label>
                    <input type="text" id="form-quycach" placeholder="Hộp 10 vỉ x 10 viên" class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Hạn Sử Dụng</label>
                    <input type="date" id="form-hansudung" class="kt-input-field">
                </div>
            </div>

            <div class="kt-form-grid-3">
                <div>
                    <label class="kt-form-label">Số Đăng Ký</label>
                    <input type="text" id="form-sdk" placeholder="VD: VD-25123-16" class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Nhà Sản Xuất</label>
                    <input type="text" id="form-nsx" placeholder="Dược Hậu Giang..." class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Nước Sản Xuất</label>
                    <input type="text" id="form-nuoc" placeholder="Việt Nam, Pháp..." class="kt-input-field">
                </div>
            </div>

            <div class="kt-form-grid-4">
                <div>
                    <label class="kt-form-label">Số Lượng Tồn</label>
                    <input type="number" id="form-ton" value="0" min="0" class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Tồn Tối Thiểu</label>
                    <input type="number" id="form-tontoithieu" value="10" min="0" class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Giá Nhập (đ)</label>
                    <input type="number" id="form-gianhap" value="0" min="0" step="0.01" class="kt-input-field">
                </div>
                <div>
                    <label class="kt-form-label">Giá Bán (đ)</label>
                    <input type="number" id="form-giaban" value="0" min="0" step="0.01" class="kt-input-field">
                </div>
            </div>

            <div>
                <label class="kt-form-label">Hướng Dẫn Sử Dụng</label>
                <textarea id="form-hdsd" rows="2" placeholder="Uống sau khi ăn..." class="kt-input-field" style="resize: vertical;"></textarea>
            </div>

            <div class="kt-form-actions">
                <button type="button" onclick="closeModalForm()" class="kt-btn-form-cancel">Hủy bỏ</button>
                <button type="submit" class="kt-btn-form-save">Lưu thông tin</button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    let currentPage = 1;
    let currentFilter = '';
    let currentSearch = '';
    const limitPerPage = 7;
    const SYSTEM_DATE = "2026-05-27";
    let cachedData = [];

    async function loadThuocData() {
        const tableBody = document.getElementById('table-body');
        if (!tableBody) return;

        try {
            const url = `src/api/getDSThuoc.php?p=${currentPage}&limit=${limitPerPage}&filter_status=${currentFilter}&search=${encodeURIComponent(currentSearch)}`;
            const response = await fetch(url);
            const resData = await response.json();

            if (!resData.success) {
                tableBody.innerHTML = `<tr><td colspan="7" class="kt-text-center-error">${resData.message}</td></tr>`;
                return;
            }

            cachedData = resData.data;

            document.getElementById('stat-total-types').innerText = resData.stats.total_thuoc_types.toLocaleString();
            document.getElementById('stat-low-stock').innerText = resData.stats.total_low_stock.toLocaleString();
            document.getElementById('stat-near-expired').innerText = resData.stats.total_near_expired.toLocaleString();
            document.getElementById('stat-expired').innerText = resData.stats.total_expired.toLocaleString();
            document.getElementById('txt-sub-total').innerText = `Tổng số ${resData.pagination.total_records.toLocaleString()} mặt hàng`;

            if (resData.data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7" class="kt-text-center-notice">Không tìm thấy dữ liệu phù hợp.</td></tr>`;
                updatePaginationControls(resData.pagination);
                return;
            }

            let rowsHtml = '';
            resData.data.forEach(thuoc => {
                let trClass = "kt-tr-normal";
                let badgeStockClass = "kt-badge-stock";
                let hsdClass = "kt-text-hsd";
                let warningIcon = "";

                if (thuoc.DangHoatDong === 0) {
                    trClass = "kt-tr-inactive";
                }

                if (thuoc.SoLuongTon <= thuoc.TonToiThieu) {
                    badgeStockClass = "kt-badge-stock-low";
                    warningIcon = `<span class="material-symbols-outlined" style="font-size: 14px;">trending_up</span>`;
                }

                if (thuoc.HanSuDung) {
                    const hsdDate = new Date(thuoc.HanSuDung);
                    const sysDate = new Date(SYSTEM_DATE);
                    const diffDays = Math.ceil((hsdDate - sysDate) / (1000 * 60 * 60 * 24));

                    if (diffDays <= 0) {
                        trClass = "kt-tr-expired";
                        hsdClass = "kt-text-hsd-danger";
                    } else if (diffDays <= 30) {
                        hsdClass = "kt-text-hsd-warning";
                    }
                }

                const formattedHSD = thuoc.HanSuDung ? thuoc.HanSuDung.split('-').reverse().join('/') : 'Không giới hạn';
                const toggleIcon = thuoc.DangHoatDong === 1 ? 'visibility_off' : 'visibility';
                const toggleTitle = thuoc.DangHoatDong === 1 ? 'Ẩn thuốc' : 'Hiện thuốc';
                const toggleBtnColor = thuoc.DangHoatDong === 1 ? 'kt-btn-toggle-on' : 'kt-btn-toggle-off';

                rowsHtml += `
                    <tr class="${trClass}">
                        <td class="kt-td">
                            <div class="kt-flex-col-cell">
                                <span class="kt-text-name">${thuoc.TenThuoc}</span>
                                <span class="kt-text-code">${thuoc.MaThuocCode}</span>
                            </div>
                        </td>
                        <td class="kt-td"><span class="kt-text-body">${thuoc.TenHoatChat || '---'}</span></td>
                        <td class="kt-td">
                            <div class="kt-flex-col-cell">
                                <span class="kt-text-body">${thuoc.QuyCach || '---'}</span>
                                <span class="kt-text-code" style="font-style: italic;">${thuoc.DangBaoChe || '---'} (${thuoc.TenDonVi})</span>
                            </div>
                        </td>
                        <td class="kt-td kt-td-right"><span class="kt-text-name">${thuoc.GiaBan.toLocaleString('vi-VN')} đ</span></td>
                        <td class="kt-td kt-td-center">
                            <div class="${badgeStockClass}">
                                ${thuoc.SoLuongTon.toLocaleString()} ${warningIcon}
                            </div>
                        </td>
                        <td class="kt-td"><span class="${hsdClass}">${formattedHSD}</span></td>
                        <td class="kt-td">
                            <div class="kt-action-wrapper">
                                <button data-edit-id="${thuoc.MaThuoc}" title="Sửa thông tin" class="btn-edit-thuoc kt-btn-action-edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                                <button data-toggle-id="${thuoc.MaThuoc}" data-status="${thuoc.DangHoatDong}" title="${toggleTitle}" class="btn-toggle-thuoc kt-btn-action-toggle ${toggleBtnColor}">
                                    <span class="material-symbols-outlined">${toggleIcon}</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tableBody.innerHTML = rowsHtml;
            updatePaginationControls(resData.pagination);
            attachRowEventListeners();

        } catch (error) {
            tableBody.innerHTML = `<tr><td colspan="7" class="kt-text-center-error">Không kết nối được API dữ liệu.</td></tr>`;
        }
    }

    function updatePaginationControls(pagination) {
        const fromRecord = pagination.total_records > 0 ? (pagination.current_page - 1) * pagination.limit + 1 : 0;
        const toRecord = Math.min(pagination.current_page * pagination.limit, pagination.total_records);
        document.getElementById('txt-pagination-info').innerText = `Hiển thị ${fromRecord} - ${toRecord} của ${pagination.total_records.toLocaleString()} mặt hàng`;

        const container = document.getElementById('pagination-controls');
        if (!container) return;
        container.innerHTML = '';

        const prevBtn = document.createElement('button');
        prevBtn.className = `kt-btn-page-nav`;
        if (pagination.current_page <= 1) {
            prevBtn.disabled = true;
        }
        prevBtn.innerHTML = `<span class="material-symbols-outlined">chevron_left</span>`;
        if (pagination.current_page > 1) prevBtn.onclick = () => { currentPage--; loadThuocData(); };
        container.appendChild(prevBtn);

        for (let i = 1; i <= pagination.total_pages; i++) {
            if (i === 1 || i === pagination.total_pages || (i >= pagination.current_page - 1 && i <= pagination.current_page + 1)) {
                const pageBtn = document.createElement('button');
                pageBtn.className = i === pagination.current_page ? "kt-btn-page-num kt-btn-page-num-active" : "kt-btn-page-num";
                pageBtn.innerText = i;
                pageBtn.onclick = () => { currentPage = i; loadThuocData(); };
                container.appendChild(pageBtn);
            } else if (i === 2 || i === pagination.total_pages - 1) {
                const dot = document.createElement('span');
                dot.className = "kt-page-dots";
                dot.innerText = "...";
                container.appendChild(dot);
            }
        }

        const nextBtn = document.createElement('button');
        nextBtn.className = `kt-btn-page-nav`;
        if (pagination.current_page >= pagination.total_pages) {
            nextBtn.disabled = true;
        }
        nextBtn.innerHTML = `<span class="material-symbols-outlined">chevron_right</span>`;
        if (pagination.current_page < pagination.total_pages) nextBtn.onclick = () => { currentPage++; loadThuocData(); };
        container.appendChild(nextBtn);
    }

    function openModalForm() {
        document.getElementById('form-thuoc').reset();
        document.getElementById('form-id').value = "0";
        document.getElementById('modal-title').innerText = "Thêm thuốc mới";
        
        const modal = document.getElementById('modal-form');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeModalForm() {
        const modal = document.getElementById('modal-form');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function handleEditClick(maThuoc) {
        const thuoc = cachedData.find(item => item.MaThuoc === maThuoc);
        if (!thuoc) return;

        openModalForm();
        document.getElementById('modal-title').innerText = "Cập nhật thông tin thuốc";
        
        document.getElementById('form-id').value = thuoc.MaThuoc;
        document.getElementById('form-code').value = thuoc.MaThuocCode;
        document.getElementById('form-name').value = thuoc.TenThuoc;
        document.getElementById('form-hoatchat').value = thuoc.TenHoatChat || '';
        document.getElementById('form-hamluong').value = thuoc.HamLuong || '';
        document.getElementById('form-baoche').value = thuoc.DangBaoChe || '';
        document.getElementById('form-quycach').value = thuoc.QuyCach || '';
        document.getElementById('form-hansudung').value = thuoc.HanSuDung || '';
        document.getElementById('form-ton').value = thuoc.SoLuongTon;
        document.getElementById('form-tontoithieu').value = thuoc.TonToiThieu;
        document.getElementById('form-giaban').value = thuoc.GiaBan;
        
        document.getElementById('form-sdk').value = thuoc.SoDangKy || '';
        document.getElementById('form-nsx').value = thuoc.NhaSanXuat || '';
        document.getElementById('form-nuoc').value = thuoc.NuocSanXuat || '';
        document.getElementById('form-gianhap').value = thuoc.GiaNhap || 0;
        document.getElementById('form-hdsd').value = thuoc.HuongDanSuDung || '';
    }

    async function handleSubmitForm(e) {
        e.preventDefault();
        
        const payload = {
            action: 'save',
            MaThuoc: parseInt(document.getElementById('form-id').value),
            MaThuocCode: document.getElementById('form-code').value,
            TenThuoc: document.getElementById('form-name').value,
            TenHoatChat: document.getElementById('form-hoatchat').value,
            HamLuong: document.getElementById('form-hamluong').value,
            MaDonVi: document.getElementById('form-donvi').value,
            DangBaoChe: document.getElementById('form-baoche').value,
            QuyCach: document.getElementById('form-quycach').value,
            HanSuDung: document.getElementById('form-hansudung').value,
            SoDangKy: document.getElementById('form-sdk').value,
            NhaSanXuat: document.getElementById('form-nsx').value,
            NuocSanXuat: document.getElementById('form-nuoc').value,
            SoLuongTon: document.getElementById('form-ton').value,
            TonToiThieu: document.getElementById('form-tontoithieu').value,
            GiaNhap: document.getElementById('form-gianhap').value,
            GiaBan: document.getElementById('form-giaban').value,
            HuongDanSuDung: document.getElementById('form-hdsd').value
        };

        try {
            const response = await fetch('src/api/saveThuoc.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            
            if (result.success) {
                if (result.message === 'insert_success') {
                    showAlert("Thêm mới dữ liệu thuốc vào hệ thống thành công!", "success");
                } else if (result.message === 'update_success') {
                    showAlert("Đã cập nhật thông tin thuốc thành công!", "success");
                } else {
                    showAlert("Thao tác thành công!", "success");
                }
                
                closeModalForm();
                loadThuocData();
            } else {
                if (result.message.includes('Mã thuốc code')) {
                    showAlert("Lỗi: Mã thuốc và tên thuốc không được để trống!", "error");
                } else {
                    showAlert("Thao tác thất bại: " + result.message, "error");
                }
            }
        } catch (error) {
            showAlert("Lỗi kết nối: Không thể gửi dữ liệu đến máy chủ.", "warning");
        }
    }

    async function handleToggleStatus(maThuoc, currentStatus) {
        const newStatus = currentStatus === 1 ? 0 : 1;
        const confirmMsg = newStatus === 0 ? "Bạn chắc chắn muốn ẩn mặt hàng này?" : "Bạn muốn cho hiển thị hoạt động lại mặt hàng này?";
        
        if (!confirm(confirmMsg)) return;

        try {
            const response = await fetch('src/api/saveThuoc.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'toggle_status',
                    MaThuoc: maThuoc,
                    DangHoatDong: newStatus
                })
            });
            const result = await response.json();
            
            if (result.success) {
                if (newStatus === 0) {
                    showAlert("Đã ẩn thành công mặt hàng thuốc này khỏi danh mục hoạt động.", "info");
                } else {
                    showAlert("Đã mở hiển thị hoạt động trở lại cho thuốc này.", "info");
                }
                loadThuocData();
            } else {
                showAlert("Không thể thực hiện cập nhật trạng thái: " + result.message, "warning");
            }
        } catch (error) {
            showAlert("Lỗi hệ thống: Mất kết nối trạng thái.", "error");
        }
    }

    function switchFilter(filterName) {
        currentFilter = filterName;
        currentPage = 1;
        const filters = ['', 'low_stock', 'expired', 'inactive'];
        filters.forEach(f => {
            const btn = document.getElementById(`btn-filter-${f || 'all'}`);
            if (btn) {
                if (f === filterName) {
                    btn.className = "kt-btn-filter kt-btn-filter-active";
                } else {
                    btn.className = "kt-btn-filter";
                }
            }
        });
        loadThuocData();
    }

    let searchTimeout;
    function handleSearch(val) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentSearch = val.trim(); currentPage = 1; loadThuocData(); }, 400);
    }

    function attachRowEventListeners() {
        document.querySelectorAll('.btn-edit-thuoc').forEach(btn => {
            btn.onclick = function() { handleEditClick(parseInt(this.getAttribute('data-edit-id'))); };
        });
        document.querySelectorAll('.btn-toggle-thuoc').forEach(btn => {
            btn.onclick = function() { 
                handleToggleStatus(
                    parseInt(this.getAttribute('data-toggle-id')), 
                    parseInt(this.getAttribute('data-status'))
                ); 
            };
        });
    }

    window.openModalForm = openModalForm;
    window.closeModalForm = closeModalForm;
    window.handleSubmitForm = handleSubmitForm;
    window.switchFilter = switchFilter;
    window.handleSearch = handleSearch;

    loadThuocData();
})();
</script>
</body>
</html>