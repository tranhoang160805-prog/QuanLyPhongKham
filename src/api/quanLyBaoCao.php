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
        report_json(false, null, 'Phương thức không hợp lệ.');
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

    // Chỉ tính toán doanh thu dựa trên các hóa đơn đã thanh toán thành công
    $where = [
        "DATE(hd.NgayThanhToan) BETWEEN :start_date AND :end_date",
        "hd.TrangThai = '1'"
    ];
    
    $params = [
        ':start_date' => $start,
        ':end_date' => $end
    ];

    if ($paymentMethod !== '') {
        $where[] = "hd.PhuongThuc = :payment_method";
        $params[':payment_method'] = $paymentMethod;
    }

    if ($search !== '') {
        $where[] = "(hd.SoHoaDon LIKE :search OR bn.HoTen LIKE :search OR bn.MaBN LIKE :search OR pk.MaPhieuKhamCode LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    $whereSql = implode(' AND ', $where);
    
    // Gốc kết nối mới: Xuất phát trực tiếp từ bảng hoadon thay vì thanhtoan
    $baseJoin = "
        FROM hoadon hd
        JOIN phieukham pk ON pk.MaPhieuKham = hd.MaPhieuKham
        JOIN benhnhan bn ON bn.MaBenhNhan = pk.MaBenhNhan
        LEFT JOIN nhanvien nv ON nv.MaNhanVien = pk.MaBacSi
        LEFT JOIN chuyenkhoa ck ON ck.MaChuyenKhoa = pk.MaChuyenKhoa
    ";

    // 1. Thống kê tổng quan (Summary)
    $stmt = $pdo->prepare("
        SELECT
            COALESCE(SUM(hd.TongThanhToan), 0) AS total_revenue,
            COALESCE(AVG(hd.TongThanhToan), 0) AS avg_payment,
            COUNT(*) AS total_transactions,
            COUNT(DISTINCT pk.MaBenhNhan) AS total_patients,
            COALESCE(SUM(hd.TongTienCLS), 0) AS total_cls,
            COALESCE(SUM(hd.TongTienThuoc), 0) AS total_medicine
        {$baseJoin}
        WHERE {$whereSql}
    ");
    $stmt->execute($params);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Doanh thu theo ngày (Daily revenue)
    $stmt = $pdo->prepare("
        SELECT DATE(hd.NgayThanhToan) AS date_label, 
               COALESCE(SUM(hd.TongThanhToan), 0) AS revenue, 
               COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY DATE(hd.NgayThanhToan)
        ORDER BY date_label ASC
    ");
    $stmt->execute($params);
    $dailyRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Tỷ trọng theo phương thức thanh toán
    $stmt = $pdo->prepare("
        SELECT COALESCE(hd.PhuongThuc, 'Chưa xác định') AS method_name,
               hd.PhuongThuc AS MaPhuongThuc,
               COALESCE(SUM(hd.TongThanhToan), 0) AS revenue,
               COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY hd.PhuongThuc
        ORDER BY revenue DESC
    ");
    $stmt->execute($params);
    $paymentStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Doanh thu theo chuyên khoa
    $stmt = $pdo->prepare("
        SELECT COALESCE(ck.TenChuyenKhoa, 'Chưa gán khoa') AS specialty,
               COALESCE(SUM(hd.TongThanhToan), 0) AS revenue,
               COUNT(*) AS transactions
        {$baseJoin}
        WHERE {$whereSql}
        GROUP BY ck.TenChuyenKhoa
        ORDER BY revenue DESC
        LIMIT 8
    ");
    $stmt->execute($params);
    $specialtyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Phân trang giao dịch
    $countStmt = $pdo->prepare("SELECT COUNT(*) {$baseJoin} WHERE {$whereSql}");
    $countStmt->execute($params);
    $totalRecords = (int) $countStmt->fetchColumn();

    // 6. Lấy danh sách chi tiết các giao dịch hóa đơn
    $sql = "
        SELECT hd.MaHoaDon AS MaThanhToan, hd.NgayThanhToan, hd.TongThanhToan AS SoTien,
               hd.MaHoaDon, hd.SoHoaDon, hd.TongTienCLS, hd.TongTienThuoc, hd.TongThanhToan, hd.TrangThai,
               pk.MaPhieuKhamCode, pk.NgayKham,
               bn.MaBN, bn.HoTen AS TenBenhNhan,
               COALESCE(nv.HoTen, 'Chưa gán') AS TenBacSi,
               COALESCE(ck.TenChuyenKhoa, 'Chưa gán khoa') AS TenChuyenKhoa,
               COALESCE(hd.PhuongThuc, 'Chưa xác định') AS PhuongThuc
        {$baseJoin}
        WHERE {$whereSql}
        ORDER BY hd.NgayThanhToan DESC, hd.MaHoaDon DESC
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

    // Danh sách các phương thức thanh toán động có trong bảng hóa đơn để render vào bộ lọc (filter dropdown)
    $methodStmt = $pdo->query("
        SELECT DISTINCT PhuongThuc AS MaPhuongThuc, COALESCE(PhuongThuc, 'Chưa xác định') AS TenPhuongThuc 
        FROM hoadon 
        WHERE PhuongThuc IS NOT NULL AND PhuongThuc != '' 
        ORDER BY PhuongThuc ASC
    ");
    $paymentMethods = $methodStmt->fetchAll(PDO::FETCH_ASSOC);

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
        'payment_methods' => $paymentMethods,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total_records' => $totalRecords,
            'total_pages' => (int) ceil($totalRecords / $limit)
        ]
    ]);
} catch (Throwable $e) {
    report_json(false, null, 'Lỗi lấy báo cáo: ' . $e->getMessage());
}
?>