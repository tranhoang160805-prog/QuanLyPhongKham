<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$menu_role = $_SESSION['user_role'] ?? 'ky-thuat-vien';
$maNVThucHien = isset($_SESSION['user_code']) ? (int)$_SESSION['user_code'] : 0;

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_results') {
    $maChiDinh = isset($_POST['ma_chi_dinh']) ? (int)$_POST['ma_chi_dinh'] : 0;
    $maPhieuKham = isset($_POST['ma_phieu_kham']) ? (int)$_POST['ma_phieu_kham'] : 0;
    $maLoaiCLS = isset($_POST['ma_loai_cls']) ? (int)$_POST['ma_loai_cls'] : 0;
    $ketLuan = isset($_POST['ket_luan']) ? trim($_POST['ket_luan']) : '';
    
    if ($maChiDinh > 0 && $maPhieuKham > 0) {
        $uploaded_file = null;
        $ketQuaText = '';
        $upload_ok = true;
        
        $is_imaging = in_array($maLoaiCLS, [3, 4, 6, 7]);
        if ($is_imaging) {
            if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['file_upload']['tmp_name'];
                $fileName = $_FILES['file_upload']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'dicom'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = 'cls_' . $maChiDinh . '_' . time() . '_' . md5(uniqid()) . '.' . $fileExtension;
                    $uploadFileDir = 'uploads/ket-qua-cls/';
                    
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0777, true);
                    }
                    
                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $uploaded_file = $newFileName;
                    } else {
                        $error_message = "Lỗi khi di chuyển tệp tải lên thư mục lưu trữ.";
                        $upload_ok = false;
                    }
                } else {
                    $error_message = "Định dạng tệp không được hỗ trợ (chỉ cho phép JPG, PNG, GIF, PDF, DICOM).";
                    $upload_ok = false;
                }
            } else {
                $uploaded_file = isset($_POST['existing_file']) ? trim($_POST['existing_file']) : null;
            }
        }
        
        if ($upload_ok) {
            if ($maLoaiCLS == 1) { // Blood test
                $glucose = trim($_POST['blood_glucose'] ?? '');
                $cholesterol = trim($_POST['blood_cholesterol'] ?? '');
                $ast = trim($_POST['blood_ast'] ?? '');
                $alt = trim($_POST['blood_alt'] ?? '');
                $ketQuaText = "Glucose: {$glucose} mmol/L\nCholesterol: {$cholesterol} mmol/L\nAST (GOT): {$ast} U/L\nALT (GPT): {$alt} U/L";
            } elseif ($maLoaiCLS == 2) { // Urine test
                $urine_ph = trim($_POST['urine_ph'] ?? '');
                $urine_sg = trim($_POST['urine_sg'] ?? '');
                $urine_protein = trim($_POST['urine_protein'] ?? '');
                $urine_glucose = trim($_POST['urine_glucose'] ?? '');
                $urine_rbc = trim($_POST['urine_rbc'] ?? '');
                $ketQuaText = "pH: {$urine_ph}\nTỷ trọng: {$urine_sg}\nProtein: {$urine_protein} mg/dL\nGlucose: {$urine_glucose} mg/dL\nHồng cầu: {$urine_rbc} /vi trường";
            } else {
                $ketQuaText = isset($_POST['ket_qua_text']) ? trim($_POST['ket_qua_text']) : '';
            }
            
            try {
                $pdo->beginTransaction();
                
                $stmtInsertKq = $pdo->prepare("
                    INSERT INTO ketquacls (MaChiDinh, KetQuaText, KetLuan, FileKetQua, MaNVThucHien, NgayThucHien) 
                    VALUES (:maChiDinh, :ketQuaText, :ketLuan, :fileKetQua, :maNVThucHien, NOW())
                    ON DUPLICATE KEY UPDATE 
                        KetQuaText = VALUES(KetQuaText),
                        KetLuan = VALUES(KetLuan),
                        FileKetQua = IF(VALUES(FileKetQua) IS NULL, FileKetQua, VALUES(FileKetQua)),
                        MaNVThucHien = VALUES(MaNVThucHien),
                        NgayThucHien = NOW()
                ");
                $stmtInsertKq->execute([
                    'maChiDinh' => $maChiDinh,
                    'ketQuaText' => $ketQuaText,
                    'ketLuan' => $ketLuan,
                    'fileKetQua' => $uploaded_file,
                    'maNVThucHien' => $maNVThucHien > 0 ? $maNVThucHien : null
                ]);
                
                $stmtUpdateCd = $pdo->prepare("UPDATE CHIDINHCLS SET trangthai = 2 WHERE MaChiDinh = ?");
                $stmtUpdateCd->execute([$maChiDinh]);
                
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM CHIDINHCLS WHERE MaPhieuKham = ? AND trangthai < 2");
                $stmtCheck->execute([$maPhieuKham]);
                $remaining = (int)$stmtCheck->fetchColumn();
                
                if ($remaining === 0) {
                    $stmtUpdatePk = $pdo->prepare("UPDATE PHIEUKHAM SET MaTrangThai = 6, NgayCapNhat = NOW() WHERE MaPhieuKham = ?");
                    $stmtUpdatePk->execute([$maPhieuKham]);
                }
                
                $pdo->commit();
                $_SESSION['toast_success'] = "Lưu kết quả xét nghiệm thành công!";
                echo "<script>window.location.href = 'index.php?workspace=1&page=kq-xet-nghiem';</script>";
                exit;
            } catch (Exception $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error_message = "Lỗi khi lưu dữ liệu kết quả: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Mã chỉ định hoặc phiếu khám không hợp lệ.";
    }
}

$maChiDinh = isset($_GET['machidinh']) ? (int)$_GET['machidinh'] : 0;
$selected_test = null;

if ($maChiDinh > 0) {
    try {
        $stmtTest = $pdo->prepare("
            SELECT 
                cd.MaChiDinh,
                cd.MaPhieuKham,
                cd.MoTaChiDinh,
                cd.NgayChiDinh,
                cd.trangthai,
                lcls.TenLoaiCLS,
                lcls.MaLoaiCLS,
                pk.MaPhieuKhamCode,
                pk.ChanDoanSoBo,
                pk.ChanDoan,
                bn.MaBenhNhan,
                bn.MaBN,
                bn.HoTen,
                bn.NgaySinh,
                bn.GioiTinh,
                bn.DiaChi,
                nv.HoTen AS TenBacSi,
                kq.KetQuaText,
                kq.KetLuan,
                kq.FileKetQua
            FROM CHIDINHCLS cd
            JOIN PHIEUKHAM pk ON pk.MaPhieuKham = cd.MaPhieuKham
            JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
            JOIN LOAICLSN lcls ON lcls.MaLoaiCLS = cd.MaLoaiCLS
            LEFT JOIN NHANVIEN nv ON nv.MaNhanVien = pk.MaBacSi
            LEFT JOIN KETQUACLS kq ON kq.MaChiDinh = cd.MaChiDinh
            WHERE cd.MaChiDinh = :maChiDinh AND cd.trangthai = 1
        ");
        $stmtTest->execute(['maChiDinh' => $maChiDinh]);
        $selected_test = $stmtTest->fetch(PDO::FETCH_ASSOC);
        
        if ($selected_test) {
            if (!empty($selected_test['NgaySinh'])) {
                $dob = new DateTime($selected_test['NgaySinh']);
                $today = new DateTime('today');
                $selected_test['Tuoi'] = $dob->diff($today)->y;
            } else {
                $selected_test['Tuoi'] = 'N/A';
            }
        } else {
            $error_message = "Chỉ định cận lâm sàng không hợp lệ hoặc không ở trạng thái đang thực hiện.";
        }
    } catch (PDOException $e) {
        $error_message = "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
    }
}

$executing_tests = [];
try {
    $fetchSql = "
        SELECT 
            cd.MaChiDinh,
            cd.MaPhieuKham,
            cd.MoTaChiDinh,
            cd.NgayChiDinh,
            bn.HoTen,
            bn.NgaySinh,
            lcls.TenLoaiCLS,
            pk.MaPhieuKhamCode
        FROM CHIDINHCLS cd
        JOIN PHIEUKHAM pk ON pk.MaPhieuKham = cd.MaPhieuKham
        JOIN BENHNHAN bn ON bn.MaBenhNhan = pk.MaBenhNhan
        JOIN LOAICLSN lcls ON lcls.MaLoaiCLS = cd.MaLoaiCLS
        WHERE cd.trangthai = 1
        ORDER BY cd.NgayChiDinh DESC
    ";
    $stmtFetch = $pdo->query($fetchSql);
    $executing_tests = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Lỗi tải danh sách chỉ định đang thực hiện: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="public/assets/css/KyThuatVien/kq-xet-nghiem.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<body>

<main>
<div class="cls-container cls-flex-col">

    <?php if (!empty($error_message)): ?>
    <div class="cls-alert-error">
        <span class="material-symbols-outlined">error</span>
        <span><?= htmlspecialchars($error_message) ?></span>
    </div>
    <?php endif; ?>

    <?php if ($selected_test): 
        $maLoai = (int)$selected_test['MaLoaiCLS'];
        $is_imaging = in_array($maLoai, [3, 4, 6, 7]);
    ?>
    <section class="cls-patient-card">
        <div class="cls-patient-left">
            <div class="cls-avatar">
                <span class="material-symbols-outlined">person</span>
            </div>
            <div class="cls-patient-info-wrapper">
                <div class="cls-meta-row">
                    <h1><?= htmlspecialchars($selected_test['HoTen']) ?></h1>
                    <span class="cls-badge-gender">
                        <?= ($selected_test['GioiTinh'] == 'M' ? 'NAM' : ($selected_test['GioiTinh'] == 'F' ? 'NỮ' : 'KHÁC')) ?> - <?= $selected_test['Tuoi'] ?> TUỔI
                    </span>
                    <span class="cls-badge-status">
                        <span class="material-symbols-outlined" style="font-size: 14px;">biotech</span> ĐANG THỰC HIỆN XÉT NGHIỆM
                    </span>
                </div>
                <div class="cls-patient-grid">
                    <p><span class="cls-label-dim">Mã BN:</span> <span class="cls-font-bold"><?= htmlspecialchars($selected_test['MaBN']) ?></span></p>
                    <!-- <p><span class="cls-label-dim">Địa chỉ:</span> <?= htmlspecialchars($selected_test['DiaChi'] ?: 'Chưa cập nhật') ?></p> -->
                </div>
            </div>
        </div>
        <div class="cls-patient-right">
            <p><span class="cls-label-dim">Bác sĩ chỉ định:</span> <span class="cls-doctor-name"><?= htmlspecialchars($selected_test['TenBacSi'] ?: 'Chưa có bác sĩ') ?></span></p>
            <p style="margin-top: 4px;"><span class="cls-label-dim">Chẩn đoán:</span> <span class="cls-italic"><?= htmlspecialchars($selected_test['ChanDoanSoBo'] ?: ($selected_test['ChanDoan'] ?: 'Chưa có chẩn đoán')) ?></span></p>
        </div>
    </section>

    <div class="cls-main-grid">
        
        <div class="cls-col-left">
            <div class="cls-panel">
                <div class="cls-panel-header">
                    <h3 class="cls-panel-title">
                        <span class="material-symbols-outlined">edit_square</span> 
                        Nhập kết quả: <?= htmlspecialchars($selected_test['TenLoaiCLS']) ?>
                    </h3>
                    <span class="cls-panel-subtitle">Mã chỉ định: #<?= $selected_test['MaChiDinh'] ?></span>
                </div>
                
                <form method="POST" action="" enctype="multipart/form-data" class="cls-form">
                    <input type="hidden" name="action" value="save_results">
                    <input type="hidden" name="ma_chi_dinh" value="<?= $selected_test['MaChiDinh'] ?>">
                    <input type="hidden" name="ma_phieu_kham" value="<?= $selected_test['MaPhieuKham'] ?>">
                    <input type="hidden" name="ma_loai_cls" value="<?= $selected_test['MaLoaiCLS'] ?>">
                    
                    <?php if ($selected_test['MoTaChiDinh']): ?>
                    <div class="cls-doctor-note">
                        <span class="cls-note-title">Ghi chú chỉ định từ bác sĩ:</span>
                        <p class="cls-note-content cls-italic">"<?= htmlspecialchars($selected_test['MoTaChiDinh']) ?>"</p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($is_imaging): ?>
                        <div>
                            <label class="cls-form-label">Tải tệp hình ảnh kết quả (.jpg, .png, .gif, .pdf):</label>
                            
                            <?php if ($selected_test['FileKetQua']): ?>
                            <div class="cls-file-history">
                                <span class="cls-file-history-text">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">file_present</span>
                                    Đã có tệp: <?= htmlspecialchars($selected_test['FileKetQua']) ?>
                                </span>
                                <input type="hidden" name="existing_file" value="<?= htmlspecialchars($selected_test['FileKetQua']) ?>">
                            </div>
                            <?php endif; ?>
                            
                            <div class="cls-upload-zone">
                                <input type="file" name="file_upload" id="file_upload" class="cls-file-input" onchange="updateFileName(this)">
                                <span class="material-symbols-outlined cls-upload-icon">cloud_upload</span>
                                <p class="cls-upload-text" id="upload-label">Kéo thả hoặc Click để tải ảnh</p>
                                <p class="cls-upload-hint">Hỗ trợ JPG, PNG, GIF, PDF, DICOM</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if ($maLoai == 1): // Blood Test ?>
                            <div class="cls-test-block">
                                <h4 class="cls-test-block-title">Các chỉ số xét nghiệm máu</h4>
                                <div class="cls-input-grid">
                                    <div>
                                        <label class="cls-sub-label">Glucose (Máu) (mmol/L):</label>
                                        <input type="text" name="blood_glucose" placeholder="Ví dụ: 5.2" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: 3.9 - 6.4 mmol/L</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">Cholesterol TP (mmol/L):</label>
                                        <input type="text" name="blood_cholesterol" placeholder="Ví dụ: 4.8" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: &lt; 5.2 mmol/L</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">AST (GOT) (U/L):</label>
                                        <input type="text" name="blood_ast" placeholder="Ví dụ: 25" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: &lt; 37 U/L</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">ALT (GPT) (U/L):</label>
                                        <input type="text" name="blood_alt" placeholder="Ví dụ: 30" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: &lt; 41 U/L</span>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($maLoai == 2): // Urine Test ?>
                            <div class="cls-test-block">
                                <h4 class="cls-test-block-title">Các chỉ số tổng phân tích nước tiểu</h4>
                                <div class="cls-input-grid">
                                    <div>
                                        <label class="cls-sub-label">pH nước tiểu:</label>
                                        <input type="text" name="urine_ph" placeholder="Ví dụ: 6.5" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: 5.0 - 8.0</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">Tỷ trọng (SG):</label>
                                        <input type="text" name="urine_sg" placeholder="Ví dụ: 1.015" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: 1.005 - 1.030</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">Protein (mg/dL):</label>
                                        <input type="text" name="urine_protein" placeholder="Ví dụ: Âm tính hoặc chỉ số" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: Âm tính</span>
                                    </div>
                                    <div>
                                        <label class="cls-sub-label">Glucose (mg/dL):</label>
                                        <input type="text" name="urine_glucose" placeholder="Ví dụ: Âm tính hoặc chỉ số" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: Âm tính</span>
                                    </div>
                                    <div class="cls-grid-col-full">
                                        <label class="cls-sub-label">Hồng cầu (RBC / vi trường):</label>
                                        <input type="text" name="urine_rbc" placeholder="Ví dụ: 0-1" class="cls-input-text" required>
                                        <span class="cls-input-hint">Bình thường: &lt; 3 hồng cầu / vi trường</span>
                                    </div>
                                </div>
                            </div>
                        <?php else: // General test ?>
                            <div>
                                <label class="cls-form-label">Kết quả xét nghiệm chi tiết:</label>
                                <textarea name="ket_qua_text" class="cls-textarea" rows="4" placeholder="Nhập các chỉ số hoặc mô tả thông số kỹ thuật..." required><?= htmlspecialchars($selected_test['KetQuaText'] ?? '') ?></textarea>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div>
                        <label class="cls-form-label">Kết luận chuyên môn:</label>
                        <textarea name="ket_luan" class="cls-textarea" rows="4" placeholder="Nhập kết luận chuyên môn về mẫu xét nghiệm hoặc hình ảnh..." required><?= htmlspecialchars($selected_test['KetLuan'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="cls-action-row">
                        <a href="index.php?workspace=1&page=kq-xet-nghiem" class="cls-btn-cancel">
                            <span class="material-symbols-outlined">close</span> Hủy bỏ
                        </a>
                        <button type="submit" class="cls-btn-submit">
                            <span class="material-symbols-outlined">save</span> Lưu kết quả xét nghiệm
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="cls-col-right">
            <div class="cls-panel" style="padding: 24px;">
                <h3 class="cls-panel-title" style="margin-bottom: 16px;">
                    <span class="material-symbols-outlined">info</span>
                    Thông tin chỉ định
                </h3>
                <div class="cls-side-info-list">
                    <div>
                        <span class="cls-side-label">Mã phiếu khám:</span>
                        <span class="cls-side-value"><?= htmlspecialchars($selected_test['MaPhieuKhamCode']) ?></span>
                    </div>
                    <div>
                        <span class="cls-side-label">Ngày giờ chỉ định:</span>
                        <span class="cls-side-value"><?= date('H:i d/m/Y', strtotime($selected_test['NgayChiDinh'])) ?></span>
                    </div>
                    <div>
                        <span class="cls-side-label">Loại cận lâm sàng:</span>
                        <span class="cls-side-value-primary"><?= htmlspecialchars($selected_test['TenLoaiCLS']) ?></span>
                    </div>
                    <div>
                        <span class="cls-side-label">Kỹ thuật viên thực hiện:</span>
                        <span class="cls-side-value-secondary"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Kỹ thuật viên') ?> (Mã: <?= $maNVThucHien ?>)</span>
                    </div>
                </div>
            </div>
            
            <?php if ($is_imaging && $selected_test['FileKetQua']): ?>
            <div class="cls-panel">
                <div class="cls-panel-header">
                    <h3 class="cls-panel-title"><span class="material-symbols-outlined">image</span> Hình ảnh đã lưu</h3>
                </div>
                <div class="cls-image-preview-wrapper">
                    <img src="uploads/ket-qua-cls/<?= htmlspecialchars($selected_test['FileKetQua']) ?>" class="cls-preview-img">
                    <a href="uploads/ket-qua-cls/<?= htmlspecialchars($selected_test['FileKetQua']) ?>" target="_blank" class="cls-view-full-link">
                        <span class="material-symbols-outlined" style="font-size: 16px;">open_in_new</span> Xem ảnh kích thước đầy đủ
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
    </div>
    <?php endif; ?>

    <?php if (!$selected_test): ?>
    <div class="cls-panel">
        <div class="cls-search-bar">
            <h3 class="cls-panel-title">
                <span class="material-symbols-outlined">pending_actions</span>
                Danh sách chỉ định đang thực hiện
            </h3>
            
            <div class="cls-search-wrapper">
                <input type="text" id="instant-search" placeholder="Gõ tên BN, loại CLS, mã phiếu..." class="cls-search-input">
                <span class="material-symbols-outlined cls-search-icon">search</span>
            </div>
        </div>
        
        <div class="cls-table-responsive">
            <table class="cls-table" id="test-table">
                <thead class="cls-thead">
                    <tr>
                        <th class="cls-th cls-th-center">STT</th>
                        <th class="cls-th">Mã Phiếu</th>
                        <th class="cls-th">Bệnh nhân</th>
                        <th class="cls-th">Loại cận lâm sàng</th>
                        <th class="cls-th">Ghi chú</th>
                        <th class="cls-th">Ngày giờ chỉ định</th>
                        <th class="cls-th cls-th-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="cls-tbody">
                    <?php if (empty($executing_tests)): ?>
                        <tr class="no-data-row">
                            <td colspan="7" class="cls-empty-row">
                                Không có chỉ định xét nghiệm nào đang ở trạng thái thực hiện cần nhập kết quả.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $stt = 1;
                        foreach ($executing_tests as $item): 
                        ?>
                            <tr class="cls-tr data-row">
                                <td class="cls-td cls-td-center stt-cell"><?= $stt++ ?></td>
                                <td class="cls-td cls-code-text search-target">#<?= htmlspecialchars($item['MaPhieuKhamCode']) ?></td>
                                <td class="cls-td">
                                    <span class="cls-block-bold search-target"><?= htmlspecialchars($item['HoTen']) ?></span>
                                    <span class="cls-block-sub">NS: <?= date('d/m/Y', strtotime($item['NgaySinh'])) ?></span>
                                </td>
                                <td class="cls-td cls-text-secondary-bold search-target"><?= htmlspecialchars($item['TenLoaiCLS']) ?></td>
                                <td class="cls-td"><?= htmlspecialchars($item['MoTaChiDinh'] ?: 'Không có') ?></td>
                                <td class="cls-td cls-label-dim"><?= date('H:i d/m/Y', strtotime($item['NgayChiDinh'])) ?></td>
                                <td class="cls-td cls-td-right">
                                    <a href="index.php?workspace=1&page=kq-xet-nghiem&machidinh=<?= $item['MaChiDinh'] ?>" class="cls-btn-action">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">edit_note</span> Nhập kết quả
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr id="no-search-results" class="hidden">
                            <td colspan="7" class="cls-empty-row">
                                Không tìm thấy kết quả phù hợp.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>
</main>

<script>
function updateFileName(input) {
    const label = document.getElementById('upload-label');
    if (input.files && input.files[0]) {
        label.innerText = "Tệp đã chọn: " + input.files[0].name;
        label.style.color = "var(--primary)";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('instant-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            const rows = document.querySelectorAll('#test-table .data-row');
            const noResultsRow = document.getElementById('no-search-results');
            let visibleCount = 0;

            rows.forEach(row => {
                const targets = row.querySelectorAll('.search-target');
                let match = false;
                
                targets.forEach(target => {
                    const text = target.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                    if (text.includes(keyword)) {
                        match = true;
                    }
                });

                if (match) {
                    row.classList.remove('hidden');
                    visibleCount++;
                    row.querySelector('.stt-cell').textContent = visibleCount;
                } else {
                    row.classList.add('hidden');
                }
            });

            if (noResultsRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noResultsRow.classList.remove('hidden');
                } else {
                    noResultsRow.classList.add('hidden');
                }
            }
        });
    }
});
</script>

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

</body></html>