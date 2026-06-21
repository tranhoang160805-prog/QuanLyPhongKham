<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lùi 2 cấp từ src/api/ để về thư mục gốc QuanLyPhongKham
require_once __DIR__ . '/../../config/database.php';

if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu.']);
    exit;
}

// 1. Nhận tham số phân trang & bộ lọc từ View gửi lên (Query String)
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$status_filter = (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') ? (int)$_GET['status_filter'] : null;

// 2. Xác định vai trò để xếp thứ tự ưu tiên (Logic phân vai của bạn)
$real_role = $_SESSION['user_role'] ?? 'le-tan';
$menu_role = $_SESSION['current_view_role'] ?? $real_role;
if ($real_role !== 'admin') {
    $menu_role = $real_role; 
}

$target_status = 1; 
if ($menu_role === 'bac-si') $target_status = 4;
if ($menu_role === 'ky-thuat-vien') $target_status = 7;
if ($menu_role === 'le-tan') $target_status = 10;

try {
    // --- PHẦN THÊM VÀO: TRUY VẤN TÍNH TOÁN SỐ LIỆU THỐNG KÊ (STATS) CHO LỚP PHP VIEW ---
    // Giả định đếm các ca đăng ký trong ngày hôm nay
    $sql_stats = "SELECT 
        COUNT(CASE WHEN MaTrangThai = 1 THEN 1 END) as total_cc,
        COUNT(CASE WHEN MaTrangThai = 2 THEN 1 END) as total_sk,
        COUNT(CASE WHEN MaTrangThai = 3 THEN 1 END) as total_kb,
        COUNT(CASE WHEN MaTrangThai = 4 THEN 1 END) as total_xn,
        COUNT(CASE WHEN MaTrangThai = 5 THEN 1 END) as total_ct,
        COUNT(CASE WHEN MaTrangThai = 7 THEN 1 END) as total_ht,
        COUNT(CASE WHEN MaTrangThai = 8 THEN 1 END) as total_huy
    FROM PHIEUKHAM WHERE DATE(NgayTao) = CURDATE()";
    
    // Nếu cấu hình bảng của bạn không có trường NgayTao, hãy dùng câu lệnh gọn dưới đây:
    // $sql_stats = "SELECT 
    //     COUNT(CASE WHEN MaTrangThai = 1 THEN 1 END) as total_cc,
    //     COUNT(CASE WHEN MaTrangThai = 2 THEN 1 END) as total_sk,
    //     COUNT(CASE WHEN MaTrangThai = 3 THEN 1 END) as total_kb,
    //     COUNT(CASE WHEN MaTrangThai = 4 THEN 1 END) as total_xn,
    //     COUNT(CASE WHEN MaTrangThai = 5 THEN 1 END) as total_ct,
    //     COUNT(CASE WHEN MaTrangThai = 7 THEN 1 END) as total_ht,
    //     COUNT(CASE WHEN MaTrangThai = 8 THEN 1 END) as total_huy
    // FROM PHIEUKHAM";

    $stmt_stats = $pdo->query($sql_stats);
    $stats_data = $stmt_stats->fetch(PDO::FETCH_ASSOC);

    // 3. Xây dựng điều kiện WHERE lọc dữ liệu danh sách bệnh nhân
    $search_keyword = (isset($_GET['search']) && trim($_GET['search']) !== '') ? trim($_GET['search']) : null;

    $where_clause = "WHERE DATE(pk.NgayTao) = DATE(NOW())";
    $params = [];
    if ($status_filter !== null) {
        $where_clause .= " AND pk.MaTrangThai = :status_filter";
        $params[':status_filter'] = $status_filter;
    }
    // Tìm kiếm theo MaPhieuKhamCode, MaBN, STT, hoặc Họ Tên
    if ($search_keyword !== null) {
        $where_clause .= " AND (
            pk.MaPhieuKhamCode LIKE :search_code OR
            pk.STT             LIKE :search_stt  OR
            bn.MaBN            LIKE :search_mabn OR
            bn.HoTen           LIKE :search_hoten
        )";
        $params[':search_code']  = '%' . $search_keyword . '%';
        $params[':search_stt']   = '%' . $search_keyword . '%';
        $params[':search_mabn']  = '%' . $search_keyword . '%';
        $params[':search_hoten'] = '%' . $search_keyword . '%';
    }

    // 4. ĐẾM TỔNG SỐ BẢN GHI (để View nhận số liệu tự tính toán số trang)
    $sql_count = "SELECT COUNT(*) FROM PHIEUKHAM pk INNER JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan $where_clause";
    $stmt_count = $pdo->prepare($sql_count);
    foreach ($params as $key => $value) {
        $paramType = (strpos($key, ':search_') === 0) ? PDO::PARAM_STR : PDO::PARAM_INT;
        $stmt_count->bindValue($key, $value, $paramType);
    }
    $stmt_count->execute();
    $total_records = (int)$stmt_count->fetchColumn();

    // Tính toán offset phân trang dựa trên số $page nhận từ View
    $total_pages = ceil($total_records / $limit);
    if ($total_pages < 1) $total_pages = 1;
    if ($page > $total_pages) $page = $total_pages; 
    $offset = ($page - 1) * $limit;

    // 5. TRUY VẤN LẤY DỮ LIỆU DANH SÁCH THÔ
    $sql_list = "SELECT 
                    pk.*,
                    bn.MaBenhNhan, 
                    bn.MaBN, 
                    bn.HoTen,
                    bn.NgaySinh,
                    bn.GioiTinh, 
                    bn.DiUng
                 FROM PHIEUKHAM pk
                 INNER JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan
                 $where_clause
                 ORDER BY 
                    CASE WHEN pk.MaTrangThai = :target_status THEN 0 ELSE 1 END ASC, 
                    CASE WHEN pk.GioTiepNhan IS NULL THEN 1 ELSE 0 END ASC,
                    pk.STT ASC,
                    pk.MaPhieuKham ASC
                 LIMIT :limit OFFSET :offset";

    $stmt_list = $pdo->prepare($sql_list);
    
    // Bind các tham số lọc động nếu có
    foreach ($params as $key => $value) {
        // Tham số search dùng kiểu chuỗi, tham số còn lại dùng kiểu số nguyên
        $paramType = (strpos($key, ':search_') === 0) ? PDO::PARAM_STR : PDO::PARAM_INT;
        $stmt_list->bindValue($key, $value, $paramType);
    }
    // Bind các tham số phân trang bắt buộc
    $stmt_list->bindValue(':target_status', $target_status, PDO::PARAM_INT);
    $stmt_list->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt_list->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt_list->execute();
    $patient_list = $stmt_list->fetchAll(PDO::FETCH_ASSOC);

    // 6. XUẤT JSON ĐỦ CẢ STATS VÀ DATA VỀ CHO FILE VIEW NHẬN DIỆN
    echo json_encode([
        'success' => true,
        'stats' => [
            'total_cc'  => (int)($stats_data['total_cc'] ?? 0),
            'total_sk'  => (int)($stats_data['total_sk'] ?? 0),
            'total_kb'  => (int)($stats_data['total_kb'] ?? 0),
            'total_xn'  => (int)($stats_data['total_xn'] ?? 0),
            'total_ct'  => (int)($stats_data['total_ct'] ?? 0),
            'total_ht'  => (int)($stats_data['total_ht'] ?? 0),
            'total_huy' => (int)($stats_data['total_huy'] ?? 0)
        ],
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'limit' => $limit
        ],
        'data' => $patient_list
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $e->getMessage()]);
}