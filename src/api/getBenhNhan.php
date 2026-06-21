<?php
/**
 * API: getBenhNhan.php
 * Chức năng: Trả về danh sách bệnh nhân từ bảng BENHNHAN
 * Hỗ trợ tìm kiếm theo: CCCD, MaBN, HoTen, SoDienThoai
 * Hỗ trợ lọc theo: TrangThai
 * Hỗ trợ phân trang: page, limit
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if (!isset($pdo)) {
        throw new Exception("Mất kết nối Database.");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =====================================================================
    // THAM SỐ ĐẦU VÀO (GET)
    // =====================================================================
    $keyword    = !empty($_GET['keyword'])    ? trim($_GET['keyword'])        : '';
    $trangThai  = isset($_GET['trangThai'])   ? (string)$_GET['trangThai']    : '';   // '' = tất cả, '1' = hoạt động, '0' = tạm dừng
    $page       = isset($_GET['page'])        ? max(1, (int)$_GET['page'])    : 1;
    $limit      = isset($_GET['limit'])       ? (int)$_GET['limit']           : 0;    // 0 = không phân trang, trả tất cả
    $maBenhNhan = !empty($_GET['maBenhNhan']) ? (int)$_GET['maBenhNhan']      : null; // lấy 1 bệnh nhân cụ thể

    $params = [];

    // =====================================================================
    // TRUY VẤN 1 BỆNH NHÂN CỤ THỂ
    // =====================================================================
    if ($maBenhNhan) {
        $stmt = $pdo->prepare("
            SELECT
                MaBenhNhan, MaTaiKhoan, MaBN, CCCD,
                HoTen, NgaySinh, GioiTinh, SoDienThoai,
                Email, DiaChi, SoBHYT, NhomMau, DiUng,
                TrangThai, NgayTao, NgayCapNhat
            FROM BENHNHAN
            WHERE MaBenhNhan = ?
        ");
        $stmt->execute([$maBenhNhan]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode([
                "success" => false,
                "message" => "Không tìm thấy bệnh nhân #" . $maBenhNhan
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode([
            "success" => true,
            "data"    => $row
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // =====================================================================
    // BUILD WHERE CLAUSE
    // =====================================================================
    $whereClauses = [];

    if ($keyword !== '') {
        $whereClauses[] = "(
            bn.HoTen        LIKE :kw  OR
            bn.CCCD         LIKE :kw  OR
            bn.MaBN         LIKE :kw  OR
            bn.SoDienThoai  LIKE :kw  OR
            bn.Email        LIKE :kw  OR
            bn.SoBHYT       LIKE :kw
        )";
        $params[':kw'] = '%' . $keyword . '%';
    }

    if ($trangThai !== '') {
        $whereClauses[] = "bn.TrangThai = :trangThai";
        $params[':trangThai'] = (int)$trangThai;
    }

    $whereSQL = count($whereClauses) > 0
        ? 'WHERE ' . implode(' AND ', $whereClauses)
        : '';

    // =====================================================================
    // ĐẾM TỔNG (dùng cho phân trang)
    // =====================================================================
    $stmtCount = $pdo->prepare("SELECT COUNT(*) AS total FROM BENHNHAN bn {$whereSQL}");
    $stmtCount->execute($params);
    $totalRecords = (int)$stmtCount->fetchColumn();

    // =====================================================================
    // TRUY VẤN DỮ LIỆU
    // =====================================================================
    $limitSQL  = '';
    $offsetSQL = '';

    if ($limit > 0) {
        $offset    = ($page - 1) * $limit;
        $limitSQL  = "LIMIT :limit";
        $offsetSQL = "OFFSET :offset";
    }

    $sql = "
        SELECT
            bn.MaBenhNhan,
            bn.MaTaiKhoan,
            bn.MaBN,
            bn.CCCD,
            bn.HoTen,
            bn.NgaySinh,
            bn.GioiTinh,
            bn.SoDienThoai,
            bn.Email,
            bn.DiaChi,
            bn.SoBHYT,
            bn.NhomMau,
            bn.DiUng,
            bn.TrangThai,
            bn.NgayTao,
            bn.NgayCapNhat,
            CASE bn.GioiTinh
                WHEN 'M' THEN 'Nam'
                WHEN 'F' THEN 'Nữ'
                ELSE 'Khác'
            END AS TenGioiTinh,
            CASE bn.TrangThai
                WHEN 1 THEN 'Hoạt động'
                ELSE 'Tạm dừng'
            END AS TenTrangThai
        FROM BENHNHAN bn
        {$whereSQL}
        ORDER BY bn.MaBenhNhan DESC
        {$limitSQL}
        {$offsetSQL}
    ";

    $stmt = $pdo->prepare($sql);

    // Bind params từ WHERE
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    // Bind LIMIT / OFFSET riêng (phải là int)
    if ($limit > 0) {
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =====================================================================
    // TRẢ KẾT QUẢ
    // =====================================================================
    $response = [
        "success" => true,
        "data"    => $data,
        "meta"    => [
            "total"       => $totalRecords,
            "page"        => $page,
            "limit"       => $limit,
            "totalPages"  => $limit > 0 ? (int)ceil($totalRecords / $limit) : 1,
            "keyword"     => $keyword,
            "trangThai"   => $trangThai,
        ]
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi hệ thống: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}