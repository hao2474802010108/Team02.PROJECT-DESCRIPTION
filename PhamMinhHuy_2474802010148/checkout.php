<?php
require_once 'db_config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php?redirect=checkout.php');
}
$userId = currentUserId();

// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("
    SELECT ci.id, ci.product_id, ci.quantity, ci.size, ci.color,
           p.name, p.price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['quantity'] * $row['price'];
    $total += $row['subtotal'];
    $cartItems[] = $row;
}

if (empty($cartItems)) {
    redirect('cart.php');
}

// Lấy thông tin user để fill sẵn
$stmt = $conn->prepare("SELECT full_name, phone, address FROM users WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$userInfo = $stmt->get_result()->fetch_assoc();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $shipping_phone   = trim($_POST['shipping_phone'] ?? '');
    $notes            = trim($_POST['notes'] ?? '');

    if ($shipping_address === '' || $shipping_phone === '') {
        $errors[] = "Vui lòng nhập địa chỉ và số điện thoại giao hàng.";
    }

    if (empty($errors)) {
        // Tạo đơn hàng
        $stmt = $conn->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_address, shipping_phone, status, notes)
            VALUES (?, ?, ?, ?, 'pending', ?)
        ");
        $stmt->bind_param('idsss', $userId, $total, $shipping_address, $shipping_phone, $notes);
        $stmt->execute();
        $orderId = $conn->insert_id;

        // Thêm chi tiết đơn hàng
        $stmtItem = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, unit_price, size, color)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($cartItems as $item) {
            $stmtItem->bind_param(
                'iiidss',
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price'],
                $item['size'],
                $item['color']
            );
            $stmtItem->execute();

            // Trừ tồn kho (nếu muốn)
            $updateStock = $conn->prepare("
                UPDATE products SET stock_quantity = stock_quantity - ?
                WHERE id = ? AND stock_quantity >= ?
            ");
            $updateStock->bind_param('iii', $item['quantity'], $item['product_id'], $item['quantity']);
            $updateStock->execute();
        }

        // Xóa giỏ hàng
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        $success = "Đặt hàng thành công! Mã đơn hàng của bạn là #{$orderId}.";
    }
}

include 'header.php';
?>

<!-- Thêm Font Awesome và CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/checkout.css">

<div class="checkout-container">
    <h1 class="checkout-title">
        <i class="fas fa-shopping-bag"></i> THANH TOÁN
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="alert-content">
                <?php foreach ($errors as $e): ?>
                    <p><?php echo esc($e); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="order-success">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>ĐẶT HÀNG THÀNH CÔNG</h2>
            <p class="order-number">
                <i class="fas fa-receipt"></i> Mã đơn hàng: <strong>#<?php echo $orderId; ?></strong>
            </p>
            <p class="success-message">
                <i class="fas fa-clock"></i> Cảm ơn bạn đã mua hàng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
            </p>
            <div class="success-actions">
                <a href="my_orders.php" class="btn btn-primary">
                    <i class="fas fa-list"></i> XEM ĐƠN HÀNG
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i> TIẾP TỤC MUA SẮM
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="checkout-content">
            <div class="checkout-form-section">
                <h3><i class="fas fa-truck"></i> THÔNG TIN GIAO HÀNG</h3>
                <form method="post" class="checkout-form" id="checkoutForm">
                    <div class="form-group">
                        <label for="shipping_address">
                            <i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng *
                        </label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" required><?php echo esc($userInfo['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="shipping_phone">
                            <i class="fas fa-phone"></i> Số điện thoại *
                        </label>
                        <input type="tel" id="shipping_phone" name="shipping_phone" 
                               value="<?php echo esc($userInfo['phone'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="notes">
                            <i class="fas fa-sticky-note"></i> Ghi chú đơn hàng
                        </label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Ghi chú về đơn hàng của bạn..."></textarea>
                    </div>
            </div>

            <div class="order-summary-section">
                <h3><i class="fas fa-clipboard-list"></i> ĐƠN HÀNG CỦA BẠN</h3>
                <div class="order-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <div class="item-info">
                                <h4 class="item-name"><?php echo esc($item['name']); ?></h4>
                                <div class="item-variants">
                                    <?php if ($item['size']): ?>
                                        <span><i class="fas fa-ruler"></i> Size: <?php echo esc($item['size']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($item['color']): ?>
                                        <span><i class="fas fa-palette"></i> Màu: <?php echo esc($item['color']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="item-quantity-price">
                                    <span class="quantity">
                                        <i class="fas fa-layer-group"></i> Số lượng: <?php echo $item['quantity']; ?>
                                    </span>
                                    <span class="subtotal"><?php echo number_format($item['subtotal']); ?>₫</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-totals">
                    <div class="total-row grand-total">
                        <span><i class="fas fa-calculator"></i> Tổng cộng:</span>
                        <span><?php echo number_format($total); ?>₫</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-place-order">
                    <i class="fas fa-lock"></i> XÁC NHẬN ĐẶT HÀNG
                </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="js/checkout.js"></script>
<?php include 'footer.php'; ?>