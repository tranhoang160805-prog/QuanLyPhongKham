<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    
    exit(); 
}
?>
<link rel="stylesheet" href="public/assets/css/BenhNhan/profile.css">
<div class="profile-page-container">
    
    <div id="profile-loader" class="profile-loader">
        <div class="profile-loader__spinner"></div>
        <span class="profile-loader__text">Đang nạp dữ liệu hồ sơ bệnh nhân...</span>
    </div>

    <div id="profile-content" class="profile-content profile-content--hidden">
        <form id="form-profile-info" onsubmit="saveProfileChanges(event)">
            
            <div class="profile-header-panel">
                <div class="profile-header-panel__left">
                    <div class="profile-avatar-wrapper">
                        <div class="profile-avatar-wrapper__initial" id="avatar-initial">--</div>
                        <div class="profile-avatar-wrapper__badge">
                            <span class="material-symbols-outlined">photo_camera</span>
                        </div>
                    </div>
                    <div class="profile-user-meta">
                        <div class="profile-user-meta__row-title">
                            <h2 class="profile-user-meta__fullname" id="txt-header-fullname">--</h2>
                            <span class="profile-status-badge">
                                <span class="profile-status-badge__dot"></span> Hoạt động
                            </span>
                        </div>
                        <div class="profile-user-meta__row-sub">
                            <span class="profile-meta-item">Mã BN: <span id="txt-header-code">--</span></span>
                            <span class="profile-meta-item">Tên đăng nhập: <span id="txt-header-username">--</span></span>
                        </div>
                    </div>
                </div>
                
                <div class="profile-header-panel__actions">
                    <button type="button" id="btn-edit-mode" onclick="enableEditMode()" class="btn-profile btn-profile--primary">
                        <span class="material-symbols-outlined">edit</span> Chỉnh sửa thông tin
                    </button>
                    <button type="button" id="btn-cancel-mode" onclick="disableEditMode()" class="btn-profile btn-profile--secondary btn-profile--hidden">
                        Hủy bỏ
                    </button>
                    <button type="submit" id="btn-save-profile" class="btn-profile btn-profile--success btn-profile--hidden">
                        <span class="material-symbols-outlined">save</span> Lưu thay đổi
                    </button>
                </div>
            </div>

            <div class="profile-grid-layout">
                
                <div class="profile-grid-layout__left-col">
                    <div class="profile-card">
                        <div class="profile-card__header">
                            <h3 class="profile-card__title">
                                <span class="material-symbols-outlined icon-blue">contact_page</span> Thông tin cá nhân & Liên lạc
                            </h3>
                        </div>
                        
                        <div class="profile-form-body">
                            <div class="form-group">
                                <label class="form-group__label">Họ và tên <span class="form-group__required">*</span></label>
                                <input type="text" name="HoTen" required readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Số CCCD/CMND <span class="form-group__required">*</span></label>
                                <input type="text" name="CCCD" required readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Ngày sinh</label>
                                <input type="date" name="NgaySinh" readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Giới tính</label>
                                <select name="GioiTinh" disabled class="form-profile-input readonly-state">
                                    <option value="M">Nam</option>
                                    <option value="F">Nữ</option>
                                    <option value="O">Khác</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Số điện thoại</label>
                                <input type="text" name="SDTBenhNhan" readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Địa chỉ Email</label>
                                <input type="email" name="Email" readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Số thẻ bảo hiểm y tế (BHYT)</label>
                                <input type="text" name="SoBHYT" readonly class="form-profile-input readonly-state">
                            </div>

                            <div class="form-group">
                                <label class="form-group__label">Nhóm máu</label>
                                <select name="NhomMau" disabled class="form-profile-input readonly-state">
                                    <option value="">Chưa xác định</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>

                            <div class="address-selector-block">
                                <div class="form-group">
                                    <label class="form-group__label">Tỉnh / Thành phố</label>
                                    <select id="province" disabled class="form-profile-input form-profile-input--select-layer">
                                        <option value="">-- Chọn Tỉnh/Thành --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-group__label">Xã / Phường / Thị trấn</label>
                                    <select id="ward" disabled class="form-profile-input form-profile-input--select-layer">
                                        <option value="">-- Chọn Xã / Phường --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group--full-width">
                                <label class="form-group__label">Địa chỉ cụ thể (Số nhà, tên đường, thôn/xóm/quận/huyện...)</label>
                                <input type="text" id="detail_address" readonly class="form-profile-input readonly-state" placeholder="Ví dụ: 123 Đường Lê Lợi, Quận 1">
                                <input type="hidden" name="DiaChi" id="full_address">
                            </div>
                        </div>
                    </div>

                    <!-- <div class="clinical-history-block">
                        <div class="clinical-card clinical-card--danger">
                            <h4 class="clinical-card__title">
                                <span class="material-symbols-outlined icon-red">warning</span> Tiền sử dị ứng (Thuốc / Thức ăn)
                            </h4>
                            <textarea name="DiUng" readonly rows="2" class="form-profile-input clinical-card__textarea readonly-state" placeholder="Không có thông tin dị ứng"></textarea>
                        </div>

                        <div class="clinical-card clinical-card--info">
                            <h4 class="clinical-card__title">
                                <span class="material-symbols-outlined icon-blue">medical_information</span> Bệnh lý nền cơ bản
                            </h4>
                            <div class="clinical-card__static-info">
                                Hệ thống tự động đồng bộ từ Sổ khám bệnh điện tử khi phát sinh dịch vụ khám.
                            </div>
                        </div>
                    </div> -->
                </div>
        </form>

        <div class="profile-grid-layout__right-col">
            <div class="profile-card">
                <div class="profile-card__header">
                    <h3 class="profile-card__title">
                        <span class="material-symbols-outlined">lock_reset</span> Đổi mật khẩu tài khoản
                    </h3>
                </div>
                <form id="form-change-password" onsubmit="savePasswordChanges(event)" class="password-form-body">
                    <div class="form-group form-group--full-width">
                        <label class="form-group__label">Mật khẩu hiện tại</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="old_password" required class="password-input-wrapper__field">
                            <span onclick="togglePasswordVisibility('old_password', this)" class="material-symbols-outlined password-input-wrapper__icon">visibility</span>
                        </div>
                    </div>

                    <div class="form-group form-group--full-width">
                        <label class="form-group__label">Mật khẩu mới</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="new_password" required minlength="6" class="password-input-wrapper__field">
                            <span onclick="togglePasswordVisibility('new_password', this)" class="material-symbols-outlined password-input-wrapper__icon">visibility</span>
                        </div>
                    </div>
                    <div class="password-strength-container" style="margin-top: 5px; margin-bottom: 15px;">
    <div class="strength-meter-bg" style="background: #e5e7eb; height: 6px; width: 100%; border-radius: 3px; overflow: hidden;">
        <div id="profileStrengthBar" style="width: 0%; height: 100%; background-color: #ef4444; transition: width 0.3s ease;"></div>
    </div>
    <span id="profileStrengthText" style="font-size: 12px; color: #6b7280; display: block; margin-top: 4px;">Mật khẩu chưa nhập</span>
