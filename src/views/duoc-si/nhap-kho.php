<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>ClinicCentral - Thêm/Sửa thông tin thuốc</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed": "#7df6ef",
                        "inverse-primary": "#a5c8ff",
                        "surface-container-lowest": "#ffffff",
                        "inverse-on-surface": "#ebf1ff",
                        "surface-variant": "#dce2f3",
                        "on-tertiary-fixed": "#001c3a",
                        "tertiary": "#1c5697",
                        "on-background": "#151c27",
                        "on-primary-fixed": "#001c3a",
                        "primary-fixed-dim": "#a5c8ff",
                        "on-primary": "#ffffff",
                        "tertiary-container": "#3b6fb1",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#dce2f3",
                        "surface-bright": "#f9f9ff",
                        "outline-variant": "#c1c6d4",
                        "on-error-container": "#93000a",
                        "surface": "#f9f9ff",
                        "on-error": "#ffffff",
                        "on-surface-variant": "#414752",
                        "primary-container": "#1a6fc4",
                        "tertiary-fixed-dim": "#a5c8ff",
                        "secondary": "#006a66",
                        "secondary-fixed-dim": "#5ed9d3",
                        "error": "#ba1a1a",
                        "on-primary-fixed-variant": "#004785",
                        "surface-tint": "#005fae",
                        "on-secondary-container": "#00716d",
                        "on-primary-container": "#edf2ff",
                        "outline": "#717783",
                        "on-tertiary": "#ffffff",
                        "surface-container-high": "#e2e8f8",
                        "background": "#f9f9ff",
                        "tertiary-fixed": "#d4e3ff",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#d4e3ff",
                        "on-tertiary-container": "#ecf1ff",
                        "on-secondary-fixed": "#00201f",
                        "on-surface": "#151c27",
                        "inverse-surface": "#2a313d",
                        "surface-container-low": "#f0f3ff",
                        "surface-dim": "#d3daea",
                        "on-tertiary-fixed-variant": "#004786",
                        "on-secondary-fixed-variant": "#00504d",
                        "primary": "#00569f",
                        "secondary-container": "#7df6ef",
                        "surface-container": "#e7eefe"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "sidebar_width": "240px",
                        "topbar_height": "60px",
                        "gutter": "1rem",
                        "container_gap": "1.25rem",
                        "margin_page": "1.5rem"
                    },
                    "fontSize": {
                        "label-md": ["13px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "h2": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "h3": ["20px", {"lineHeight": "28px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "22.4px", "fontWeight": "400"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "h1": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-surface">
<!-- SideNavBar (Shared Component) -->

<!-- Main Content -->
<main class="pt-[calc(60px+1.5rem)] ml-sidebar_width p-margin_page min-h-screen">
<div class="max-w-5xl mx-auto">
<!-- Breadcrumb & Header -->
<div class="mb-6">
<nav class="flex items-center gap-2 text-body-sm text-on-surface-variant mb-2">
<a class="hover:text-primary" href="#">Kho thuốc</a>
<span class="material-symbols-outlined text-[14px]" data-icon="chevron_right">chevron_right</span>
<span class="text-primary font-semibold">Thêm thuốc mới</span>
</nav>
<div class="flex justify-between items-end">
<div>
<h2 class="text-h2 font-h2 text-on-surface">Thông tin dược phẩm</h2>
<p class="text-body-md text-on-surface-variant">Cập nhật thông tin chi tiết vào hệ thống quản lý ClinicCentral</p>
</div>
<div class="flex gap-3">
<button class="px-6 py-2.5 border border-outline text-on-surface font-label-md text-label-md rounded-xl hover:bg-surface-container-high transition-all active:scale-95">Hủy bỏ</button>
<button class="px-6 py-2.5 bg-primary text-on-primary font-label-md text-label-md rounded-xl shadow-sm hover:opacity-90 transition-all active:scale-95 flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]" data-icon="save">save</span>
                            Lưu thông tin
                        </button>
</div>
</div>
</div>
<!-- Form Content -->
<div class="grid grid-cols-12 gap-container_gap">
<!-- Main Form Section -->
<div class="col-span-8 space-y-container_gap">
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="text-label-md font-label-md text-primary uppercase tracking-wider">Thông tin định danh</h3>
</div>
<div class="p-6 grid grid-cols-2 gap-x-6 gap-y-4">
<!-- Tên thuốc -->
<div class="col-span-2 space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant flex items-center gap-1">
                                    Tên thuốc <span class="text-error">*</span>
</label>
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" placeholder="Ví dụ: Paracetamol 500mg" type="text"/>
</div>
<!-- Mã thuốc -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Mã thuốc</label>
<div class="relative">
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all bg-surface-container-lowest" placeholder="MED-XXXXX" type="text"/>
<span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-[18px]" data-icon="qr_code">qr_code</span>
</div>
</div>
<!-- Hoạt chất -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Hoạt chất</label>
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" placeholder="Ví dụ: Acetaminophen" type="text"/>
</div>
<!-- Nhóm thuốc -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Nhóm thuốc</label>
<select class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all appearance-none bg-surface-container-lowest">
<option value="">Chọn nhóm thuốc</option>
<option>Kháng sinh</option>
<option>Giảm đau, hạ sốt</option>
<option>Kháng viêm</option>
<option>Thực phẩm chức năng</option>
</select>
</div>
<!-- Nhà sản xuất -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Nhà sản xuất</label>
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" placeholder="Ví dụ: Dược Hậu Giang" type="text"/>
</div>
</div>
</section>
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="text-label-md font-label-md text-primary uppercase tracking-wider">Thông số thương mại &amp; Đơn vị</h3>
</div>
<div class="p-6 grid grid-cols-2 gap-x-6 gap-y-4">
<!-- Đơn vị tính -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Đơn vị tính</label>
<div class="flex gap-2">
<button class="flex-1 py-2 px-3 border border-primary bg-primary-container text-on-primary-container rounded-xl text-body-sm font-semibold">Viên</button>
<button class="flex-1 py-2 px-3 border border-outline-variant text-on-surface-variant hover:bg-surface-container-high rounded-xl text-body-sm transition-all">Vỉ</button>
<button class="flex-1 py-2 px-3 border border-outline-variant text-on-surface-variant hover:bg-surface-container-high rounded-xl text-body-sm transition-all">Hộp</button>
</div>
</div>
<!-- Định mức tồn tối thiểu -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Tồn kho tối thiểu</label>
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all" type="number" value="100"/>
</div>
<!-- Đơn giá nhập -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Đơn giá nhập</label>
<div class="relative">
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all pr-12 text-right font-semibold" type="text" value="1.200"/>
<span class="absolute right-4 top-1/2 -translate-y-1/2 text-body-sm text-on-surface-variant">VNĐ</span>
</div>
</div>
<!-- Đơn giá bán -->
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Đơn giá bán</label>
<div class="relative">
<input class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all pr-12 text-right font-semibold text-primary" type="text" value="1.500"/>
<span class="absolute right-4 top-1/2 -translate-y-1/2 text-body-sm text-on-surface-variant">VNĐ</span>
</div>
</div>
</div>
</section>
<!-- Liều dùng description -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="text-label-md font-label-md text-primary uppercase tracking-wider">Hướng dẫn sử dụng</h3>
</div>
<div class="p-6">
<div class="space-y-1.5">
<label class="text-label-md font-label-md text-on-surface-variant">Mô tả liều dùng mặc định</label>
<textarea class="w-full px-4 py-2.5 border border-outline-variant rounded-xl text-body-md focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all resize-none" placeholder="Ví dụ: Uống sau khi ăn, mỗi ngày 2 lần, mỗi lần 1 viên..." rows="3"></textarea>
</div>
</div>
</section>
</div>
<!-- Sidebar Info Section -->
<div class="col-span-4 space-y-container_gap">
<!-- Image Upload -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6 flex flex-col items-center text-center">
<div class="w-full aspect-square bg-surface-container-high rounded-xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center mb-4 cursor-pointer hover:border-primary transition-colors group">
<span class="material-symbols-outlined text-[48px] text-on-surface-variant group-hover:text-primary transition-colors" data-icon="add_a_photo">add_a_photo</span>
<p class="text-body-sm font-label-md text-on-surface-variant mt-2">Tải ảnh thuốc</p>
<p class="text-[10px] text-outline mt-1">Hỗ trợ JPG, PNG (Tối đa 2MB)</p>
</div>
<p class="text-body-sm text-on-surface-variant">Hình ảnh giúp dược sĩ nhận diện thuốc nhanh chóng hơn trong quá trình cấp phát.</p>
</section>
<!-- Inventory Summary Card -->
<section class="bg-primary border border-primary/20 rounded-xl shadow-sm p-6 text-on-primary">
<div class="flex items-center gap-2 mb-4">
<span class="material-symbols-outlined" data-icon="info">info</span>
<h4 class="font-bold text-label-md">Gợi ý thiết lập</h4>
</div>
<ul class="space-y-3">
<li class="flex gap-3 text-body-sm items-start">
<span class="material-symbols-outlined text-[16px] mt-0.5" data-icon="check_circle">check_circle</span>
<span>Mã thuốc nên tuân theo định dạng chuẩn MED- để dễ dàng truy xuất qua máy quét barcode.</span>
</li>
<li class="flex gap-3 text-body-sm items-start opacity-90">
<span class="material-symbols-outlined text-[16px] mt-0.5" data-icon="lightbulb">lightbulb</span>
<span>Lợi nhuận gộp dự kiến: <span class="font-bold">25.0%</span> dựa trên giá bán và giá nhập hiện tại.</span>
</li>
</ul>
</section>
<!-- Quick Status -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm p-6">
<h4 class="text-label-md font-label-md text-on-surface mb-4">Trạng thái dược phẩm</h4>
<div class="space-y-4">
<label class="flex items-center justify-between cursor-pointer">
<span class="text-body-md">Đang kinh doanh</span>
<div class="relative inline-flex items-center cursor-pointer">
<input checked="" class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</div>
</label>
<label class="flex items-center justify-between cursor-pointer">
<span class="text-body-md">Thuốc kê đơn (H)</span>
<div class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox"/>
<div class="w-11 h-6 bg-outline rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</div>
</label>
</div>
</section>
</div>
</div>
</div>
</main>
<!-- Success Modal (Hidden by Default) -->
<div class="hidden fixed inset-0 z-[60] flex items-center justify-center px-4 bg-on-background/40 backdrop-blur-sm">
<div class="bg-surface-container-lowest rounded-full p-8 max-w-sm w-full shadow-xl border border-outline-variant text-center">
<div class="w-16 h-16 bg-secondary-container text-on-secondary-container rounded-full flex items-center justify-center mx-auto mb-4">
<span class="material-symbols-outlined text-[32px]" data-icon="check">check</span>
</div>
<h3 class="text-h3 font-h3 text-on-surface mb-2">Thành công</h3>
<p class="text-body-md text-on-surface-variant mb-6">Thông tin dược phẩm đã được lưu trữ an toàn trong hệ thống.</p>
<button class="w-full py-3 bg-primary text-on-primary rounded-xl font-label-md text-label-md hover:opacity-90 transition-all">Quay lại danh sách</button>
</div>
</div>
</body></html>