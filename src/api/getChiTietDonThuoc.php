<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/database.php';

try {
    $maPhieuKham = isset($_GET['ma_phieu_kham']) ? (int)$_GET['ma_phieu_kham'] : 0;

    if ($maPhieuKham <= 0) {
        echo json_encode(['success' => false, 'message' => 'Mã phiếu khám không hợp lệ.']);
        exit;
    }

    $sql = "SELECT dt.MaDonThuoc, dt.NgayKeToa, dt.LoiDan,
                   bn.HoTen AS HoTen, bn.MaBN AS MaBN, bn.DiUng,
                   t.MaThuoc, t.TenThuoc, t.HamLuong, t.DangBaoChe,
                   ctdt.SoLuong, dvt.TenDonVi, ctdt.DonGia, ctdt.CachDung,
                   t.SoLuongTon
            FROM DONTHUOC dt
            JOIN PHIEUKHAM pk ON pk.MaPhieuKham = dt.MaPhieuKham
            JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
            JOIN CHITIETDONTHUOC ctdt ON ctdt.MaDonThuoc = dt.MaDonThuoc
            JOIN THUOC t ON t.MaThuoc = ctdt.MaThuoc
            JOIN DONVITINH dvt ON t.MaDonVi = dvt.MaDonVi
            WHERE pk.MaPhieuKham = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$maPhieuKham]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        echo json_encode([
            'success' => false, 
            'message' => 'Không tìm thấy đơn thuốc hoặc thông tin cấp phát liên quan đến phiếu khám này.'
        ]);
        exit;
    }

    // Tách thông tin chung hành chính và mảng chi tiết danh mục thuốc
    $firstRow = $rows[0];
    $result = [
        'success' => true,
        'info' => [
            'MaDonThuoc' => $firstRow['MaDonThuoc'],
            'NgayKeToa'  => $firstRow['NgayKeToa'],
            'LoiDan'     => $firstRow['LoiDan'] ?? 'Không có lời dặn.',
            'HoTen'      => $firstRow['HoTen'],
            'MaBN'       => $firstRow['MaBN'],
            'DiUng'      => $firstRow['DiUng'] ?? 'Không có'
        ],
        'thuoc_list' => []
    ];

    foreach ($rows as $row) {
        $result['thuoc_list'][] = [
            'MaThuoc' => $row['MaThuoc'],
            'TenThuoc'    => $row['TenThuoc'],
            'HamLuong'   => $row['HamLuong'],
            'DangBaoChe' => $row['DangBaoChe'],
            'SoLuong'    => (int)$row['SoLuong'],
            'TenDonVi'   => $row['TenDonVi'],
            'DonGia'     => (float)$row['DonGia'],
            'CachDung'   => $row['CachDung'],
            'SoLuongTon' => (int)$row['SoLuongTon']
        ];
    }

    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}