<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($pdo)) {
            throw new Exception("Kết nối cơ sở dữ liệu thất bại.");
        }

        // 1. Thu thập dữ liệu từ form
        $dataToUpdate = [
            'ten_phong_kham' => $_POST['clinic_name'] ?? '',
            'so_dien_thoai'  => $_POST['hotline'] ?? '',
            'email'          => $_POST['email'] ?? '',
            'dia_chi'        => $_POST['address'] ?? ''
        ];

        // 2. Xử lý giờ mở cửa (Chuyển mảng thành JSON)
        $start_times = $_POST['start_time'] ?? [];
        $end_times   = $_POST['end_time'] ?? [];
        $open_status = $_POST['open_status'] ?? [];
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $scheduleData = [];

        foreach ($days as $day) {
            $scheduleData[$day] = [
                'start' => $start_times[$day] ?? '08:00',
                'end'   => $end_times[$day] ?? '17:00',
                'open'  => isset($open_status[$day]) // Trả về true/false
            ];
        }
        
        // Thêm JSON vào danh sách cập nhật
        $dataToUpdate['gio_mo_cua'] = json_encode($scheduleData);

        // 3. Thực hiện cập nhật vào Database
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE CAUHINHHETHONG SET GiaTri = :value WHERE KhoacCauHinh = :key");

        foreach ($dataToUpdate as $key => $value) {
            $stmt->execute([
                ':value' => $value,
                ':key'   => $key
            ]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Cập nhật cấu hình thành công!']);

    } catch (Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức yêu cầu không hợp lệ.']);
}