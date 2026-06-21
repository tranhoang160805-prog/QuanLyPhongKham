<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập hệ thống.']);
    exit;
}

$maTaiKhoan = intval($_SESSION['user_id']);
$action = isset($_GET['action']) ? $_GET['action'] : '';

function getMaBenhNhan($pdo, $maTaiKhoan) {
    $stmt = $pdo->prepare("SELECT MaBenhNhan FROM BENHNHAN WHERE MaTaiKhoan = ?");
    $stmt->execute([$maTaiKhoan]);
    $result = $stmt->fetch();
    return $result ? intval($result['MaBenhNhan']) : 0;
}

switch ($action) {
    // ========================================================
    // TÁC VỤ 4: TẠO MỚI LỊCH HẸN (ĐÃ BỎ maChuyenKhoa & maBacSi)
    // Người dùng chỉ cần nhập vấn đề sức khỏe (ghi_chu)
    // ========================================================
    case 'create_booking':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
            exit;
        }

        $maBenhNhan = getMaBenhNhan($pdo, $maTaiKhoan);
        if ($maBenhNhan <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Tài khoản chưa được liên kết hồ sơ bệnh nhân.']);
            exit;
        }

        $input  = json_decode(file_get_contents('php://input'), true);
        $ghiChu = isset($input['ghi_chu']) ? trim($input['ghi_chu']) : '';

        if (strlen($ghiChu) < 10) {
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng mô tả vấn đề sức khỏe ít nhất 10 ký tự.']);
            exit;
        }

        try {
            // Ngày hẹn mặc định là hôm nay, giờ hẹn NULL (chưa phân công)
            $ngayHen    = date('Y-m-d');
            $maTrangThai = 1; // Chờ xác nhận / phân công

            $sql = "INSERT INTO LICHHEN (MaBenhNhan, NgayHen, MaTrangThai, GhiChu, NguoiTao, NgayTao) 
                    VALUES (:ma_benh_nhan, :ngay_hen, :ma_trang_thai, :ghi_chu, :nguoi_tao, NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':ma_benh_nhan'  => $maBenhNhan,
                ':ngay_hen'      => $ngayHen,
                ':ma_trang_thai' => $maTrangThai,
                ':ghi_chu'       => $ghiChu,
                ':nguoi_tao'     => $maTaiKhoan
            ]);

            $maLichHen = $pdo->lastInsertId();

            echo json_encode([
                'status'      => 'success',
                'message'     => 'Đặt lịch thành công.',
                'maLichHen'   => $maLichHen,
                'maBenhNhan'  => $maBenhNhan
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi lưu lịch hẹn: ' . $e->getMessage()]);
        }
        break;

    // ========================================================
    // TÁC VỤ 5: LẤY DANH SÁCH PHIẾU KHÁM CỦA BỆNH NHÂN
    // ========================================================
    case 'get_my_phieu_kham':
        $maBenhNhan = getMaBenhNhan($pdo, $maTaiKhoan);
        if ($maBenhNhan <= 0) {
            echo json_encode(['status' => 'success', 'data' => [], 'count' => 0]);
            exit;
        }

        try {
            // Lấy danh sách phiếu khám theo mã bệnh nhân, mới nhất trước
            // Nếu có bảng TRANGTHAILICHHEN hoặc trạng thái riêng cho phiếu khám, JOIN vào đây
            $sql = "SELECT pk.STT, pk.MaPhieuKhamCode, pk.MaBenhNhan, pk.LyDoKham,
                           pk.MaTrangThai, pk.NgayTao, pk.GioTiepNhan,
                           tt.TenTrangThai
                    FROM PHIEUKHAM pk
                    LEFT JOIN TRANGTHAILICHCHHEN tt ON pk.MaTrangThai = tt.MaTrangThai
                    WHERE pk.MaBenhNhan = ?
                    ORDER BY pk.NgayTao DESC, pk.STT DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$maBenhNhan]);
            $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'status' => 'success',
                'data'   => $list,
                'count'  => count($list)
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi truy vấn phiếu khám: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Hành động yêu cầu không hợp lệ.']);
        break;
}
?>