<?php
// getChiTietHoaDon.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Kiểm tra quyền truy cập (nếu hệ thống của bạn có phân quyền)
if (!isset($_SESSION['user_role'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.'
    ]);
    exit;
}

try {
    // 2. Lấy và kiểm tra tham số mã phiếu khám từ URL
    $maPhieuKham = isset($_GET['maphieukham']) ? intval($_GET['maphieukham']) : 0;
    
    if ($maPhieuKham <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Mã phiếu khám không hợp lệ.'
        ]);
        exit;
    }

    // 3. Truy vấn thông tin chi tiết dịch vụ và thuốc từ VIEW
    $sql = "SELECT 
                MaPhieuKham,
                MaPhieuKhamCode,
                MaBenhNhan,
                LoaiMuc,
                MaMuc,
                TenMuc,
                SoLuong,
                DonGia,
                NgayTaoMuc
            FROM v_hoadon_chitiet 
            WHERE MaPhieuKham = :ma_phieu_kham
            ORDER BY LoaiMuc ASC, NgayTaoMuc ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ma_phieu_kham' => $maPhieuKham]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Duyệt mảng dữ liệu để tính toán ThanhTien từng mục và TongTien của cả hóa đơn
    $chitiet = [];
    $tongTienHoaDon = 0;
    $maPhieuKhamCode = '';
    $maBenhNhan = 0;

    foreach ($records as $row) {
        // Lưu lại thông tin chung của phiếu khám
        if (empty($maPhieuKhamCode)) {
            $maPhieuKhamCode = $row['MaPhieuKhamCode'];
            $maBenhNhan = $row['MaBenhNhan'];
        }

        // Ép kiểu dữ liệu số để tính toán chính xác
        $soLuong = intval($row['SoLuong']);
        $donGia = floatval($row['DonGia']);
        $thanhTien = $soLuong * $donGia; // Code tự tính toán dựa trên yêu cầu của bạn

        // Tích lũy vào tổng tiền của cả hóa đơn
        $tongTienHoaDon += $thanhTien;

        // Đóng gói lại phần tử để trả về dữ liệu chuẩn
        $chitiet[] = [
            'LoaiMuc'    => $row['LoaiMuc'],
            'MaMuc'      => intval($row['MaMuc']),
            'TenMuc'     => $row['TenMuc'],
            'SoLuong'    => $soLuong,
            'DonGia'     => $donGia,
            'ThanhTien'  => $thanhTien, // Trả thêm cột thành tiền đã tính cho frontend dễ hiển thị
            'NgayTaoMuc' => $row['NgayTaoMuc']
        ];
    }

    // 5. Trả kết quả JSON về cho client
    echo json_encode([
        'status' => 'success',
        'data' => [
            'MaPhieuKham'     => $maPhieuKham,
            'MaPhieuKhamCode' => $maPhieuKhamCode,
            'MaBenhNhan'      => $maBenhNhan,
            'TongTien'        => $tongTienHoaDon, 
            'ChiTietMục'      => $chitiet 
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Lỗi hệ thống khi lấy thông tin hóa đơn: ' . $e->getMessage()
    ]);
}