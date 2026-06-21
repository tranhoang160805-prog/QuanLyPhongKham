<?php
// 1. Phải luôn khởi động session trước khi đọc dữ liệu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h2>KẾT QUẢ KIỂM TRA SESSION HỆ THỐNG</h2>";

// 2. Kiểm tra xem user_id có tồn tại không
if (isset($_SESSION['user_id'])) {
    echo "<p>Mã người dùng hiện tại (user_id): <strong style='color: green; font-size: 20px;'>" . $_SESSION['user_id'] . "</strong></p>";
} else {
    echo "<p style='color: red;'>Cảnh báo: Chưa có ai đăng nhập hoặc Session dữ liệu đang trống!</p>";
}

echo "<hr>";
echo "<h3>Toàn bộ dữ liệu đang lưu trong $_SESSION:</h3>";
echo "<pre>";
print_r($_SESSION); // In ra toàn bộ mảng Session để xem có lưu thêm tên, quyền hạn gì không
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Địa Chính 2 Cấp (v2)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; font-weight: bold; }
        select { padding: 8px; width: 250px; border-radius: 4px; }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/src/views/layouts/alert.php'; ?>
<div class="test-card">
        <h2>Hệ Thống Kiểm Thử Thông Báo</h2>
        <p>Bấm vào các nút bên dưới để kiểm tra chức năng kích hoạt thông báo Toast (Thay thế hàm alert mặc định).</p>
        
        <div class="btn-grid">
            <button class="btn-test btn-success" onclick="showAlert('Cập nhật hồ sơ bệnh án thành công!', 'success')">
                <span class="material-symbols-outlined">check_circle</span>
                Thành công
            </button>
            
            <button class="btn-test btn-error" onclick="showAlert('Lỗi: Chưa chọn bệnh nhân hợp lệ trên hệ thống!', 'error')">
                <span class="material-symbols-outlined">cancel</span>
                Thất bại (Lỗi)
            </button>
            
            <button class="btn-test btn-warning" onclick="showAlert('Cảnh báo: Nhịp tim bệnh nhân đang vượt ngưỡng 120!', 'warning')">
                <span class="material-symbols-outlined">warning</span>
                Cảnh báo
            </button>
            
            <button class="btn-test btn-info" onclick="showAlert('Hệ thống đang tải dữ liệu lâm sàng ngầm...', 'info')">
                <span class="material-symbols-outlined">info</span>
                Thông tin
            </button>
        </div>
    </div>

    <h2>Chọn Địa Chỉ (Mô hình 2 Cấp - API v2)</h2>

    <!-- Dropdown Tỉnh Thành -->
    <div class="form-group">
        <label for="province">Tỉnh / Thành phố:</label>
        <select id="province">
            <option value="">-- Chọn Tỉnh / Thành phố --</option>
        </select>
    </div>

    <!-- Dropdown Xã Phường -->
    <div class="form-group">
        <label for="ward">Xã / Phường / TT:</label>
        <select id="ward" disabled>
            <option value="">-- Chọn Xã / Phường --</option>
        </select>
    </div>

    <script>
        const API_BASE = 'https://provinces.open-api.vn/api/v2';
        const provinceSelect = document.getElementById('province');
        const wardSelect = document.getElementById('ward');

        // 1. Tải danh sách Tỉnh/Thành phố khi trang vừa load xong
        window.addEventListener('DOMContentLoaded', async () => {
            try {
                const response = await fetch(`${API_BASE}/p/`);
                const provinces = await response.json();
                
                // Sắp xếp tên tỉnh theo bảng chữ cái ABC
                provinces.sort((a, b) => a.name.localeCompare(b.name));

                // Đổ dữ liệu vào select Tỉnh
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.code;
                    option.textContent = province.name;
                    provinceSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Không thể tải danh sách tỉnh:', error);
            }
        });

        // 2. Lắng nghe sự kiện khi người dùng chọn Tỉnh
        provinceSelect.addEventListener('change', async (e) => {
            const provinceCode = e.target.value;

            // Reset dropdown Xã/Phường về trạng thái ban đầu
            wardSelect.innerHTML = '<option value="">-- Chọn Xã / Phường --</option>';
            
            if (!provinceCode) {
                wardSelect.disabled = true;
                return;
            }

            try {
                // Gọi API lấy chi tiết tỉnh kèm theo danh sách xã (depth=2)
                const response = await fetch(`${API_BASE}/p/${provinceCode}?depth=2`);
                const data = await response.json();
                const wards = data.wards || [];

                // Sắp xếp tên xã theo bảng chữ cái ABC
                wards.sort((a, b) => a.name.localeCompare(b.name));

                // Đổ dữ liệu vào select Xã
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });

                // Kích hoạt lại dropdown Xã
                wardSelect.disabled = false;
            } catch (error) {
                console.error('Không thể tải danh sách xã/phường:', error);
            }
        });
    </script>

</body>
</html>