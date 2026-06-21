// ==========================================
// 1. CÁC HÀM TIỆN ÍCH (MODAL & UI)
// ==========================================
function toggleModal(modalId, shouldShow) {
  const modal = document.getElementById(modalId);
  if (!modal) return;

  if (shouldShow) {
    modal.classList.remove("hidden");
    modal.style.display = "flex"; // Đảm bảo modal hiển thị đè lên màn hình
    document.body.style.overflow = "hidden";
  } else {
    modal.classList.add("hidden");
    modal.style.display = "none"; // Ẩn hoàn toàn modal
    document.body.style.overflow = "";

    const form = modal.querySelector("form");
    if (form) {
      form.reset();
      // Reset ảnh mặc định khi đóng
      const avatarPreview = document.getElementById("emp-avatar-preview");
      if (avatarPreview) {
        avatarPreview.src =
          "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
      }
    }
  }
}

function togglePasswordVisibility(inputId, buttonEl) {
  const input = document.getElementById(inputId);
  const icon = buttonEl.querySelector(".material-symbols-outlined");
  if (!input || !icon) return;
  input.type = input.type === "password" ? "text" : "password";
  icon.textContent = input.type === "text" ? "visibility_off" : "visibility";
}

// ==========================================
// 2. HÀM XỬ LÝ NHÂN VIÊN
// ==========================================
function openAddUserModal() {
  // 1. Reset form về trạng thái trống ban đầu
  const form = document.getElementById("form-add-employee");
  if (form) form.reset();

  // 2. Cấu hình lại tiêu đề và nút bấm cho chế độ THÊM
  const title = document.getElementById("modal-employee-title");
  const btn = document.getElementById("btn-submit-employee");

  if (title) title.innerText = "Thêm nhân viên mới";
  if (btn) btn.innerText = "Thêm nhân viên";
  if (form) form.action = "../src/controllers/NhanVienController.php"; // Đường dẫn từ thư mục hiện tại qua controller

  // 3. Hiển thị lại các ô nhập mật khẩu (Tránh bị ẩn từ lệnh sửa trước đó)
  const passwordInput = document.getElementById("emp-password");
  if (passwordInput) {
    passwordInput.required = true;
    const passwordWrapper = passwordInput.closest(".form-group");
    if (passwordWrapper) passwordWrapper.style.display = "block";
  }

  const confirmPasswordInput = document.getElementById("emp-confirm-password");
  if (confirmPasswordInput) {
    confirmPasswordInput.required = true;
    const confirmWrapper = confirmPasswordInput.closest(".form-group");
    if (confirmWrapper) confirmWrapper.style.display = "block";
  }

  // 4. Mở khóa ô tên đăng nhập
  const username = document.getElementById("emp-username");
  if (username) {
    username.readOnly = false;
    username.style.backgroundColor = "#fff";
  }

  document.getElementById("emp-id").value = "";

  // 5. Kích hoạt hiển thị Modal
  toggleModal("modal-add-employee", true);
}

