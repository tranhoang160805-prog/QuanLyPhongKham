<?php
if (ob_get_length()) {
    ob_clean();
}

// 3. Thiết lập Header chuẩn JSON
header('Content-Type: application/json; charset=utf-8');

// 4. Gọi kết nối database (Đường dẫn lùi 2 cấp từ src/api/ về thư mục gốc dự án)
require_once __DIR__ . '/../../config/database.php';

$role_id = $_GET['role'] ?? 'all';
$status  = $_GET['status'] ?? 'all';
$search  = trim($_GET['search'] ?? '');

try {
    if (!isset($pdo)) {
        echo json_encode(['success' => false, 'message' => 'Không có kết nối database']);
        exit;
    }

    // Câu lệnh truy vấn SQL - Đã sửa đổi để lấy toàn bộ thông tin từ bảng NHANVIEN và TAIKHOAN
    $sql = "SELECT 
                tk.MaTaiKhoan AS maTaiKhoan,
                tk.TenDangNhap AS username,
                tk.DangHoatDong AS is_active,
                tk.NgayTao AS created_at,
                nv.MaNhanVien AS maNhanVien,
                nv.HoTen AS hoTen,
                nv.AnhThe AS anhThe,
                nv.SoDienThoai AS soDienThoai,
                nv.Email AS email,
                nv.NgaySinh AS ngaySinh,
                nv.GioiTinh AS gioiTinh,
                nv.CCCD AS cccd,
                nv.DiaChi AS diaChi,
                nv.BangCap AS bangCap,
                nv.SoChungChi AS soChungChi,
                nv.NgayVaoLam AS ngayVaoLam,
                vt.TenVaiTro AS role_name,
                vt.MoTa AS role_description
            FROM TAIKHOAN tk
            INNER JOIN NHANVIEN nv ON tk.MaTaiKhoan = nv.MaTaiKhoan
            LEFT JOIN TAIKHOAN_VAITRO tkvt ON tk.MaTaiKhoan = tkvt.MaTaiKhoan
            LEFT JOIN VAITRO vt ON tkvt.MaVaiTro = vt.MaVaiTro
            WHERE (vt.TenVaiTro != 'BENH_NHAN' OR vt.TenVaiTro IS NULL)";

    $params = [];

    // Bộ lọc chức vụ (Xử lý theo cả mã vai trò hoặc tên vai trò nếu giao diện của bạn truyền sang)
    if ($role_id !== 'all' && !empty($role_id)) {
        if (is_numeric($role_id)) {
            $sql .= " AND vt.MaVaiTro = :role_id";
            $params[':role_id'] = intval($role_id);
        } else {
            $sql .= " AND vt.TenVaiTro = :role_id";
            $params[':role_id'] = $role_id;
        }
    }

    // Bộ lọc trạng thái hoạt động
    if ($status === 'active') {
        $sql .= " AND tk.DangHoatDong = 1";
    } elseif ($status === 'locked' || $status === 'inactive') {
        $sql .= " AND tk.DangHoatDong = 0";
    }

    // Thanh tìm kiếm thông minh: Tìm theo Họ tên thật, Tên đăng nhập hoặc Số điện thoại
    if (!empty($search)) {
        $sql .= " AND (nv.HoTen LIKE :search OR tk.TenDangNhap LIKE :search OR nv.SoDienThoai LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // Sắp xếp tài khoản mới tạo lên đầu
    $sql .= " ORDER BY tk.NgayTao DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $raw_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach ($raw_data as $row) {
        // Tạo chữ viết tắt Avatar lấy từ Họ Tên thật (An toàn với tiếng Việt bằng mb_substr)
        $name_for_avatar = !empty($row['hoTen']) ? $row['hoTen'] : $row['username'];
        $initials = mb_strtoupper(mb_substr($name_for_avatar, 0, 2, "UTF-8"), "UTF-8");
        
        $user_status = ($row['is_active'] == 1) ? 'active' : 'inactive';
        
        // Phân cấp class CSS tương ứng chức vụ cho Badge màu sắc
        $role_class = 'role-staff';
        if ($row['role_name'] === 'QUAN_LY' || $row['role_name'] === 'IT_ADMIN') {
            $role_class = 'role-admin';
        } elseif ($row['role_name'] === 'BAC_SI') {
            $role_class = 'role-doctor';
        } elseif ($row['role_name'] === 'DIEU_DUONG' || $row['role_name'] === 'KY_THUAT_VIEN') {
            $role_class = 'role-nurse';
        }

        // Đẩy phần tử vào mảng và map chính xác các key mà giao diện JavaScript đang chờ nhận
        $users[] = [
            'id'               => $row['maTaiKhoan'],
            'staff_id'         => $row['maNhanVien'],
            'initials'         => $initials,
            'name'             => $row['hoTen'],
            'avatar'           => $row['anhThe'],
            'username'         => $row['username'],
            'phone'            => $row['soDienThoai'] ?? 'Chưa cập nhật',
            'email'            => $row['email'] ?? 'Chưa cập nhật',
            'birthdate'        => $row['ngaySinh'],
            'gender'           => $row['gioiTinh'],
            'id_card'          => $row['cccd'],
            'address'          => $row['diaChi'],
            'qualification'    => $row['bangCap'],
            'license_number'   => $row['soChungChi'],
            'hire_date'        => $row['ngayVaoLam'],
            'role'             => $row['role_description'] ?? 'Chưa phân quyền',
            'role_name'        => $row['role_name'],
            'role_class'       => $role_class,
            'created_at'       => !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : date('d/m/Y'),
            'status'           => $user_status
        ];
    }

    // Xuất dữ liệu JSON sạch ra màn hình
    echo json_encode(['success' => true, 'data' => $users]);
    exit(); // Ngắt ngay lập tức để không chạy bất kỳ dòng code thừa nào phía dưới

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn hệ thống: ' . $e->getMessage()]);
    exit();
}