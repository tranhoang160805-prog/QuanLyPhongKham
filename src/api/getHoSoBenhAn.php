<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');


$maTaiKhoan = $_SESSION['user_id'];


try {

    $stmtPatient = $pdo->prepare("SELECT MaBenhNhan FROM BENHNHAN WHERE MaTaiKhoan = :maTaiKhoan LIMIT 1");
    $stmtPatient->execute(['maTaiKhoan' => $maTaiKhoan]);
    $patient = $stmtPatient->fetch();

    if (!$patient) {
        http_response_code(444);
        echo json_code(false, "Không tìm thấy thông tin bệnh nhân tương ứng với tài khoản này.");
        exit();
    }

    $maBenhNhan = $patient['MaBenhNhan'];

    $sqlRecords = "
        SELECT 
            pk.MaPhieuKhamCode,
            pk.NgayKham,
            pk.GioKham,
            pk.LyDoKham,
            pk.TrieuChung,
            pk.TienSuBenh,
            pk.ThongSoSinhTon,
            pk.ChanDoan,
            pk.GhiChu,
            nv.HoTen AS TenBacSi,
            ck.TenChuyenKhoa,
            tt.TenTrangThai
        FROM PHIEUKHAM pk
        INNER JOIN NHANVIEN nv ON pk.MaBacSi = nv.MaNhanVien
        INNER JOIN CHUYENKHOA ck ON pk.MaChuyenKhoa = ck.MaChuyenKhoa
        INNER JOIN TRANGTHAIPHIEUKHAM tt ON pk.MaTrangThai = tt.MaTrangThai
        WHERE pk.MaBenhNhan = :maBenhNhan
        ORDER BY pk.NgayKham DESC, pk.GioKham DESC
    ";

    $stmtRecords = $pdo->prepare($sqlRecords);
    $stmtRecords->execute(['maBenhNhan' => $maBenhNhan]);
    $records = $stmtRecords->fetchAll();

    foreach ($records as &$row) {
        if (!empty($row['ThongSoSinhTon'])) {
            $row['ThongSoSinhTon'] = json_decode($row['ThongSoSinhTon'], true);
        }
    }

    // 5. Trả về kết quả thành công
    echo json_code(true, "Lấy danh sách hồ sơ bệnh án thành công.", [
        "MaBenhNhan" => $maBenhNhan,
        "DanhSachPhieuKham" => $records
    ]);

} catch (\PDOException $e) {
    // Xử lý lỗi kết nối hoặc truy vấn SQL
    http_response_code(500);
    echo json_code(false, "Lỗi hệ thống: " . $e->getMessage());
}

/**
 * Hàm bổ trợ định dạng JSON trả về cho đồng bộ
 */
function json_code($success, $message, $data = null) {
    $response = [
        "success" => $success,
        "message" => $message
    ];
    if ($data !== null) {
        $response["data"] = $data;
    }
    return json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>