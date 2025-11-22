<?php
require_once 'db_config.php';
require_once 'functions.php';

// Lấy id sản phẩm từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect('index.php');
}

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("
    SELECT p.*, c.name AS category_name, b.name AS brand_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE p.id = ? AND p.status = 'available'
");
$stmt->bind_param('i', $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    include 'header.php';
    echo "<p>Không tìm thấy sản phẩm.</p>";
    include 'footer.php';
    exit;
}

include 'header.php';
?>

<h2><?php echo esc($product['name']); ?></h2>

<div class="product-detail">
    <div class="product-detail-image">
        <?php if (!empty($product['image_url'])): ?>
            <img src="<?php echo esc($product['image_url']); ?>" alt="<?php echo esc($product['name']); ?>">
        <?php else: ?>
            <div style="background:#eee; height:300px; display:flex; align-items:center; justify-content:center;">
                No image
            </div>
        <?php endif; ?>
    </div>

    <div class="product-detail-info">
        <p><strong>Giá:</strong> <?php echo number_format($product['price']); ?> VND</p>
        <?php if ($product['original_price'] > $product['price']): ?>
            <p><del><?php echo number_format($product['original_price']); ?> VND</del></p>
        <?php endif; ?>

        <?php if (!empty($product['category_name'])): ?>
            <p><strong>Danh mục:</strong> <?php echo esc($product['category_name']); ?></p>
        <?php endif; ?>

        <?php if (!empty($product['brand_name'])): ?>
            <p><strong>Thương hiệu:</strong> <?php echo esc($product['brand_name']); ?></p>
        <?php endif; ?>

        <?php if (!empty($product['material'])): ?>
            <p><strong>Chất liệu:</strong> <?php echo esc($product['material']); ?></p>
        <?php endif; ?>

        <?php if (!empty($product['gender'])): ?>
            <p><strong>Giới tính:</strong> <?php echo esc($product['gender']); ?></p>
        <?php endif; ?>

        <?php if (!empty($product['description'])): ?>
            <p><strong>Mô tả:</strong><br><?php echo nl2br(esc($product['description'])); ?></p>
        <?php endif; ?>

        <hr>

        <form method="post" action="cart.php">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

            <!-- Tạm thời cho user chọn size/color tự do -->
            <label>Size:</label><br>
            <select name="size">
                <option value="">-- Chọn size --</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
            </select><br><br>

            <label>Màu:</label><br>
            <input type="text" name="color" placeholder="VD: Đen, Trắng..."><br><br>

            <label>Số lượng:</label><br>
            <input type="number" name="quantity" value="1" min="1"><br><br>

            <button type="submit" class="btn">Thêm vào giỏ hàng</button>
        </form>
    </div>
</div>

<?php include 'footer.php';
