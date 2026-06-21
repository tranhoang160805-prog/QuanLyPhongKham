<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

function getPdoConnection() {
    global $pdo;
    if (!isset($pdo)) {
        require __DIR__ . '/../../config/database.php';
    }
    return $pdo;
}

// Hàm sinh số hóa đơn tự động tích hợp trực tiếp nội bộ
function apiGenerateSoHoaDon($db) {
    $prefix = 'HD-' . date('Ymd') . '-';
    $sql = "SELECT SoHoaDon FROM HOADON WHERE SoHoaDon LIKE :prefix ORDER BY MaHoaDon DESC LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':prefix' => $prefix . '%']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $nextSeq = 1;
    if ($row && isset($row['SoHoaDon'])) {
        $parts = explode('-', $row['SoHoaDon']);
        if (count($parts) === 3) {
            $nextSeq = intval($parts[2]) + 1;
        }
    }
    return $prefix . str_pad((string)$nextSeq, 4, '0', STR_PAD_LEFT);
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Phương thức HTTP không hợp lệ. Chỉ chấp nhận POST.');
    }

    // Đọc JSON gửi lên hoặc dữ liệu Form truyền thống
    $inputData = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = trim($inputData['action'] ?? '');
    $db = getPdoConnection();

    switch ($action) {
        // ==========================================
        // ACTION 1: LẬP (TẠO) HÓA ĐƠN MỚI
        // ==========================================
        case 'create':
            if (empty($inputData['MaPhieuKham'])) throw new Exception('Thiếu thông tin Mã phiếu khám.');

            $soHoaDon = apiGenerateSoHoaDon($db);

            $sql = "INSERT INTO HOADON 
                    (SoHoaDon, MaPhieuKham, TongTienKham, TongTienCLS, TongTienThuoc, TongCong, GiamGia, TongThanhToan, TrangThai, NgayTao) 
                    VALUES 
                    (:so_hd, :ma_pk, :tien_kham, :tien_cls, :tien_thuoc, :tong_cong, :giam_gia, :tong_thanh_toan, :trang_thai, CURRENT_TIMESTAMP)";
            
            $stmt = $db->prepare($sql);
            $success = $stmt->execute([
                ':so_hd'           => $soHoaDon,
                ':ma_pk'           => intval($inputData['MaPhieuKham']),
                ':tien_kham'       => floatval($inputData['TongTienKham'] ?? 150000),
                ':tien_cls'        => floatval($inputData['TongTienCLS'] ?? 0),
                ':tien_thuoc'      => floatval($inputData['TongTienThuoc'] ?? 0),
                ':tong_cong'       => floatval($inputData['TongCong']),
                ':giam_gia'        => floatval($inputData['GiamGia'] ?? 0),
                ':tong_thanh_toan' => floatval($inputData['TongThanhToan']),
                ':trang_thai'      => $inputData['TrangThai'] ?? 'CHO_THANH_TOAN'
            ]);

            if (!$success) throw new Exception('Lỗi hệ thống, không thể khởi tạo hóa đơn.');

            echo json_encode([
                'success' => true,
                'message' => 'Lập hóa đơn thành công!',
                'data' => [
                    'MaHoaDon' => (int)$db->lastInsertId(),
                    'SoHoaDon' => $soHoaDon
                ]
            ]);
            break;

        // ==========================================
        // ACTION 2: TIẾN HÀNH THANH TOÁN (TRANSACTION)
        // ==========================================
        case 'pay':
            $maHoaDon = intval($inputData['MaHoaDon'] ?? 0);
            $maPhuongThuc = intval($inputData['MaPhuongThuc'] ?? 0);
            $soTien = floatval($inputData['SoTien'] ?? 0);
            $nguoiThu = intval($inputData['NguoiThu'] ?? 0);
            $ghiChu = trim($inputData['GhiChu'] ?? '');

            if ($maHoaDon <= 0 || $maPhuongThuc <= 0 || $soTien <= 0) {
                throw new Exception('Thông tin thanh toán không hợp lệ (Hóa đơn, Phương thức & Số tiền không được trống).');
            }

            try {
                $db->beginTransaction();

                // 1. Cập nhật trạng thái sang Đã thanh toán
                $sql_ud = "UPDATE HOADON SET TrangThai = 'DA_THANH_TOAN', NgayThanhToan = CURRENT_TIMESTAMP WHERE MaHoaDon = :ma_hd";
                $stmt_ud = $db->prepare($sql_ud);
                $stmt_ud->execute([':ma_hd' => $maHoaDon]);

                // 2. Chèn Biên lai thanh toán vào bảng lưu trữ giao dịch
                $sql_pay = "INSERT INTO THANHTOAN (MaHoaDon, MaPhuongThuc, SoTien, NguoiThu, NgayThanhToan, GhiChu)
                            VALUES (:ma_hd, :ma_pt, :so_tien, :nguoi_thu, CURRENT_TIMESTAMP, :ghi_chu)";
                $stmt_pay = $db->prepare($sql_pay);
                $stmt_pay->execute([
                    ':ma_hd'     => $maHoaDon,
                    ':ma_pt'     => $maPhuongThuc,
                    ':so_tien'   => $soTien,
                    ':nguoi_thu' => $nguoiThu,
                    ':ghi_chu'   => $ghiChu
                ]);

                $db->commit();
                echo json_encode([
                    'success' => true,
                    'message' => 'Thanh toán hóa đơn hoàn tất thành công!'
                ]);
            } catch (Exception $txEx) {
                $db->rollBack();
                throw $txEx;
            }
            break;

        // ==========================================
        // ACTION 3: HỦY HÓA ĐƠN
        // ==========================================
        case 'cancel':
            $maHoaDon = intval($inputData['MaHoaDon'] ?? 0);
            if ($maHoaDon <= 0) throw new Exception('Mã hóa đơn cần hủy không hợp lệ.');

            $sql = "UPDATE HOADON SET TrangThai = 'DA_HUY' WHERE MaHoaDon = :ma_hd";
            $stmt = $db->prepare($sql);
            $stmt->execute([':ma_hd' => $maHoaDon]);

            echo json_encode([
                'success' => true,
                'message' => 'Đã hủy hóa đơn thành công.'
            ]);
            break;

        default:
            throw new Exception('Action POST không hợp lệ hoặc không hỗ trợ.');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}