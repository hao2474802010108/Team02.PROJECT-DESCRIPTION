// cart.js - JavaScript cho trang giỏ hàng

document.addEventListener('DOMContentLoaded', function() {
    initCartPage();
});

function initCartPage() {
    initQuantityControls();
    initCartForm();
    initAutoUpdate();
}

// Khởi tạo điều khiển số lượng
function initQuantityControls() {
    const quantityControls = document.querySelectorAll('.item-quantity');
    
    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.minus');
        const plusBtn = control.querySelector('.plus');
        const input = control.querySelector('.qty-input');
        
        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', function() {
                updateQuantity(input, -1);
            });
            
            plusBtn.addEventListener('click', function() {
                updateQuantity(input, 1);
            });
            
            input.addEventListener('change', function() {
                validateQuantity(this);
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '' || this.value < 1) {
                    this.value = 1;
                }
            });
        }
    });
}

// Cập nhật số lượng
function updateQuantity(input, change) {
    let currentValue = parseInt(input.value) || 1;
    const max = parseInt(input.getAttribute('max')) || 999;
    const min = parseInt(input.getAttribute('min')) || 1;
    
    let newValue = currentValue + change;
    
    if (newValue < min) {
        newValue = min;
    } else if (newValue > max) {
        newValue = max;
        showMessage(`Số lượng tối đa là ${max}`, 'warning');
    }
    
    input.value = newValue;
    
    // Kích hoạt sự kiện change để tính lại tổng tiền
    input.dispatchEvent(new Event('change', { bubbles: true }));
}

// Validate số lượng
function validateQuantity(input) {
    const value = parseInt(input.value);
    const max = parseInt(input.getAttribute('max')) || 999;
    const min = parseInt(input.getAttribute('min')) || 1;
    
    if (isNaN(value) || value < min) {
        input.value = min;
    } else if (value > max) {
        input.value = max;
        showMessage(`Số lượng tối đa là ${max}`, 'warning');
    }
}

// Khởi tạo form giỏ hàng
function initCartForm() {
    const form = document.getElementById('cartForm');
    const updateBtn = form?.querySelector('.btn-update');
    
    if (form && updateBtn) {
        form.addEventListener('submit', function(e) {
            // Thêm hiệu ứng loading
            updateBtn.classList.add('btn-loading');
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
        });
    }
}

// Tự động cập nhật khi thay đổi số lượng (tùy chọn)
function initAutoUpdate() {
    const inputs = document.querySelectorAll('.qty-input');
    let updateTimeout;
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(updateTimeout);
            
            // Tự động cập nhật sau 1.5 giây không thay đổi
            updateTimeout = setTimeout(() => {
                // Có thể thêm logic tự động cập nhật ở đây
                // Hiện tại để trống để người dùng tự click cập nhật
            }, 1500);
        });
    });
}

// Hiển thị message
function showMessage(message, type = 'info') {
    // Tạo toast container nếu chưa có
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            max-width: 400px;
        `;
        document.body.appendChild(toastContainer);
    }
    
    // Tạo toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        background: ${getToastColor(type)};
        color: white;
        padding: 12px 20px;
        margin-bottom: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: slideInRight 0.3s ease-out;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    `;
    
    // Thêm icon theo type
    const icon = getToastIcon(type);
    toast.innerHTML = `${icon} ${message}`;
    
    toastContainer.appendChild(toast);
    
    // Tự động xóa sau 3 giây
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }
    }, 3000);
    
    // Cho phép click để đóng
    toast.addEventListener('click', () => {
        toast.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    });
}

// Lấy màu toast
function getToastColor(type) {
    const colors = {
        success: '#27ae60',
        error: '#e50010',
        warning: '#f39c12',
        info: '#3498db'
    };
    return colors[type] || colors.info;
}

// Lấy icon toast
function getToastIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    return icons[type] || icons.info;
}

// Thêm CSS animations cho toast
const toastStyles = document.createElement('style');
toastStyles.textContent = `
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(toastStyles);