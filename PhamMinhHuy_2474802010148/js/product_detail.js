// Product Detail JavaScript - Uniqlo Theme
document.addEventListener('DOMContentLoaded', function() {
    // Quantity selector functionality
    const quantityInput = document.getElementById('quantity');
    const quantityButtons = document.querySelectorAll('.quantity-btn');
    
    quantityButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            let currentValue = parseInt(quantityInput.value);
            const max = parseInt(quantityInput.getAttribute('max')) || 10;
            const min = parseInt(quantityInput.getAttribute('min')) || 1;
            
            if (action === 'increase' && currentValue < max) {
                quantityInput.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > min) {
                quantityInput.value = currentValue - 1;
            }
            
            // Trigger change event
            quantityInput.dispatchEvent(new Event('change'));
        });
    });
    
    // Form validation
    const addToCartForm = document.getElementById('addToCartForm');
    
    addToCartForm.addEventListener('submit', function(e) {
        const size = document.getElementById('size').value;
        const color = document.getElementById('color').value.trim();
        const quantity = document.getElementById('quantity').value;
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate size
        if (!size) {
            isValid = false;
            errorMessage = 'Vui lòng chọn kích thước';
        }
        // Validate color
        else if (!color) {
            isValid = false;
            errorMessage = 'Vui lòng nhập màu sắc';
        }
        // Validate quantity
        else if (quantity < 1 || quantity > 10) {
            isValid = false;
            errorMessage = 'Số lượng phải từ 1 đến 10';
        }
        
        if (!isValid) {
            e.preventDefault();
            showNotification(errorMessage, 'error');
        } else {
            // Add loading state to button
            const submitButton = this.querySelector('.btn-add-to-cart');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>ĐANG THÊM...</span>';
            submitButton.disabled = true;
            
            // Revert after 3 seconds if form doesn't submit
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 3000);
        }
    });
    
    // Show notification function
    function showNotification(message, type = 'info') {
        // Remove existing notification
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'error' ? '#e50010' : '#28a745'};
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            animation: slideInRight 0.3s ease-out;
            max-width: 300px;
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
        
        // Add click to dismiss
        notification.addEventListener('click', function() {
            this.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (this.parentNode) {
                    this.remove();
                }
            }, 300);
        });
    }
    
    // Add CSS animations for notifications
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .notification {
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .notification:hover {
            transform: translateY(-2px);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    `;
    document.head.appendChild(style);
    
    // Input validation styling
    const inputs = document.querySelectorAll('input[required], select[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = '#e50010';
                this.style.boxShadow = '0 0 0 2px rgba(229, 0, 16, 0.1)';
            } else {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 2px rgba(40, 167, 69, 0.1)';
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 2px rgba(40, 167, 69, 0.1)';
            }
        });
    });
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});