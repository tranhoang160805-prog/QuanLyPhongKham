<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

function ql_json($success, $data = null, $message = '')
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function ql_scalar(PDO $pdo, $sql, array $params = [], $default = 0)
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $value = $stmt->fetchColumn();
    return $value === false || $value === null ? $default : $value;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        ql_json(false, null, 'Phuong thuc khong hop le.');
    }

    $today = date('Y-m-d');
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');

    $summary = [
        'patients_total' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM benhnhan"),
        'staff_active' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM nhanvien WHERE DangHoatDong = 1"),
        'appointments_today' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM lichhen WHERE NgayHen = ?", [$today]),
        'checkups_today' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM phieukham WHERE NgayKham = ?", [$today]),
        'pending_invoices' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM hoadon WHERE TrangThai IN ('CHO_THANH_TOAN', '0') AND NgayThanhToan IS NULL"),
        'low_stock' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM thuoc WHERE DangHoatDong = 1 AND SoLuongTon <= TonToiThieu"),
        'expired_medicine' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM thuoc WHERE DangHoatDong = 1 AND HanSuDung IS NOT NULL AND HanSuDung < CURDATE()"),
        'cls_waiting' => (int) ql_scalar($pdo, "SELECT COUNT(*) FROM chidinhcls WHERE trangthai = 0"),
        'revenue_today' => (float) ql_scalar($pdo, "SELECT COALESCE(SUM(SoTien), 0) FROM thanhtoan WHERE DATE(NgayThanhToan) = ?", [$today]),
        'revenue_month' => (float) ql_scalar($pdo, "SELECT COALESCE(SUM(SoTien), 0) FROM thanhtoan WHERE DATE(NgayThanhToan) BETWEEN ? AND ?", [$monthStart, $monthEnd])
    ];

    $stmt = $pdo->prepare("
        SELECT DATE(NgayThanhToan) AS label, COALESCE(SUM(SoTien), 0) AS value
        FROM thanhtoan
        WHERE DATE(NgayThanhToan) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(NgayThanhToan)
        ORDER BY label ASC
    ");
    $stmt->execute();
    $rawRevenue = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $revenueLast7 = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} day"));
        $revenueLast7[] = [
            'date' => $date,
            'label' => date('d/m', strtotime($date)),
            'value' => (float) ($rawRevenue[$date] ?? 0)
        ];
    }

    $stmt = $pdo->query("
        SELECT pk.MaTrangThai, COALESCE(tt.TenTrangThai, CONCAT('Trang thai ', pk.MaTrangThai)) AS TenTrangThai, COUNT(*) AS SoLuong
        FROM phieukham pk
        LEFT JOIN trangthaiphieukham tt ON tt.MaTrangThai = pk.MaTrangThai
        WHERE pk.NgayKham >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY pk.MaTrangThai, tt.TenTrangThai
        ORDER BY SoLuong DESC
        LIMIT 8
    ");
    $statusStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT pk.MaPhieuKhamCode, pk.NgayKham, pk.GioKham, pk.MaTrangThai,
               bn.MaBN, bn.HoTen AS TenBenhNhan,
               nv.HoTen AS TenBacSi,
               COALESCE(tt.TenTrangThai, CONCAT('Trang thai ', pk.MaTrangThai)) AS TrangThai
        FROM phieukham pk
        JOIN benhnhan bn ON bn.MaBenhNhan = pk.MaBenhNhan
        LEFT JOIN nhanvien nv ON nv.MaNhanVien = pk.MaBacSi
        LEFT JOIN trangthaiphieukham tt ON tt.MaTrangThai = pk.MaTrangThai
        ORDER BY pk.NgayTao DESC, pk.MaPhieuKham DESC
        LIMIT 8
    ");
    $stmt->execute();
    $recentCheckups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT lh.MaLichHen, lh.NgayHen, lh.GioHen, lh.MaTrangThai,
               bn.MaBN, bn.HoTen, bn.SoDienThoai,
               COALESCE(tt.TenTrangThai, CONCAT('Trang thai ', lh.MaTrangThai)) AS TrangThai
        FROM lichhen lh
        JOIN benhnhan bn ON bn.MaBenhNhan = lh.MaBenhNhan
        LEFT JOIN trangthailichchhen tt ON tt.MaTrangThai = lh.MaTrangThai
        WHERE lh.NgayHen >= CURDATE()
        ORDER BY lh.NgayHen ASC, lh.GioHen ASC
        LIMIT 8
    ");
    $stmt->execute();
    $upcomingAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("
        SELECT MaThuocCode, TenThuoc, SoLuongTon, TonToiThieu, HanSuDung
        FROM thuoc
        WHERE DangHoatDong = 1 AND (SoLuongTon <= TonToiThieu OR (HanSuDung IS NOT NULL AND HanSuDung < DATE_ADD(CURDATE(), INTERVAL 30 DAY)))
        ORDER BY (SoLuongTon <= TonToiThieu) DESC, HanSuDung ASC
        LIMIT 8
    ");
    $medicineAlerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ql_json(true, [
        'summary' => $summary,
        'revenue_last_7_days' => $revenueLast7,
        'status_stats' => $statusStats,
        'recent_checkups' => $recentCheckups,
        'upcoming_appointments' => $upcomingAppointments,
        'medicine_alerts' => $medicineAlerts
    ]);
} catch (Throwable $e) {
    ql_json(false, null, 'Loi lay du lieu dashboard: ' . $e->getMessage());
}
