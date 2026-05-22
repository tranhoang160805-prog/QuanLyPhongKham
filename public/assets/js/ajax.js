// thêm nhân viên mới
document.getElementById('form-add-employee').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch('../src/controllers/NhanVienController.php', { // Kiểm tra lại đường dẫn chính xác tới file Controller của bạn
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            
            // 1. Tự động tắt form modal (Sử dụng chính hàm toggleModal của bạn)
            if (typeof toggleModal === 'function') {
                toggleModal('modal-add-employee', false);
            } else {
                document.getElementById('modal-add-employee').classList.add('hidden');
            }
            
            // 2. Reset lại các ô nhập liệu trong form cho lần sau
            document.getElementById('form-add-employee').reset();
            
            // 3. Tải lại trang tại chỗ để cập nhật danh sách nhân viên mới vào bảng
            window.location.reload(); 
        } else {
            alert('Thất bại: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra trong quá trình gửi dữ liệu!');
    });
});

// 1. HÀM LỌC VÀ TÌM KIẾM NHÂN VIÊN
function fetchFilteredEmployees() {
    const role = document.getElementById('filter-role').value;
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('search-employee').value;

    const url = `../src/api/loc-nhan-vien.php?role=${role}&status=${status}&search=${encodeURIComponent(search)}`;

    fetch(url)
        .then(response => response.json())
        .then(result => {
            const tbody = document.querySelector('.user-table tbody');
            if (!tbody) return;

            tbody.innerHTML = '';

            if (result.success && result.data.length > 0) {
                result.data.forEach(user => {
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-id', user.id);
                    
                    const capitalizedStatus = user.status.charAt(0).toUpperCase() + user.status.slice(1);
                    // Thao tác xử lý nút khóa / mở khóa động an toàn bằng cách bọc nháy đơn '${user.id}'
            let lockButtonHTML = '';
            if (user.status === 'active') {
                lockButtonHTML = `
                    <button class="btn-action-icon btn-lock" title="Khóa tài khoản" onclick="toggleUserStatus('${user.id}', 'lock', this)">
                        <span class="material-symbols-outlined" style="color: var(--color-error, #ba1a1a);">lock</span>
                    </button>
                `;
            } else {
                lockButtonHTML = `
                    <button class="btn-action-icon btn-unlock" title="Kích hoạt tài khoản" onclick="toggleUserStatus('${user.id}', 'unlock', this)">
                        <span class="material-symbols-outlined" style="color: var(--color-success, #2e7d32);">lock_open</span>
                    </button>
                `;
            }
            
            // Render dữ liệu vào dòng bảng
            tr.innerHTML = `
                <td>
                    <div class="user-profile-cell">
                        <div class="info">
                            <p class="name">${escapeHtml(user.name || user.hoTen)}</p>
                            <p class="email">${escapeHtml(user.email)}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge-role ${escapeHtml(user.role_class)}">
                        ${escapeHtml(user.role)}
                    </span>
                </td>
                <td class="date-cell">${escapeHtml(user.created_at)}</td>
                <td>
                    <div class="center-wrapper">
                        <span class="badge-status status-${escapeHtml(user.status)}">
                            <span class="dot"></span> ${capitalizedStatus}
                        </span>
                    </div>
                </td>
                <td>
                    <div class="actions-cell">
                        <button class="btn-action-icon" title="Sửa thông tin nhân viên" onclick="openEditUserModal('${user.id}')">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                                                                                    
                        ${lockButtonHTML}
                        
                        <button class="btn-action-icon" title="Đặt lại mật khẩu" onclick="resetUserPassword('${user.id}')">
                            <span class="material-symbols-outlined">lock_reset</span>
                        </button>
                    </div>
                </td>
            `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 32px; color: var(--color-on-surface-variant);">
                            Không tìm thấy nhân viên nào phù hợp.
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Lỗi khi tải bộ lọc:', error);
        });
}

function escapeHtml(text) {
    return text ? String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;") : '';
}

// LẮNG NGHE SỰ KIỆN THANH BỘ LỌC TỰ ĐỘNG
document.addEventListener("DOMContentLoaded", function() {
    const filterRole = document.getElementById('filter-role');
    const filterStatus = document.getElementById('filter-status');
    const searchEmployee = document.getElementById('search-employee');

    if (filterRole) filterRole.addEventListener('change', fetchFilteredEmployees);
    if (filterStatus) filterStatus.addEventListener('change', fetchFilteredEmployees);
    if (searchEmployee) searchEmployee.addEventListener('input', fetchFilteredEmployees);
    fetchFilteredEmployees();
});


// 2. HÀM THAO TÁC KHÓA / MỞ KHÓA TÀI KHOẢN (Gọi sang file xử lý cập nhật trạng thái)
function toggleUserStatus(id, action, btnElement) {
    // 1. Tạo lời nhắn hỏi phù hợp theo hành động
    const message = (action === 'unlock') 
        ? "Bạn có chắc chắn muốn KÍCH HOẠT lại tài khoản này không?" 
        : "Bạn có chắc chắn muốn KHÓA tài khoản của nhân viên này không?";

    // 2. Hiển thị hộp thoại Modal Confirm của trình duyệt
    if (!confirm(message)) {
        return; 
    }

    // 3. Nếu người dùng bấm "OK", hệ thống tiếp tục xử lý gửi AJAX như bình thường
    const activeValue = (action === 'unlock') ? 1 : 0;
    const url = '../src/api/trang-thai-tai-khoan.php';

    const formData = new FormData();
    formData.append('id', id);
    formData.append('is_active', activeValue);

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Cập nhật database thành công -> Tự động gọi hàm lọc để cập nhật giao diện giữ nguyên bộ lọc
            fetchFilteredEmployees();
        } else {
            alert('Lỗi từ hệ thống: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối API trạng thái:', error);
        alert('Không thể kết nối tới máy chủ xử lý.');
    });
}

// 3. HÀM ĐẶT LẠI MẬT KHẨU NHÂN VIÊN
function resetUserPassword(employeeId) {
    // 1. Gọi AJAX để lấy thông tin nhân viên theo ID từ Backend PHP
    fetch(`../src/api/getNhanVien.php?id=${employeeId}`)
        .then(response => {
            if (!response.ok) throw new Error('Kết nối API thất bại, mã lỗi: ' + response.status);
            return response.text(); 
        })
        .then(textData => {
            try {
                const result = JSON.parse(textData);
                
                // Kiểm tra thuộc tính "success" từ API của bạn
                if (result.success) {
                    
                    // Lấy object chứa thông tin nằm trong key "data" của bạn
                    const employee = result.data; 

                    // 2. Đổ dữ liệu thực tế từ API vào đúng các thẻ ID trên Modal
                    document.getElementById('modal_employee_id').value = employee.MaNhanVien; // Thay cho id
                    document.getElementById('modal_employee_name').innerText = employee.HoTen; // Thay cho full_name
                    document.getElementById('modal_employee_username').innerText = employee.TenDangNhap; // Thay cho username
                    
                    // Xử lý hiển thị ảnh thẻ (nếu có thư mục chứa ảnh thì bạn nối thêm đường dẫn vào trước, ví dụ: `../uploads/${employee.AnhThe}`)
                    const avatarPath = employee.AnhThe ? `../uploads/avatars/${employee.AnhThe}` : 'default-avatar.png'; 
                    document.getElementById('modal_employee_avatar').src = avatarPath;
                    document.getElementById('modal_employee_avatar').alt = employee.HoTen;

                    // 3. Reset lại form nhập liệu mật khẩu cũ (nếu có)
                    document.getElementById('form_reset_password').reset();
                    if (typeof checkStrength === "function") checkStrength(''); // Reset thanh đo độ mạnh

                    // 4. Hiển thị modal lên màn hình
                    toggleModal('modal-change-password', true);
                } else {
                    alert('Không thể tải thông tin nhân viên: ' + (result.message || 'Lỗi không xác định'));
                }
            } catch (jsonError) {
                console.error("Dữ liệu trả về không phải JSON chuẩn hoặc lỗi logic JS:", jsonError, textData);
                alert('Lỗi: Phản hồi từ Server không hợp lệ hoặc lỗi phân tách dữ liệu!');
            }
        })
        .catch(error => {
            console.error('Lỗi kết nối API:', error);
            alert('Đã có lỗi xảy ra khi tải dữ liệu! Hãy kiểm tra tab Network trong F12.');
        });
}