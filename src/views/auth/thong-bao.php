<div id="toast-container"></div>
<style>
    /* --- HỆ THỐNG TOAST NOTIFICATION DÙNG CHUNG --- */
#toast-container {
    position: fixed;
    bottom: 24px;
    left: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.toast-msg {
    background: #ffffff;
    color: #1f2937;
    padding: 14px 20px;
    border-radius: 8px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 320px;
    max-width: 420px;
    position: relative;
    /* overflow: hidden; */
    font-size: 0.875rem;
    font-weight: 500;
    animation: toastSlideIn 0.3s ease forwards;
}

/* Biến thể màu sắc */
.toast-msg.toast-success { border-left: 5px solid #10b981; }
.toast-msg.toast-success .toast-icon { color: #10b981; }

.toast-msg.toast-error { border-left: 5px solid #ef4444; }
.toast-msg.toast-error .toast-icon { color: #ef4444; }

/* Thanh thời gian chạy ngược */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    animation: toastProgress 5s linear forwards; /* Chạy chuẩn 3 giây */
}

.toast-success .toast-progress { background-color: #10b981; }
.toast-error .toast-progress { background-color: #ef4444; }

/* Các hiệu ứng Animation chuyển động */
@keyframes toastSlideIn {
    from { transform: translateX(-150%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes toastSlideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(-150%); opacity: 0; }
}

@keyframes toastProgress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
<script>
function showToast(type, message) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast-msg toast-${type}`;
    const icon = type === 'success' ? 'check_circle' : 'error';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined toast-icon">${icon}</span>
        <span class="toast-text">${message}</span>
        <div class="toast-progress"></div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastSlideOut 0.3s ease forwards';
        setTimeout(() => { toast.remove(); }, 300);
    }, 4700);
}

// Đoạn script tự động kiểm tra biến Session từ PHP truyền xuống
document.addEventListener("DOMContentLoaded", function() {
    <?php if (isset($_SESSION['toast_error'])): ?>
        showToast('error', '<?= addslashes($_SESSION['toast_error']) ?>');
        <?php unset($_SESSION['toast_error']); // Xóa luôn tránh lặp lại lỗi khi F5 ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['toast_success'])): ?>
        showToast('success', '<?= addslashes($_SESSION['toast_success']) ?>');
        <?php unset($_SESSION['toast_success']); ?>
    <?php endif; ?>
});
</script>