</div>

                    <div class="form-group form-group--full-width">
                        <label class="form-group__label">Xác nhận mật khẩu mới</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="confirm_password" required minlength="6" class="password-input-wrapper__field">
                            <span onclick="togglePasswordVisibility('confirm_password', this)" class="material-symbols-outlined password-input-wrapper__icon">visibility</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-profile btn-profile--dark btn-profile--full">
                        <span class="material-symbols-outlined">vpn_key</span> Cập nhật mật khẩu mới
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const API_BASE = 'https://provinces.open-api.vn/api/v2';
const provinceSelect = document.getElementById('province');
const wardSelect = document.getElementById('ward');
const detailAddressInp = document.getElementById('detail_address');
const fullAddressHidden = document.getElementById('full_address');

let localProfileData = {};

document.addEventListener("DOMContentLoaded", async function () {
    await initProvince2Layers();
    loadPatientProfile();
});

async function initProvince2Layers() {
    [provinceSelect, wardSelect].forEach(element => {
        if(element) element.addEventListener('change', composeFullAddress);
    });
    if(detailAddressInp) detailAddressInp.addEventListener('input', composeFullAddress);

    try {
        const response = await fetch(`${API_BASE}/p/`);
        const provinces = await response.json();
        
        provinces.sort((a, b) => a.name.localeCompare(b.name));

        provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/Thành --</option>';
        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province.code;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Không thể tải danh sách tỉnh:', error);
    }

    provinceSelect.addEventListener('change', async (e) => {
        const provinceCode = e.target.value;
        wardSelect.innerHTML = '<option value="">-- Chọn Xã / Phường --</option>';
        
        if (!provinceCode) {
            wardSelect.disabled = true;
            composeFullAddress();
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/p/${provinceCode}?depth=2`);
            const data = await response.json();
            const wards = data.wards || [];

            wards.sort((a, b) => a.name.localeCompare(b.name));

            wards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.code;
                option.textContent = ward.name;
                wardSelect.appendChild(option);
            });

            if(!provinceSelect.hasAttribute('disabled')){
                wardSelect.disabled = false;
            }
        } catch (error) {
            console.error('Không thể tải danh sách xã/phường:', error);
        }
        composeFullAddress();
    });
}

function composeFullAddress() {
    const pText = provinceSelect.selectedOptions[0]?.text || '';
    const wText = wardSelect.selectedOptions[0]?.text || '';
    const detail = detailAddressInp.value.trim();

    let arr = [];
    if (detail) arr.push(detail);
    if (wText && !wText.includes('--')) arr.push(wText);
    if (pText && !pText.includes('--')) arr.push(pText);

    fullAddressHidden.value = arr.join(', ');
}

async function parseAndSetAddress(fullAddressText) {
    if (!fullAddressText) return;
    const parts = fullAddressText.split(',').map(item => item.trim());
    
    try {
        const response = await fetch(`${API_BASE}/p/`);
        const provinces = await response.json();
        
        let matchedProvince = provinces.find(p => parts.includes(p.name));
        if (!matchedProvince) {
            detailAddressInp.value = fullAddressText;
            fullAddressHidden.value = fullAddressText;
            return;
        }

        provinceSelect.value = matchedProvince.code;

        const resWards = await fetch(`${API_BASE}/p/${matchedProvince.code}?depth=2`);
        const dataWards = await resWards.json();
        const wards = dataWards.wards || [];
        
        wards.sort((a, b) => a.name.localeCompare(b.name));

        wardSelect.innerHTML = '<option value="">-- Chọn Xã / Phường --</option>';
        wards.forEach(w => {
            const option = document.createElement('option');
            option.value = w.code;
            option.textContent = w.name;
            wardSelect.appendChild(option);
        });

        let matchedWard = wards.find(w => parts.includes(w.name));
        if (matchedWard) {
            wardSelect.value = matchedWard.code;
        }

        const skipLabels = [matchedProvince.name, matchedWard ? matchedWard.name : ''];
        const remainingParts = parts.filter(p => !skipLabels.includes(p));
        
        detailAddressInp.value = remainingParts.join(', ');
        fullAddressHidden.value = fullAddressText;

    } catch (e) {
        console.error("Gặp lỗi khi giải mã phân tách chuỗi địa chỉ: ", e);
        detailAddressInp.value = fullAddressText;
    }
}

function loadPatientProfile() {
    fetch('src/api/getThongTinBenhNhan.php?action=get_profile')
        .then(response => response.json())
        .then(async result => {
            if (result.status === 'success') {
                const data = result.data;
                localProfileData = data;

                document.getElementById('txt-header-fullname').innerText = data.HoTen || 'Chưa cập nhật';
                document.getElementById('txt-header-code').innerText = data.MaBN;
                document.getElementById('txt-header-username').innerText = data.TenDangNhap;
                
                if (data.HoTen) {
                    const parts = data.HoTen.trim().split(' ');
                    const lastWord = parts[parts.length - 1];
                    document.getElementById('avatar-initial').innerText = lastWord.substring(0, 1).toUpperCase();
                }

                const form = document.getElementById('form-profile-info');
                Object.keys(data).forEach(key => {
                    const inputElement = form.elements[key];
                    if (inputElement && key !== 'DiaChi') { 
                        inputElement.value = data[key] !== null ? data[key] : '';
                    }
                });

                await parseAndSetAddress(data.DiaChi);

                document.getElementById('profile-loader').classList.add('profile-content--hidden');
                document.getElementById('profile-content').classList.remove('profile-content--hidden');
            } else {
                showAlert('Lỗi: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            showAlert('Không thể kết nối đến máy chủ lấy thông tin.');
        });
}

function enableEditMode() {
    document.querySelectorAll('.form-profile-input').forEach(input => {
        input.removeAttribute('readonly');
        input.removeAttribute('disabled');
        input.classList.remove('readonly-state', 'form-profile-input--disabled');
    });

    if (!provinceSelect.value) {
        wardSelect.setAttribute('disabled', true);
        wardSelect.classList.add('form-profile-input--disabled');
    }

    document.getElementById('btn-edit-mode').classList.add('btn-profile--hidden');
    document.getElementById('btn-cancel-mode').classList.remove('btn-profile--hidden');
    document.getElementById('btn-save-profile').classList.remove('btn-profile--hidden');
}

function disableEditMode() {
    document.querySelectorAll('.form-profile-input').forEach(input => {
        input.setAttribute('readonly', true);
        if (input.tagName === 'SELECT') {
            input.setAttribute('disabled', true);
        }
        input.classList.add('readonly-state');
    });

    ['province', 'ward'].forEach(id => {
        document.getElementById(id).classList.add('form-profile-input--disabled');
    });

    document.getElementById('btn-edit-mode').classList.remove('btn-profile--hidden');
    document.getElementById('btn-cancel-mode').classList.add('btn-profile--hidden');
    document.getElementById('btn-save-profile').classList.add('btn-profile--hidden');

    loadPatientProfile();
}

function saveProfileChanges(event) {
    event.preventDefault();
    composeFullAddress();
    
    const form = document.getElementById('form-profile-info');
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    fetch('src/api/getThongTinBenhNhan.php?action=update_profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            showAlert(result.message);
            loadPatientProfile();
            disableEditMode();
        } else {
            showAlert('Cập nhật thất bại: ' + result.message);
        }
    })
    .catch(err => {
        console.error('Error saving data:', err);
        showAlert('Có lỗi xảy ra trong quá trình truyền dữ liệu.');
    });
}

function savePasswordChanges(event) {
    event.preventDefault();

    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if (newPassword !== confirmPassword) {
        showAlert('Mật khẩu mới và mật khẩu nhập lại xác nhận không trùng khớp.');
        return;
    }

    const payload = {
        old_password: oldPassword,
        new_password: newPassword,
        confirm_password: confirmPassword
    };

    fetch('src/api/getThongTinBenhNhan.php?action=change_password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            showAlert(result.message);
            document.getElementById('form-change-password').reset();
        } else {
            showAlert('Lỗi đổi mật khẩu: ' + result.message);
        }
    })
    .catch(err => {
        console.error('Error changing password:', err);
        showAlert('Lỗi đường truyền hệ thống khi cập nhật mật khẩu.');
    });
}

function togglePasswordVisibility(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        iconElement.innerText = "visibility_off";
    } else {
        input.type = "password";
        iconElement.innerText = "visibility";
    }
}
// Biến toàn cục để theo dõi độ mạnh mật khẩu và thông tin tài khoản hiện tại
let isProfileStrongPassword = false;
let currentProfileUsername = ''; // Sẽ được nạp khi trang tải profile thành công

// 1. LẮNG NGHE KIỂM TRA ĐỘ MẠNH MẬT KHẨU REAL-TIME
document.getElementById('new_password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('profileStrengthBar');
    const text = document.getElementById('profileStrengthText');
    let score = 0;

    if (val.length === 0) {
        bar.style.width = '0%';
        text.textContent = 'Mật khẩu chưa nhập';
        text.style.color = '#6b7280';
        isProfileStrongPassword = false;
        return;
    }

    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[a-z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^\w]/.test(val)) score++;

    if (score <= 2) {
        bar.style.width = '33%';
        bar.style.backgroundColor = '#ef4444';
        text.textContent = 'Độ mạnh: Yếu (Cần tối thiểu 8 ký tự, chữ hoa, số, ký tự đặc biệt)';
        text.style.color = '#ef4444';
        isProfileStrongPassword = false;
    } else if (score <= 4) {
        bar.style.width = '66%';
        bar.style.backgroundColor = '#f59e0b';
        text.textContent = 'Độ mạnh: Trung bình (Thêm ký tự đặc biệt hoặc chữ hoa)';
        text.style.color = '#f59e0b';
        isProfileStrongPassword = false;
    } else if (score === 5) {
        bar.style.width = '100%';
        bar.style.backgroundColor = '#10b981';
        text.textContent = 'Độ mạnh: Mạnh';
        text.style.color = '#10b981';
        isProfileStrongPassword = true;
    }
});

// 2. HÀM KHI NGƯỜI DÙNG BẤM 'CẬP NHẬT MẬT KHẨU MỚI' (Giai đoạn 1: Gửi OTP)
function changePassword(event) {
    event.preventDefault();

    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const btnSubmit = event.target.querySelector('button[type="submit"]') || document.getElementById('btnChangePwd');

    // Bước A: Kiểm tra trùng khớp mật khẩu dữ liệu đầu vào
    if (newPassword !== confirmPassword) {
        showAlert('Mật khẩu mới và mật khẩu nhập lại xác nhận không trùng khớp.', 'warning');
        return;
    }

    // Bước B: Ép kiểm tra độ mạnh giống hệt trang Đăng ký
    if (!isProfileStrongPassword) {
        showAlert('Mật khẩu mới quá yếu! Vui lòng tạo mật khẩu mạnh theo hướng dẫn thanh đo bên dưới.', 'warning');
        document.getElementById('new_password').focus();
        return;
    }

    if (oldPassword === newPassword) {
        showAlert('Mật khẩu mới không được trùng với mật khẩu cũ hiện tại.', 'warning');
        return;
    }

    if(btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = 'Đang gửi mã xác thực OTP...';
    }

    // Bước C: Gọi API gửi mã OTP (Dùng chung kiến trúc gửi tin, truyền hành động cụ thể)
    // Để có currentProfileUsername, bạn hãy gán nó tại hàm nạp thông tin bệnh nhân ban đầu (data.data.TenDangNhap)
    if (!currentProfileUsername) {
        // Dự phòng lấy từ thuộc tính ẩn hoặc tên đang hiển thị trên header profile
        const headerNameSpan = document.getElementById('txt-header-fullname');
        currentProfileUsername = headerNameSpan ? headerNameSpan.textContent.trim() : '';
    }

    const formData = new FormData();
    formData.append('action', 'send_profile_otp'); 
    formData.append('username', currentProfileUsername);

    // Bạn có thể xử lý việc gửi Mail trực tiếp trong file API phụ hoặc file hiện tại
    fetch('src/api/getThongTinBenhNhan.php?action=send_profile_otp', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(result => {
        if(btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'Cập nhật mật khẩu mới';
        }

        if (result.status === 'success') {
            // GỌI FILE MỞ MODAL XÁC THỰC OTP ĐA NĂNG
            openOtpModal(currentProfileUsername);
            showAlert(result.message || 'Mã OTP xác thực đã được gửi về Email đăng ký hồ sơ của bạn.', 'success');
        } else {
            showAlert(result.message, 'warning');
        }
    })
    .catch(err => {
        if(btnSubmit) {
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'Cập nhật mật khẩu mới';
        }
        console.error(err);
        showAlert('Lỗi kết nối máy chủ gửi mã xác thực OTP.', 'warning');
    });
}

// 3. ĐÓN NHẬN TÍN HIỆU THÀNH CÔNG TỪ OTP-HANDLER.PHP (Giai đoạn 2: Lưu mật khẩu)
document.addEventListener('otpSuccess', function(e) {
    // Kiểm tra xem có đúng luồng đang đổi mật khẩu ở trang Profile hay không
    const oldPassword = document.getElementById('old_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    if(!oldPassword || !newPassword) return; // Nếu form rỗng, bỏ qua (tránh xung đột với đăng ký)

    showAlert('Xác thực OTP chính xác! Đang tiến hành lưu cấu trúc mật khẩu mới...', 'success');

    const payload = {
        old_password: oldPassword,
        new_password: newPassword,
        confirm_password: confirmPassword
    };

    // Tiến hành gọi API chính thức cập nhật vào Database
    fetch('src/api/getThongTinBenhNhan.php?action=change_password_final', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            showAlert('Chúc mừng! ' + result.message, 'success');
            document.getElementById('form-change-password').reset();
            document.getElementById('profileStrengthBar').style.width = '0%';
            document.getElementById('profileStrengthText').textContent = 'Mật khẩu chưa nhập';
        } else {
            showAlert('Lỗi đổi mật khẩu: ' + result.message, 'warning');
        }
    })
    .catch(err => {
        console.error('Error final change password:', err);
        showAlert('Lỗi hệ thống khi thực thi cập nhật mật khẩu mới.', 'warning');
    });
});

// Lưu ý bổ sung: Khi hàm nạp dữ liệu Profile chạy thành công (Hàm fetch ban đầu của bạn khi vào trang)
// Hãy gán: currentProfileUsername = data.TenDangNhap; để JS nắm giữ chuẩn xác tên tài khoản nhé!
</script>