function openEditUserModal(employeeId) {
  // 1. Reset form để dọn dữ liệu cũ
  const form = document.getElementById("form-add-employee");
  if (form) form.reset();

  // 2. Cập nhật giao diện sang chế độ SỬA
  document.getElementById("modal-employee-title").innerText =
    "Cập nhật thông tin nhân viên";
  document.getElementById("btn-submit-employee").innerText = "Cập nhật";
  document.getElementById("form-add-employee").action =
    "../src/controllers/NhanVienController.php";

  // 3. Ẩn các ô mật khẩu khi sửa (Do sửa không nhất thiết phải đổi pass luôn)
  const passwordInput = document.getElementById("emp-password");
  if (passwordInput) {
    passwordInput.required = false;
    const passwordWrapper = passwordInput.closest(".form-group");
    if (passwordWrapper) passwordWrapper.style.display = "none";
  }

  const confirmPasswordInput = document.getElementById("emp-confirm-password");
  if (confirmPasswordInput) {
    confirmPasswordInput.required = false;
    const confirmWrapper = confirmPasswordInput.closest(".form-group");
    if (confirmWrapper) confirmWrapper.style.display = "none";
  }

  // Khóa ô tên đăng nhập không cho sửa trùng dữ liệu logic
  const usernameInput = document.getElementById("emp-username");
  if (usernameInput) {
    usernameInput.readOnly = true;
    usernameInput.style.backgroundColor = "#f1f3f5";
  }

  // 4. Gửi yêu cầu AJAX lấy dữ liệu đổ vào các trường thông tin
  // Lưu ý: Đảm bảo đường dẫn file 'getNhanVien.php' chính xác từ thư mục chạy file.
  fetch(`src/api/getNhanVien.php?id=${employeeId}`)
    .then((response) => response.json())
    .then((res) => {
      if (res.success) {
        const emp = res.data;

        // 5. Điền chính xác dữ liệu trả về vào các trường dữ liệu tương ứng trong nhan-vien.php
        document.getElementById("emp-id").value = employeeId;
        document.getElementById("emp-username").value =
          emp.TenDangNhap || emp.username || "";
        document.getElementById("emp-email").value =
          emp.Email || emp.email || "";
        document.getElementById("emp-phone").value =
          emp.SoDienThoai || emp.phone || "";
        document.getElementById("emp-role").value =
          emp.MaVaiTro || emp.role_id || "";
        document.getElementById("emp-fullname").value =
          emp.HoTen || emp.name || "";
        document.getElementById("emp-idcard").value =
          emp.CCCD || emp.id_card || "";
        document.getElementById("emp-birthdate").value =
          emp.NgaySinh || emp.birthdate || "";
        document.getElementById("emp-gender").value = emp.GioiTinh || "M";
        document.getElementById("emp-address").value =
          emp.DiaChi || emp.address || "";
        document.getElementById("emp-qualification").value =
          emp.BangCap || emp.qualification || "";
        document.getElementById("emp-specialty").value =
          emp.MaChuyenMon || emp.maChuyenMon || "";

        // Hiển thị ảnh đại diện cũ
        const previewImg = document.getElementById("emp-avatar-preview");
        if (previewImg) {
          previewImg.src = emp.AnhThe
            ? `public/assets/img/${emp.AnhThe}`
            : "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 24 24' fill='%23ccc'><path d='M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z'/></svg>";
        }

        // 6. Sau khi nạp dữ liệu thành công mới bật Modal lên
        toggleModal("modal-add-employee", true);
      } else {
        showAlert("Lỗi: " + res.message);
      }
    })
    .catch((err) => {
      console.error(err);
      // Backup: Nếu API lỗi chưa kịp phản hồi, vẫn mở modal để tránh đứng giao diện người dùng
      toggleModal("modal-add-employee", true);
    });
}

// ==========================================
// 3. KHỞI TẠO SỰ KIỆN KHI TRANG TẢI XONG
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
  // Xử lý Submit Form kiểm tra mật khẩu trùng khớp
  const addForm = document.getElementById("form-add-employee");
  if (addForm) {
    addForm.addEventListener("submit", function (e) {
      const staffId = document.getElementById("emp-id").value;
      const pass = document.getElementById("emp-password")?.value;
      const confirm = document.getElementById("emp-confirm-password")?.value;

      // Chỉ bắt buộc trùng mật khẩu khi thêm mới hoặc khi sửa mà có nhập mật khẩu
      if (!staffId || pass.length > 0) {
        if (pass !== confirm) {
          e.preventDefault();
          showAlert("Lỗi: Nhập lại mật khẩu không trùng khớp!");
        }
      }
    });
  }

  // Xem trước hình ảnh (Preview) khi chọn file
  const avatarInput = document.getElementById("emp-avatar-input");
  avatarInput?.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) =>
        (document.getElementById("emp-avatar-preview").src = e.target.result);
      reader.readAsDataURL(file);
    }
  });

  // Nhấn phím ESC để tắt Modal nhanh
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      const openModal = document.querySelector(".modal-backdrop:not(.hidden)");
      if (openModal) toggleModal(openModal.id, false);
    }
  });
});
