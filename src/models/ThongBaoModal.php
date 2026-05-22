<div id="global-notification-modal" class="g-modal-overlay" style="display: none;">
    <div class="g-modal-container">
        <div class="g-modal-header">
            <h4 id="g-modal-title">Thông báo</h4>
            <button class="g-modal-close-x" onclick="CustomModal.close()">&times;</button>
        </div>
        
        <div class="g-modal-body">
            <div id="g-modal-icon-container" class="g-modal-icon"></div>
            <p id="g-modal-message">Nội dung thông báo ở đây...</p>
        </div>
        
        <div class="g-modal-footer">
            <button id="g-btn-cancel" class="g-btn g-btn-secondary" style="display: none;">Hủy</button>
            <button id="g-btn-confirm" class="g-btn g-btn-primary" style="display: none;">Xác nhận</button>
            <button id="g-btn-close" class="g-btn g-btn-outline" style="display: none;" onclick="CustomModal.close()">Đóng</button>
        </div>
    </div>
</div>

<style>
/* CSS Scoped riêng cho Modal thông báo để không bị vỡ giao diện hệ thống */
.g-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
    animation: fadeIn 0.2s ease-out;
}

.g-modal-container {
    background-color: #ffffff;
    border-radius: 8px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    padding: 20px;
    animation: slideUp 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.g-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 10px;
}

.g-modal-header h4 {
    margin: 0;
    font-size: 18px;
    color: #1a1a1a;
    font-weight: 600;
}

.g-modal-close-x {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    line-height: 1;
}

.g-modal-close-x:hover {
    color: #333;
}

.g-modal-body {
    text-align: center;
    padding: 10px 0 20px 0;
}

.g-modal-icon {
    font-size: 48px;
    margin-bottom: 12px;
}

#g-modal-message {
    margin: 0;
    font-size: 15px;
    color: #4a4a4a;
    line-height: 1.5;
}

.g-modal-footer {
    display: flex;
    justify-content: cubic-bezier;
    gap: 10px;
    border-top: 1px solid #f0f0f0;
    padding-top: 15px;
}

/* Base Button Styles */
.g-btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.2s ease;
    min-width: 80px;
}

.g-btn-primary {
    background-color: #ba1a1a; /* Đồng bộ tông đỏ lỗi/khóa của bạn */
    color: white;
}
.g-btn-primary:hover { background-color: #a01414; }

.g-btn-secondary {
    background-color: #e0e0e0;
    color: #333;
}
.g-btn-secondary:hover { background-color: #d5d5d5; }

.g-btn-outline {
    background-color: transparent;
    border: 1px solid #ccc;
    color: #666;
}
.g-btn-outline:hover { background-color: #f5f5f5; }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<script>
// Thư viện điều khiển Modal thông báo động dạng Hướng đối tượng (Global Object)
const CustomModal = {
    // Hàm hiển thị tổng quát
    show: function(options) {
        // Lấy các element trong DOM
        const modal = document.getElementById('global-notification-modal');
        const titleEl = document.getElementById('g-modal-title');
        const messageEl = document.getElementById('g-modal-message');
        const iconContainer = document.getElementById('g-modal-icon-container');
        
        const btnConfirm = document.getElementById('g-btn-confirm');
        const btnCancel = document.getElementById('g-btn-cancel');
        const btnClose = document.getElementById('g-btn-close');

        // Cấu hình text cơ bản
        titleEl.innerText = options.title || 'Thông báo';
        messageEl.innerText = options.message || '';
        
        // Cấu hình biểu tượng (nếu dùng Material Symbols của bạn)
        if (options.icon) {
            iconContainer.innerHTML = `<span class="material-symbols-outlined" style="color: ${options.iconColor || '#666'}; font-size:48px;">${options.icon}</span>`;
            iconContainer.style.display = 'block';
        } else {
            iconContainer.style.display = 'none';
        }

        // Tự động cấu hình hiển thị / ẩn các nút theo yêu cầu gọi
        // Nút Xác nhận
        if (options.showConfirm) {
            btnConfirm.style.display = 'block';
            btnConfirm.innerText = options.confirmText || 'Xác nhận';
            if (options.confirmClass) {
                btnConfirm.className = `g-btn ${options.confirmClass}`;
            } else {
                btnConfirm.className = `g-btn g-btn-primary`;
            }
            // Gán sự kiện click cho nút xác nhận
            btnConfirm.onclick = function() {
                if (typeof options.onConfirm === 'function') {
                    options.onConfirm();
                }
                CustomModal.close();
            };
        } else {
            btnConfirm.style.display = 'none';
        }

        // Nút Hủy
        if (options.showCancel) {
            btnCancel.style.display = 'block';
            btnCancel.innerText = options.cancelText || 'Hủy';
            btnCancel.onclick = function() {
                if (typeof options.onCancel === 'function') {
                    options.onCancel();
                }
                CustomModal.close();
            };
        } else {
            btnCancel.style.display = 'none';
        }

        // Nút Đóng độc lập
        if (options.showClose) {
            btnClose.style.display = 'block';
            btnClose.innerText = options.closeText || 'Đóng';
        } else {
            btnClose.style.display = 'none';
        }

        // Hiện modal lên màn hình
        modal.style.display = 'flex';
    },

    // Hàm ẩn modal
    close: function() {
        document.getElementById('global-notification-modal').style.display = 'none';
    }
};
</script>