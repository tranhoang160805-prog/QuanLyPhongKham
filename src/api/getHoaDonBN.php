<?php
// 1. Cấu hình header trả về JSON và ngăn chặn lưu cache
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Nhúng file kết nối cơ sở dữ liệu ($pdo)
// Hãy điều chỉnh lại đường dẫn dẫn tới file chứa kết nối database thực tế của bạn
require_once __DIR__ . '/../../config/database.php';

// 3. Kiểm tra quyền truy cập hệ thống
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Hết phiên làm việc. Vui lòng đăng nhập lại.']);
    exit;
}

// 4. Kiểm tra tham số đầu vào
$maHoaDon = isset($_GET['ma_hoa_don']) ? (int)$_GET['ma_hoa_don'] : 0;
if ($maHoaDon <= 0) {
    echo json_encode(['success' => false, 'message' => 'Mã hóa đơn không hợp lệ hoặc thiếu dữ liệu đầu vào.']);
    exit;
}

try {
    // TRUY VẤN TẦNG 1: Thông tin tổng quan hóa đơn & phiếu khám
    $stmtInfo = $pdo->prepare("
        SELECT 
            hd.SoHoaDon,
            pk.MaPhieuKhamCode,
            pk.NgayKham,
            pk.LyDoKham,
            pk.ChanDoan,
            hd.TongTienKham,
            hd.TongTienCLS,
            hd.TongTienThuoc,
            hd.GiamGia,
            hd.TongThanhToan,
            hd.TrangThai
        FROM hoadon hd
        JOIN phieukham pk ON pk.MaPhieuKham = hd.MaPhieuKham
        WHERE hd.MaHoaDon = :ma_hd
    ");
    $stmtInfo->execute(['ma_hd' => $maHoaDon]);
    $invoiceInfo = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    // Nếu không tìm thấy bản ghi nào khớp
    if (!$invoiceInfo) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin hóa đơn này trong hệ thống.']);
        exit;
    }

    // TRUY VẤN TẦNG 2: Lấy danh sách các dịch vụ Cận Lâm Sàng (Xét nghiệm, siêu âm, chụp X-Quang...)
    $stmtCls = $pdo->prepare("
        SELECT 
            cls.DonGia,
            cls.SoLuong,
            cls.ThanhTien,
            cls.TrangThai
        FROM chidinhcls cls
        JOIN hoadon hd ON hd.MaPhieuKham = cls.MaPhieuKham
        WHERE hd.MaHoaDon = :ma_hd
    ");
    $stmtCls->execute(['ma_hd' => $maHoaDon]);
    $clsDetails = $stmtCls->fetchAll(PDO::FETCH_ASSOC);

    // TRUY VẤN TẦNG 3: Lấy danh sách danh mục thuốc kèm đơn thuốc (nếu có)
    $stmtThuoc = $pdo->prepare("
        SELECT 
            t.TenThuoc,
            ctdt.SoLuong,
            ctdt.DonGia,
            ctdt.CachDung
        FROM hoadon hd
        JOIN donthuoc dt ON dt.MaPhieuKham = hd.MaPhieuKham
        JOIN chitietdonthuoc ctdt ON ctdt.MaDonThuoc = dt.MaDonThuoc
        JOIN thuoc t ON t.MaThuoc = ctdt.MaThuoc
        WHERE hd.MaHoaDon = :ma_hd
    ");
    $stmtThuoc->execute(['ma_hd' => $maHoaDon]);
    $thuocDetails = $stmtThuoc->fetchAll(PDO::FETCH_ASSOC);

    // 5. Đóng gói dữ liệu đồng bộ gửi về Client
    echo json_encode([
        'success' => true,
        'info'    => $invoiceInfo,
        'cls'     => $clsDetails,
        'thuoc'   => $thuocDetails
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Trả ra thông báo lỗi dạng chuỗi JSON sạch để Frontend không bị vỡ định dạng phân tích dữ liệu
    echo json_encode(['success' => false, 'message' => 'Lỗi xử lý Database từ API: ' . $e->getMessage()]);
}
exit;