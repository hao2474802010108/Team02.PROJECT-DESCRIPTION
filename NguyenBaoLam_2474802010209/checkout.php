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

<h2>Thanh toán</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
            <p><?php echo esc($e); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <p><?php echo esc($success); ?></p>
        <p><a href="my_orders.php">Xem đơn hàng của tôi</a></p>
    </div>
<?php else: ?>
    <h3>Thông tin giao hàng</h3>
    <form method="post">
        <label>Địa chỉ giao hàng:</label><br>
        <textarea name="shipping_address" rows="3"><?php echo esc($userInfo['address'] ?? ''); ?></textarea><br><br>

        <label>Số điện thoại:</label><br>
        <input type="text" name="shipping_phone" value="<?php echo esc($userInfo['phone'] ?? ''); ?>"><br><br>

        <label>Ghi chú:</label><br>
        <textarea name="notes" rows="3"></textarea><br><br>

        <h3>Tóm tắt đơn hàng</h3>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <?php echo esc($item['name']); ?>
                    (x<?php echo $item['quantity']; ?>)
                    - <?php echo number_format($item['subtotal']); ?> VND
                </li>
            <?php endforeach; ?>
        </ul>
        <p><strong>Tổng tiền: <?php echo number_format($total); ?> VND</strong></p>

        <button type="submit" class="btn btn-primary">Xác nhận đặt hàng</button>
    </form>
<?php endif; ?>

<?php include 'footer.php';
