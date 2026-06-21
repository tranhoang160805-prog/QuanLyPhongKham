<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    
    exit(); 
}

$userId = $_SESSION['user_id'];
$listDonThuoc = [];
try {
    $stmtList = $pdo->prepare("
        SELECT 
            pk.MaPhieuKham, /* Giữ lại khóa chính này để làm tham số gọi API */
            pk.MaPhieuKhamCode,
            pk.NgayKham,
            pk.ChanDoan,
            dt.MaDonThuoc,
            dt.NgayKeToa,
            dt.LoiDan
        FROM taikhoan tk
        JOIN benhnhan  bn ON bn.MaTaiKhoan  = tk.MaTaiKhoan
        JOIN phieukham pk ON pk.MaBenhNhan  = bn.MaBenhNhan
        JOIN donthuoc  dt ON dt.MaPhieuKham = pk.MaPhieuKham
        WHERE tk.MaTaiKhoan = :user_id
        ORDER BY dt.NgayKeToa DESC
    ");
    $stmtList->execute(['user_id' => $userId]);
    $listDonThuoc = $stmtList->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = $e->getMessage();
}
?>
<link rel="stylesheet" href="public/assets/css/BenhNhan/don-thuoc.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<body>

<main>
    <div class="header-section">
        <div class="header-content">
            <h2>Lịch Sử Cấp Thuốc</h2>
            <p class="text-variant">Theo dõi danh sách đơn thuốc đã kê và thông tin chi tiết liều dùng từ hệ thống</p>
        </div>
        <a href="index.php?page=benh-an" class="btn-back">
            <span class="material-symbols-outlined">arrow_back</span> Quay lại
        </a>
    </div>

    <div class="alert-info-box">
        <div class="alert-icon-wrapper">
            <span class="material-symbols-outlined">medication</span>
        </div>
        <div>
            <p class="alert-title">Thống kê đơn thuốc cá nhân</p>
            <p class="text-variant">Hệ thống ghi nhận tổng cộng <strong><?= count($listDonThuoc) ?></strong> đơn thuốc trong lịch sử khám.</p>
        </div>
    </div>

    <div class="history-list">
        <?php if (!empty($listDonThuoc)): ?>
            <?php foreach ($listDonThuoc as $row): ?>
                <div class="history-card">
                    <div class="card-meta">
                        <span class="badge-type">Đơn thuốc</span>
                        <p class="text-date"><?= date('d/m/Y', strtotime($row['NgayKeToa'])) ?></p>
                        <p class="text-code">Mã phiếu khám: <?= htmlspecialchars($row['MaPhieuKhamCode']) ?></p>
                    </div>

                    <div class="card-content">
                        <p class="text-service-name">Mã đơn thuốc: #<?= htmlspecialchars($row['MaDonThuoc']) ?></p>
                        <p class="text-diagnosis-summary">
                            <strong>Chẩn đoán bệnh:</strong> <?= htmlspecialchars($row['ChanDoan'] ?: 'Chưa ghi nhận chẩn đoán') ?>
                        </p>
                        <p class="text-variant" style="font-style: italic; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 500px;">
                            <strong>Lời dặn:</strong> <?= htmlspecialchars($row['LoiDan'] ?: 'Không có lời dặn kèm theo') ?>
                        </p>
                    </div>

                    <div class="card-action">
                        <span class="badge-status">Đã cấp thuốc</span>
                        <button class="btn-view-results" onclick="triggerLoadPrescriptionDetail(<?= $row['MaPhieuKham'] ?>)">
                            <span class="material-symbols-outlined" style="font-size:18px;">receipt_long</span>
                            Xem chi tiết
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <span class="material-symbols-outlined" style="font-size:48px; color:var(--outline); margin-bottom:12px;">receipt</span>
                <p class="text-variant">Hiện tại bạn chưa có dữ liệu lịch sử cấp thuốc nào.</p>
                <?php if (isset($errorMsg)) echo "<p style='color:red; font-size:12px; margin-top:8px;'>Hệ thống lỗi: $errorMsg</p>"; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<div id="prescription-modal" class="modal-backdrop hidden" onclick="shutdownPrescriptionModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Chi tiết danh mục thuốc</h3>
            <button class="btn-close" onclick="shutdownPrescriptionModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modal-body">
            
            <div class="table-responsive">
                <table class="medicine-table">
                    <thead>
                        <tr>
                            <th>Tên thuốc / Hoạt chất</th>
                            <th>Dạng bào chế</th>
                            <th class="text-right">Số lượng</th>
                            <th>Cách dùng / Liều lượng</th>
                        </tr>
                    </thead>
                    <tbody id="medicine-table-body">
                        </tbody>
                </table>
            </div>

            <div class="info-panel" style="border-left: 4px solid var(--primary); margin-top: 8px;">
                <p class="panel-label" style="color:var(--primary);">Lời dặn chung từ bác sĩ điều trị</p>
                <p class="panel-value" id="modal-prescription-advice" style="font-weight: 500; white-space: pre-line;">-</p>
            </div>
            
        </div>
    </div>
</div>

<script>
function triggerLoadPrescriptionDetail(maPhieuKham) {
    if (!maPhieuKham) return;

    const apiEndpoint = `src/api/getChiTietDonThuoc.php?ma_phieu_kham=${maPhieuKham}`;

    fetch(apiEndpoint)
        .then(response => {
            if (!response.ok) {
                throw new Error("Mạng phản hồi không ổn định.");
            }
            return response.json();
        })
        .then(res => {
            if (res.success) {
                const tableBody = document.getElementById('medicine-table-body');
                tableBody.innerHTML = ''; // Xóa dữ liệu cũ trong bảng

                // FIX 1: Đổi từ 'res.details' thành 'res.thuoc_list' theo đúng cấu trúc Backend
                const medicineDetails = res.thuoc_list || [];
                
                if (medicineDetails.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" style="text-align:center;">Không có thuốc nào trong toa này.</td></tr>`;
                } else {
                    medicineDetails.forEach(item => {
                        const rowHtml = `
                            <tr>
                                <td>
                                    <span class="text-bold-primary">${escapeHtml(item.TenThuoc)}</span>
                                </td>
                                <td>${escapeHtml(item.DangBaoChe || 'Viên nén')}</td>
                                <td class="text-right" style="font-weight:600;">${item.SoLuong} ${escapeHtml(item.TenDonVi || '')}</td>
                                <td style="color: var(--secondary); font-weight: 500;">${escapeHtml(item.CachDung || 'Theo chỉ dẫn')}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += rowHtml;
                    });
                }

                // FIX 2: Đổi từ 'res.prescription' thành 'res.info' và 'LoiDanChung' thành 'LoiDan'
                const prescriptionInfo = res.info || {};
                document.getElementById('modal-prescription-advice').innerText = prescriptionInfo.LoiDan || "Uống thuốc đúng giờ theo chỉ định của bác sĩ.";

                // Hiển thị Modal mượt mà
                const modal = document.getElementById('prescription-modal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);
            } else {
                showAlert(res.message || "Không thể lấy thông tin chi tiết đơn thuốc.");
            }
        })
        .catch(err => {
            console.error("Lỗi parse cấu trúc API:", err);
            showAlert("Không thể tải thông tin chi tiết đơn thuốc. Vui lòng kiểm tra lại đường dẫn file getChiTietDonThuoc.php.");
        });
}

function shutdownPrescriptionModal(event) {
    if (event && event.target !== document.getElementById('prescription-modal')) {
        return; 
    }
    const modal = document.getElementById('prescription-modal');
    modal.style.opacity = '0';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        shutdownPrescriptionModal();
    }
});
</script>
</body>
</html>