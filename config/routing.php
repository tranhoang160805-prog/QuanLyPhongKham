<?php
$allowed_pages = [
    // trang lỗi
    '404'            => 'src/views/errors/404.php',
    '403'            => 'src/views/errors/403.php',
    '503'            => 'src/views/errors/bao-tri.php',

    'login'          => 'src/views/auth/login.php',
    'dang-ky'        => 'src/views/auth/dangky.php',

    'home'           => 'src/views/auth/trang-chu.php',
    'doctors'        => 'src/views/auth/doi-ngu.php',
    'services'       => 'src/views/auth/dich-vu.php',
    
    // Phân hệ: Quản lý
    'dashboard'      => 'src/views/quan-ly/dashboard.php',
    'nhan-vien'      => 'src/views/quan-ly/nhan-vien.php',
    'bao-cao'        => 'src/views/quan-ly/bao-cao.php',
    'danh-muc'       => 'src/views/quan-ly/danh-muc.php',
    'cai-dat'        => 'src/views/quan-ly/cai-dat-he-thong.php',

    // Phân hệ: Bệnh nhân
    'profile'       => 'src/views/benh-nhan/profile.php',
    'dat-lich'       => 'src/views/benh-nhan/dat-lich.php',
    'lich-su-kham'   => 'src/views/benh-nhan/lich-su-kham.php',
    'don-thuoc'      => 'src/views/benh-nhan/don-thuoc.php',
    'benh-an'        => 'src/views/benh-nhan/benh-an-dt.php',
    'hoa-don-bn'     => 'src/views/benh-nhan/hoa-don-bn.php',
    'xet-nghiem'     => 'src/views/benh-nhan/ls-xet-nghiem.php',

    // Phân hệ: Lễ tân
    'ds-benh-nhan'   => 'src/views/le-tan/ds-benh-nhan.php',
    'lich-hen-lt'    => 'src/views/le-tan/lich-hen.php',
    'hoa-don-lt'     => 'src/views/le-tan/hoa-don.php',
    'thanh-toan'     => 'src/views/le-tan/thanh-toan-hd.php',

    // Phân hệ: Điều dưỡng
    'so-kham'        => 'src/views/dieu-duong/so-kham.php',
    'ls-so-kham'     => 'src/views/dieu-duong/ls-so-kham.php',

    // Phân hệ: Bác sĩ
    'kham-benh'      => 'src/views/bac-si/kham-benh.php',
    'chi-dinh'       => 'src/views/bac-si/chi-dinh-cls.php',
    'cap-thuoc-bs'   => 'src/views/bac-si/cap-thuoc.php',
    'ds-kham-benh'   => 'src/views/bac-si/ds-kham-benh.php',
    'kq'             => 'src/views/bac-si/xem-kq-cls.php',

    // Phân hệ: Kỹ thuật viên
    'xet-nghiem-ktv' => 'src/views/ky-thuat-vien/xet-nghiem.php',
    'ls-xet-nghiem'  => 'src/views/ky-thuat-vien/ls-xet-nghiem.php',
    'kq-xet-nghiem'  => 'src/views/ky-thuat-vien/kq-xet-nghiem.php',

    // Phân hệ: Dược sĩ
    'kho-thuoc'      => 'src/views/duoc-si/kho-thuoc.php',
    'cap-phat'       => 'src/views/duoc-si/cap-phat.php',
    'nhap-kho'       => 'src/views/duoc-si/nhap-kho.php',
];

