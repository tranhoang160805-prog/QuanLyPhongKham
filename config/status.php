<?php
define('STATUS_CONFIG', [
    // 1. Trạng thái Phiếu khám
    'exam' => [
        1 => 'Cấp cứu',
        2 => 'Chờ sơ khám',
        3 => 'Chờ khám bệnh',
        4 => 'Chờ xét nghiệm',
        5 => 'Đang xét nghiệm',
        6 => 'Đã xét nghiệm',
        7 => 'Chờ cấp thuốc',
        8 => 'Đã cấp thuốc',
        9 => 'Đã hủy'
    ],

    // 2. Trạng thái Hóa đơn
    'bill' => [
        0 => 'Chưa thanh toán',
        1 => 'Đã thanh toán'
    ],

    // 3. Trạng thái Chỉ định Cận lâm sàng (CLS)
    'cls' => [
        0 => 'Chưa thực hiện',
        1 => 'Đã thực hiện'
    ],

    // 4. Trạng thái Thông báo
    'notification' => [
        0 => 'Chưa đọc',
        1 => 'Đã đọc'
    ]
]);