<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check role permission
$menu_role = $_SESSION['user_role'] ?? 'ky-thuat-vien';

// POST processing: Confirm test execution (single or all)
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'confirm_test') {
        $maChiDinh = isset($_POST['ma_chi_dinh']) ? (int)$_POST['ma_chi_dinh'] : 0;
        $maPhieuKham = isset($_POST['ma_phieu_kham']) ? (int)$_POST['ma_phieu_kham'] : 0;
        
        if ($maChiDinh > 0 && $maPhieuKham > 0) {
            try {
                $pdo->beginTransaction();
                
                // 1. Cập nhật trangthai = 1 (Đang thực hiện) cho chỉ định cận lâm sàng đang được chọn
                $stmtUpdateCd = $pdo->prepare("UPDATE CHIDINHCLS SET trangthai = 1 WHERE MaChiDinh = ?");
                $stmtUpdateCd->execute([$maChiDinh]);
                
                // 2. Cập nhật MaTrangThai = 5 (Đang xét nghiệm) cho phiếu khám đang được chọn
                $stmtUpdatePk = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 5, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
                $stmtUpdatePk->execute([$maPhieuKham]);
                
                $pdo->commit();
                
                $_SESSION['toast_success'] = "Chuyển trạng thái chỉ định cận lâm sàng thành công! Đang tiến hành xét nghiệm.";
                echo "<script>window.location.href = 'index.php?workspace=1&page=xet-nghiem-ktv&maphieukham=" . $maPhieuKham . "';</script>";
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error_message = "Lỗi khi cập nhật trạng thái: " . $e->getMessage();
            }
        } else {
            $error_message = "Mã chỉ định hoặc mã phiếu khám không hợp lệ.";
        }
    } elseif ($_POST['action'] === 'confirm_all_tests') {
        $maPhieuKham = isset($_POST['ma_phieu_kham']) ? (int)$_POST['ma_phieu_kham'] : 0;
        
        if ($maPhieuKham > 0) {
            try {
                $pdo->beginTransaction();
                
                // 1. Cập nhật trangthai = 1 cho tất cả chỉ định của phiếu khám đang chờ xét nghiệm (trangthai = 0)
                $stmtUpdateAllCd = $pdo->prepare("UPDATE CHIDINHCLS SET trangthai = 1 WHERE MaPhieuKham = ? AND trangthai = 0");
                $stmtUpdateAllCd->execute([$maPhieuKham]);
                
                // 2. Cập nhật MaTrangThai = 5 (Đang xét nghiệm) cho phiếu khám đang được chọn
                $stmtUpdatePk = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 5, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
                $stmtUpdatePk->execute([$maPhieuKham]);
                
                $pdo->commit();
                
                $_SESSION['toast_success'] = "Xác nhận thực hiện tất cả chỉ định thành công! Đang tiến hành xét nghiệm.";
                echo "<script>window.location.href = 'index.php?workspace=1&page=xet-nghiem-ktv&maphieukham=" . $maPhieuKham . "';</script>";
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error_message = "Lỗi khi cập nhật trạng thái hàng loạt: " . $e->getMessage();
            }
        } else {
            $error_message = "Mã phiếu khám không hợp lệ.";
        }
    }
}

// GET processing: Fetch selected patient or list all pending indications
$maphieukham = isset($_GET['maphieukham']) ? (int)$_GET['maphieukham'] : (isset($_GET['ma_phieu']) ? (int)$_GET['ma_phieu'] : 0);

$selected_patient = null;
$indications = [];

