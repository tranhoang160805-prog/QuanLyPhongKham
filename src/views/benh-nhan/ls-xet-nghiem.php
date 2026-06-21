<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    
    exit(); 
}


$userId = $_SESSION['user_id'];

if (isset($_GET['action']) && $_GET['action'] === 'get_detail') {
    header('Content-Type: application/json; charset=utf-8');
    $maChiDinh = isset($_GET['ma_chi_dinh']) ? (int)$_GET['ma_chi_dinh'] : 0;

    if ($maChiDinh <= 0) {
        echo json_encode(['success' => false, 'message' => 'Mã chỉ định không hợp lệ.']);
        exit;
    }

    try {
        // Câu lệnh SELECT thứ 2 của bạn
        $stmtDetail = $pdo->prepare("
            SELECT 
                cls.NgayChiDinh,
                kq.KetQuaText,
                kq.KetLuan,
                kq.FileKetQua,
                kq.NgayThucHien
            FROM chidinhcls cls
            JOIN ketquacls kq ON kq.MaChiDinh = cls.MaChiDinh
            WHERE cls.MaChiDinh = :ma_chi_dinh
        ");
        $stmtDetail->execute(['ma_chi_dinh' => $maChiDinh]);
        $detail = $stmtDetail->fetch(PDO::FETCH_ASSOC);

        if ($detail) {
            // Định dạng lại ngày giờ hiển thị cho đẹp mắt
            $detail['NgayChiDinhFmt'] = date('H:i d/m/Y', strtotime($detail['NgayChiDinh']));
            $detail['NgayThucHienFmt'] = $detail['NgayThucHien'] ? date('H:i d/m/Y', strtotime($detail['NgayThucHien'])) : 'Chưa cập nhật';
            echo json_encode(['success' => true, 'data' => $detail]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Hiện chưa có dữ liệu kết quả xét nghiệm cho chỉ định này.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage()]);
    }
    exit;
}

// =========================================================================
// LẤY DANH SÁCH TẤT CẢ LẦN XÉT NGHIỆM / CẬN LÂM SÀNG CỦA BỆNH NHÂN
// =========================================================================
$listCLS = [];
$totalTests = 0;
try {
    // Câu lệnh SELECT thứ 1 của bạn tích hợp biến session
    $stmtList = $pdo->prepare("
        SELECT 
            cls.MaChiDinh, /* Lấy thêm để làm ID định danh khi bấm nút */
            pk.MaPhieuKhamCode,
            pk.NgayKham,
            pk.ChanDoan,
            cls.TrangThai AS TrangThaiCLS,
            l.TenLoaiCLS
        FROM taikhoan tk
        JOIN benhnhan bn ON bn.MaTaiKhoan = tk.MaTaiKhoan
        JOIN phieukham pk ON pk.MaBenhNhan = bn.MaBenhNhan
        JOIN chidinhcls cls ON cls.MaPhieuKham = pk.MaPhieuKham
        JOIN loaiclsn l ON l.MaLoaiCLS = cls.MaLoaiCLS
        WHERE tk.MaTaiKhoan = :user_id
        ORDER BY pk.NgayKham DESC, cls.NgayChiDinh DESC
    ");
    $stmtList->execute(['user_id' => $userId]);
    $listCLS = $stmtList->fetchAll(PDO::FETCH_ASSOC);
    $totalTests = count($listCLS);
} catch (PDOException $e) {
    $dbError = $e->getMessage();
}
?>
<link rel="stylesheet" href="public/assets/css/BenhNhan/ls-xet-nghiem.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<body>

<main>
    <div class="header-section">
        <div class="header-content">
            <h2>Lịch Sử Xét Nghiệm</h2>
            <p class="text-variant">Theo dõi các kết quả xét nghiệm & kiểm tra cận lâm sàng của bạn</p>
        </div>
        <a href="index.php?page=benh-an" class="btn-back-home">
            <span class="material-symbols-outlined">arrow_back</span> Quay lại
        </a>
    </div>

    <div class="summary-header">
        <div class="summary-title-block">
            <div class="summary-icon">
                <span class="material-symbols-outlined">biotech</span>
            </div>
            <h3>Tổng quan hồ sơ</h3>
        </div>
        <div class="summary-stats">
            <div>
                <p class="stat-num"><?= $totalTests ?></p>
                <p class="stat-label">Tổng số chỉ định</p>
            </div>
        </div>
    </div>

    <div class="history-timeline">
        <?php if (!empty($listCLS)): ?>
            <?php foreach ($listCLS as $cls): ?>
                <div class="history-card">
                    <div class="meta-info">
                        <span class="test-type-badge"><?= htmlspecialchars($cls['TenLoaiCLS']) ?></span>
                        <p class="date-text"><?= date('d/m/Y', strtotime($cls['NgayKham'])) ?></p>
                        <p class="code-text">Mã phiếu: <?= htmlspecialchars($cls['MaPhieuKhamCode']) ?></p>
                    </div>

                    <div class="test-content">
                        <!-- <p class="service-name"><?= htmlspecialchars($cls['TenDichVu']) ?></p> -->
                        <p class="diagnosis-text">
                            <strong>Chẩn đoán sơ bộ:</strong> <?= htmlspecialchars($cls['ChanDoan'] ?: 'Chưa ghi nhận') ?>
                        </p>
                        <p class="text-variant" style="font-size: 13px;">
                            <!-- <strong>Chi phí:</strong> <?= number_format($cls['DonGia'], 0, ',', '.') ?> VNĐ -->
                        </p>
                    </div>

                    <div class="action-zone">
                        <?php if ($cls['TrangThaiCLS'] == 2 || $cls['TrangThaiCLS'] === 'Đã hoàn thành'): ?>
                            <span class="status-badge status-done">Đã có kết quả</span>
                            <button class="btn-view-detail" onclick="openDetailModal(<?= $cls['MaChiDinh'] ?>)">
                                <span class="material-symbols-outlined" style="font-size:18px;">description</span>
                                Xem kết quả
                            </button>
                        <?php else: ?>
                            <span class="status-badge status-waiting">Chờ thực hiện</span>
                            <button class="btn-view-detail" style="opacity: 0.5; cursor: not-allowed;" disabled>
                                Chưa có KQ
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="background:#fff; text-align:center; padding:3rem; border-radius:0.75rem; border:1px solid var(--outline-variant);">
                <span class="material-symbols-outlined" style="font-size:48px; color:var(--outline); margin-bottom:1rem;">folder_open</span>
                <p class="text-variant">Bạn không có dữ liệu xét nghiệm hoặc cận lâm sàng nào trong hệ thống.</p>
                <?php if (isset($dbError)) echo "<p style='color:red; font-size:12px; margin-top:0.5rem;'>Lỗi: $dbError</p>"; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<div id="detail-modal" class="modal-backdrop hidden" onclick="closeDetailModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="m-service-name">Chi tiết kết quả xét nghiệm</h3>
            <button class="btn-close-modal" onclick="closeDetailModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="detail-row-grid">
                <div class="info-block">
                    <p class="info-block-label">Ngày giờ chỉ định</p>
                    <p class="info-block-value" id="m-date-assign">-</p>
                </div>
                <div class="info-block">
                    <p class="info-block-label">Ngày giờ thực hiện</p>
                    <p class="info-block-value" id="m-date-done">-</p>
                </div>
            </div>

            <div class="info-block">
                <p class="info-block-label">Thông số / Chỉ số kết quả</p>
                <p class="info-block-value" id="m-result-text" style="white-space: pre-line; font-family: monospace; background:#fff; padding:0.5rem; border-radius:4px; border:1px solid #eee; margin-top:0.25rem;">-</p>
            </div>

            <div class="info-block" style="border-left: 4px solid var(--secondary);">
                <p class="info-block-label" style="color:var(--secondary);">Kết luận của Bác sĩ chuyên khoa</p>
                <p class="info-block-value value-highlight" id="m-conclusion">-</p>
            </div>

            <div class="info-block" id="m-file-wrapper" style="display: none;">
                <p class="info-block-label">Tài liệu đính kèm</p>
                <a href="#" id="m-file-link" target="_blank" class="btn-download-file">
                    <span class="material-symbols-outlined">download</span> Tải file kết quả xét nghiệm (.pdf/image)
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(maChiDinh) {
    if (!maChiDinh) return;

    // Thay đường dẫn này bằng đường dẫn thực tế đến file bạn vừa tạo ở Bước 1
    const apiUrl = `src/api/getChiTietXN.php?ma_chi_dinh=${maChiDinh}`;

    fetch(apiUrl)
        .then(response => {
            // Kiểm tra nếu phản hồi trả về lỗi HTTP (ví dụ 404 hoặc 500)
            if (!response.ok) {
                throw new Error("Lỗi mạng phản hồi không hợp lệ");
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                const record = result.data;
                
                // Đổ dữ liệu vào giao diện Modal như cũ
                document.getElementById('m-service-name').innerText = record.TenDichVu;
                document.getElementById('m-date-assign').innerText = record.NgayChiDinhFmt;
                document.getElementById('m-date-done').innerText = record.NgayThucHienFmt;
                document.getElementById('m-result-text').innerText = record.KetQuaText || "Không có nội dung chỉ số chi tiết.";
                document.getElementById('m-conclusion').innerText = record.KetLuan || "Chưa có kết luận cụ thể.";

                // Xử lý khối hiển thị tài liệu đính kèm
                const fileWrapper = document.getElementById('m-file-wrapper');
                if (record.FileKetQua) {
                    fileWrapper.style.display = 'block';
                    document.getElementById('m-file-link').href = record.FileKetQua;
                } else {
                    fileWrapper.style.display = 'none';
                }

                // Kích hoạt mở mượt hiệu ứng hiển thị Modal
                const modal = document.getElementById('detail-modal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            } else {
                // Hiển thị thông báo nếu không tìm thấy bản ghi kết quả trong DB
                showAlert(result.message);
            }
        })
        .catch(error => {
            console.error("Lỗi chi tiết kết nối AJAX:", error);
            showAlert("Có lỗi xảy ra trong quá trình kết nối máy chủ dữ liệu. Vui lòng kiểm tra lại đường dẫn API.");
        });
}

function closeDetailModal(event) {
    // Nếu event được truyền vào, kiểm tra xem người dùng có click trúng vùng backdrop bên ngoài không
    if (event && event.target !== document.getElementById('detail-modal')) {
        return; 
    }
    const modal = document.getElementById('detail-modal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 250);
}

// Lắng nghe sự kiện phím ESC để đóng Modal nhanh cho người dùng
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDetailModal();
    }
});
</script>

</body>
</html>