<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';

header('Content-Type: application/json; charset=utf-8');

function settings_json($success, $data = null, $message = '')
{
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function default_schedule()
{
    return [
        'Monday' => ['start' => '08:00', 'end' => '17:00', 'open' => true],
        'Tuesday' => ['start' => '08:00', 'end' => '17:00', 'open' => true],
        'Wednesday' => ['start' => '08:00', 'end' => '17:00', 'open' => true],
        'Thursday' => ['start' => '08:00', 'end' => '17:00', 'open' => true],
        'Friday' => ['start' => '08:00', 'end' => '17:00', 'open' => true],
        'Saturday' => ['start' => '08:00', 'end' => '12:00', 'open' => true],
        'Sunday' => ['start' => '08:00', 'end' => '12:00', 'open' => false],
    ];
}

function normalize_schedule($schedule)
{
    $defaults = default_schedule();
    if (!is_array($schedule)) {
        return $defaults;
    }

    foreach ($defaults as $day => $default) {
        $row = $schedule[$day] ?? [];
        $defaults[$day] = [
            'start' => preg_match('/^\d{2}:\d{2}$/', $row['start'] ?? '') ? $row['start'] : $default['start'],
            'end' => preg_match('/^\d{2}:\d{2}$/', $row['end'] ?? '') ? $row['end'] : $default['end'],
            'open' => !empty($row['open'])
        ];
    }

    return $defaults;
}

function load_settings(PDO $pdo)
{
    $stmt = $pdo->query("SELECT KhoacCauHinh, GiaTri FROM cauhinhhethong");
    $configs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $schedule = json_decode($configs['gio_mo_cua'] ?? '', true);

    $stmt = $pdo->query("
        SELECT c.ThoiGianCapNhat, tk.TenDangNhap
        FROM cauhinhhethong c
        LEFT JOIN taikhoan tk ON tk.MaTaiKhoan = c.NguoiCapNhat
        WHERE c.ThoiGianCapNhat IS NOT NULL
        ORDER BY c.ThoiGianCapNhat DESC
        LIMIT 1
    ");

    return [
        'configs' => [
            'ten_phong_kham' => $configs['ten_phong_kham'] ?? '',
            'so_dien_thoai' => $configs['so_dien_thoai'] ?? '',
            'email' => $configs['email'] ?? '',
            'dia_chi' => $configs['dia_chi'] ?? '',
            'logo_url' => $configs['logo_url'] ?? 'public/assets/img/icon.png',
            'bao_tri' => (int) ($configs['bao_tri'] ?? 0),
            'ngan_hang' => $configs['ngan_hang'] ?? '',
            'stk' => $configs['stk'] ?? '',
            'ctk' => $configs['ctk'] ?? '',
            'tien_to_benh_nhan' => $configs['tien_to_benh_nhan'] ?? 'BN',
            'tien_to_phieu_kham' => $configs['tien_to_phieu_kham'] ?? 'PK',
            'tien_to_hoa_don' => $configs['tien_to_hoa_don'] ?? 'HD',
            'gio_mo_cua' => normalize_schedule($schedule),
        ],
        'last_update' => $stmt->fetch(PDO::FETCH_ASSOC) ?: null
    ];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        settings_json(true, load_settings($pdo));
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        settings_json(false, null, 'Phuong thuc khong hop le.');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $schedule = normalize_schedule($input['gio_mo_cua'] ?? []);
    $keys = [
        'ten_phong_kham' => trim($input['ten_phong_kham'] ?? ''),
        'so_dien_thoai' => trim($input['so_dien_thoai'] ?? ''),
        'email' => trim($input['email'] ?? ''),
        'dia_chi' => trim($input['dia_chi'] ?? ''),
        'logo_url' => trim($input['logo_url'] ?? 'public/assets/img/icon.png'),
        'bao_tri' => !empty($input['bao_tri']) ? '1' : '0',
        'ngan_hang' => trim($input['ngan_hang'] ?? ''),
        'stk' => trim($input['stk'] ?? ''),
        'ctk' => trim($input['ctk'] ?? ''),
        'tien_to_benh_nhan' => trim($input['tien_to_benh_nhan'] ?? 'BN'),
        'tien_to_phieu_kham' => trim($input['tien_to_phieu_kham'] ?? 'PK'),
        'tien_to_hoa_don' => trim($input['tien_to_hoa_don'] ?? 'HD'),
        'gio_mo_cua' => json_encode($schedule, JSON_UNESCAPED_UNICODE),
    ];

    if ($keys['ten_phong_kham'] === '') {
        settings_json(false, null, 'Ten phong kham khong duoc de trong.');
    }

    if ($keys['email'] !== '' && !filter_var($keys['email'], FILTER_VALIDATE_EMAIL)) {
        settings_json(false, null, 'Email khong hop le.');
    }

    $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        INSERT INTO cauhinhhethong (KhoacCauHinh, GiaTri, NguoiCapNhat, ThoiGianCapNhat)
        VALUES (:key_name, :value_text, :user_id, NOW())
        ON DUPLICATE KEY UPDATE
            GiaTri = VALUES(GiaTri),
            NguoiCapNhat = VALUES(NguoiCapNhat),
            ThoiGianCapNhat = NOW()
    ");

    foreach ($keys as $key => $value) {
        $stmt->execute([
            ':key_name' => $key,
            ':value_text' => $value,
            ':user_id' => $userId
        ]);
    }

    $pdo->commit();
    settings_json(true, load_settings($pdo), 'Da luu cau hinh he thong.');
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    settings_json(false, null, 'Loi cau hinh he thong: ' . $e->getMessage());
}
