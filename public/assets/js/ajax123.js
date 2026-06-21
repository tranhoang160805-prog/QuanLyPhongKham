// ==========================================
// THÀNH PHẦN TOÀN CỤC CÓ THỂ GỌI TỪ MỌI NƠI (KỂ CẢ KHI CHUYỂN TRANG BẰNG AJAX)
// ==========================================

// 1. Hàm chống XSS cho các chuỗi HTML động
function escapeHtml(text) {
  if (!text) return "";
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.toString().replace(/[&<>"']/g, (m) => map[m]);
}

// 2. Hàm lọc và tìm kiếm nhân viên (Đưa ra ngoài phạm vi DOMContentLoaded)
function fetchFilteredEmployees() {
  const filterRoleEl = document.getElementById("filter-role");
  const filterStatusEl = document.getElementById("filter-status");
  const searchEmployeeEl = document.getElementById("search-employee");

  // Nếu trang hiện tại không có các bộ lọc này, hủy xử lý ngầm (tránh lỗi null)
  if (!filterRoleEl || !filterStatusEl || !searchEmployeeEl) return;

  const role = filterRoleEl.value;
  const status = filterStatusEl.value;
  const search = searchEmployeeEl.value;

  const url = `src/api/getNhanVienList.php?role=${role}&status=${status}&search=${encodeURIComponent(search)}`;

  fetch(url)
    .then((response) => response.json())
    .then((result) => {
      const gridSection = document.querySelector(".employee-grid");
      if (!gridSection) return;

      gridSection.innerHTML = ""; // Xóa nội dung cũ

      if (result.success && result.data.length > 0) {
        result.data.forEach((user) => {
          const is_active = user.status === "active";
          const status_color = is_active ? "#2e7d32" : "#f57c00";

          const card = document.createElement("div");
          card.className = "employee-card";
          card.innerHTML = `
                        <div class="card-body">
                            <div class="card-header">
                                <div class="avatar-wrapper">
                                    <img src="public/assets/img/${escapeHtml(user.avatar)}" alt="Avatar">
                                    <span class="status-dot" style="background: ${status_color};"></span>
                                </div>
                                <span class="role-badge">${escapeHtml(user.role)}</span>
                            </div>
                            
                            <h3 style="margin: 0 0 5px 0; font-size: 1.1rem;">${escapeHtml(user.name || user.username)}</h3>
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #444;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">call</span>
                                <span>${escapeHtml(user.phone || "Chưa cập nhật")}</span>
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #444;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">mail</span>
                                <span>${escapeHtml(user.email || "Chưa cập nhật")}</span>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn-grid" onclick="openEditUserModal('${user.staff_id || user.id}')">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btn-grid" onclick="toggleUserStatus('${user.id}', '${is_active ? "lock" : "unlock"}', this)">
                                <span class="material-symbols-outlined">${is_active ? "lock" : "lock_open"}</span> 
                            </button>
                            <button class="btn-grid" title="Đặt lại mật khẩu" onclick="resetUserPassword('${user.staff_id || user.id}')">
                                <span class="material-symbols-outlined">lock_reset</span>
                            </button>
                        </div>
                    `;
          gridSection.appendChild(card);
        });
      } else {
        gridSection.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                        Không tìm thấy nhân viên nào phù hợp.
                    </div>
                `;
      }
    })
    .catch((error) => {
      console.error("Lỗi khi tải bộ lọc nhân viên:", error);
    });
}

// 3. Hàm kích hoạt/ràng buộc sự kiện khi trang nhân viên được render xong
function initEmployeePageEvents() {
  const filterRole = document.getElementById("filter-role");
  const filterStatus = document.getElementById("filter-status");
  const searchEmployee = document.getElementById("search-employee");

  if (filterRole) filterRole.addEventListener("change", fetchFilteredEmployees);
  if (filterStatus)
    filterStatus.addEventListener("change", fetchFilteredEmployees);
  if (searchEmployee)
    searchEmployee.addEventListener("input", fetchFilteredEmployees);

  // Gọi nạp dữ liệu lần đầu cho trang
  fetchFilteredEmployees();
}

// Đưa hàm khởi tạo lên đối tượng window cao nhất để script chuyển trang AJAX của bạn có thể gọi lại
window.initEmployeePageEvents = initEmployeePageEvents;

// 4. Thao tác khóa / mở khóa tài khoản
function toggleUserStatus(id, action, btnElement) {
  const message =
    action === "unlock"
      ? "Bạn có chắc chắn muốn KÍCH HOẠT lại tài khoản này không?"
      : "Bạn có chắc chắn muốn KHÓA tài khoản của nhân viên này không?";

  if (!confirm(message)) return;

  const activeValue = action === "unlock" ? 1 : 0;
  const url = "src/api/getTrangThaiTaiKhoan.php";

  const formData = new FormData();
  formData.append("id", id);
  formData.append("is_active", activeValue);

  fetch(url, { method: "POST", body: formData })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        showAlert("Đã cập nhật trạng thái thành công!");
        fetchFilteredEmployees();
      } else {
        showAlert("Lỗi từ hệ thống: " + result.message);
      }
    })
    .catch((error) => {
      console.error("Lỗi kết nối API trạng thái:", error);
      showAlert("Không thể kết nối tới máy chủ xử lý.");
    });
}

// 5. Đặt lại mật khẩu nhân viên
function resetUserPassword(employeeId) {
  fetch(`src/api/getNhanVien.php?id=${employeeId}`)
    .then((response) => {
      if (!response.ok)
        throw new Error("Kết nối API thất bại, mã lỗi: " + response.status);
      return response.text();
    })
    .then((textData) => {
      try {
        const result = JSON.parse(textData);
        if (result.success) {
          const employee = result.data;

          document.getElementById("modal_employee_id").value =
            employee.MaNhanVien;
          document.getElementById("modal_employee_name").innerText =
            employee.HoTen;
          document.getElementById("modal_employee_username").innerText =
            employee.TenDangNhap;

          const avatarPath = employee.AnhThe
            ? `uploads/avatars/${employee.AnhThe}`
            : "default-avatar.png";
          document.getElementById("modal_employee_avatar").src = avatarPath;
          document.getElementById("modal_employee_avatar").alt = employee.HoTen;

          document.getElementById("form_reset_password").reset();
          if (typeof checkStrength === "function") checkStrength("");

          toggleModal("modal-change-password", true);
        } else {
          showAlert(
            "Không thể tải thông tin nhân viên: " +
              (result.message || "Lỗi không xác định"),
          );
        }
      } catch (jsonError) {
        console.error("Lỗi phân tách dữ liệu:", jsonError);
        showAlert("Lỗi: Phản hồi từ Server không hợp lệ!");
      }
    })
    .catch((error) => {
      console.error("Lỗi kết nối API:", error);
      showAlert("Đã có lỗi xảy ra khi tải dữ liệu!");
    });
}

// 6. Lưu cấu hình hệ thống
function saveClinicSettings() {
  const form = document.getElementById("clinicForm");
  if (!form) return;
  const formData = new FormData(form);

  fetch("src/controllers/CauHinhController.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      if (result.success) {
        showAlert("Lưu cấu hình thành công!");
      } else {
        showAlert("Lỗi: " + result.message);
      }
    })
    .catch((err) => console.error("Lỗi kết nối:", err));
}

// ==========================================
// KHỐI KHỞI CHẠY LẦN ĐẦU KHI TẢI TRANG HOÀN TOÀN (F5)
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
  // Gắn xử lý form thêm nhân viên
  const addEmployeeForm = document.getElementById("form-add-employee");
  if (addEmployeeForm) {
    addEmployeeForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch("src/controllers/NhanVienController.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            showAlert(data.message);
            const modal = document.getElementById("modal-add-employee");
            if (modal) modal.classList.add("hidden");
            this.reset();
            window.location.reload();
          } else {
            showAlert("Thất bại: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showAlert("Có lỗi xảy ra trong quá trình gửi dữ liệu!");
        });
    });
  }

  // Gắn sự kiện nút lưu cấu hình
  const saveBtn = document.getElementById("btn-save-clinic");
  if (saveBtn) {
    saveBtn.addEventListener("click", saveClinicSettings);
  }

  // Khởi chạy API Địa chỉ Tỉnh / Huyện / Xã
  const host = "https://provinces.open-api.vn/api/";
  const provinceSelect = document.getElementById("province");
  const districtSelect = document.getElementById("district");
  const wardSelect = document.getElementById("ward");

  if (provinceSelect && districtSelect && wardSelect) {
    fetch(`${host}p/`)
      .then((response) => response.json())
      .then((data) => {
        data.forEach((item) => {
          provinceSelect.options[provinceSelect.options.length] = new Option(
            item.name,
            item.code,
          );
        });
      })
      .catch((err) => console.error("Lỗi tải danh sách tỉnh:", err));

    provinceSelect.addEventListener("change", function () {
      districtSelect.innerHTML = '<option value="">Chọn Quận / Huyện</option>';
      wardSelect.innerHTML = '<option value="">Chọn Phường / Xã</option>';
      wardSelect.disabled = true;

      if (!this.value) {
        districtSelect.disabled = true;
        return;
      }

      fetch(`${host}p/${this.value}?depth=2`)
        .then((response) => response.json())
        .then((data) => {
          districtSelect.disabled = false;
          data.districts.forEach((item) => {
            districtSelect.options[districtSelect.options.length] = new Option(
              item.name,
              item.code,
            );
          });
        })
        .catch((err) => console.error("Lỗi tải danh sách huyện:", err));
    });

    districtSelect.addEventListener("change", function () {
      wardSelect.innerHTML = '<option value="">Chọn Phường / Xã</option>';
      if (!this.value) {
        wardSelect.disabled = true;
        return;
      }

      fetch(`${host}d/${this.value}?depth=2`)
        .then((response) => response.json())
        .then((data) => {
          wardSelect.disabled = false;
          data.wards.forEach((item) => {
            wardSelect.options[wardSelect.options.length] = new Option(
              item.name,
              item.code,
            );
          });
        })
        .catch((err) => console.error("Lỗi tải danh sách xã:", err));
    });
  }

  // Tự động gọi nạp dữ liệu nhân viên khi load trang trực tiếp bằng F5
  initEmployeePageEvents();
});
