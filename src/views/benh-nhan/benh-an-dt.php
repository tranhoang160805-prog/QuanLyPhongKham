<!DOCTYPE html>

<html lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed": "#d4e3ff",
                        "surface-bright": "#f9f9ff",
                        "inverse-surface": "#2a313d",
                        "error-container": "#ffdad6",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#1c5697",
                        "tertiary-container": "#3b6fb1",
                        "primary-container": "#1a6fc4",
                        "on-primary-container": "#edf2ff",
                        "on-secondary-fixed-variant": "#00504d",
                        "on-primary-fixed": "#001c3a",
                        "on-secondary": "#ffffff",
                        "on-tertiary-container": "#ecf1ff",
                        "secondary-fixed": "#7df6ef",
                        "surface-tint": "#005fae",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#e2e8f8",
                        "primary-fixed": "#d4e3ff",
                        "on-surface": "#151c27",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#dce2f3",
                        "primary-fixed-dim": "#a5c8ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#d3daea",
                        "on-surface-variant": "#414752",
                        "inverse-on-surface": "#ebf1ff",
                        "background": "#f9f9ff",
                        "on-tertiary-fixed": "#001c3a",
                        "on-error": "#ffffff",
                        "surface-variant": "#dce2f3",
                        "surface-container": "#e7eefe",
                        "on-background": "#151c27",
                        "on-secondary-container": "#00716d",
                        "secondary-container": "#7df6ef",
                        "secondary-fixed-dim": "#5ed9d3",
                        "tertiary-fixed-dim": "#a5c8ff",
                        "error": "#ba1a1a",
                        "outline": "#717783",
                        "secondary": "#006a66",
                        "inverse-primary": "#a5c8ff",
                        "surface": "#f9f9ff",
                        "outline-variant": "#c1c6d4",
                        "on-tertiary-fixed-variant": "#004786",
                        "on-secondary-fixed": "#00201f",
                        "surface-container-low": "#f0f3ff",
                        "primary": "#00569f",
                        "on-primary-fixed-variant": "#004785"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "sidebar_width": "240px",
                        "gutter": "1rem",
                        "container_gap": "1.25rem",
                        "margin_page": "1.5rem",
                        "topbar_height": "60px"
                    },
                    "fontFamily": {
                        "h1": ["Inter"], "h3": ["Inter"], "label-md": ["Inter"], "body-md": ["Inter"], "body-sm": ["Inter"], "h2": ["Inter"], "h1-mobile": ["Inter"]
                    },
                    "fontSize": {
                        "h1": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "h3": ["20px", {"lineHeight": "28px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-md": ["13px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "22.4px", "fontWeight": "400"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "h2": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "h1-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { background-color: #f9f9ff; font-family: 'Inter', sans-serif; }
        .patient-summary-header { background-color: #E8F4FD; }
        .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="text-on-surface">
<!-- TopAppBar -->

<!-- Main Content -->
<main class="max-w-7xl mx-auto flex-1 p-margin_page">
<!-- Patient Summary Header -->

<!-- Bento Grid Categories -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-container_gap">
<!-- Lịch sử khám bệnh -->
<a class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col text-left hover-lift group" href="index.php?page=lich-su-kham">
<div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
<span class="material-symbols-outlined text-primary group-hover:text-white">history</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Lịch sử khám bệnh</h3>
<p class="text-body-sm text-on-surface-variant mb-6">Xem lại các lần thăm khám, chẩn đoán chi tiết và hướng dẫn của bác sĩ chuyên khoa.</p>
<div class="mt-auto flex items-center text-primary font-label-md text-label-md">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</a>
<!-- Kết quả xét nghiệm -->
<button class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col text-left hover-lift group" onclick="window.location.href='index.php?page=xet-nghiem'">
<div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
<span class="material-symbols-outlined text-emerald-600 group-hover:text-white">biotech</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Kết quả xét nghiệm</h3>
<p class="text-body-sm text-on-surface-variant mb-6">Tra cứu kết quả xét nghiệm máu, X-quang, Siêu âm và các chẩn đoán cận lâm sàng khác.</p>
<div class="mt-auto flex items-center text-emerald-600 font-label-md text-label-md">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</button>
<!-- Lịch sử cấp thuốc -->
<button class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col text-left hover-lift group" onclick="window.location.href='index.php?page=don-thuoc'">
<div class="w-12 h-12 rounded-lg bg-amber-50 flex items-center justify-center mb-6 group-hover:bg-amber-600 transition-colors">
<span class="material-symbols-outlined text-amber-600 group-hover:text-white">medication</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Lịch sử cấp thuốc</h3>
<p class="text-body-sm text-on-surface-variant mb-6">Quản lý các đơn thuốc đã nhận, hướng dẫn liều dùng và theo dõi tình trạng sử dụng thuốc.</p>
<div class="mt-auto flex items-center text-amber-600 font-label-md text-label-md">
                        Xem chi tiết <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</button>
<!-- Lịch sử thanh toán -->
<button class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col text-left hover-lift group" onclick="window.location.href='index.php?page=hoa-don-bn'">
<div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors">
<span class="material-symbols-outlined text-purple-600 group-hover:text-white">receipt_long</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Lịch sử thanh toán</h3>
<p class="text-body-sm text-on-surface-variant mb-6">Theo dõi các hóa đơn viện phí, lịch sử giao dịch và tình trạng thanh toán trực tuyến.</p>
<div class="mt-auto flex items-center text-purple-600 font-label-md text-label-md">
                        Xem hóa đơn <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</button>
<!-- Đặt lịch hẹn -->
<button class="bg-primary text-white p-6 rounded-xl border border-primary card-shadow flex flex-col text-left hover-lift group" onclick="window.location.href='index.php?page=dat-lich'">
<div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-white">calendar_add_on</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Đặt lịch hẹn</h3>
<p class="text-body-sm text-white/80 mb-6">Lối tắt nhanh để đặt lịch khám mới với bác sĩ yêu thích hoặc theo chuyên khoa mong muốn.</p>
<div class="mt-auto flex items-center text-white font-label-md text-label-md">
                        Đặt ngay <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</button>
<!-- Cài đặt tài khoản -->
<button class="bg-white p-6 rounded-xl border border-outline-variant card-shadow flex flex-col text-left hover-lift group" onclick="window.location.href='index.php?page=profile'">
<div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center mb-6 group-hover:bg-slate-700 transition-colors">
<span class="material-symbols-outlined text-slate-700 group-hover:text-white">settings_applications</span>
</div>
<h3 class="font-h3 text-h3 mb-2">Cài đặt tài khoản</h3>
<p class="text-body-sm text-on-surface-variant mb-6">Thay đổi mật khẩu, quản lý phương thức xác thực và cập nhật thông tin liên lạc cá nhân.</p>
<div class="mt-auto flex items-center text-slate-700 font-label-md text-label-md">
                        Cập nhật <span class="material-symbols-outlined ml-1 text-[16px]">arrow_forward</span>
</div>
</button>
</div>
</main>
</div>
<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-outline-variant flex justify-around py-3 z-50">
<button class="flex flex-col items-center gap-1 text-on-surface-variant">
<span class="material-symbols-outlined">dashboard</span>
<span class="text-[10px] font-medium">Bảng tin</span>
</button>
<button class="flex flex-col items-center gap-1 text-primary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
<span class="text-[10px] font-bold">Hồ sơ</span>
</button>
<button class="flex flex-col items-center gap-1 text-on-surface-variant">
<span class="material-symbols-outlined">calendar_month</span>
<span class="text-[10px] font-medium">Lịch hẹn</span>
</button>
<button class="flex flex-col items-center gap-1 text-on-surface-variant">
<span class="material-symbols-outlined">settings</span>
<span class="text-[10px] font-medium">Cài đặt</span>
</button>
</nav>
<script>
        // Simple micro-interaction for active states
        document.querySelectorAll('nav a, nav button').forEach(item => {
            item.addEventListener('click', function() {
                // This would normally handle routing
                console.log('Navigating to:', this.innerText.trim());
            });
        });
    </script>
</body></html>