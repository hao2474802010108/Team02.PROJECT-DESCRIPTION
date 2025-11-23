<?php
require_once 'db_config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php?redirect=cart.php');
}

$userId = currentUserId();

// Xử lý thêm sản phẩm vào giỏ (từ product_detail.php)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity   = (int)($_POST['quantity'] ?? 1);
    $size       = trim($_POST['size'] ?? '');
    $color      = trim($_POST['color'] ?? '');

    if ($product_id > 0 && $quantity > 0) {
        // Kiểm tra sản phẩm có tồn tại & còn bán không
        $stmt = $conn->prepare("SELECT id, price FROM products WHERE id = ? AND status = 'available'");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if ($product) {
            // Kiểm tra xem đã có trong cart chưa (cùng product + size + color)
            $stmt = $conn->prepare("
                SELECT id, quantity FROM cart_items
                WHERE user_id = ? AND product_id = ? AND size <=> ? AND color <=> ?
            ");
            $stmt->bind_param('iiss', $userId, $product_id, $size, $color);
            $stmt->execute();
            $item = $stmt->get_result()->fetch_assoc();

            if ($item) {
                // Cộng dồn số lượng
                $newQty = $item['quantity'] + $quantity;
                $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
                $stmt->bind_param('ii', $newQty, $item['id']);
                $stmt->execute();
            } else {
                // Thêm mới
                $stmt = $conn->prepare("
                    INSERT INTO cart_items (user_id, product_id, quantity, size, color)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param('iiiss', $userId, $product_id, $quantity, $size, $color);
                $stmt->execute();
            }
        }
    }

    redirect('cart.php');
}

// Xóa 1 item trong giỏ
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
    }
    redirect('cart.php');
}

// Cập nhật số lượng (form trên trang giỏ)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $quantities = $_POST['quantities'] ?? []; // mảng [cart_item_id => quantity]

    foreach ($quantities as $itemId => $qty) {
        $itemId = (int)$itemId;
        $qty    = (int)$qty;
        if ($itemId > 0 && $qty > 0) {
            $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param('iii', $qty, $itemId, $userId);
            $stmt->execute();
        }
    }
    redirect('cart.php');
}

// Lấy danh sách sản phẩm trong giỏ
$stmt = $conn->prepare("
    SELECT ci.id, ci.quantity, ci.size, ci.color,
           p.name, p.price, p.image_url
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.user_id = ?
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['quantity'] * $row['price'];
    $total += $row['subtotal'];
    $items[] = $row;
}

include 'header.php';
?>

<h2>Giỏ hàng</h2>

<?php if (empty($items)): ?>
    <p>Giỏ hàng của bạn đang trống.</p>
<?php else: ?>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <table class="table">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Size</th>
                <th>Màu</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="<?php echo esc($item['image_url']); ?>" alt="" style="width:60px; vertical-align:middle;">
                        <?php endif; ?>
                        <?php echo esc($item['name']); ?>
                    </td>
                    <td><?php echo esc($item['size']); ?></td>
                    <td><?php echo esc($item['color']); ?></td>
                    <td><?php echo number_format($item['price']); ?> VND</td>
                    <td>
                        <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" style="width:60px;">
                    </td>
                    <td><?php echo number_format($item['subtotal']); ?> VND</td>
                    <td>
                        <a href="cart.php?remove=<?php echo $item['id']; ?>" onclick="return confirm('Xóa sản phẩm này?');">X</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Tổng cộng: <?php echo number_format($total); ?> VND</strong></p>

        <button type="submit" class="btn">Cập nhật giỏ hàng</button>
        <a href="checkout.php" class="btn btn-primary">Tiến hành thanh toán</a>
    </form>
<?php endif; ?>

<?php include 'footer.php';
