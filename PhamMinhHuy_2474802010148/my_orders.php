<?php
require_once 'db_config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php?redirect=my_orders.php');
}

$user_id = currentUserId();

// Lấy danh sách đơn hàng
$stmt = $conn->prepare("SELECT id, total_amount, status, created_at 
                        FROM orders 
                        WHERE user_id = ?
                        ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultOrders = $stmt->get_result();
$orders = [];
while ($order = $resultOrders->fetch_assoc()) {
    $orders[] = $order;
}
$stmt->close();

include 'header.php';
?>

<!-- Thêm CSS -->
<link rel="stylesheet" href="css/my_orders.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="orders-container">
    <h2 class="page-title">
        <i class="fas fa-shopping-bag"></i>
        Đơn Hàng Của Tôi
    </h2>

    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h3>Bạn chưa có đơn hàng nào</h3>
            <p>Hãy khám phá các sản phẩm tuyệt vời của chúng tôi và bắt đầu mua sắm!</p>
            <a href="products.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                Mua Sắm Ngay
            </a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <?php
                // Lấy chi tiết order
                $oid = $order['id'];
                $stmtItems = $conn->prepare("SELECT oi.quantity, oi.unit_price, oi.size, oi.color, 
                                                    p.name, p.image_url 
                                             FROM order_items oi
                                             JOIN products p ON oi.product_id = p.id
                                             WHERE oi.order_id = ?");
                $stmtItems->bind_param("i", $oid);
                $stmtItems->execute();
                $resItems = $stmtItems->get_result();
                $order_items = [];
                while ($item = $resItems->fetch_assoc()) {
                    $order_items[] = $item;
                }
                $stmtItems->close();
                ?>

                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3 class="order-id">Đơn hàng #<?php echo $order['id']; ?></h3>
                            <div class="order-meta">
                                <span class="order-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                </span>
                                <span class="order-total">
                                    <i class="fas fa-receipt"></i>
                                    <?php echo number_format($order['total_amount']); ?> VND
                                </span>
                            </div>
                        </div>
                        <div class="order-status <?php echo esc($order['status']); ?>">
                            <span class="status-badge">
                                <?php echo getStatusText($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-items">
                        <h4 class="items-title">Chi tiết sản phẩm</h4>
                        <div class="items-list">
                            <?php foreach ($order_items as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <?php if (!empty($item['image_url'])): ?>
                                            <img src="<?php echo esc($item['image_url']); ?>" 
                                                 alt="<?php echo esc($item['name']); ?>">
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fas fa-tshirt"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-details">
                                        <h5 class="item-name"><?php echo esc($item['name']); ?></h5>
                                        <div class="item-attributes">
                                            <?php if (!empty($item['size'])): ?>
                                                <span class="attribute">
                                                    <i class="fas fa-ruler"></i>
                                                    Size: <?php echo esc($item['size']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($item['color'])): ?>
                                                <span class="attribute">
                                                    <i class="fas fa-palette"></i>
                                                    Màu: <?php echo esc($item['color']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="item-quantity">
                                        <span class="qty">x<?php echo (int)$item['quantity']; ?></span>
                                    </div>
                                    <div class="item-price">
                                        <span class="unit-price"><?php echo number_format($item['unit_price']); ?> VND</span>
                                        <span class="subtotal"><?php echo number_format($item['unit_price'] * $item['quantity']); ?> VND</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Thêm JavaScript -->
<script src="js/my_orders.js"></script>

<?php
include 'footer.php';

// Helper function để hiển thị text trạng thái
function getStatusText($status) {
    $statuses = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy'
    ];
    return $statuses[$status] ?? $status;
}
?>