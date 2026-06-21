<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

function report_json($success, $data = null, $message = '')
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function report_date_or_default($value, $fallback)
{
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value) ? $value : $fallback;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        report_json(false, null, 'Phuong thuc khong hop le.');
    }

    $today = date('Y-m-d');
    $start = report_date_or_default($_GET['start'] ?? '', date('Y-m-01'));
    $end = report_date_or_default($_GET['end'] ?? '', $today);
    if ($start > $end) {
        [$start, $end] = [$end, $start];
    }

    $paymentMethod = trim($_GET['payment_method'] ?? '');
    $search = trim($_GET['search'] ?? '');
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $limit = max(5, min(50, (int) ($_GET['limit'] ?? 10)));
    $offset = ($page - 1) * $limit;

    $where = ["DATE(tt.NgayThanhToan) BETWEEN :start_date AND :end_date"];
    $params = [
        ':start_date' => $start,
        ':end_date' => $end
    ];

    if ($paymentMethod !== '') {
        $where[] = "tt.MaPhuongThuc = :payment_method";
        $params[':payment_method'] = (int) $paymentMethod;
    }

    if ($search !== '') {
        $where[] = "(hd.SoHoaDon LIKE :search OR bn.HoTen LIKE :search OR bn.MaBN LIKE :search OR pk.MaPhieuKhamCode LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $whereSql = implode(' AND ', $where);
    $baseJoin = "
        FROM thanhtoan tt
        JOIN hoadon hd ON hd.MaHoaDon = tt.MaHoaDon
        JOIN phieukham pk ON pk.MaPhieuKham = hd.MaPhieuKham
        JOIN benhnhan bn ON bn.MaBenhNhan = pk.MaBenhNhan
        LEFT JOIN nhanvien nv ON nv.MaNhanVien = pk.MaBacSi
        LEFT JOIN chuyenkhoa ck ON ck.MaChuyenKhoa = pk.MaChuyenKhoa
        LEFT JOIN phuongthuctt pt ON pt.MaPhuongThuc = tt.MaPhuongThuc
    ";

    $stmt = $pdo->prepare("
        SELECT
            COALESCE(SUM(tt.SoTien), 0) AS total_revenue,
            COALESCE(AVG(tt.SoTien), 0) AS avg_payment,
            COUNT(*) AS total_transactions,
            COUNT(DISTINCT pk.MaBenhNhan) AS total_patients,
            COALESCE(SUM(hd.TongTienCLS), 0) AS total_cls,
            COALESCE(SUM(hd.TongTienThuoc), 0) AS total_medicine
        {$baseJoin}
        WHERE {$whereSql}
    ");
    $stmt->execute($params);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT DATE(tt.NgayThanhToan) AS date_label, COALESCE(SUM(tt.SoTien), 0) AS revenue, COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY DATE(tt.NgayThanhToan)
        ORDER BY date_label ASC
    ");
    $stmt->execute($params);
    $dailyRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT COALESCE(pt.TenPhuongThuc, 'Khong xac dinh') AS method_name,
               tt.MaPhuongThuc,
               COALESCE(SUM(tt.SoTien), 0) AS revenue,
               COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY tt.MaPhuongThuc, pt.TenPhuongThuc
        ORDER BY revenue DESC
    ");
    $stmt->execute($params);
    $paymentStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT COALESCE(ck.TenChuyenKhoa, 'Chua gan khoa') AS specialty,
               COALESCE(SUM(tt.SoTien), 0) AS revenue,
               COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY ck.TenChuyenKhoa
        ORDER BY revenue DESC
        LIMIT 8
    ");
    $stmt->execute($params);
    $specialtyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countStmt = $pdo->prepare("SELECT COUNT(*) {$baseJoin} WHERE {$whereSql}");
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    $sql = "
        SELECT tt.MaThanhToan, tt.NgayThanhToan, tt.SoTien,
               hd.MaHoaDon, hd.SoHoaDon, hd.TongTienCLS, hd.TongTienThuoc, hd.TongThanhToan, hd.TrangThai,
               pk.MaPhieuKhamCode, pk.NgayKham,
               bn.MaBN, bn.HoTen AS TenBenhNhan,
               COALESCE(nv.HoTen, 'Chua gan') AS TenBacSi,
               COALESCE(ck.TenChuyenKhoa, 'Chua gan khoa') AS TenChuyenKhoa,
               COALESCE(pt.TenPhuongThuc, 'Khong xac dinh') AS PhuongThuc
        {$baseJoin}
        WHERE {$whereSql}
        ORDER BY tt.NgayThanhToan DESC, tt.MaThanhToan DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $methodStmt = $pdo->query("SELECT MaPhuongThuc, TenPhuongThuc FROM phuongthuctt ORDER BY MaPhuongThuc ASC");

    report_json(true, [
        'filters' => [
            'start' => $start,
            'end' => $end,
            'payment_method' => $paymentMethod,
            'search' => $search
        ],
        'summary' => $summary,
        'daily_revenue' => $dailyRevenue,
        'payment_stats' => $paymentStats,
        'specialty_stats' => $specialtyStats,
        'transactions' => $transactions,
        'payment_methods' => $methodStmt->fetchAll(PDO::FETCH_ASSOC),
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_records' => $totalRecords,
            'total_pages' => (int) ceil($totalRecords / $limit)
        ]
    ]);
} catch (Throwable $e) {
    report_json(false, null, 'Loi lay bao cao: ' . $e->getMessage());
}
