<?php
require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Hàm bổ trợ kết nối PDO toàn cục
function getPdoConnection() {
    global $pdo;
    if (!isset($pdo)) {
        require __DIR__ . '/../../config/database.php';
    }
    return $pdo;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Phương thức HTTP không hợp lệ. Chỉ chấp nhận GET.');
    }

    $action = trim($_GET['action'] ?? '');
    $db = getPdoConnection();

    switch ($action) {
        // ==========================================
        // ACTION 1: LẤY THỐNG KÊ NGÀY HÔM NAY
        // ==========================================
        case 'stats':
            $sql_rev = "SELECT SUM(TongThanhToan) as total_rev FROM HOADON 
                        WHERE TrangThai = 'DA_THANH_TOAN' AND DATE(NgayThanhToan) = CURRENT_DATE()";
            $stmt = $db->query($sql_rev);
            $res_rev = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_rev = floatval($res_rev['total_rev'] ?? 0);

            $sql_pending = "SELECT COUNT(*) as pending_count FROM HOADON WHERE TrangThai = 'CHO_THANH_TOAN'";
            $stmt_pending = $db->query($sql_pending);
            $res_pending = $stmt_pending->fetch(PDO::FETCH_ASSOC);
            $pending_count = intval($res_pending['pending_count'] ?? 0);

            echo json_encode([
                'success' => true,
                'data' => [
                    'today_revenue' => $total_rev,
                    'pending_payment' => $pending_count
                ]
            ]);
            break;

        // ==========================================
        // ACTION 2: DANH SÁCH HÓA ĐƠN (CÓ LỌC & PHÂN TRANG)
        // ==========================================
        case 'list':
            $search = trim($_GET['search'] ?? '');
            $trangThai = trim($_GET['trangthai'] ?? '');
            $ngay = trim($_GET['ngay'] ?? '');
            $page = max(1, intval($_GET['page'] ?? 1));
            $limit = max(1, min(100, intval($_GET['limit'] ?? 10)));
            $offset = ($page - 1) * $limit;

            $conditions = [];
            $params = [];

            // Thiết lập câu lệnh SELECT dữ liệu và COUNT dữ liệu phân trang
            $sql_base = "FROM HOADON hd
                         JOIN PHIEUKHAM pk ON hd.MaPhieuKham = pk.MaPhieuKham
                         JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan";

            if (!empty($search)) {
                $conditions[] = "(bn.HoTen LIKE :search OR bn.MaBN LIKE :search OR hd.SoHoaDon LIKE :search)";
                $params[':search'] = '%' . $search . '%';
            }
            if (!empty($trangThai)) {
                $conditions[] = "hd.TrangThai = :trangthai";
                $params[':trangthai'] = $trangThai;
            }
            if (!empty($ngay)) {
                $conditions[] = "DATE(hd.NgayTao) = :ngay";
                $params[':ngay'] = $ngay;
            }

            $whereSql = !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";

            // 2.1 Đếm tổng số dòng phục vụ phân trang
            $countStmt = $db->prepare("SELECT COUNT(*) " . $sql_base . $whereSql);
            foreach ($params as $k => $v) $countStmt->bindValue($k, $v);
            $countStmt->execute();
            $totalRecords = (int)$countStmt->fetchColumn();
            $totalPages = ceil($totalRecords / $limit);

            // 2.2 Lấy mảng dữ liệu thực tế
            $sql_data = "SELECT hd.*, bn.HoTen as TenBenhNhan, bn.MaBN as MaBenhNhanCode, bn.SoDienThoai, pk.MaPhieuKhamCode " 
                        . $sql_base . $whereSql . " ORDER BY hd.NgayTao DESC LIMIT :limit OFFSET :offset";
            
            $dataStmt = $db->prepare($sql_data);
            foreach ($params as $k => $v) $dataStmt->bindValue($k, $v);
            $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $dataStmt->execute();
            $list = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'data' => $list,
                'pagination' => [
                    'total_records' => $totalRecords,
                    'total_pages' => $totalPages,
                    'current_page' => $page,
                    'limit' => $limit
                ]
            ]);
            break;

        // ==========================================
        // ACTION 3: LẤY CÁC CA CHỜ LẬP HÓA ĐƠN (ĐÃ KHÁM XONG)
        // ==========================================
        case 'pending_patients':
            $sql = "SELECT pk.MaPhieuKham, pk.MaPhieuKhamCode, pk.NgayKham, pk.ChanDoan, 
                           bn.MaBenhNhan, bn.MaBN as MaBenhNhanCode, bn.HoTen, bn.GioiTinh, bn.NgaySinh, bn.SoDienThoai
                    FROM PHIEUKHAM pk
                    JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan
                    LEFT JOIN HOADON hd ON pk.MaPhieuKham = hd.MaPhieuKham
                    WHERE pk.MaTrangThai = 5 AND hd.MaHoaDon IS NULL
                    ORDER BY pk.NgayKham DESC, pk.GioKham DESC";
            $stmt = $db->query($sql);
            echo json_encode([
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ]);
            break;

        // ==========================================
        // ACTION 4: LẤY CHI TIẾT ĐỂ LẬP HÓA ĐƠN MỚI TỪ PHIẾU KHÁM
        // ==========================================
        case 'checkup_billing_info':
            $maPhieuKham = intval($_GET['MaPhieuKham'] ?? 0);
            if ($maPhieuKham <= 0) throw new Exception('Mã phiếu khám không hợp lệ.');

            $sql = "SELECT pk.MaPhieuKham, pk.MaPhieuKhamCode, pk.NgayKham, pk.ChanDoan,
                           bn.MaBenhNhan, bn.MaBN as MaBenhNhanCode, bn.HoTen, bn.NgaySinh, bn.GioiTinh, bn.SoDienThoai, bn.DiaChi, bn.SoBHYT
                    FROM PHIEUKHAM pk
                    JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan
                    WHERE pk.MaPhieuKham = :maPhieuKham LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute([':maPhieuKham' => $maPhieuKham]);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$info) throw new Exception('Không tìm thấy dữ liệu phiếu khám.');

            $info['TongTienKham'] = 150000; // Tiền khám mặc định cố định

            // Lấy cận lâm sàng
            $sql_cls = "SELECT TenDichVu, DonGia, SoLuong, ThanhTien FROM chidinhcls 
                        WHERE MaPhieuKham = :maPhieuKham AND TrangThai = 'HOAN_THANH'";
            $stmt_cls = $db->prepare($sql_cls);
            $stmt_cls->execute([':maPhieuKham' => $maPhieuKham]);
            $info['cls_items'] = $stmt_cls->fetchAll(PDO::FETCH_ASSOC);

            // Lấy đơn thuốc
            $sql_thuoc = "SELECT t.TenThuoc, t.HamLuong, ctdt.SoLuong, ctdt.DonGia, ctdt.ThanhTien, dvt.TenDonVi
                          FROM donthuoc dt
                          JOIN chitietdonthuoc ctdt ON dt.MaDonThuoc = ctdt.MaDonThuoc
                          JOIN thuoc t ON ctdt.MaThuoc = t.MaThuoc
                          LEFT JOIN donvitinh dvt ON t.MaDonVi = dvt.MaDonVi
                          WHERE dt.MaPhieuKham = :maPhieuKham";
            $stmt_thuoc = $db->prepare($sql_thuoc);
            $stmt_thuoc->execute([':maPhieuKham' => $maPhieuKham]);
            $info['med_items'] = $stmt_thuoc->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $info]);
            break;

        // ==========================================
        // ACTION 5: XEM CHI TIẾT HÓA ĐƠN ĐÃ LẬP (ĐỂ IN)
        // ==========================================
        case 'detail':
            $id = trim($_GET['id'] ?? '');
            if (empty($id)) throw new Exception('Thiếu ID hóa đơn hoặc Số hóa đơn.');

            $sql = "SELECT hd.*, 
                           bn.HoTen as TenBenhNhan, bn.MaBN as MaBenhNhanCode, bn.NgaySinh, bn.GioiTinh, bn.SoDienThoai, bn.DiaChi, bn.SoBHYT, bn.DiUng,
                           pk.MaPhieuKhamCode, pk.NgayKham, pk.GioKham, pk.LyDoKham, pk.ChanDoan,
                           nv.HoTen as TenBacSi,
                           tt.MaPhuongThuc, tt.GhiChu as GhiChuThanhToan, tt.NgayThanhToan, ptt.TenPhuongThuc
                    FROM HOADON hd
                    JOIN PHIEUKHAM pk ON hd.MaPhieuKham = pk.MaPhieuKham
                    JOIN BENHNHAN bn ON pk.MaBenhNhan = bn.MaBenhNhan
                    JOIN NHANVIEN nv ON pk.MaBacSi = nv.MaNhanVien
                    LEFT JOIN THANHTOAN tt ON hd.MaHoaDon = tt.MaHoaDon
                    LEFT JOIN PHUONGTHUCTT ptt ON tt.MaPhuongThuc = ptt.MaPhuongThuc
                    WHERE hd.MaHoaDon = :id_int OR hd.SoHoaDon = :id_str LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->execute([':id_int' => intval($id), ':id_str' => $id]);
            $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$invoice) throw new Exception('Không tìm thấy hóa đơn.');

            // Lấy CLS đi kèm
            $sql_cls = "SELECT TenDichVu, DonGia, SoLuong, ThanhTien FROM chidinhcls WHERE MaPhieuKham = :maPhieuKham";
            $stmt_cls = $db->prepare($sql_cls);
            $stmt_cls->execute([':maPhieuKham' => $invoice['MaPhieuKham']]);
            $invoice['cls_items'] = $stmt_cls->fetchAll(PDO::FETCH_ASSOC);

            // Lấy thuốc đi kèm
            $sql_thuoc = "SELECT t.TenThuoc, t.HamLuong, ctdt.SoLuong, ctdt.DonGia, ctdt.ThanhTien, ctdt.CachDung, dvt.TenDonVi
                          FROM donthuoc dt
                          JOIN chitietdonthuoc ctdt ON dt.MaDonThuoc = ctdt.MaDonThuoc
                          JOIN thuoc t ON ctdt.MaThuoc = t.MaThuoc
                          LEFT JOIN donvitinh dvt ON t.MaDonVi = dvt.MaDonVi
                          WHERE dt.MaPhieuKham = :maPhieuKham";
            $stmt_thuoc = $db->prepare($sql_thuoc);
            $stmt_thuoc->execute([':maPhieuKham' => $invoice['MaPhieuKham']]);
            $invoice['med_items'] = $stmt_thuoc->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $invoice]);
            break;

        default:
            throw new Exception('Action GET không hợp lệ hoặc không hỗ trợ.');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}