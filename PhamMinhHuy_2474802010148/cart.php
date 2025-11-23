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
        $stmt = $conn->prepare("SELECT id, price, stock_quantity FROM products WHERE id = ? AND status = 'available'");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();

        if ($product) {
            // Kiểm tra số lượng tồn kho
            if ($quantity > $product['stock_quantity']) {
                $_SESSION['error_message'] = "Số lượng sản phẩm trong kho không đủ!";
                redirect('cart.php');
            }

            // Kiểm tra xem đã có trong cart chưa (cùng product + size + color)
            $stmt = $conn->prepare("
                SELECT id, quantity FROM cart_items
                WHERE user_id = ? AND product_id = ? AND size <=> ? AND color <=> ?
            ");
            $stmt->bind_param('iiss', $userId, $product_id, $size, $color);
            $stmt->execute();
            $item = $stmt->get_result()->fetch_assoc();

            if ($item) {
                // Cộng dồn số lượng và kiểm tra tồn kho
                $newQty = $item['quantity'] + $quantity;
                if ($newQty > $product['stock_quantity']) {
                    $_SESSION['error_message'] = "Số lượng sản phẩm trong kho không đủ!";
                    redirect('cart.php');
                }
                
                $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
                $stmt->bind_param('ii', $newQty, $item['id']);
                $stmt->execute();
                $_SESSION['success_message'] = "Đã cập nhật giỏ hàng!";
            } else {
                // Thêm mới
                $stmt = $conn->prepare("
                    INSERT INTO cart_items (user_id, product_id, quantity, size, color)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->bind_param('iiiss', $userId, $product_id, $quantity, $size, $color);
                $stmt->execute();
                $_SESSION['success_message'] = "Đã thêm sản phẩm vào giỏ hàng!";
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
        $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
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
            // Kiểm tra tồn kho trước khi cập nhật
            $stmt = $conn->prepare("
                SELECT p.stock_quantity 
                FROM cart_items ci 
                JOIN products p ON ci.product_id = p.id 
                WHERE ci.id = ? AND ci.user_id = ?
            ");
            $stmt->bind_param('ii', $itemId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            if ($product && $qty <= $product['stock_quantity']) {
                $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
                $stmt->bind_param('iii', $qty, $itemId, $userId);
                $stmt->execute();
            }
        }
    }
    $_SESSION['success_message'] = "Đã cập nhật giỏ hàng!";
    redirect('cart.php');
}

// Lấy danh sách sản phẩm trong giỏ
$stmt = $conn->prepare("
    SELECT ci.id, ci.quantity, ci.size, ci.color,
           p.name, p.price, p.image_url, p.stock_quantity
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

<!-- Thêm CSS -->
<link rel="stylesheet" href="css/cart.css">
<!-- Thêm Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="cart-container">
    <h2 class="page-title">
        <i class="fas fa-shopping-cart"></i>
        Giỏ Hàng Của Bạn
    </h2>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success_message']; ?>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error_message']; ?>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart fa-3x"></i>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p>Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
            <a href="products.php" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i>
                Mua Sắm Ngay
            </a>
        </div>
    <?php else: ?>
        <form method="post" class="cart-form" id="cartForm">
            <input type="hidden" name="action" value="update">
            
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item" data-item-id="<?php echo $item['id']; ?>">
                        <div class="item-image">
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?php echo esc($item['image_url']); ?>" alt="<?php echo esc($item['name']); ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-tshirt"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-details">
                            <h3 class="item-name"><?php echo esc($item['name']); ?></h3>
                            
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
                            
                            <div class="item-price">
                                <?php echo number_format($item['price']); ?> VND
                            </div>
                        </div>
                        
                        <div class="item-quantity">
                            <button type="button" class="qty-btn minus" data-action="decrease">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   name="quantities[<?php echo $item['id']; ?>]" 
                                   value="<?php echo $item['quantity']; ?>" 
                                   min="1" 
                                   max="<?php echo $item['stock_quantity']; ?>"
                                   class="qty-input">
                            <button type="button" class="qty-btn plus" data-action="increase">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <div class="item-subtotal">
                            <span class="subtotal-amount">
                                <?php echo number_format($item['subtotal']); ?> VND
                            </span>
                        </div>
                        
                        <div class="item-actions">
                            <a href="cart.php?remove=<?php echo $item['id']; ?>" 
                               class="remove-btn" 
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-card">
                    <h3 class="summary-title">
                        <i class="fas fa-receipt"></i>
                        Tổng Thanh Toán
                    </h3>
                    
                    <div class="summary-row">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total); ?> VND</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span><strong>Tổng cộng:</strong></span>
                        <span><strong><?php echo number_format($total); ?> VND</strong></span>
                    </div>
                    
                    <div class="summary-actions">
                        <button type="submit" class="btn btn-update">
                            <i class="fas fa-sync-alt"></i>
                            Cập Nhật Giỏ Hàng
                        </button>
                        
                        <a href="checkout.php" class="btn btn-checkout">
                            <i class="fas fa-credit-card"></i>
                            Tiến Hành Thanh Toán
                        </a>
                        
                        <a href="products.php" class="btn-continue">
                            <i class="fas fa-arrow-left"></i>
                            Tiếp Tục Mua Sắm
                        </a>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<!-- Thêm JavaScript -->
<script src="js/cart.js"></script>

<?php include 'footer.php'; ?>