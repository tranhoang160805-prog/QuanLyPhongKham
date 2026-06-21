<div id="system-toast-alert" class="sys-alert-box sys-alert-hidden">
    <span id="sys-alert-icon" class="material-symbols-outlined"></span>
    <span id="sys-alert-text"></span>
</div>

<style>
.sys-alert-box {
    position: fixed;
    top: 80px;
    right: 60px;
    z-index: 99999;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 24px;
    border-radius: 3px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
    font-size: 16px;
    font-weight: 600;
    font-family: system-ui, -apple-system, sans-serif;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    opacity: 1;
    transform: translateY(0);
}

.sys-alert-hidden {
    opacity: 0;
    transform: translateY(-20px);
    pointer-events: none;
}

/* Các tone màu trạng thái hệ thống */
.sys-alert-success {
    background-color: #ecfdf5 !important;
    border: 1px solid #a7f3d0 !important;
    color: #065f46 !important;
}

.sys-alert-error {
    background-color: #fef2f2 !important;
    border: 1px solid #fca5a5 !important;
    color: #991b1b !important;
}

.sys-alert-warning {
    background-color: #fffbeb !important;
    border: 1px solid #fde68a !important;
    color: #92400e !important;
}

.sys-alert-info {
    background-color: #f0f9ff !important;
    border: 1px solid #bae6fd !important;
    color: #0369a1 !important;
}

.sys-alert-box .material-symbols-outlined {
    font-size: 20px;
    vertical-align: middle;
}
</style>

<script>
window.sysTimeoutTracker = window.sysTimeoutTracker || null;

function showAlert(text, type = 'success', duration = 3500) {
    const alertBox = document.getElementById('system-toast-alert');
    const alertText = document.getElementById('sys-alert-text');
    const alertIcon = document.getElementById('sys-alert-icon');
    
    if (!alertBox || !alertText || !alertIcon) {
        // Fallback an toàn nếu DOM chưa tải kịp
        console.log(`[Alert Fallback]: ${text}`);
        return;
    }

    // Xóa bộ đếm thời gian cũ nếu user kích hoạt thông báo liên tục
    if (window.sysTimeoutTracker) {
        clearTimeout(window.sysTimeoutTracker);
    }

    // Thiết lập nội dung lời nhắn
    alertText.innerText = text;

    // Chỉ định icon tương ứng từ Google Material Symbols
    let iconName = 'check_circle';
    if (type === 'error') iconName = 'cancel';
    if (type === 'warning') iconName = 'warning';
    if (type === 'info') iconName = 'info';
    alertIcon.innerText = iconName;

    // Gán class màu sắc dựa trên type
    alertBox.className = `sys-alert-box sys-alert-${type}`;

    // Lên lịch ẩn hộp thông báo
    window.sysTimeoutTracker = setTimeout(() => {
        alertBox.classList.add('sys-alert-hidden');
    }, duration);
}

window.showAlert = showAlert;
</script>