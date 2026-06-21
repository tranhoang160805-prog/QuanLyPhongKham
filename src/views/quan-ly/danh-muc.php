<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Form Thêm/Sửa Cận lâm sàng - ClinicCentral</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        body {
            background-color: #f4f7fb;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary": "#006a66",
                        "surface-container-high": "#e2e8f8",
                        "on-error": "#ffffff",
                        "on-primary-container": "#edf2ff",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed-variant": "#004785",
                        "background": "#f9f9ff",
                        "tertiary-container": "#3b6fb1",
                        "primary-container": "#1a6fc4",
                        "on-primary-fixed": "#001c3a",
                        "on-tertiary-fixed": "#001c3a",
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#5ed9d3",
                        "on-primary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-secondary-container": "#00716d",
                        "primary": "#00569f",
                        "on-secondary-fixed-variant": "#00504d",
                        "surface-dim": "#d3daea",
                        "surface-bright": "#f9f9ff",
                        "on-error-container": "#93000a",
                        "tertiary-fixed": "#d4e3ff",
                        "surface-tint": "#005fae",
                        "surface-container": "#e7eefe",
                        "inverse-surface": "#2a313d",
                        "surface": "#f9f9ff",
                        "surface-variant": "#dce2f3",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#ecf1ff",
                        "tertiary": "#1c5697",
                        "secondary-fixed": "#7df6ef",
                        "inverse-on-surface": "#ebf1ff",
                        "on-background": "#151c27",
                        "on-surface-variant": "#414752",
                        "surface-container-highest": "#dce2f3",
                        "primary-fixed": "#d4e3ff",
                        "on-secondary-fixed": "#00201f",
                        "surface-container-low": "#f0f3ff",
                        "on-tertiary-fixed-variant": "#004786",
                        "inverse-primary": "#a5c8ff",
                        "secondary-container": "#7df6ef",
                        "outline": "#717783",
                        "outline-variant": "#c1c6d4",
                        "on-surface": "#151c27",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed-dim": "#a5c8ff",
                        "tertiary-fixed-dim": "#a5c8ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "sidebar_width": "240px",
                        "container_gap": "1.25rem",
                        "gutter": "1rem",
                        "margin_page": "1.5rem",
                        "topbar_height": "60px"
                    },
                    "fontSize": {
                        "h2": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "h1": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "h3": ["20px", {"lineHeight": "28px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "h1-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "label-md": ["13px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "body-md": ["14px", {"lineHeight": "22.4px", "fontWeight": "400"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-surface">
<!-- SideNavBar -->

<!-- Main Content Canvas -->
<main class="">
<div class="p-margin_page max-w-5xl mx-auto">
<!-- Breadcrumb & Title -->
<div class="mb-8">

<div class="flex items-center justify-between">
<h2 class="text-h1 text-on-surface">Cấu hình danh mục</h2>
<div class="flex gap-3">
<button class="px-6 py-2.5 rounded-xl border border-outline text-primary font-semibold hover:bg-surface-container-high transition-colors">
                            Hủy bỏ
                        </button>
<button class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-semibold shadow-md hover:bg-primary/90 transition-all active:scale-95">
                            Lưu thông tin
                        </button>
</div>
</div>
</div>
<!-- Bento Layout Form -->
<div class="grid grid-cols-12 gap-container_gap">
<!-- Main Form Section -->
<div class="col-span-12 lg:col-span-8 space-y-container_gap">
<!-- General Information Card -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-h3 text-h3 text-primary flex items-center gap-2">
<span class="material-symbols-outlined">info</span>
                                Thông tin chung
                            </h3>
</div>
<div class="p-6 grid grid-cols-2 gap-6">
<div class="col-span-2 md:col-span-1 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Mã Cận lâm sàng <span class="text-error">*</span></label>
<input class="w-full px-4 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md" placeholder="VD: XQ-001" type="text" value="XQ-001"/>
<p class="text-body-sm text-on-surface-variant italic">Mã định danh duy nhất cho dịch vụ.</p>
</div>
<div class="col-span-2 md:col-span-1 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Phân loại <span class="text-error">*</span></label>
<select class="w-full px-4 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md">
<option value="test">Xét nghiệm (Test)</option>
<option selected="" value="xray">X-Quang (X-ray)</option>
<option value="ultrasound">Siêu âm (Ultrasound)</option>
<option value="mri">Chụp cộng hưởng từ (MRI)</option>
</select>
</div>
<div class="col-span-2 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Tên dịch vụ <span class="text-error">*</span></label>
<input class="w-full px-4 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md" placeholder="Nhập tên đầy đủ của dịch vụ cận lâm sàng" type="text"/>
</div>
</div>
</section>
<!-- Pricing & Details Card -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-h3 text-h3 text-primary flex items-center gap-2">
<span class="material-symbols-outlined">payments</span>
                                Định mức &amp; Đơn giá
                            </h3>
</div>
<div class="p-6 grid grid-cols-2 gap-6">
<div class="col-span-2 md:col-span-1 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Đơn giá (VND) <span class="text-error">*</span></label>
<div class="relative">
<input class="w-full pl-4 pr-16 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md" placeholder="0" type="number"/>
<span class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">VND</span>
</div>
</div>
<div class="col-span-2 md:col-span-1 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Bảo hiểm chi trả (%)</label>
<div class="relative">
<input class="w-full pl-4 pr-12 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md" placeholder="80" type="number"/>
<span class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold">%</span>
</div>
</div>
<div class="col-span-2 space-y-2">
<label class="font-label-md text-label-md text-on-surface">Ghi chú chuyên môn</label>
<textarea class="w-full px-4 py-2.5 border border-outline rounded-lg focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-body-md" placeholder="Mô tả hướng dẫn hoặc chuẩn bị cần thiết cho bệnh nhân..." rows="3"></textarea>
</div>
</div>
</section>
</div>
<!-- Sidebar Form Controls -->
<div class="col-span-12 lg:col-span-4 space-y-container_gap">
<!-- Status & Visibility -->
<section class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-h3 text-h3 text-primary flex items-center gap-2">
<span class="material-symbols-outlined">visibility</span>
                                Trạng thái
                            </h3>
</div>
<div class="p-6 space-y-6">
<div class="flex flex-col gap-4">
<label class="flex items-center gap-4 p-4 border border-primary-container bg-primary-container/5 rounded-xl cursor-pointer group transition-all hover:bg-primary-container/10">
<input checked="" class="w-5 h-5 text-primary border-outline focus:ring-primary" name="status" type="radio" value="active"/>
<div>
<p class="font-label-md text-label-md text-primary">Hoạt động (Active)</p>
<p class="text-body-sm text-on-surface-variant">Dịch vụ hiển thị cho bác sĩ và bệnh nhân.</p>
</div>
</label>
<label class="flex items-center gap-4 p-4 border border-outline-variant rounded-xl cursor-pointer group transition-all hover:bg-surface-container-low">
<input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="status" type="radio" value="hidden"/>
<div>
<p class="font-label-md text-label-md text-on-surface">Tạm ẩn (Hidden)</p>
<p class="text-body-sm text-on-surface-variant">Ngừng cung cấp dịch vụ tạm thời.</p>
</div>
</label>
</div>
</div>
</section>
<!-- Preview Card -->
<section class="bg-primary-container/10 border border-primary-container/20 rounded-xl p-6 relative overflow-hidden">
<div class="absolute top-0 right-0 p-4 opacity-10">
<span class="material-symbols-outlined text-[100px]" style="font-variation-settings: 'FILL' 1;">medical_information</span>
</div>
<h4 class="font-label-md text-label-md text-primary mb-4 uppercase tracking-widest">Xem trước hiển thị</h4>
<div class="bg-surface-container-lowest p-4 rounded-lg shadow-sm border border-outline-variant relative z-10">
<div class="flex justify-between items-start mb-3">
<span class="px-2 py-1 bg-secondary-container text-on-secondary-container text-[10px] font-bold rounded uppercase">X-Quang</span>
<span class="text-primary font-bold text-h3">XQ-001</span>
</div>
<p class="font-h3 text-h3 text-on-surface mb-2 leading-tight">Chụp X-Quang Phổi Thẳng</p>
<div class="flex items-center justify-between mt-4 pt-4 border-t border-outline-variant">
<p class="text-body-sm text-on-surface-variant">Giá niêm yết:</p>
<p class="font-bold text-primary text-h2">150.000 <span class="text-body-sm">VND</span></p>
</div>
</div>
</section>
<!-- Audit Trail (Mini) -->
<div class="px-2 space-y-2">
<div class="flex items-center gap-2 text-body-sm text-on-surface-variant">
<span class="material-symbols-outlined text-[18px]">history</span>
<span>Cập nhật lần cuối: 12/10/2023 - 14:30</span>
</div>
<div class="flex items-center gap-2 text-body-sm text-on-surface-variant">
<span class="material-symbols-outlined text-[18px]">person</span>
<span>Người thực hiện: Admin_Quang</span>
</div>
</div>
</div>
</div>
<!-- Image/Visual Anchor Section (Healthcare Modernism) -->
<div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-container_gap">
<div class="h-64 rounded-xl overflow-hidden shadow-sm border border-outline-variant relative group">
<img alt="Lab Infrastructure" class="w-full h-full object-cover grayscale-[20%] group-hover:scale-105 transition-transform duration-700" data-alt="A clean, sterile medical laboratory setting with modern automated blood analysis machines and high-tech equipment under cool bright lighting. The atmosphere is professional and precise, featuring a palette of white and surgical blues. A medical professional in a white coat is partially visible in the background, ensuring a high-standard healthcare environment for diagnostic excellence." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAP0C9Cv0WhE-3idOii1iKvuQrDyarmU8oGQjJJ9CFfMYabwhTqIKnBEXww_BvODkVhat7PtRcwCsIIbqE9ZOBBtmlyv7ffq1mxunWonUmJ57YJ2KdnzQ3kgC-T_oDlqIGvH0Q8pyTjQyUdBtHGRprHUu1POCfxduvJSt4jvFI5vvi1jpv8eUZW_88UKq5m5w3vfT9i2gxzNYuo0_GUd17zCFkhgikD6KwVt1In3GX7vgj0FPQ1MW8F6jJUGR0qIlUyeSTSbY9LMO4"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-background/60 to-transparent flex items-end p-6">
<p class="text-on-primary font-h3">Hạ tầng xét nghiệm hiện đại</p>
</div>
</div>
<div class="h-64 rounded-xl overflow-hidden shadow-sm border border-outline-variant relative group">
<img alt="Radiology Tech" class="w-full h-full object-cover grayscale-[20%] group-hover:scale-105 transition-transform duration-700" data-alt="A high-resolution display showing a detailed chest X-ray in a darkened radiology suite. The image is crisp and clear, illustrating advanced imaging technology used for patient diagnosis. Soft blue light from the monitors illuminates the dark space, emphasizing the precision and technical sophistication of the ClinicCentral imaging department. The mood is focused and clinical." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcehNZnIPnewn3C6pDL0iTd5VjipeNgYjgY6yD-3N-PH7PfdfCaHeTpvLwz0olIzhXmR-VEYHEZs_3GOYLke7Elk3lfFvDK-LauuX4Wpm1Pscq_DAqaCebhfKpOdYcKhHlMuiFd78Pcsn4n8yYo4GlTt_gxmgxuApzXpRcmUUaL18vyh5ucNAeg0mIg_HLnpEcCsokHQMfVcixNHLPyCK6lA3ahGReM2XgV3TBMq7xqfRZE-mOhtLgE_p5KInqEvYSjDs1Y4CDFtQ"/>
<div class="absolute inset-0 bg-gradient-to-t from-on-background/60 to-transparent flex items-end p-6">
<p class="text-on-primary font-h3">Công nghệ chẩn đoán hình ảnh</p>
</div>
</div>
</div>
</div>
</main>
<!-- Floating Action Feedback (Mockup) -->
<div class="fixed bottom-8 right-8 flex flex-col gap-3 pointer-events-none">
<div class="bg-inverse-surface text-inverse-on-surface px-6 py-3 rounded-full shadow-xl flex items-center gap-3 opacity-0 animate-in slide-in-from-bottom-4">
<span class="material-symbols-outlined text-secondary-fixed">check_circle</span>
<span class="text-body-md">Đã lưu thay đổi thành công!</span>
</div>
</div>
</body></html>