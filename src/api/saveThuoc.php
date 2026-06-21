<?php
// Đường dẫn: src/api/saveThuoc.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: POST');
require_once __DIR__ . '/../../config/database.php';

try {
    // Lấy dữ liệu từ body request (JSON)
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit;
    }

    $action = $input['action'] ?? '';

    // =========================================================================
    // HÀNH ĐỘNG 1: BẬT / TẮT TRẠNG THÁI HOẠT ĐỘNG (XÓA MỀM)
    // =========================================================================
    if ($action === 'toggle_status') {
        $id = (int)($input['MaThuoc'] ?? 0);
        $new_status = (int)($input['DangHoatDong'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Mã thuốc không hợp lệ.']);
            exit;
        }

        $sql = "UPDATE THUOC SET DangHoatDong = :status, NgayCapNhat = NOW() WHERE MaThuoc = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $new_status, ':id' => $id]);

        echo json_encode(['success' => true, 'message' => 'toggle_success']);
        exit;
    }

    // =========================================================================
    // HÀNH ĐỘNG 2: THÊM MỚI HOẶC CẬP NHẬT THÔNG TIN THUỐC
    // =========================================================================
    if ($action === 'save') {
        $id = isset($input['MaThuoc']) ? (int)$input['MaThuoc'] : 0;

        // Thu thập và làm sạch dữ liệu đầu vào
        $data = [
            ':MaThuocCode'     => trim($input['MaThuocCode'] ?? ''),
            ':TenThuoc'        => trim($input['TenThuoc'] ?? ''),
            ':TenHoatChat'     => trim($input['TenHoatChat'] ?? null),
            ':HamLuong'        => trim($input['HamLuong'] ?? null),
            ':MaDonVi'         => (int)($input['MaDonVi'] ?? 1),
            ':DangBaoChe'      => trim($input['DangBaoChe'] ?? null),
            ':QuyCach'         => trim($input['QuyCach'] ?? null),
            ':NhaSanXuat'      => trim($input['NhaSanXuat'] ?? null),
            ':NuocSanXuat'     => trim($input['NuocSanXuat'] ?? null),
            ':SoDangKy'        => trim($input['SoDangKy'] ?? null),
            ':HanSuDung'       => !empty($input['HanSuDung']) ? $input['HanSuDung'] : null,
            ':SoLuongTon'      => (int)($input['SoLuongTon'] ?? 0),
            ':TonToiThieu'     => (int)($input['TonToiThieu'] ?? 10),
            ':GiaNhap'         => (float)($input['GiaNhap'] ?? 0),
            ':GiaBan'          => (float)($input['GiaBan'] ?? 0),
            ':HuongDanSuDung'  => trim($input['HuongDanSuDung'] ?? null)
        ];

        // Validate các trường bắt buộc
        if (empty($data[':MaThuocCode']) || empty($data[':TenThuoc'])) {
            echo json_encode(['success' => false, 'message' => 'Mã thuốc code và Tên thuốc không được để trống.']);
            exit;
        }

        if ($id > 0) {
            // Trường hợp SỬA (UPDATE)
            $sql = "UPDATE THUOC SET 
                        MaThuocCode = :MaThuocCode, TenThuoc = :TenThuoc, TenHoatChat = :TenHoatChat, 
                        HamLuong = :HamLuong, MaDonVi = :MaDonVi, DangBaoChe = :DangBaoChe, 
                        QuyCach = :QuyCach, NhaSanXuat = :NhaSanXuat, NuocSanXuat = :NuocSanXuat, 
                        SoDangKy = :SoDangKy, HanSuDung = :HanSuDung, SoLuongTon = :SoLuongTon, 
                        TonToiThieu = :TonToiThieu, GiaNhap = :GiaNhap, GiaBan = :GiaBan, 
                        HuongDanSuDung = :HuongDanSuDung, NgayCapNhat = NOW() 
                    WHERE MaThuoc = :MaThuoc";
            $data[':MaThuoc'] = $id;
            $msg = "update_success";
        } else {
            // Trường hợp THÊM (INSERT)
            $sql = "INSERT INTO THUOC (
                        MaThuocCode, TenThuoc, TenHoatChat, HamLuong, MaDonVi, DangBaoChe, 
                        QuyCach, NhaSanXuat, NuocSanXuat, SoDangKy, HanSuDung, SoLuongTon, 
                        TonToiThieu, GiaNhap, GiaBan, HuongDanSuDung, DangHoatDong, NgayTao
                    ) VALUES (
                        :MaThuocCode, :TenThuoc, :TenHoatChat, :HamLuong, :MaDonVi, :DangBaoChe, 
                        :QuyCach, :NhaSanXuat, :NuocSanXuat, :SoDangKy, :HanSuDung, :SoLuongTon, 
                        :TonToiThieu, :GiaNhap, :GiaBan, :HuongDanSuDung, 1, NOW()
                    )";
            $msg = "insert_success";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        echo json_encode(['success' => true, 'message' => $msg]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi xử lý CSDL: ' . $e->getMessage()]);
}
exit;