$page_titles = [
    'home'           => 'Trang chủ - Phòng khám Hương Sơn',
    'doctors'        => 'Đội ngũ Bác sĩ - Phòng khám Hương Sơn',
    'services'       => 'Dịch vụ & Bảng giá - Phòng khám Hương Sơn',
    
    // Quản lý
    'dashboard'      => 'Bảng điều khiển tổng quan',
    'nhan-vien'      => 'Quản lý nhân sự & Tài khoản',
    'bao-cao'        => 'Báo cáo thống kê doanh thu',
    'danh-muc'       => 'Quản lý danh mục hệ thống',
    'cai-dat'        => 'Cấu hình hệ thống ',

    // Bệnh nhân
    'dat-lich'       => 'Đặt lịch hẹn khám bệnh trực tuyến',
    'lich-hen'       => 'Sổ tay lịch hẹn cá nhân',
    'lich-su-kham'   => 'Lịch sử khám bệnh & Kết quả',
    'don-thuoc'      => 'Danh sách đơn thuốc điện tử',
    'xet-nghiem'     => 'Đánh giá dịch vụ & Đóng góp ý kiến',
    'hoa-don-bn'     => 'Tra cứu hóa đơn viện phí ',

    // Lễ tân
    'ds-benh-nhan'   => 'Tiếp đón & Quản lý thông tin bệnh nhân',
    'phieu-kham'     => 'Khởi tạo phiếu đăng ký khám bệnh',
    'lich-hen-lt'    => 'Điều phối lịch hẹn khám tại quầy',
    'hoa-don-lt'     => 'Thanh toán viện phí & Xuất hóa đơn',
    'thanh-toan'     => 'Thanh toán viện phí',

    // Điều dưỡng
    'so-kham'        => 'Cập nhật chỉ số sinh tồn',
    'ls-so-kham'     => 'Lịch sử tiếp nhận phân phòng khám',

    // Bác sĩ
    'kham-benh'      => 'Tiến trình chẩn đoán & Kê toa thuốc',
    'chi-dinh'       => 'Chỉ định cận lâm sàng',
    'cap-thuoc-bs'   => 'Xem nhanh lịch sử cấp thuốc',
    'ds-kham-benh'   => 'Danh sách hàng đợi bệnh nhân phòng khám',

    // Kỹ thuật viên
    'xet-nghiem-ktv' => 'Cập nhật kết quả xét nghiệm',
    'ls-xet-nghiem'  => 'Nhật ký thực hiện cận lâm sàng',

    // Dược sĩ
    'kho-thuoc'      => 'Quản lý kho thuốc',
    'cap-phat'       => 'Quầy cấp phát thuốc',
    'nhap-kho'       => 'Nhập thuốc',
];

$menus_by_role = [
    'admin' => [
        ['id' => 'dashboard',    'label' => 'Dashboard Admin',    'icon' => 'dashboard'],
        ['id' => 'nhan-vien',      'label' => 'Quản lý Nhân sự',    'icon' => 'badge'],
        ['id' => 'bao-cao',      'label' => 'Báo cáo & Thống kê', 'icon' => 'assessment'],
        // ['id' => 'danh-muc',     'label' => 'Quản lý Danh mục',   'icon' => 'category'],
        ['id' => 'cai-dat',      'label' => 'Cài đặt hệ thống',   'icon' => 'settings_suggest']
    ],
    'benh-nhan' => [
        ['id' => 'dat-lich',     'label' => 'Đặt lịch hẹn',       'icon' => 'add_task'],
        ['id' => 'lich-hen',     'label' => 'Lịch hẹn của tôi',   'icon' => 'event_note'],
        ['id' => 'lich-su-kham', 'label' => 'Lịch sử khám',       'icon' => 'history'],
        ['id' => 'don-thuoc',    'label' => 'Đơn thuốc',          'icon' => 'prescriptions'],
        ['id' => 'xet-nghiem',   'label' => 'Kết quả xét nghiệm', 'icon' => 'mic'],
        ['id' => 'hoa-don-bn',   'label' => 'Hóa đơn',            'icon' => 'receipt_long']
    ],
    'le-tan' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Phiếu khám hôm nay', 'icon' => 'groups'],
        ['id' => 'lich-hen-lt',  'label' => 'Lịch hẹn',           'icon' => 'calendar_month'],
        // ['id' => 'hoa-don-lt',   'label' => 'Hóa đơn',            'icon' => 'payments'],
        // ['id' => 'thanh-toan',   'label' => 'Thanh toán',          'icon' => 'credit_card']
    ],
    'dieu-duong' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Phiếu khám hôm nay', 'icon' => 'groups'],
        // ['id' => 'so-kham',      'label' => 'Sơ khám',            'icon' => 'monitor_heart'],
        // ['id' => 'ls-so-kham',   'label' => 'Lịch sử sơ khám',    'icon' => 'manage_search']
    ],
    'bac-si' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Phiếu khám hôm nay', 'icon' => 'groups'],
        // ['id' => 'kham-benh',    'label' => 'Khám bệnh',          'icon' => 'stethoscope'],
        ['id' => 'kq',     'label' => 'Kết quả xét nghiệm',   'icon' => 'biotech'],
    ],
    'ky-thuat-vien' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Phiếu khám hôm nay', 'icon' => 'groups'],
        // ['id' => 'xet-nghiem-ktv','label' => 'Xét nghiệm',         'icon' => 'science'],
        ['id' => 'kq-xet-nghiem',     'label' => 'Kết quả xét nghiệm',   'icon' => 'biotech'],
        // ['id' => 'ls-xet-nghiem', 'label' => 'Lịch sử xét nghiệm', 'icon' => 'lab_profile'],
    ],
    'duoc-si' => [
        ['id' => 'ds-benh-nhan', 'label' => 'Phiếu khám hôm nay', 'icon' => 'groups'],
        ['id' => 'kho-thuoc',    'label' => 'Kho thuốc',          'icon' => 'inventory_2'],
        // ['id' => 'cap-phat',     'label' => 'Cấp phát thuốc',     'icon' => 'vaccines']
    ]
];