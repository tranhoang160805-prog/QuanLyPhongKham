<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed-dim": "#a5c8ff",
                        "on-error": "#ffffff",
                        "secondary": "#006a66",
                        "on-secondary": "#ffffff",
                        "background": "#f9f9ff",
                        "on-secondary-fixed-variant": "#00504d",
                        "on-error-container": "#93000a",
                        "primary-fixed": "#d4e3ff",
                        "outline-variant": "#c1c6d4",
                        "surface-tint": "#005fae",
                        "on-surface-variant": "#414752",
                        "primary-container": "#1a6fc4",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#e2e8f8",
                        "inverse-on-surface": "#ebf1ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-highest": "#dce2f3",
                        "surface-variant": "#dce2f3",
                        "secondary-fixed-dim": "#5ed9d3",
                        "on-tertiary-fixed-variant": "#004786",
                        "tertiary-fixed-dim": "#a5c8ff",
                        "secondary-fixed": "#7df6ef",
                        "on-secondary-fixed": "#00201f",
                        "primary": "#00569f",
                        "on-primary-fixed-variant": "#004785",
                        "on-tertiary-container": "#ecf1ff",
                        "on-primary-fixed": "#001c3a",
                        "on-background": "#151c27",
                        "inverse-surface": "#2a313d",
                        "surface-container-low": "#f0f3ff",
                        "surface-container": "#e7eefe",
                        "on-secondary-container": "#00716d",
                        "tertiary": "#1c5697",
                        "secondary-container": "#7df6ef",
                        "surface": "#f9f9ff",
                        "surface-bright": "#f9f9ff",
                        "on-surface": "#151c27",
                        "on-tertiary-fixed": "#001c3a",
                        "on-primary-container": "#edf2ff",
                        "error": "#ba1a1a",
                        "tertiary-fixed": "#d4e3ff",
                        "on-tertiary": "#ffffff",
                        "surface-dim": "#d3daea",
                        "outline": "#717783",
                        "tertiary-container": "#3b6fb1",
                        "error-container": "#ffdad6",
                        "inverse-primary": "#a5c8ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "margin_page": "1.5rem",
                        "container_gap": "1.25rem",
                        "sidebar_width": "240px",
                        "topbar_height": "60px",
                        "gutter": "1rem"
                    },
                    "fontFamily": {
                        "h3": ["Inter"],
                        "h2": ["Inter"],
                        "label-md": ["Inter"],
                        "h1-mobile": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "h1": ["Inter"]
                    },
                    "fontSize": {
                        "h3": ["20px", {"lineHeight": "28px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "h2": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-md": ["13px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "h1-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-sm": ["12px", {"lineHeight": "18px", "fontWeight": "400"}],
                        "body-md": ["14px", {"lineHeight": "22.4px", "fontWeight": "400"}],
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
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col">

<!-- Main Content Area: 403 Error Canvas -->
<main class="flex-grow flex items-center justify-center pt-[60px] px-margin_page">
<div class="max-w-3xl w-full flex flex-col items-center text-center">
<!-- Visual Hero: Bento-style illustration or high-end icon -->
<div class="mb-10 relative">
<!-- Decorative Background Element -->
<div class="absolute inset-0 bg-primary/5 blur-3xl -z-10 rounded-full"></div>
<div class="bg-white border border-outline-variant rounded-xl p-8 shadow-[0_1px_3px_rgba(0,0,0,0.08)] flex flex-col items-center gap-4">
<div class="w-24 h-24 bg-error-container rounded-full flex items-center justify-center mb-2">
<span class="material-symbols-outlined text-error text-[48px]" data-icon="lock" style="font-variation-settings: 'FILL' 1;">lock</span>
</div>
<span class="font-label-md text-label-md text-error px-3 py-1 bg-error-container/20 rounded-full uppercase tracking-widest">Error 403</span>
</div>
</div>
<!-- Error Messaging -->
<div class="space-y-4 max-w-lg">
<h1 class="font-h1 text-h1 text-on-surface">403 - Truy cập bị từ chối</h1>
<p class="font-h3 text-h3 text-primary">Bạn không có quyền truy cập.</p>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Tài khoản của bạn không có đủ thẩm quyền để xem nội dung này. Vui lòng liên hệ quản trị viên nếu bạn tin rằng đây là một lỗi.
                </p>
</div>
<!-- Action Cluster -->
<div class="mt-12 flex flex-col sm:flex-row items-center gap-4 w-full justify-center">
<button class="flex items-center justify-center gap-2 bg-primary text-on-primary font-body-md text-body-md px-8 py-3 rounded hover:opacity-90 transition-opacity w-full sm:w-auto" onclick="window.location.href='index.php'">
<span class="material-symbols-outlined text-[18px]" data-icon="dashboard">dashboard</span>
                    Quay về Trang chủ
                </button>

</div>
<!-- Contextual Support Info Card -->
<div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-4 w-full text-left">
<div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant flex items-start gap-4">
<div class="p-2 bg-white rounded shadow-sm">
<span class="material-symbols-outlined text-secondary" data-icon="support_agent">support_agent</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Hỗ trợ kỹ thuật</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Gửi yêu cầu nếu bạn cần cấp quyền đặc biệt.</p>
</div>
</div>
<div class="bg-surface-container-low p-5 rounded-lg border border-outline-variant flex items-start gap-4">
<div class="p-2 bg-white rounded shadow-sm">
<span class="material-symbols-outlined text-secondary" data-icon="admin_panel_settings">admin_panel_settings</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Quản trị viên hệ thống</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Kiểm tra trạng thái tài khoản của bạn.</p>
</div>
</div>
</div>
</div>
</main>

</body></html>