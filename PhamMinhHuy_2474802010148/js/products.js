// Products Page JavaScript - Uniqlo Theme
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filter-form');
    const productGrid = document.querySelector('.product-grid');
    
    // Auto submit form when category changes
    const categorySelect = document.querySelector('select[name="category"]');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            filterForm.submit();
        });
    }
    
    // Add loading state to filter form
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TÌM...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Quick view functionality (optional enhancement)
    const quickViewButtons = document.querySelectorAll('.product-card .btn');
    quickViewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add quick view functionality here if needed
            console.log('Quick view for product');
        });
    });
    
    // Search input enhancement
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        // Clear search button
        const clearSearch = document.createElement('button');
        clearSearch.type = 'button';
        clearSearch.innerHTML = '<i class="fas fa-times"></i>';
        clearSearch.style.cssText = `
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            display: none;
        `;
        
        searchInput.parentNode.style.position = 'relative';
        searchInput.parentNode.appendChild(clearSearch);
        
        searchInput.addEventListener('input', function() {
            clearSearch.style.display = this.value ? 'block' : 'none';
        });
        
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            filterForm.submit();
        });
    }
    
    // Price formatting
    const priceElements = document.querySelectorAll('.product-card p strong');
    priceElements.forEach(priceEl => {
        const priceText = priceEl.textContent;
        // Ensure price format is consistent
        if (priceText.includes('VND')) {
            priceEl.textContent = priceText.replace('VND', '₫');
        }
    });
    
    // Add to cart functionality from product list
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId, 1);
        });
    });
});

// Add to cart function (if needed)
function addToCart(productId, quantity) {
    // Implement add to cart functionality here
    console.log(`Adding product ${productId} to cart`);
    
    // Show success notification
    showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success');
}

// Notification function
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `product-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            </span>
            <span class="notification-message">${message}</span>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#e50010' : '#ff4444'};
        color: white;
        border-radius: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        max-width: 300px;
        animation: slideInRight 0.3s ease;
        border: 1px solid ${type === 'success' ? '#cc000e' : '#cc0000'};
    `;
    
    const notificationContent = notification.querySelector('.notification-content');
    notificationContent.style.cssText = `
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
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
    
    .notification-icon {
        font-size: 16px;
        width: 16px;
        text-align: center;
    }
    
    .notification-message {
        font-size: 14px;
        line-height: 1.4;
    }
`;
document.head.appendChild(style);