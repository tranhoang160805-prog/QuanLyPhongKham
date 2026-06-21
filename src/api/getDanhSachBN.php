<?php
header('Content-Type: application/json; charset=utf-8');


// Nhúng file cấu hình kết nối CSDL của bạn vào đây
require_once __DIR__ . '/../../config/database.php'; 

// ==========================================================================
// 2. XỬ LÝ LOGIC LẤY DỮ LIỆU
// ==========================================================================
try {
    if (!isset($pdo)) {
        throw new Exception("Không thể kết nối đến Cơ sở dữ liệu.");
    }

    // Trường hợp A: File khác muốn lấy CHI TIẾT của 1 lịch hẹn duy nhất (?id=5)
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $maLichHen = (int)$_GET['id'];

        $sql = "SELECT lh.*, 
                       bn.HoTen AS TenBenhNhan, bn.SoDienThoai AS SdtBenhNhan,
                       tt.TenTrangThai
                FROM LICHHEN lh
                INNER JOIN BENHNHAN bn ON lh.MaBenhNhan = bn.MaBenhNhan
                INNER JOIN TRANGTHAILICHCHHEN tt ON lh.MaTrangThai = tt.MaTrangThai
                WHERE lh.MaLichHen = :maLichHen";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['maLichHen' => $maLichHen]);
        $lichHen = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lichHen) {
            // Trả về chuỗi JSON thành công cho lịch hẹn đơn lẻ
            echo json_encode([
                'status' => 200,
                'success' => true,
                'message' => 'Lấy chi tiết lịch hẹn thành công.',
                'data' => $lichHen
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            // Trả về JSON thông báo không tìm thấy mã
            http_response_code(404);
            echo json_encode([
                'status' => 404,
                'success' => false,
                'message' => 'Không tìm thấy lịch hẹn với mã yêu cầu.'
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    // Trường hợp B: File khác muốn lấy TOÀN BỘ danh sách lịch hẹn
    $sqlAll = "SELECT lh.*,
                      bn.*,
                      tt.TenTrangThai, lh.MaTrangThai
               FROM LICHHEN lh
               INNER JOIN BENHNHAN bn ON lh.MaBenhNhan = bn.MaBenhNhan
               INNER JOIN TRANGTHAILICHCHHEN tt ON lh.MaTrangThai = tt.MaTrangThai
               ORDER BY lh.NgayHen DESC";

    $stmtAll = $pdo->prepare($sqlAll);
    $stmtAll->execute();
    $listLichHen = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

    // Trả về chuỗi JSON toàn bộ danh sách cho file bên ngoài nhận kết quả
    echo json_encode([
        'status' => 200,
        'success' => true,
        'total' => count($listLichHen),
        'message' => 'Lấy danh sách lịch hẹn thành công.',
        'data' => $listLichHen
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    // Trả về chuỗi JSON báo lỗi hệ thống nếu xảy ra trục trặc cấu trúc SQL
    http_response_code(500);
    echo json_encode([
        'status' => 500,
        'success' => false,
        'message' => 'Lỗi kết nối API: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}