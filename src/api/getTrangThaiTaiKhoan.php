<?php
if (ob_get_length()) {
    ob_clean();
}
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$is_active = isset($_POST['is_active']) ? intval($_POST['is_active']) : -1;

if ($id <= 0 || ($is_active !== 0 && $is_active !== 1)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
    exit;
}

try {
    if (!isset($pdo)) {
        echo json_encode(['success' => false, 'message' => 'Mất kết nối database.']);
        exit;
    }

    // Thực hiện lệnh UPDATE trực tiếp vào bảng TAIKHOAN -> DangHoatDong
    $sql = "UPDATE TAIKHOAN SET DangHoatDong = :is_active WHERE MaTaiKhoan = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':is_active' => $is_active,
        ':id' => $id
    ]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi không thể cập nhật.']);
    }
    exit;

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}