<?php
// Đường dẫn: src/api/getDSThuoc.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;
    $filter = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : ''; 

    if ($page < 1) $page = 1;
    if ($limit < 1) $limit = 7;
    $offset = ($page - 1) * $limit;

    // Thời mốc hệ thống của ứng dụng
    $current_date = "2026-05-27";

    // =========================================================================
    // 1. THỐNG KÊ SỐ LIỆU CHO 4 Ô BENTO (Luôn tính trên toàn kho, trừ bộ lọc ra)
    // =========================================================================
    $sql_bento = "SELECT 
        COUNT(CASE WHEN DangHoatDong = 1 THEN 1 END) as total_types,
        COUNT(CASE WHEN DangHoatDong = 1 AND SoLuongTon <= TonToiThieu THEN 1 END) as total_low_stock,
        COUNT(CASE WHEN DangHoatDong = 1 AND HanSuDung IS NOT NULL AND HanSuDung <= DATE_ADD(:curr_date1, INTERVAL 30 DAY) AND HanSuDung > :curr_date2 THEN 1 END) as total_near_expired,
        COUNT(CASE WHEN DangHoatDong = 1 AND HanSuDung IS NOT NULL AND HanSuDung <= :curr_date3 THEN 1 END) as total_expired
    FROM THUOC";
    
    $stmt_bento = $pdo->prepare($sql_bento);
    $stmt_bento->execute([
        ':curr_date1' => $current_date,
        ':curr_date2' => $current_date,
        ':curr_date3' => $current_date
    ]);
    $bento_stats = $stmt_bento->fetch(PDO::FETCH_ASSOC);


    // =========================================================================
    // 2. XỬ LÝ ĐIỀU KIỆN LỌC (WHERE) THEO TAB & Ô TÌM KIẾM CHO DANH SÁCH BẢNG
    // =========================================================================
    $where_clauses = [];
    $sql_params = [];

    // Xử lý bộ lọc Tab trạng thái hoạt động / ẩn danh mục
    if ($filter === 'inactive') {
        // Chỉ lấy thuốc đã bị ẩn mềm (DangHoatDong = 0)
        $where_clauses[] = "t.DangHoatDong = 0";
    } else {
        // Mặc định các tab khác (all, expired, low_stock) chỉ lấy thuốc đang hoạt động
        $where_clauses[] = "t.DangHoatDong = 1";

        if ($filter === 'expired') {
            // Lọc cả thuốc đã quá hạn HOẶC sắp hết hạn trong vòng 30 ngày
            $where_clauses[] = "t.HanSuDung IS NOT NULL AND t.HanSuDung <= DATE_ADD(:filter_date, INTERVAL 30 DAY)";
            $sql_params[':filter_date'] = $current_date;
        } elseif ($filter === 'low_stock') {
            // Lọc thuốc sắp hết kho
            $where_clauses[] = "t.SoLuongTon <= t.TonToiThieu";
        }
    }

    // Xử lý tìm kiếm chuỗi text (Tên thuốc, Mã Code, Hoạt chất)
    if ($search !== '') {
        $where_clauses[] = "(t.TenThuoc LIKE :search OR t.MaThuocCode LIKE :search_code OR t.TenHoatChat LIKE :search_hc)";
        $search_wildcard = "%$search%";
        $sql_params[':search'] = $search_wildcard;
        $sql_params[':search_code'] = $search_wildcard;
        $sql_params[':search_hc'] = $search_wildcard;
    }

    $where_str = "WHERE " . implode(" AND ", $where_clauses);


    // =========================================================================
    // 3. TÍNH SỐ LƯỢNG BẢN GHI PHÂN TRANG CỦA DANH SÁCH ĐANG HIỂN THỊ
    // =========================================================================
    $sql_count = "SELECT COUNT(t.MaThuoc) FROM THUOC t $where_str";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($sql_params);
    $total_records = (int)$stmt_count->fetchColumn();
    $total_pages = $total_records > 0 ? ceil($total_records / $limit) : 1;


    // =========================================================================
    // 4. LẤY DỮ LIỆU THỰC TẾ PHÂN TRANG (DATA LIST)
    // =========================================================================
    $sql_data = "SELECT t.*, d.TenDonVi 
                 FROM THUOC t 
                 LEFT JOIN DONVITINH d ON t.MaDonVi = d.MaDonVi 
                 $where_str 
                 ORDER BY t.NgayTao DESC 
                 LIMIT :offset, :limit";
                 
    $stmt_data = $pdo->prepare($sql_data);
    
    // Bind các tham số điều kiện lọc
    foreach ($sql_params as $param_key => $param_val) {
        $stmt_data->bindValue($param_key, $param_val);
    }
    // Bind tham số phân trang bắt buộc dạng Số nguyên INT
    $stmt_data->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt_data->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt_data->execute();
    $thuoc_list = $stmt_data->fetchAll(PDO::FETCH_ASSOC);

    // Chuẩn hóa dữ liệu trả về Frontend
    $formatted_list = [];
    foreach ($thuoc_list as $thuoc) {
        $formatted_list[] = [
            'MaThuoc'        => (int)$thuoc['MaThuoc'],
            'MaThuocCode'    => $thuoc['MaThuocCode'],
            'TenThuoc'       => $thuoc['TenThuoc'],
            'TenHoatChat'    => $thuoc['TenHoatChat'],
            'HamLuong'       => $thuoc['HamLuong'],
            'TenDonVi'       => $thuoc['TenDonVi'] ?? 'Chưa xác định',
            'DangBaoChe'     => $thuoc['DangBaoChe'],
            'QuyCach'        => $thuoc['QuyCach'],
            'NhaSanXuat'    => $thuoc['NhaSanXuat'],
            'NuocSanXuat'    => $thuoc['NuocSanXuat'],
            'SoDangKy'      => $thuoc['SoDangKy'],
            'GiaNhap'        => (float)$thuoc['GiaNhap'],
            'SoLuongTon'     => (int)$thuoc['SoLuongTon'],
            'TonToiThieu'    => (int)$thuoc['TonToiThieu'],
            'GiaBan'         => (float)$thuoc['GiaBan'],
            'HanSuDung'      => $thuoc['HanSuDung'],
            'DangHoatDong'   => (int)$thuoc['DangHoatDong'] // Trả về để frontend biết xử lý opacity dòng Inactive
        ];
    }

    // Trả kết quả JSON khớp cấu trúc Frontend nhận diện
    echo json_encode([
        'success' => true,
        'stats'   => [
            'total_thuoc_types' => (int)($bento_stats['total_types'] ?? 0),
            'total_low_stock'   => (int)($bento_stats['total_low_stock'] ?? 0),
            'total_near_expired'=> (int)($bento_stats['total_near_expired'] ?? 0),
            'total_expired'     => (int)($bento_stats['total_expired'] ?? 0)
        ],
        'pagination' => [
            'current_page'  => $page,
            'limit'         => $limit,
            'total_pages'   => $total_pages,
            'total_records' => $total_records
        ],
        'data' => $formatted_list
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()]);
}
exit;