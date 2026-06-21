<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // SỬA TẠI ĐÂY: Nhận đúng emp_id thay vì employee_id từ form nhan-vien.php gửi lên
    $employee_id   = trim($_POST['emp_id'] ?? '');

    // Thu thập dữ liệu chung từ Form gửi lên
    $username      = trim($_POST['username'] ?? '');
    $password      = $_POST['password'] ?? '';
    $email         = trim($_POST['email'] ?? '');
    $phone         = trim($_POST['phone'] ?? '');
    $role_id       = $_POST['role_id'] ?? '';
    
    $fullname      = trim($_POST['fullname'] ?? '');
    $id_card       = trim($_POST['id_card'] ?? '');
    $birthdate     = $_POST['birthdate'] ?? null;
    $gender        = $_POST['gender'] ?? 'Nam';
    $address       = trim($_POST['address'] ?? null);
    $qualification = trim($_POST['qualification'] ?? null);
    $specialty_raw = trim($_POST['specialty'] ?? '');
    $specialty = ($specialty_raw === '' || $specialty_raw === '0') ? null : $specialty_raw;

    // Mặc định tên ảnh thẻ ban đầu là null
    $db_image_path = null; 
    $old_image_path = null; // Dùng để lưu vết ảnh cũ phục vụ cho việc xóa khi Sửa
    $is_upload_new_image = false;

    // === XỬ LÝ LƯU VÀO DATABASE SỬ DỤNG TRANSACTION ===
    try {
        if (!isset($pdo)) {
            throw new Exception("Kết nối cơ sở dữ liệu thất bại.");
        }

        $pdo->beginTransaction(); // Bắt đầu phiên làm việc an toàn

        // ====================================================================
        // TRƯỜNG HỢP 1: CẬP NHẬT THÔNG TIN NHÂN VIÊN (UPDATE WHERE ID IS NOT EMPTY)
        // ====================================================================
        if (!empty($employee_id)) {
            
            // 1. Lấy thông tin Mã tài khoản và Ảnh thẻ hiện tại của nhân viên cần sửa
            $stmt_get = $pdo->prepare("SELECT MaTaiKhoan, AnhThe FROM NHANVIEN WHERE MaNhanVien = :emp_id");
            $stmt_get->execute([':emp_id' => $employee_id]);
            $current_emp = $stmt_get->fetch(PDO::FETCH_ASSOC);

            if (!$current_emp) {
                throw new Exception("Không tìm thấy nhân viên hợp lệ trên hệ thống để cập nhật.");
            }

            $id_tai_khoan_hien_tai = $current_emp['MaTaiKhoan'];
            $old_image_path = $current_emp['AnhThe'];
            $db_image_path = $old_image_path; // Tạm thời giữ ảnh cũ nếu không upload ảnh mới

            // SỬA TẠI ĐÂY: Đổi từ avatar_file thành avatar để đồng bộ với thẻ <input type="file" name="avatar">
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath   = $_FILES['avatar']['tmp_name'];
                $fileName      = $_FILES['avatar']['name'];
                $fileSize      = $_FILES['avatar']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    if ($fileSize <= 2 * 1024 * 1024) {
                        $uploadFileDir = __DIR__ . '/../../public/assets/img/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        $newFileName = 'avatar_' . uniqid() . '.' . $fileExtension;
                        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                            $db_image_path = $newFileName; // Nhận tên file ảnh mới
                            $is_upload_new_image = true;   // Đánh dấu để xóa ảnh cũ sau khi commit thành công
                        } else {
                            throw new Exception("Không thể di chuyển file ảnh mới vào thư mục lưu trữ.");
                        }
                    } else {
                        throw new Exception("Kích thước file ảnh mới quá lớn (Phải dưới 2MB).");
                    }
                } else {
                    throw new Exception("Định dạng file ảnh mới không hỗ trợ (Chỉ nhận JPG, JPEG, PNG).");
                }
            }

            // 4. Cập nhật bảng NHANVIEN (Chạy lệnh UPDATE đúng ID nhân viên)
            $sql_nv = "UPDATE NHANVIEN SET 
                        HoTen = :fullname, AnhThe = :anh_the, NgaySinh = :birthdate, 
                        GioiTinh = :gender, CCCD = :cccd, SoDienThoai = :phone, 
                        Email = :email, DiaChi = :address, BangCap = :qualification, 
                        MaChuyenKhoa = :specialty 
                      WHERE MaNhanVien = :emp_id";
            $stmt_nv = $pdo->prepare($sql_nv);
            $stmt_nv->execute([
                ':fullname'      => $fullname,
                ':anh_the'       => $db_image_path,
                ':birthdate'     => $birthdate,
                ':gender'        => $gender,
                ':cccd'          => $id_card,
                ':phone'         => $phone,
                ':email'         => $email,
                ':address'       => $address,
                ':qualification' => $qualification,
                ':specialty'     => $specialty,
                ':emp_id'        => $employee_id
            ]);

            // 5. Cập nhật phân quyền mới vào bảng TAIKHOAN_VAITRO
            if (!empty($role_id)) {
                $sql_vt = "UPDATE TAIKHOAN_VAITRO SET MaVaiTro = :ma_vt WHERE MaTaiKhoan = :ma_tk";
                $stmt_vt = $pdo->prepare($sql_vt);
                $stmt_vt->execute([
                    ':ma_vt' => intval($role_id),
                    ':ma_tk' => $id_tai_khoan_hien_tai
                ]);
            }

            $pdo->commit(); // Lưu thay đổi Sửa thành công

            // Nếu update thành công và có ảnh mới -> Xóa file ảnh vật lý cũ trên Server để giải phóng bộ nhớ
            if ($is_upload_new_image && $old_image_path && file_exists(__DIR__ . '/../../public/assets/img/' . $old_image_path)) {
                @unlink(__DIR__ . '/../../public/assets/img/' . $old_image_path);
            }

            echo json_encode(['success' => true, 'message' => 'Cập nhật thông tin nhân viên thành công!']);
            exit;

        // ====================================================================
        // TRƯỜNG HỢP 2: THÊM MỚI NHÂN VIÊN HOÀN TOÀN (INSERT ĐỐI VỚI ID RỖNG)
        // ====================================================================
        } else {
            
            // SỬA TẠI ĐÂY: Đổi từ avatar_file thành avatar
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath   = $_FILES['avatar']['tmp_name'];
                $fileName      = $_FILES['avatar']['name'];
                $fileSize      = $_FILES['avatar']['size'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    if ($fileSize <= 2 * 1024 * 1024) {
                        $uploadFileDir = __DIR__ . '/../../public/assets/img/';
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }
                        $newFileName = 'avatar_' . uniqid() . '.' . $fileExtension;
                        if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                            $db_image_path = $newFileName; 
                        } else {
                            throw new Exception("Không thể di chuyển file ảnh vào thư mục lưu trữ.");
                        }
                    } else {
                        throw new Exception("Kích thước file ảnh quá lớn (Phải dưới 2MB).");
                    }
                } else {
                    throw new Exception("Định dạng file không được hỗ trợ (Chỉ nhận JPG, JPEG, PNG).");
                }
            }

            // 2. Chèn dữ liệu mới vào bảng TAIKHOAN
            if (empty($password)) {
                throw new Exception("Vui lòng nhập mật khẩu khi thêm nhân viên mới.");
            }

            $sql_tk = "INSERT INTO TAIKHOAN (TenDangNhap, MatKhauHash, DangHoatDong, NgayTao) 
                       VALUES (:username, :password, 1, NOW())";
            $stmt_tk = $pdo->prepare($sql_tk);
            $stmt_tk->execute([
                ':username' => $username,
                ':password' => password_hash($password, PASSWORD_DEFAULT) 
            ]);

            // Lấy mã tài khoản vừa tự động sinh
            $id_tai_khoan_moi = $pdo->lastInsertId();

            // 3. Chèn dữ liệu mới vào bảng NHANVIEN
            $sql_nv = "INSERT INTO NHANVIEN (MaTaiKhoan, HoTen, AnhThe, NgaySinh, GioiTinh, CCCD, SoDienThoai, Email, DiaChi, BangCap, MaChuyenKhoa, NgayVaoLam) 
                       VALUES (:ma_tk, :fullname, :anh_the, :birthdate, :gender, :cccd, :phone, :email, :address, :qualification, :specialty, NOW())";
            $stmt_nv = $pdo->prepare($sql_nv);
            $stmt_nv->execute([
                ':ma_tk'         => $id_tai_khoan_moi,
                ':fullname'      => $fullname,
                ':anh_the'       => $db_image_path,
                ':birthdate'     => $birthdate,
                ':gender'        => $gender,
                ':cccd'          => $id_card,
                ':phone'         => $phone,
                ':email'         => $email,
                ':address'       => $address,
                ':qualification' => $qualification,
                ':specialty'     => $specialty
            ]);

            // 4. Chèn phân quyền tương ứng vào bảng TAIKHOAN_VAITRO
            if (!empty($role_id)) {
                $sql_vt = "INSERT INTO TAIKHOAN_VAITRO (MaTaiKhoan, MaVaiTro) VALUES (:ma_tk, :ma_vt)";
                $stmt_vt = $pdo->prepare($sql_vt);
                $stmt_vt->execute([
                    ':ma_tk' => $id_tai_khoan_moi,
                    ':ma_vt' => intval($role_id)
                ]);
            }

            $pdo->commit(); // Lưu tất cả mọi thay đổi thêm mới thành công

            echo json_encode(['success' => true, 'message' => 'Thêm nhân viên mới và lưu ảnh thẻ thành công!']);
            exit;
        }

    } catch (Exception $e) {
        $pdo->rollBack(); // Hủy toàn bộ thao tác ghi DB nếu dính bất kỳ lỗi gì
        
        // Xóa file ảnh tạm vừa tải lên nếu database bị lỗi để tránh rác hosting
        if ($is_upload_new_image || empty($employee_id)) {
            if ($db_image_path && file_exists(__DIR__ . '/../../public/assets/img/' . $db_image_path)) {
                @unlink(__DIR__ . '/../../public/assets/img/' . $db_image_path);
            }
        }

        echo json_encode(['success' => false, 'message' => 'Thất bại: ' . $e->getMessage()]);
        exit;
    }
}