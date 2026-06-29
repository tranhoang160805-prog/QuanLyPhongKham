 
<link rel="stylesheet" href="public/assets/css/BenhNhan/benh-an-dt.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<body class="app-body text-on-surface">

    <div class="min-h-screen flex flex-col">
        <main class="max-w-7xl mx-auto flex-1 p-margin_page w-full">
            
            <div class="bento-grid">
                
                <a class="bento-card group" href="index.php?page=lich-su-kham">
                    <div class="bento-card__icon-wrapper bg-blue-50 group-hover:bg-primary">
                        <span class="material-symbols-outlined text-primary group-hover:text-white">history</span>
                    </div>
                    <h3 class="bento-card__title">Lịch sử khám bệnh</h3>
                    <p class="bento-card__desc text-on-surface-variant">Xem lại các lần thăm khám, chẩn đoán chi tiết và hướng dẫn của bác sĩ chuyên khoa.</p>
                    <div class="bento-card__action text-primary">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </a>

                <button class="bento-card group" onclick="window.location.href='index.php?page=xet-nghiem'">
                    <div class="bento-card__icon-wrapper bg-emerald-50 group-hover:bg-emerald-600">
                        <span class="material-symbols-outlined text-emerald-600 group-hover:text-white">biotech</span>
                    </div>
                    <h3 class="bento-card__title">Kết quả xét nghiệm</h3>
                    <p class="bento-card__desc text-on-surface-variant">Tra cứu kết quả xét nghiệm máu, X-quang, Siêu âm và các chẩn đoán cận lâm sàng khác.</p>
                    <div class="bento-card__action text-emerald-600">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </button>

                <button class="bento-card group" onclick="window.location.href='index.php?page=don-thuoc'">
                    <div class="bento-card__icon-wrapper bg-amber-50 group-hover:bg-amber-600">
                        <span class="material-symbols-outlined text-amber-600 group-hover:text-white">medication</span>
                    </div>
                    <h3 class="bento-card__title">Lịch sử cấp thuốc</h3>
                    <p class="bento-card__desc text-on-surface-variant">Quản lý các đơn thuốc đã nhận, hướng dẫn liều dùng và theo dõi tình trạng sử dụng thuốc.</p>
                    <div class="bento-card__action text-amber-600">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </button>

                <button class="bento-card group" onclick="window.location.href='index.php?page=hoa-don-bn'">
                    <div class="bento-card__icon-wrapper bg-purple-50 group-hover:bg-purple-600">
                        <span class="material-symbols-outlined text-purple-600 group-hover:text-white">receipt_long</span>
                    </div>
                    <h3 class="bento-card__title">Lịch sử thanh toán</h3>
                    <p class="bento-card__desc text-on-surface-variant">Theo dõi các hóa đơn viện phí, lịch sử giao dịch và tình trạng thanh toán trực tuyến.</p>
                    <div class="bento-card__action text-purple-600">
                        Xem hóa đơn <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </button>

                <button class="bento-card bento-card--primary" onclick="window.location.href='index.php?page=dat-lich'">
                    <div class="bento-card__icon-wrapper bg-white/20">
                        <span class="material-symbols-outlined text-white">calendar_add_on</span>
                    </div>
                    <h3 class="bento-card__title">Đặt lịch hẹn</h3>
                    <p class="bento-card__desc text-white/80">Lối tắt nhanh để đặt lịch khám mới với bác sĩ yêu thích hoặc theo chuyên khoa mong muốn.</p>
                    <div class="bento-card__action text-white">
                        Đặt ngay <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </button>

                <button class="bento-card group" onclick="window.location.href='index.php?page=profile'">
                    <div class="bento-card__icon-wrapper bg-slate-100 group-hover:bg-slate-700">
                        <span class="material-symbols-outlined text-slate-700 group-hover:text-white">settings_applications</span>
                    </div>
                    <h3 class="bento-card__title">Cài đặt tài khoản</h3>
                    <p class="bento-card__desc text-on-surface-variant">Thay đổi mật khẩu, quản lý phương thức xác thực và cập nhật thông tin liên lạc cá nhân.</p>
                    <div class="bento-card__action text-slate-700">
                        Cập nhật <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
                    </div>
                </button>
                
            </div>
        </main>
    </div>

    <nav class="mobile-nav">
        <button class="mobile-nav__item text-on-surface-variant">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="mobile-nav__label font-medium">Bảng tin</span>
        </button>
        <button class="mobile-nav__item text-primary">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
            <span class="mobile-nav__label font-bold">Hồ sơ</span>
        </button>
        <button class="mobile-nav__item text-on-surface-variant">
            <span class="material-symbols-outlined">calendar_month</span>
            <span class="mobile-nav__label font-medium">Lịch hẹn</span>
        </button>
        <button class="mobile-nav__item text-on-surface-variant">
            <span class="material-symbols-outlined">settings</span>
            <span class="mobile-nav__label font-medium">Cài đặt</span>
        </button>
    </nav>

    <script>
        // Micro-interaction xử lý sự kiện click chuyển tab/trang công việc
        document.querySelectorAll('.mobile-nav__item, .bento-card').forEach(element => {
            element.addEventListener('click', function() {
                console.log('Navigating to:', this.innerText.replace(/\n/g, ' ').trim());
            });
        });
    </script>
</body>
</html>