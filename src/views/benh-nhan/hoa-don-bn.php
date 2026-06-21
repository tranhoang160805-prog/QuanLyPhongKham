<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chặn truy cập trực tiếp nếu chưa đăng nhập bệnh nhân
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=403.php");
    exit(); 
}

$userId = $_SESSION['user_id'];

// TRUY VẤN DANH SÁCH LỊCH SỬ THANH TOÁN TỔNG QUAN ĐỂ RENDER THẺ CARD TRANG CHỦ
$listHoaDon = [];
try {
    $stmtList = $pdo->prepare("
        SELECT 
            tk.MaTaiKhoan,
            bn.MaBN,
            bn.HoTen,
            hd.MaHoaDon,
            hd.SoHoaDon,
            hd.TongTienKham,
            hd.TongTienCLS,
            hd.TongTienThuoc,
            hd.TongThanhToan,
            hd.TrangThai,
            hd.NgayTao
        FROM taikhoan tk
        JOIN benhnhan bn ON bn.MaTaiKhoan = tk.MaTaiKhoan
        JOIN phieukham pk ON pk.MaBenhNhan = bn.MaBenhNhan
        JOIN hoadon hd ON hd.MaPhieuKham = pk.MaPhieuKham
        WHERE tk.MaTaiKhoan = :user_id
        ORDER BY hd.NgayTao DESC
    ");
    $stmtList->execute(['user_id' => $userId]);
    $listHoaDon = $stmtList->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMsg = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <style>
        :root {
            --surface: #f9f9ff;
            --on-surface: #151c27;
            --on-surface-variant: #414752;
            --primary: #00569f;
            --primary-fixed: #d4e3ff;
            --outline-variant: #c1c6d4;
            --outline: #717783;
            --error: #ba1a1a;
            --secondary: #006a66;
            --surface-container-low: #f0f3ff;
            --background-alert: #E8F4FD;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: var(--surface); color: var(--on-surface); font-family: system-ui, sans-serif; line-height: 1.5; }
        main { padding: 24px; max-width: 1200px; margin: 0 auto; }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .header-content h2 { font-size: 32px; font-weight: 700; letter-spacing: -0.02em; }
        .text-variant { color: var(--on-surface-variant); font-size: 14px; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background-color: #ffffff; border: 1px solid var(--outline-variant); color: var(--on-surface-variant); font-size: 14px; font-weight: 600; border-radius: 8px; text-decoration: none; }
        .alert-info-box { background-color: var(--background-alert); border: 1px solid rgba(0, 86, 159, 0.15); border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 32px; }
        .alert-icon-wrapper { width: 48px; height: 48px; background-color: rgba(0, 86, 159, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); }
        .alert-title { font-size: 16px; font-weight: 600; color: var(--primary); }
        .history-list { display: flex; flex-direction: column; gap: 16px; }
        .history-card { background-color: #ffffff; border: 1px solid var(--outline-variant); border-radius: 12px; padding: 20px; display: grid; grid-template-columns: 1.2fr 2.5fr 1.3fr; align-items: center; gap: 24px; }
        .card-meta { display: flex; flex-direction: column; gap: 4px; }
        .badge-type { display: inline-block; align-self: flex-start; padding: 4px 12px; background-color: var(--primary-fixed); color: var(--primary); font-size: 12px; font-weight: 600; border-radius: 16px; }
        .text-date { font-size: 16px; font-weight: 700; }
        .text-code { font-size: 13px; color: var(--outline); font-family: monospace; }
        .text-invoice-no { font-size: 16px; font-weight: 700; color: var(--primary); }
        .price-summary { font-size: 14px; color: var(--on-surface-variant); display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px 16px; }
        .total-payment { font-size: 16px; font-weight: 700; color: var(--error); margin-top: 4px; }
        .card-action { display: flex; flex-direction: column; align-items: flex-end; gap: 12px; }
        .badge-status { padding: 4px 12px; border-radius: 16px; font-size: 12px; font-weight: 600; }
        .status-paid { background-color: rgba(0, 106, 102, 0.12); color: var(--secondary); }
        .status-unpaid { background-color: rgba(186, 26, 26, 0.12); color: var(--error); }
        .btn-view-detail { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background-color: #ffffff; border: 1px solid var(--primary); color: var(--primary); font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .btn-view-detail:hover { background-color: var(--primary); color: #ffffff; }
        
        /* Modal Styles */
        .modal-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; z-index: 1000; opacity: 0; transition: opacity 0.2s ease; }
        .modal-backdrop.hidden { display: none !important; }
        .modal-box { background-color: #ffffff; width: 100%; max-width: 850px; max-height: 90vh; border-radius: 16px; box-shadow: 0 24px 48px rgba(0, 0, 0, 0.12); overflow-y: auto; }
        .modal-header { padding: 20px 24px; background-color: var(--surface-container-low); border-bottom: 1px solid var(--outline-variant); display: flex; justify-content: space-between; align-items: center; }
        .modal-header h3 { font-size: 18px; font-weight: 700; color: var(--primary); }
        .btn-close { background: none; border: none; color: var(--outline); cursor: pointer; }
        .modal-body { padding: 24px; display: flex; flex-direction: column; gap: 20px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .info-panel { background-color: var(--surface); padding: 12px 16px; border-radius: 8px; }
        .panel-label { font-size: 11px; color: var(--outline); text-transform: uppercase; font-weight: 700; }
        .panel-value { font-size: 14px; font-weight: 600; }
        .section-title { font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 8px; border-bottom: 1px dashed var(--outline-variant); padding-bottom: 4px; display: flex; align-items: center; gap: 6px; }
        .table-responsive { width: 100%; overflow-x: auto; border: 1px solid var(--outline-variant); border-radius: 8px; }
        .detail-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; }
        .detail-table th { background-color: var(--surface-container-low); padding: 10px 12px; border-bottom: 1px solid var(--outline-variant); }
        .detail-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; }
        .text-right { text-align: right; }
        .text-bold { font-weight: 600; }
        .financial-summary { align-self: flex-end; width: 100%; max-width: 360px; background-color: var(--surface-container-low); padding: 16px; border-radius: 12px; display: flex; flex-direction: column; gap: 6px; border: 1px solid var(--outline-variant); }
        .financial-row { display: flex; justify-content: space-between; font-size: 13px; }
        .financial-row.grand-total { font-size: 16px; font-weight: 700; color: var(--error); border-top: 1px solid var(--outline-variant); padding-top: 6px; }
    </style>
</head>
<body>

<main>
    <div class="header-section">
        <div class="header-content">
            <h2>Lịch Sử Thanh Toán</h2>
            <p class="text-variant">Tra cứu hóa đơn viện phí</p>
        </div>
        <a href="index.php?page=benh-an" class="btn-back">
            <span class="material-symbols-outlined">arrow_back</span> Quay lại
        </a>
    </div>

    <div class="alert-info-box">
        <div class="alert-icon-wrapper">
            <span class="material-symbols-outlined">payments</span>
        </div>
        <div>
            <p class="alert-title">Thống kê hóa đơn cá nhân</p>
            <p class="text-variant">Hệ thống ghi nhận tài khoản bệnh nhân có tổng cộng <strong><?= count($listHoaDon) ?></strong> hóa đơn giao dịch.</p>
        </div>
    </div>

    <div class="history-list">
        <?php if (!empty($listHoaDon)): ?>
            <?php foreach ($listHoaDon as $row): ?>
                <div class="history-card">
                    <div class="card-meta">
                        <span class="badge-type">Hóa đơn y tế</span>
                        <p class="text-date"><?= date('d/m/Y H:i', strtotime($row['NgayTao'])) ?></p>
                        <p class="text-code">Mã GD: #<?= htmlspecialchars($row['MaHoaDon']) ?></p>
                    </div>
                    <div class="card-content">
                        <p class="text-invoice-no">Số hóa đơn: <?= htmlspecialchars($row['SoHoaDon'] ?: 'Chưa cấp số') ?></p>
                        <div class="price-summary">
                            <span>Khám bệnh: <strong><?= number_format($row['TongTienKham'], 0, ',', '.') ?> đ</strong></span>
                        </div>
                        <p class="total-payment">Tổng thanh toán: <?= number_format($row['TongThanhToan'], 0, ',', '.') ?> đ</p>
                    </div>
                    <div class="card-action">
                        <span class="badge-status <?= trim($row['TrangThai']) === 'Đã thanh toán' ? 'status-paid' : 'status-unpaid' ?>">
                            <?= htmlspecialchars($row['TrangThai']) ?>
                        </span>
                        <button class="btn-view-detail" onclick="triggerLoadInvoiceDetail(<?= $row['MaHoaDon'] ?>)">
                            <span class="material-symbols-outlined" style="font-size:18px;">receipt</span> Chi tiết hóa đơn
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--outline); padding: 48px;">Tài khoản chưa phát sinh dữ liệu hóa đơn nào.</p>
        <?php endif; ?>
    </div>
</main>

<div id="invoice-modal" class="modal-backdrop hidden" onclick="shutdownInvoiceModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Chi Tiết Hóa Đơn Điện Tử</h3>
            <button class="btn-close" onclick="shutdownInvoiceModal()">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="modal-body">
            
            <div class="info-grid">
                <div class="info-panel">
                    <p class="panel-label">Số hóa đơn / Số phiếu khám</p>
                    <p class="panel-value" id="m-invoice-no">-</p>
                </div>
                <div class="info-panel">
                    <p class="panel-label">Ngày khám bệnh</p>
                    <p class="panel-value" id="m-invoice-date">-</p>
                </div>
                <div class="info-panel" style="grid-column: span 2;">
                    <p class="panel-label">Lý do khám & Chẩn đoán lâm sàng</p>
                    <p class="panel-value" id="m-invoice-diagnosis">-</p>
                </div>
            </div>

            <div>
                <h4 class="section-title"><span class="material-symbols-outlined" style="font-size:18px;">biotech</span> Chi tiết chỉ định cận lâm sàng (CLS)</h4>
                <div class="table-responsive">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Tên dịch vụ kỹ thuật</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">SL</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody id="cls-table-body"></tbody>
                    </table>
                </div>
            </div>

            <div>
                <h4 class="section-title"><span class="material-symbols-outlined" style="font-size:18px;">pill</span> Chi tiết danh mục thuốc trong đơn</h4>
                <div class="table-responsive">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Tên thuốc / Biệt dược</th>
                                <th class="text-right">Đơn giá</th>
                                <th class="text-right">Số lượng</th>
                                <th class="text-right">Thành tiền</th>
                                <th>Hướng dẫn sử dụng</th>
                            </tr>
                        </thead>
                        <tbody id="thuoc-table-body"></tbody>
                    </table>
                </div>
            </div>

            <div class="financial-summary">
                <div class="financial-row"><span>Tổng chi phí khám bệnh:</span><span id="m-summary-kham" class="text-bold">0 đ</span></div>
                <div class="financial-row"><span>Tổng chi phí cận lâm sàng:</span><span id="m-summary-cls" class="text-bold">0 đ</span></div>
                <div class="financial-row"><span>Tổng chi phí tiền thuốc:</span><span id="m-summary-thuoc" class="text-bold">0 đ</span></div>
                <div class="financial-row" style="color: var(--secondary);"><span>Miễn giảm / Bảo hiểm:</span><span id="m-summary-giam">-0 đ</span></div>
                <div class="financial-row grand-total"><span>Tổng thực thu thực tế:</span><span id="m-summary-grand">0 đ</span></div>
            </div>

        </div>
    </div>
</div>

<script>
function triggerLoadInvoiceDetail(maHoaDon) {
    if (!maHoaDon) return;

    // GỌI THẲNG TỚI FILE API ĐỘC LẬP VỪA TẠO
    const targetUrl = `src/api/getHoaDonBN.php?ma_hoa_don=${maHoaDon}`;

    fetch(targetUrl)
        .then(response => {
            if (!response.ok) throw new Error("API phản hồi lỗi HTTP " + response.status);
            return response.json();
        })
        .then(res => {
            if (res.success) {
                const info = res.info;
                const clsList = res.cls || [];
                const thuocList = res.thuoc || [];

                // 1. Đổ dữ liệu thông tin chung
                document.getElementById('m-invoice-no').innerText = `${info.SoHoaDon || 'Chưa cấp số'} / [${info.MaPhieuKhamCode || 'N/A'}]`;
                document.getElementById('m-invoice-date').innerText = info.NgayKham ? formatDateString(info.NgayKham) : '-';
                document.getElementById('m-invoice-diagnosis').innerText = `[Lý do]: ${info.LyDoKham || 'Không ghi nhận'} — [Chẩn đoán]: ${info.ChanDoan || 'Chưa có chẩn đoán'}`;

                // 2. Render danh mục Cận Lâm Sàng (CLS)
                const clsBody = document.getElementById('cls-table-body');
                clsBody.innerHTML = '';
                if (clsList.length === 0) {
                    clsBody.innerHTML = `<tr><td colspan="4" style="text-align:center; color:gray; font-style:italic; padding:12px;">Không phát sinh chi phí dịch vụ cận lâm sàng.</td></tr>`;
                } else {
                    clsList.forEach(item => {
                        clsBody.innerHTML += `
                            <tr>
                                <td class="text-bold" style="color:var(--primary);">${escapeHtml(item.TenDichVu)}</td>
                                <td class="text-right">${formatVND(item.DonGia)}</td>
                                <td class="text-right text-bold">${item.SoLuong}</td>
                                <td class="text-right text-bold">${formatVND(item.ThanhTien)}</td>
                            </tr>
                        `;
                    });
                }

                // 3. Render danh mục Toa Thuốc
                const thuocBody = document.getElementById('thuoc-table-body');
                thuocBody.innerHTML = '';
                if (thuocList.length === 0) {
                    thuocBody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:gray; font-style:italic; padding:12px;">Không phát sinh chi phí thuốc trong đợt khám này.</td></tr>`;
                } else {
                    thuocList.forEach(item => {
                        const thanhTienThuoc = (item.SoLuong || 0) * (item.DonGia || 0);
                        thuocBody.innerHTML += `
                            <tr>
                                <td class="text-bold">${escapeHtml(item.TenThuoc)}</td>
                                <td class="text-right">${formatVND(item.DonGia)}</td>
                                <td class="text-right text-bold">${item.SoLuong}</td>
                                <td class="text-right text-bold">${formatVND(thanhTienThuoc)}</td>
                                <td style="color: var(--secondary); font-style: italic;">${escapeHtml(item.CachDung || 'Theo chỉ định bác sĩ')}</td>
                            </tr>
                        `;
                    });
                }

                // 4. Cập nhật khối tài chính
                document.getElementById('m-summary-kham').innerText = formatVND(info.TongTienKham);
                document.getElementById('m-summary-cls').innerText = formatVND(info.TongTienCLS);
                document.getElementById('m-summary-thuoc').innerText = formatVND(info.TongTienThuoc);
                document.getElementById('m-summary-giam').innerText = `-${formatVND(info.GiamGia || 0)}`;
                document.getElementById('m-summary-grand').innerText = formatVND(info.TongThanhToan);

                // Mở Modal
                const modal = document.getElementById('invoice-modal');
                modal.classList.remove('hidden');
                setTimeout(() => { modal.style.opacity = '1'; }, 10);
            } else {
                showAlert("Lỗi từ API: " + res.message);
            }
        })
        .catch(err => {
            console.error("Lỗi Fetch:", err);
            showAlert("Không thể kết nối đến máy chủ API. Vui lòng kiểm tra lại đường dẫn mạng.");
        });
}

function shutdownInvoiceModal(event) {
    if (event && event.target !== document.getElementById('invoice-modal')) return;
    const modal = document.getElementById('invoice-modal');
    modal.style.opacity = '0';
    setTimeout(() => { modal.classList.add('hidden'); }, 200);
}

function formatVND(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount || 0) + ' đ';
}

function formatDateString(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return dateStr;
    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') shutdownInvoiceModal();
});
</script>
</body>
</html>