if ($maphieukham > 0) {
    try {
        // Fetch patient info
        $stmtPatient = $pdo->prepare("
            SELECT 
                pk.MaPhieuKham,
                pk.MaPhieuKhamCode,
                pk.ChanDoan,
                pk.MaBacSi,
                bn.MaBenhNhan,
                bn.MaBN,
                bn.HoTen,
                bn.NgaySinh,
                bn.GioiTinh,
                bn.DiaChi,
                nv.HoTen AS TenBacSi
            FROM PHIEUKHAM pk
            JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
            LEFT JOIN NHANVIEN nv ON nv.MaNhanVien = pk.MaBacSi
            WHERE pk.MaPhieuKham = :maPhieuKham
        ");
        $stmtPatient->execute(['maPhieuKham' => $maphieukham]);
        $selected_patient = $stmtPatient->fetch(PDO::FETCH_ASSOC);
        
        if ($selected_patient) {
            // Calculate age
            if (!empty($selected_patient['NgaySinh'])) {
                $dob = new DateTime($selected_patient['NgaySinh']);
                $today = new DateTime('today');
                $selected_patient['Tuoi'] = $dob->diff($today)->y;
            } else {
                $selected_patient['Tuoi'] = 'N/A';
            }
            
            // Query pending test orders for this patient
            $stmtInds = $pdo->prepare("
                SELECT cd.MaChiDinh, cd.MaPhieuKham,
                       cd.MoTaChiDinh, cd.NgayChiDinh,
                       bn.HoTen, bn.NgaySinh,
                       lcls.TenLoaiCLS
                FROM CHIDINHCLS cd
                JOIN PHIEUKHAM pk ON pk.MaPhieuKham = cd.MaPhieuKham
                JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
                JOIN LOAICLSN lcls ON lcls.MaLoaiCLS = cd.MaLoaiCLS
                WHERE cd.trangthai = 0 AND pk.MaPhieuKham = :maPhieuKham
                ORDER BY cd.NgayChiDinh
            ");
            $stmtInds->execute(['maPhieuKham' => $maphieukham]);
            $indications = $stmtInds->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
    }
}

// Pagination parameters for general pending queue
$limit = 10;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $limit;
$total_records = 0;
$total_pages = 0;

$all_pending_indications = [];
try {
    // Count total pending records
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM CHIDINHCLS WHERE trangthai = 0");
    $total_records = (int)$stmtCount->fetchColumn();
    $total_pages = ceil($total_records / $limit);

    // Fetch paginated pending indications
    $stmtAll = $pdo->prepare("
        SELECT cd.MaChiDinh, cd.MaPhieuKham,
               cd.MoTaChiDinh, cd.NgayChiDinh,
               bn.HoTen, bn.NgaySinh,
               lcls.TenLoaiCLS
        FROM CHIDINHCLS cd
        JOIN PHIEUKHAM pk ON pk.MaPhieuKham = cd.MaPhieuKham
        JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
        JOIN LOAICLSN lcls ON lcls.MaLoaiCLS = cd.MaLoaiCLS
        WHERE cd.trangthai = 0
        ORDER BY cd.NgayChiDinh DESC
        LIMIT :limit OFFSET :offset
    ");
    $stmtAll->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmtAll->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmtAll->execute();
    $all_pending_indications = $stmtAll->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi truy vấn danh sách chỉ định: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="public/assets/css/KyThuatVien/xet-nghiem.css">
<div class="xn-body-layout">
<main class="">
<div class="xn-main-wrap xn-space-stack">

    <?php if (!empty($error_message)): ?>
    <div class="xn-alert-error-container">
        <span class="material-symbols-outlined xn-material-icon xn-alert-icon">error</span>
        <span class="xn-alert-text"><?= htmlspecialchars($error_message) ?></span>
    </div>
    <?php endif; ?>

    <?php if ($selected_patient): ?>
    <section class="xn-patient-banner-selected">
        <div class="xn-avatar-wrapper">
            <div class="xn-avatar-circle">
                <span class="material-symbols-outlined xn-material-icon xn-avatar-icon-size">person</span>
            </div>
            <div class="xn-info-block">
                <div class="xn-info-name-row">
                    <h1 class="xn-patient-fullname-heading"><?= htmlspecialchars($selected_patient['HoTen']) ?></h1>
                    <span class="xn-badge-info-pill">
                        <?= ($selected_patient['GioiTinh'] == 'M' ? 'NAM' : ($selected_patient['GioiTinh'] == 'F' ? 'NỮ' : 'KHÁC')) ?> - <?= $selected_patient['Tuoi'] ?> TUỔI
                    </span>
                    <span class="xn-badge-waiting-pill">
                        <span class="material-symbols-outlined xn-material-icon" style="font-size:14px;">science</span> ĐANG CHỜ XÉT NGHIỆM
                    </span>
                </div>
                <div class="xn-meta-data-row">
                    <p class="xn-meta-text-style"><span class="xn-label-dim">Mã BN:</span> <span class="xn-value-bold"><?= htmlspecialchars($selected_patient['MaBN']) ?></span></p>
                    <p class="xn-meta-text-style"><span class="xn-label-dim">Địa chỉ:</span> <?= htmlspecialchars($selected_patient['DiaChi'] ?: 'Chưa cập nhật') ?></p>
                </div>
            </div>
        </div>
        <div class="xn-doctor-assignment-sidebar">
            <p class="xn-meta-text-style"><span class="xn-label-dim">Bác sĩ chỉ định:</span> <span class="xn-doctor-name-highlight"><?= htmlspecialchars($selected_patient['TenBacSi'] ?: 'Chưa có bác sĩ') ?></span></p>
            <p class="xn-meta-text-style" style="margin-top: 4px;"><span class="xn-label-dim">Chẩn đoán:</span> <span class="xn-diagnose-italic-val"><?= htmlspecialchars($selected_patient['ChanDoan'] ?: 'Chưa có chẩn đoán sơ bộ') ?></span></p>
        </div>
    </section>
    <?php else: ?>
    <section class="xn-patient-banner-empty">
        <div class="xn-avatar-wrapper" style="align-items: center;">
            <div class="xn-avatar-circle-empty">
                <span class="material-symbols-outlined xn-material-icon xn-avatar-icon-size">group</span>
            </div>
            <div class="xn-info-block">
                <h1 class="xn-heading-empty-status">CHƯA CHỌN PHIẾU KHÁM / BỆNH NHÂN</h1>
                <p class="xn-meta-text-style xn-label-dim" style="margin-top: 4px;">Vui lòng chọn một phiếu khám hoặc chỉ định từ danh sách bên dưới để bắt đầu thực hiện.</p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <div class="xn-grid-layout-configuration">
        <div class="xn-col-span-full-width">
            
            <?php if ($selected_patient): ?>
            <div class="xn-panel-card-box">
                <div class="xn-panel-header-toolbar">
                    <h3 class="xn-panel-heading-title"><span class="material-symbols-outlined xn-material-icon" style="color:var(--primary);">edit_note</span> Chỉ định cần thực hiện của bệnh nhân</h3>
                    <div class="xn-panel-actions-wrapper">
                        <span class="xn-meta-text-style xn-label-dim">Tổng số: <?= count($indications) ?> chỉ định</span>
                        <?php if (count($indications) > 0): ?>
                        <form method="POST" action="" onsubmit="return confirm('Xác nhận thực hiện TẤT CẢ chỉ định cận lâm sàng của bệnh nhân này?');">
                            <input type="hidden" name="action" value="confirm_all_tests">
                            <input type="hidden" name="ma_phieu_kham" value="<?= $selected_patient['MaPhieuKham'] ?>">
                            <button type="submit" class="xn-btn-action-core xn-btn-secondary-action">
                                <span class="material-symbols-outlined xn-material-icon" style="font-size:16px;">select_all</span> Thực hiện tất cả
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="xn-table-responsive-container">
                    <table class="xn-table-data-standard">
                        <thead class="xn-table-thead-theme">
                            <tr>
                                <th class="xn-table-th-cell">Tên chỉ định CLS</th>
                                <th class="xn-table-th-cell">Mô tả / Yêu cầu</th>
                                <th class="xn-table-th-cell">Ngày chỉ định</th>
                                <th class="xn-table-th-cell xn-table-th-center" style="font-weight: 700;">Trạng thái</th>
                                <th class="xn-table-th-cell xn-table-th-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="xn-table-tbody-divide">
                            <?php if (empty($indications)): ?>
                                <tr>
                                    <td colspan="5" class="xn-table-td-cell xn-table-td-center xn-diagnose-italic-val" style="padding: 32px 24px;">
                                        Không có chỉ định cận lâm sàng nào đang ở trạng thái chờ xét nghiệm cho bệnh nhân này.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($indications as $ind): ?>
                                    <tr class="xn-table-row-interactive">
                                        <td class="xn-table-td-cell xn-cell-title-bold"><?= htmlspecialchars($ind['TenLoaiCLS']) ?></td>
                                        <td class="xn-table-td-cell"><?= htmlspecialchars($ind['MoTaChiDinh'] ?: 'Không có') ?></td>
                                        <td class="xn-table-td-cell xn-label-dim"><?= date('H:i d/m/Y', strtotime($ind['NgayChiDinh'])) ?></td>
                                        <td class="xn-table-td-cell xn-table-td-center">
                                            <span class="xn-status-badge-container">
                                                <span class="xn-status-dot-indicator"></span> Chờ thực hiện
                                            </span>
                                        </td>
                                        <td class="xn-table-td-cell xn-table-td-right">
                                            <form method="POST" action="" class="inline-block" onsubmit="return confirm('Xác nhận thực hiện chỉ định này? Chuyển phiếu khám sang trạng thái Đang xét nghiệm.');">
                                                <input type="hidden" name="action" value="confirm_test">
                                                <input type="hidden" name="ma_chi_dinh" value="<?= $ind['MaChiDinh'] ?>">
                                                <input type="hidden" name="ma_phieu_kham" value="<?= $ind['MaPhieuKham'] ?>">
                                                <button type="submit" class="xn-btn-action-core xn-btn-primary-action">
                                                    <span class="material-symbols-outlined xn-material-icon" style="font-size:14px;">check_circle</span> Xác nhận thực hiện
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>
</div>
</main>
</div>

<?php if (isset($_SESSION['toast_success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showAlert === 'function') {
                showAlert(<?= json_encode($_SESSION['toast_success']) ?>, 'success');
            }
        });
    </script>
    <?php unset($_SESSION['toast_success']); ?>
<?php endif; ?>