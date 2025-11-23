// Products Page JavaScript - Uniqlo Theme
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filter-form');
    const productGrid = document.querySelector('.product-grid');
    const searchInput = document.querySelector('input[name="q"]');
    
    // Auto submit form when category changes
    const categorySelect = document.querySelector('select[name="category"]');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            // Thêm hiệu ứng loading
            const submitBtn = filterForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG LỌC...';
                submitBtn.disabled = true;
                
                // Restore button text after a short delay
                setTimeout(() => {
                    filterForm.submit();
                }, 300);
            } else {
                filterForm.submit();
            }
        });
    }
    
    // Add loading state to filter form
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG TÌM...';
                submitBtn.disabled = true;
                
                // Thêm class loading cho product grid
                if (productGrid) {
                    productGrid.classList.add('loading');
                }
            }
        });
    }
    
    // Product card hover effects - GIỐNG INDEX
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach((card, index) => {
        // Thêm delay animation giống index
        card.style.animationDelay = `${(index % 8) * 0.1 + 0.1}s`;
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Search input enhancement với clear button
    if (searchInput) {
        const searchWrapper = document.createElement('div');
        searchWrapper.className = 'search-wrapper';
        searchInput.parentNode.insertBefore(searchWrapper, searchInput);
        searchWrapper.appendChild(searchInput);
        
        // Clear search button
        const clearSearch = document.createElement('button');
        clearSearch.type = 'button';
        clearSearch.className = 'clear-search';
        clearSearch.innerHTML = '<i class="fas fa-times"></i>';
        clearSearch.style.display = 'none';
        
        searchWrapper.appendChild(clearSearch);
        
        searchInput.addEventListener('input', function() {
            clearSearch.style.display = this.value ? 'block' : 'none';
        });
        
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            this.style.display = 'none';
            // Auto submit khi clear search
            filterForm.submit();
        });
        
        // Auto search với debounce
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    filterForm.submit();
                }
            }, 500);
        });
    }
    
    // Price formatting - ĐỒNG BỘ VỚI INDEX
    const priceElements = document.querySelectorAll('.product-price');
    priceElements.forEach(priceEl => {
        const priceText = priceEl.textContent;
        // Đảm bảo format giá nhất quán
        if (priceText.includes('VND')) {
            priceEl.textContent = priceText.replace('VND', '₫');
        } else if (!priceText.includes('₫')) {
            priceEl.textContent = priceText + '₫';
        }
    });
    
    // Quick add to cart functionality (nếu cần)
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    addToCartButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            addToCart(productId, 1);
        });
    });
    
    // Update results count
    updateResultsCount();
});

// Update results count
function updateResultsCount() {
    const productCards = document.querySelectorAll('.product-card');
    const noProducts = document.querySelector('.no-products');
    const resultsCount = document.createElement('div');
    resultsCount.className = 'results-count';
    
    if (productCards.length > 0) {
        resultsCount.innerHTML = `Tìm thấy <strong>${productCards.length}</strong> sản phẩm phù hợp`;
    } else if (noProducts) {
        resultsCount.innerHTML = `Không tìm thấy sản phẩm nào phù hợp`;
    }
    
    const productGrid = document.querySelector('.product-grid');
    if (productGrid && productCards.length > 0) {
        productGrid.parentNode.insertBefore(resultsCount, productGrid);
    }
}

// Add to cart function
function addToCart(productId, quantity) {
    // Hiển thị loading state
    const btn = document.querySelector(`[data-product-id="${productId}"]`);
    if (btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        // Giả lập API call
        setTimeout(() => {
            showNotification('Đã thêm sản phẩm vào giỏ hàng', 'success');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }
}

// Notification function - GIỐNG INDEX STYLE
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.product-notification');
    existingNotifications.forEach(notif => notif.remove());
    
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
    
    // Style giống với theme
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#e50010' : '#ff4444'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        max-width: 300px;
        animation: slideInRight 0.3s ease;
        border: none;
    `;
    
    const notificationContent = notification.querySelector('.notification-content');
    notificationContent.style.cssText = `
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        font-weight: 500;
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