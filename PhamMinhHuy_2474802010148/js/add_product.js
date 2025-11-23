// add_product.js - JavaScript cho trang thêm sản phẩm với theme Uniqlo Red

document.addEventListener('DOMContentLoaded', function() {
    initProductForm();
});

function initProductForm() {
    const form = document.querySelector('.form');
    const submitBtn = form?.querySelector('.btn[type="submit"]');
    
    // Khởi tạo các tính năng
    initImagePreview();
    initPriceSync();
    initCharacterCounters();
    initFormValidation();
    initAutoSave();
    
    // Xử lý submit form
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (validateForm()) {
                // Thêm hiệu ứng loading
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm sản phẩm...';
                
                // Clear auto-save data khi submit thành công
                clearAutoSave();
            } else {
                e.preventDefault();
                showMessage('Vui lòng kiểm tra lại thông tin đã nhập', 'error');
            }
        });
    }
}

// Preview hình ảnh
function initImagePreview() {
    const imageUrlInput = document.querySelector('input[name="image_url"]');
    
    if (imageUrlInput) {
        // Tạo container preview
        const previewContainer = document.createElement('div');
        previewContainer.className = 'image-preview';
        
        const previewImg = document.createElement('img');
        previewImg.alt = 'Preview sản phẩm';
        previewContainer.appendChild(previewImg);
        
        // Chèn preview container sau input
        imageUrlInput.parentNode.appendChild(previewContainer);
        
        // Xử lý sự kiện input với debounce
        imageUrlInput.addEventListener('input', debounce(function() {
            const url = this.value.trim();
            
            if (url && isValidImageUrl(url)) {
                previewImg.src = url;
                previewImg.classList.add('visible');
                
                // Xử lý lỗi tải ảnh
                previewImg.onerror = function() {
                    previewImg.classList.remove('visible');
                    showMessage('⚠ Không thể tải ảnh từ URL này', 'warning');
                };
                
                previewImg.onload = function() {
                    showMessage('✅ Ảnh preview đã được tải', 'success', 2000);
                };
            } else {
                previewImg.classList.remove('visible');
            }
        }, 500));
    }
}

// Kiểm tra URL ảnh hợp lệ
function isValidImageUrl(url) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
    const urlPattern = /^https?:\/\/.+\..+/;
    
    return urlPattern.test(url) && 
           imageExtensions.some(ext => url.toLowerCase().includes(ext));
}

// Đồng bộ giá
function initPriceSync() {
    const priceInput = document.querySelector('input[name="price"]');
    const originalPriceInput = document.querySelector('input[name="original_price"]');
    
    if (priceInput && originalPriceInput) {
        priceInput.addEventListener('blur', function() {
            const price = parseFloat(this.value);
            const originalPrice = parseFloat(originalPriceInput.value);
            
            // Nếu giá gốc chưa có hoặc bằng 0, tự động điền bằng giá bán
            if (price > 0 && (!originalPrice || originalPrice === 0)) {
                originalPriceInput.value = price;
                showMessage('💰 Giá gốc đã được tự động điền', 'success', 2000);
            }
        });
    }
}

// Đếm ký tự
function initCharacterCounters() {
    const textInputs = document.querySelectorAll('input[type="text"], textarea');
    
    textInputs.forEach(input => {
        if (input.name === 'name' || input.name === 'description') {
            const maxLength = input.name === 'name' ? 255 : 1000;
            const counter = document.createElement('div');
            counter.className = 'char-counter';
            updateCounter(counter, input, maxLength);
            
            input.parentNode.appendChild(counter);
            
            input.addEventListener('input', function() {
                updateCounter(counter, this, maxLength);
            });
        }
    });
}

// Cập nhật bộ đếm
function updateCounter(counter, input, maxLength) {
    const currentLength = input.value.length;
    counter.textContent = `${currentLength}/${maxLength}`;
    
    if (currentLength > maxLength) {
        counter.textContent = `⚠ ${currentLength}/${maxLength} (Vượt quá giới hạn)`;
        counter.classList.add('warning');
    } else if (currentLength > maxLength * 0.8) {
        counter.classList.add('warning');
    } else {
        counter.classList.remove('warning');
    }
}

// Validation form
function initFormValidation() {
    const nameInput = document.querySelector('input[name="name"]');
    const priceInput = document.querySelector('input[name="price"]');
    const stockInput = document.querySelector('input[name="stock_quantity"]');
    
    if (nameInput) {
        nameInput.addEventListener('blur', function() {
            validateField(this, 'Tên sản phẩm không được để trống');
        });
    }
    
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!value || value <= 0) {
                showFieldError(this, 'Giá bán phải lớn hơn 0');
            } else if (value > 1000000000) {
                showFieldError(this, 'Giá bán không được vượt quá 1 tỷ');
            } else {
                clearFieldError(this);
            }
        });
    }
    
    if (stockInput) {
        stockInput.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (value < 0) {
                showFieldError(this, 'Số lượng tồn kho không được âm');
            } else {
                clearFieldError(this);
            }
        });
    }
}

// Validate toàn bộ form
function validateForm() {
    const nameInput = document.querySelector('input[name="name"]');
    const priceInput = document.querySelector('input[name="price"]');
    
    let isValid = true;
    
    if (!nameInput.value.trim()) {
        showFieldError(nameInput, 'Tên sản phẩm không được để trống');
        isValid = false;
    }
    
    const price = parseFloat(priceInput.value);
    if (!price || price <= 0) {
        showFieldError(priceInput, 'Giá bán phải lớn hơn 0');
        isValid = false;
    }
    
    return isValid;
}

// Hiển thị lỗi cho field
function showFieldError(input, message) {
    clearFieldError(input);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    
    input.style.borderColor = '#e50010';
    input.style.backgroundColor = '#ffe6e6';
    input.parentNode.appendChild(errorDiv);
}

// Xóa lỗi field
function clearFieldError(input) {
    const existingError = input.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    input.style.borderColor = '';
    input.style.backgroundColor = '';
}

// Auto-save form data
function initAutoSave() {
    const form = document.querySelector('.form');
    const inputs = form.querySelectorAll('input, textarea, select');
    
    // Load saved data
    inputs.forEach(input => {
        const savedValue = localStorage.getItem(`product_${input.name}`);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }
    });
    
    // Save on input
    inputs.forEach(input => {
        input.addEventListener('input', debounce(function() {
            localStorage.setItem(`product_${this.name}`, this.value);
        }, 1000));
    });
}

// Clear auto-save data
function clearAutoSave() {
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
        if (key.startsWith('product_')) {
            localStorage.removeItem(key);
        }
    });
}

// Hiển thị message
function showMessage(message, type = 'info', duration = 3000) {
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
    
    // Tự động xóa
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }
    }, duration);
    
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

// Utility: Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
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