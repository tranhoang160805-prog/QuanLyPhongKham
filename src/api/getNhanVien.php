<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        if (isset($pdo)) {
            $sql = "SELECT nv.*, tk.TenDangNhap, tv.MaVaiTro 
                    FROM NHANVIEN nv
                    LEFT JOIN TAIKHOAN tk ON nv.MaTaiKhoan = tk.MaTaiKhoan
                    LEFT JOIN TAIKHOAN_VAITRO tv ON tk.MaTaiKhoan = tv.MaTaiKhoan
                    WHERE nv.MaNhanVien = :id OR nv.MaTaiKhoan = :id_phong_ho";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':id_phong_ho' => $id
            ]);
            
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($employee) {
                echo json_encode(['success' => true, 'data' => $employee]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => "Không tìm thấy dữ liệu nhân viên nào khớp với ID (hoặc Mã TK) là: " . $id
                ]);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID nhân viên.']);
}
exit;