// my_orders.js - JavaScript cho trang đơn hàng của tôi

document.addEventListener('DOMContentLoaded', function() {
    initOrdersPage();
});

function initOrdersPage() {
    initOrderActions();
    initPrintFunctionality();
}

// Khởi tạo các hành động đơn hàng
function initOrderActions() {
    // Có thể thêm các event listeners khác ở đây
}

// Xem chi tiết đơn hàng
function viewOrderDetails(orderId) {
    // Có thể mở modal hoặc chuyển đến trang chi tiết
    window.location.href = `order_detail.php?id=${orderId}`;
}

// Hủy đơn hàng
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.classList.add('btn-loading');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
        
        fetch('cancel_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}`
        })
        .then(response => {
            if (response.ok) {
                showMessage('✅ Đã hủy đơn hàng thành công!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                throw new Error('Failed to cancel order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('❌ Có lỗi xảy ra khi hủy đơn hàng', 'error');
        })
        .finally(() => {
            button.classList.remove('btn-loading');
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
}

// In đơn hàng
function printOrder(orderId) {
    // Tạo cửa sổ in
    const printWindow = window.open(`print_order.php?id=${orderId}`, '_blank');
    
    // Tự động in sau khi cửa sổ mở
    printWindow.onload = function() {
        printWindow.print();
    };
}

// Hiển thị message
function showMessage(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getToastColor(type)};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideInRight 0.3s ease-out;
        cursor: pointer;
    `;
    
    toast.innerHTML = `
        <i class="fas ${getToastIcon(type)}"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    // Tự động xóa sau 4 giây
    setTimeout(() => {
        if (toast.parentNode) {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }
    }, 4000);
    
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
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    return icons[type] || icons.info;
}

// Thêm CSS animations
const ordersStyles = document.createElement('style');
ordersStyles.textContent = `
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
    
    .toast {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }
`;
document.head.appendChild(ordersStyles);

// Filter và sort đơn hàng (có thể thêm sau)
function filterOrders(status) {
    const orderCards = document.querySelectorAll('.order-card');
    
    orderCards.forEach(card => {
        const cardStatus = card.querySelector('.order-status').classList[1];
        if (status === 'all' || cardStatus === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Search orders (có thể thêm sau)
function searchOrders(query) {
    const orderCards = document.querySelectorAll('.order-card');
    const searchTerm = query.toLowerCase();
    
    orderCards.forEach(card => {
        const orderId = card.querySelector('.order-id').textContent.toLowerCase();
        const orderItems = card.querySelectorAll('.item-name');
        let found = false;
        
        // Tìm trong order ID
        if (orderId.includes(searchTerm)) {
            found = true;
        }
        
        // Tìm trong tên sản phẩm
        orderItems.forEach(item => {
            if (item.textContent.toLowerCase().includes(searchTerm)) {
                found = true;
            }
        });
        
        card.style.display = found ? 'block' : 'none';
    });
}