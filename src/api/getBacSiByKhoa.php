<?php
/**
 * API: getBacSiByKhoa.php
 * Chức năng: Trả về danh sách bác sĩ theo chuyên khoa
 *
 * Tham số GET:
 *   maChuyenKhoa (bắt buộc) — ID chuyên khoa cần lấy bác sĩ
 *   chỉLấyHoạtĐộng          — mặc định true, chỉ lấy bác sĩ đang hoạt động (TrangThai = 1)
 *
 * Ví dụ:
 *   getBacSiByKhoa.php?maChuyenKhoa=3
 *   getBacSiByKhoa.php?maChuyenKhoa=3&tatCa=1   ← lấy kể cả bác sĩ ngưng hoạt động
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// =====================================================================
// VALIDATE THAM SỐ ĐẦU VÀO
// =====================================================================
$maChuyenKhoa = !empty($_GET['maChuyenKhoa']) ? (int)$_GET['maChuyenKhoa'] : 0;
$tatCa        = !empty($_GET['tatCa'])         ? true                       : false; // false = chỉ lấy đang hoạt động

if ($maChuyenKhoa <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Tham số maChuyenKhoa không hợp lệ hoặc bị thiếu."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if (!isset($pdo)) {
        throw new Exception("Mất kết nối Database.");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // =====================================================================
    // KIỂM TRA CHUYÊN KHOA TỒN TẠI
    // =====================================================================
    $stmtCK = $pdo->prepare("
        SELECT MaChuyenKhoa, TenChuyenKhoa
        FROM CHUYENKHOA
        WHERE MaChuyenKhoa = ?
        LIMIT 1
    ");
    $stmtCK->execute([$maChuyenKhoa]);
    $chuyenKhoaRow = $stmtCK->fetch(PDO::FETCH_ASSOC);

    if (!$chuyenKhoaRow) {
        echo json_encode([
            "success" => false,
            "message" => "Chuyên khoa #" . $maChuyenKhoa . " không tồn tại trong hệ thống."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // =====================================================================
    // TRUY VẤN BÁC SĨ THEO CHUYÊN KHOA
    // Kết hợp bảng BACSI + NHANVIEN (hoặc TAIKHOAN) để lấy tên đầy đủ
    // Điều chỉnh tên bảng/cột nếu schema thực tế khác
    // =====================================================================
    $trangThaiFilter = $tatCa ? '' : 'AND bs.TrangThai = 1';

    /*
     * Schema giả định:
     *   BACSI (MaBacSi, MaChuyenKhoa, MaNhanVien, TrangThai, ...)
     *   NHANVIEN (MaNhanVien, HoTen, SoDienThoai, Email, ...)
     *
     * Nếu tên bác sĩ lưu trực tiếp trong bảng BACSI (cột HoTen), bỏ JOIN đi.
     */
    $sql = "
        SELECT
            bs.MaBacSi,
            bs.MaChuyenKhoa,
            bs.TrangThai,
            COALESCE(bs.HoTen, nv.HoTen, 'Chưa có tên')       AS TenBacSi,
            COALESCE(bs.ChucDanh, bs.HocVi, '')                 AS ChucDanh,
            COALESCE(bs.SoDienThoai, nv.SoDienThoai, '')        AS SoDienThoai,
            COALESCE(bs.Email, nv.Email, '')                     AS Email,
            ck.TenChuyenKhoa
        FROM BACSI bs
        LEFT JOIN NHANVIEN nv  ON nv.MaNhanVien  = bs.MaNhanVien
        LEFT JOIN CHUYENKHOA ck ON ck.MaChuyenKhoa = bs.MaChuyenKhoa
        WHERE bs.MaChuyenKhoa = :maChuyenKhoa
        {$trangThaiFilter}
        ORDER BY TenBacSi ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':maChuyenKhoa', $maChuyenKhoa, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // =====================================================================
    // TRẢ KẾT QUẢ
    // =====================================================================
    echo json_encode([
        "success"     => true,
        "chuyenKhoa"  => $chuyenKhoaRow,
        "data"        => $data,
        "total"       => count($data),
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi hệ thống: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}