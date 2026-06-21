<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/HoaDonModel.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Phương thức HTTP không hợp lệ. Chỉ chấp nhận GET.');
    }

    $action = trim($_GET['action'] ?? '');

    if ($action === 'stats') {
        $stats = HoaDonModel::getTodayStats();
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
        exit;
    }

    if ($action === 'list') {
        $search = trim($_GET['search'] ?? '');
        $trangThai = trim($_GET['trangthai'] ?? '');
        $ngay = trim($_GET['ngay'] ?? '');
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = max(1, min(100, intval($_GET['limit'] ?? 10)));
        $offset = ($page - 1) * $limit;

        $filters = [
            'search' => $search,
            'trangthai' => $trangThai,
            'ngay' => $ngay
        ];

        $list = HoaDonModel::getList($filters, $limit, $offset);
        $total = HoaDonModel::count($filters);

        echo json_encode([
            'success' => true,
            'data' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
        exit;
    }

    if ($action === 'detail') {
        $id = trim($_GET['id'] ?? '');

        if ($id === '') {
            throw new Exception('Thiếu ID hóa đơn để xem chi tiết.');
        }

        $invoice = HoaDonModel::findInvoiceDetail($id);
        if ($invoice) {
            echo json_encode([
                'success' => true,
                'data' => $invoice
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy hóa đơn có ID: ' . $id
            ]);
        }
        exit;
    }

    if ($action === 'pending_patients') {
        $patients = HoaDonModel::getPendingCheckups();
        echo json_encode([
            'success' => true,
            'data' => $patients
        ]);
        exit;
    }

    if ($action === 'checkup_billing_info') {
        $maPhieuKham = intval($_GET['MaPhieuKham'] ?? 0);

        if ($maPhieuKham <= 0) {
            throw new Exception('Mã phiếu khám không hợp lệ.');
        }

        $info = HoaDonModel::getCheckupDetailForBilling($maPhieuKham);
        if ($info) {
            echo json_encode([
                'success' => true,
                'data' => $info
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu hóa đơn nào ứng với phiếu khám này.'
            ]);
        }
        exit;
    }

    throw new Exception('Hành động không được hỗ trợ.');

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}