<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.'
    ]);
    exit;
}

try {
    $sql = "SELECT 
                cd.MaChiDinh,
                cd.MaPhieuKham,
                pk.MaPhieuKhamCode,
                cd.MaLoaiCLS,
                lcls.TenLoaiCLS,
                lcls.DonGia,
                cd.MoTaChiDinh,
                cd.NgayChiDinh,
                cd.trangthai AS TrangThaiChiDinh,
                
                -- Thông tin bệnh nhân
                bn.MaBN,
                bn.HoTen,
                bn.NgaySinh,
                bn.GioiTinh,
                bn.DiaChi,
                
                -- Thông tin kết quả cận lâm sàng (nếu có)
                kq.MaKetQua,
                kq.KetQuaText,
                kq.KetLuan,
                kq.FileKetQua,
                kq.NgayThucHien,
                kq.MaNVThucHien,
                nv.HoTen AS TenNhanVienThucHien
            FROM chidinhcls cd
            INNER JOIN phieukham pk ON cd.MaPhieuKham = pk.MaPhieuKham
            INNER JOIN benhnhan bn ON pk.MaBenhNhan = bn.MaBenhNhan
            INNER JOIN loaiclsn lcls ON cd.MaLoaiCLS = lcls.MaLoaiCLS
            LEFT JOIN ketquacls kq ON cd.MaChiDinh = kq.MaChiDinh
            LEFT JOIN nhanvien nv ON kq.MaNVThucHien = nv.MaNhanVien
            WHERE pk.MaTrangThai IN (5, 6) AND DATE(cd.NgayChiDinh) = CURDATE()
            ORDER BY cd.MaChiDinh DESC, cd.MaPhieuKham DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'count' => count($data),
        'data' => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi truy vấn cơ sở dữ liệu hệ thống: ' . $e->getMessage()
    ]);
}
exit;