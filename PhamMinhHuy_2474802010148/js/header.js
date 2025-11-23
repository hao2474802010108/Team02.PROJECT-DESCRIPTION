// header.js - JavaScript chuyên biệt cho header

class HeaderManager {
    constructor() {
        this.isMobileMenuOpen = false;
        this.cartCount = 0;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCartCount();
        this.setActiveNav();
        this.setupAccessibility();
    }

    bindEvents() {
        // Search functionality
        this.bindSearch();
        
        // Mobile menu
        this.bindMobileMenu();
        
        // Cart interactions
        this.bindCartEvents();
        
        // User menu
        this.bindUserMenu();
        
        // Window resize
        this.bindResize();
    }

    // Search functionality
    bindSearch() {
        const searchInput = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.search-btn');

        if (searchInput) {
            // Search on button click
            if (searchBtn) {
                searchBtn.addEventListener('click', () => this.handleSearch());
            }

            // Search on Enter key
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.handleSearch();
                }
            });

            // Clear search on Escape
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    searchInput.blur();
                }
            });

            // Auto-suggest với debounce
            searchInput.addEventListener('input', this.debounce(() => {
                this.handleSearchSuggestions();
            }, 300));
        }
    }

    handleSearch() {
        const searchInput = document.querySelector('.search-input');
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        
        if (searchTerm) {
            // Thêm hiệu ứng loading
            this.setSearchLoading(true);
            
            // Chuyển hướng sau delay nhỏ để thấy hiệu ứng
            setTimeout(() => {
                window.location.href = `products.php?search=${encodeURIComponent(searchTerm)}`;
            }, 300);
        } else {
            this.showMessage('Vui lòng nhập từ khóa tìm kiếm', 'warning');
            searchInput.focus();
        }
    }

    setSearchLoading(isLoading) {
        const searchBtn = document.querySelector('.search-btn');
        if (searchBtn) {
            if (isLoading) {
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                searchBtn.disabled = true;
            } else {
                searchBtn.innerHTML = '<i class="fas fa-search"></i>';
                searchBtn.disabled = false;
            }
        }
    }

    handleSearchSuggestions() {
        // Có thể phát triển tính năng gợi ý tìm kiếm sau
        // console.log('Search suggestions feature can be implemented here');
    }

    // Mobile menu functionality
    bindMobileMenu() {
        const mobileToggle = document.querySelector('.mobile-toggle');
        const navContainer = document.getElementById('navContainer');

        if (mobileToggle && navContainer) {
            mobileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleMobileMenu();
            });

            // Close mobile menu khi click bên ngoài
            document.addEventListener('click', (e) => {
                if (this.isMobileMenuOpen && 
                    !navContainer.contains(e.target) && 
                    !mobileToggle.contains(e.target)) {
                    this.closeMobileMenu();
                }
            });

            // Close mobile menu khi click trên nav links
            const navLinks = navContainer.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.closeMobileMenu();
                });
            });

            // Close mobile menu với Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMobileMenuOpen) {
                    this.closeMobileMenu();
                }
            });
        }
    }

    toggleMobileMenu() {
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        const navContainer = document.getElementById('navContainer');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        navContainer.classList.add('mobile-open');
        mobileToggle.innerHTML = '<i class="fas fa-times"></i>';
        mobileToggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        this.isMobileMenuOpen = true;
        
        // Focus trap cho mobile menu
        this.setupMobileMenuFocusTrap();
    }

    closeMobileMenu() {
        const navContainer = document.getElementById('navContainer');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        navContainer.classList.remove('mobile-open');
        mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
        mobileToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        this.isMobileMenuOpen = false;
        
        // Return focus to toggle button
        mobileToggle.focus();
    }

    setupMobileMenuFocusTrap() {
        // Focus trap implementation cho accessibility
        const navContainer = document.getElementById('navContainer');
        const focusableElements = navContainer.querySelectorAll(
            'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            firstElement.focus();
            
            navContainer.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === firstElement) {
                            e.preventDefault();
                            lastElement.focus();
                        }
                    } else {
                        if (document.activeElement === lastElement) {
                            e.preventDefault();
                            firstElement.focus();
                        }
                    }
                }
            });
        }
    }

    // Cart functionality
    bindCartEvents() {
        const cartLinks = document.querySelectorAll('.cart-link');
        
        cartLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                this.animateCartClick(e.currentTarget);
            });
            
            link.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.animateCartClick(e.currentTarget);
                }
            });
        });
    }

    animateCartClick(element) {
        const cartIcon = element.querySelector('i') || element;
        cartIcon.style.transform = 'scale(1.2)';
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1)';
        }, 200);
    }

    async loadCartCount() {
        try {
            const response = await fetch('ajax/get_cart_count.php');
            const data = await response.json();
            
            if (data.success) {
                this.cartCount = data.cart_count;
                this.updateCartCount(this.cartCount);
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }

    updateCartCount(count) {
        const cartCounts = document.querySelectorAll('.cart-count');
        cartCounts.forEach(element => {
            const previousCount = parseInt(element.getAttribute('data-previous') || 0);
            element.textContent = count;
            
            // Thêm hiệu ứng khi số lượng thay đổi
            if (count > previousCount) {
                this.animateCartUpdate(element);
            }
            
            element.setAttribute('data-previous', count);
            element.setAttribute('aria-label', `${count} sản phẩm trong giỏ hàng`);
        });
    }

    animateCartUpdate(element) {
        element.style.animation = 'none';
        setTimeout(() => {
            element.style.animation = 'cartPulse 0.6s ease';
        }, 10);
    }

    // User menu functionality
    bindUserMenu() {
        const userWelcome = document.querySelector('.user-welcome');
        
        if (userWelcome) {
            userWelcome.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleUserMenu();
            });

            userWelcome.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.toggleUserMenu();
                }
            });
        }
    }

    toggleUserMenu() {
        // Có thể phát triển dropdown user menu sau
        this.showMessage('Tính năng menu người dùng sẽ được phát triển sau', 'info');
    }

    // Window resize handler
    bindResize() {
        window.addEventListener('resize', this.debounce(() => {
            if (window.innerWidth > 768 && this.isMobileMenuOpen) {
                this.closeMobileMenu();
            }
        }, 250));
    }

    // Set active navigation
    setActiveNav() {
        const currentPage = this.getCurrentPage();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && this.isActivePage(href, currentPage)) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');
            } else {
                link.classList.remove('active');
                link.removeAttribute('aria-current');
            }
        });
    }

    getCurrentPage() {
        const path = window.location.pathname;
        return path.split('/').pop() || 'index.php';
    }

    isActivePage(linkHref, currentPage) {
        // Xử lý các trường hợp đặc biệt
        if (linkHref.includes('?')) {
            const baseHref = linkHref.split('?')[0];
            return baseHref === currentPage;
        }
        return linkHref === currentPage;
    }

    // Accessibility setup
    setupAccessibility() {
        // Thêm ARIA labels nếu cần
        const cartLinks = document.querySelectorAll('.cart-link');
        cartLinks.forEach(link => {
            link.setAttribute('aria-label', 'Giỏ hàng');
        });
    }

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    showMessage(message, type = 'info') {
        // Có thể tích hợp với hệ thống thông báo chung
        console.log(`${type.toUpperCase()}: ${message}`);
        
        // Tạm thời dùng alert, có thể thay bằng toast notification sau
        if (type === 'warning' || type === 'error') {
            alert(message);
        }
    }

    // Public methods để các file khác có thể gọi
    refreshCart() {
        this.loadCartCount();
    }

    addToCart(productId, quantity = 1) {
        // Gọi API thêm vào giỏ hàng
        fetch('ajax/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.cartCount = data.cart_count;
                this.updateCartCount(this.cartCount);
                this.showMessage('Đã thêm vào giỏ hàng', 'success');
            } else {
                this.showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showMessage('Lỗi khi thêm vào giỏ hàng', 'error');
        });
    }

    // Get current cart count
    getCartCount() {
        return this.cartCount;
    }

    // Check if mobile menu is open
    isMobileMenuOpen() {
        return this.isMobileMenuOpen;
    }
}

// Khởi tạo HeaderManager khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.headerManager = new HeaderManager();
    
    // Auto-refresh cart count mỗi 30 giây
    setInterval(() => {
        window.headerManager.refreshCart();
    }, 30000);
});

// Export cho module system (nếu cần)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = HeaderManager;
}