<?php
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['user_id']);
    $password = $_POST['new_password'];

    if (empty($id) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit;
    }

    $sql = "UPDATE TAIKHOAN SET MatKhauHash = :pass WHERE MaTaiKhoan = :id";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':pass' => password_hash($password, PASSWORD_DEFAULT),
        ':id'   => $id
    ]);

    echo json_encode(['success' => $result, 'message' => $result ? 'Thành công' : 'Thất bại']);
}