function toggleModal(modalId, shouldShow) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Không tìm thấy modal với ID:', modalId);
        return;
    }
    
    if (shouldShow) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            const avatarPreview = document.getElementById('emp-avatar-preview');
            if (avatarPreview) {
                avatarPreview.src = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
            }

            const passwordInput = document.getElementById('emp-password');
            if (passwordInput) {
                const passwordWrapper = passwordInput.closest('.form-group');
                if (passwordWrapper) passwordWrapper.style.display = 'flex';
            }

            const confirmPasswordInput = document.getElementById('emp-confirm-password');
            if (confirmPasswordInput) {
                const confirmWrapper = confirmPasswordInput.closest('.form-group');
                if (confirmWrapper) confirmWrapper.style.display = 'flex';
            }
        }
    }
}
// ==========================================
// THÊM: HÀM MỞ MODAL Ở TRẠNG THÁI "SỬA NHÂN VIÊN"
// ==========================================
function openEditUserModal(employeeId) {
    // 1. Thay đổi text tiêu đề, nút bấm và chuyển hướng Action của Form về Controller Sửa
    document.getElementById('modal-employee-title').innerText = "Cập nhật thông tin nhân viên";
    document.getElementById('btn-submit-employee').innerText = "Cập nhật";
    // document.getElementById('form-add-employee').action = "../src/controllers/NhanVienController.php";

    // 2. Tinh chỉnh và ẨN HOÀN TOÀN ô mật khẩu + ô nhập lại mật khẩu khi sửa
    const passwordInput = document.getElementById('emp-password');
    if (passwordInput) {
        passwordInput.required = false;
        const passwordWrapper = passwordInput.closest('.form-group');
        if (passwordWrapper) passwordWrapper.style.display = 'none';
    }
    
    const confirmPasswordInput = document.getElementById('emp-confirm-password');
    if (confirmPasswordInput) {
        confirmPasswordInput.required = false;
        const confirmWrapper = confirmPasswordInput.closest('.form-group');
        if (confirmWrapper) confirmWrapper.style.display = 'none';
    }
    
    // Khóa ô tên đăng nhập không cho sửa lung tung
    document.getElementById('emp-username').readOnly = true;
    document.getElementById('emp-username').style.backgroundColor = '#f1f3f5';

    // 3. Gửi yêu cầu AJAX (Fetch) lên backend lấy dữ liệu cũ của nhân viên theo ID
    fetch(`../src/api/getNhanVien.php?id=${employeeId}`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const emp = res.data;
                
                // 4. Điền dữ liệu vào các ô tương ứng trong form
                document.getElementById('emp-id').value = emp.MaNhanVien;
                document.getElementById('emp-username').value = emp.TenDangNhap;
                document.getElementById('emp-email').value = emp.Email;
                document.getElementById('emp-phone').value = emp.SoDienThoai;
                document.getElementById('emp-role').value = emp.MaVaiTro;
                document.getElementById('emp-fullname').value = emp.HoTen;
                document.getElementById('emp-idcard').value = emp.CCCD;
                document.getElementById('emp-birthdate').value = emp.NgaySinh;
                document.getElementById('emp-gender').value = emp.GioiTinh;
                document.getElementById('emp-address').value = emp.DiaChi;
                document.getElementById('emp-qualification').value = emp.BangCap;
                document.getElementById('emp-specialty').value = emp.MaChuyenKhoa;

                // Hiển thị ảnh thẻ cũ lên khung preview nếu có
                const previewImg = document.getElementById('emp-avatar-preview');
                if (previewImg) {
                    previewImg.src = emp.AnhThe ? `../public/assets/img/${emp.AnhThe}` : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
                }

                // 5. Gọi hàm toggleModal để bật Modal lên
                toggleModal('modal-add-employee', true);
            } else {
                alert("Lỗi: " + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Không thể tải thông tin nhân viên từ hệ thống.");
        });
}

// ==========================================
// THÊM: HÀM MỞ MODAL Ở TRẠNG THÁI "THÊM MỚI" 
// (Giúp dọn dẹp các chữ "Sửa" nếu trước đó vừa mở form sửa)
// ==========================================
function openAddUserModal() {
    // Trả lại các trạng thái ban đầu cho Form Thêm mới
    document.getElementById('modal-employee-title').innerText = "Thêm nhân viên mới";
    document.getElementById('btn-submit-employee').innerText = "Thêm nhân viên";
    document.getElementById('form-add-employee').action = "../src/controllers/NhanVienController.php";
    
    const passwordInput = document.getElementById('emp-password');
    if (passwordInput) {
        passwordInput.required = true;
        document.getElementById('help-password').style.display = 'none';
    }
    
    document.getElementById('emp-username').readOnly = false;
    document.getElementById('emp-username').style.backgroundColor = '#fff';
    document.getElementById('emp-id').value = "";

    // Reset sạch form rồi mở modal lên
    toggleModal('modal-add-employee', true);
}

//  Đóng modal khi click vào backdrop (vùng tối bên ngoài)
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các modal backdrop
    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
    
    modalBackdrops.forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            // Chỉ đóng khi click trực tiếp vào backdrop, không phải modal-container
            if (e.target === backdrop) {
                toggleModal(backdrop.id, false);
            }
        });
    });
    
    // Đóng modal khi nhấn phím ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Tìm modal đang mở (không có class hidden)
            const openModal = document.querySelector('.modal-backdrop:not(.hidden)');
            if (openModal) {
                toggleModal(openModal.id, false);
            }
        }
    });
});

// Preview ảnh khi chọn file
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('emp-avatar-input');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const preview = document.getElementById('emp-avatar-preview');
            const file = e.target.files[0];
            
            if (file) {
                // Kiểm tra kích thước file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Kích thước ảnh vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.');
                    e.target.value = ''; // Xóa file đã chọn
                    return;
                }
                
                // Kiểm tra định dạng file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Chỉ chấp nhận file JPG, JPEG hoặc PNG.');
                    e.target.value = '';
                    return;
                }
                
                // Hiển thị preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                // Reset về icon mặc định
                preview.src = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
            }
        });
    }
});

// mật khẩu
function togglePasswordVisibility(inputId, buttonEl) {
    const input = document.getElementById(inputId);
    const icon = buttonEl.querySelector('.material-symbols-outlined');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

function validateEmployeeForm() {
    const password = document.getElementById('emp-password').value;
    const confirmPassword = document.getElementById('emp-confirm-password').value;
    const empId = document.getElementById('emp-id').value;

    // Chỉ bắt buộc kiểm tra khớp mật khẩu khi thêm mới, hoặc khi sửa mà người dùng có nhập pass mới
    if (empId === "" || password !== "") {
        if (password !== confirmPassword) {
            alert("Lỗi: Nhập lại mật khẩu không trùng khớp với mật khẩu đã nhập!");
            return false;
        }
    }
    return true;
}

// khóa tài khoản nhân viên
function openAddUserModal() { 
        console.log('Đang kích hoạt mở modal thêm nhân viên...');
        toggleModal('modal-add-employee', true); 
}
function viewSystemLogs() { 
    console.log('Xem log hệ thống'); 
}


