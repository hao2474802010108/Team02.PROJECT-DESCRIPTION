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
    echo "<div class='product-not-found'><p>Không tìm thấy sản phẩm.</p></div>";
    include 'footer.php';
    exit;
}

include 'header.php';
?>

<!-- Thêm CSS và Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/product_detail.css">

<div class="product-detail-container">
    <div class="breadcrumb">
        <a href="index.php"><i class="fas fa-home"></i> Trang chủ</a>
        <span class="separator">/</span>
        <a href="products.php">Sản phẩm</a>
        <span class="separator">/</span>
        <span class="current"><?php echo esc($product['name']); ?></span>
    </div>

    <div class="product-detail">
        <div class="product-image-section">
            <?php if (!empty($product['image_url'])): ?>
                <div class="main-image">
                    <img src="<?php echo esc($product['image_url']); ?>" alt="<?php echo esc($product['name']); ?>" id="mainImage">
                </div>
            <?php else: ?>
                <div class="main-image no-image">
                    <i class="fas fa-tshirt"></i>
                    <span>Không có hình ảnh</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info-section">
            <h1 class="product-title"><?php echo esc($product['name']); ?></h1>
            
            <div class="product-price-section">
                <span class="current-price"><?php echo number_format($product['price']); ?>₫</span>
                <?php if ($product['original_price'] > $product['price']): ?>
                    <span class="original-price"><?php echo number_format($product['original_price']); ?>₫</span>
                    <span class="discount-badge">-<?php echo round(($product['original_price'] - $product['price']) / $product['original_price'] * 100); ?>%</span>
                <?php endif; ?>
            </div>

            <div class="product-meta">
                <?php if (!empty($product['category_name'])): ?>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <strong>Danh mục:</strong>
                        <span><?php echo esc($product['category_name']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product['brand_name'])): ?>
                    <div class="meta-item">
                        <i class="fas fa-copyright"></i>
                        <strong>Thương hiệu:</strong>
                        <span><?php echo esc($product['brand_name']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product['material'])): ?>
                    <div class="meta-item">
                        <i class="fas fa-palette"></i>
                        <strong>Chất liệu:</strong>
                        <span><?php echo esc($product['material']); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product['gender'])): ?>
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <strong>Giới tính:</strong>
                        <span><?php echo esc($product['gender']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($product['description'])): ?>
                <div class="product-description">
                    <h3><i class="fas fa-info-circle"></i> Mô tả sản phẩm</h3>
                    <p><?php echo nl2br(esc($product['description'])); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="cart.php" class="add-to-cart-form" id="addToCartForm">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <div class="form-group">
                    <label for="size"><i class="fas fa-ruler"></i> Kích thước:</label>
                    <select name="size" id="size" required>
                        <option value="">-- Chọn size --</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="color"><i class="fas fa-palette"></i> Màu sắc:</label>
                    <input type="text" name="color" id="color" placeholder="VD: Đen, Trắng, Xám..." required>
                </div>

                <div class="form-group">
                    <label for="quantity"><i class="fas fa-layer-group"></i> Số lượng:</label>
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn" data-action="decrease">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="10">
                        <button type="button" class="quantity-btn" data-action="increase">+</button>
                    </div>
                </div>

                <button type="submit" class="btn btn-add-to-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span>THÊM VÀO GIỎ HÀNG</span>
                </button>
            </form>

            <div class="product-actions">
                <a href="products.php" class="btn btn-continue-shopping">
                    <i class="fas fa-arrow-left"></i>
                    <span>TIẾP TỤC MUA SẮM</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="js/product_detail.js"></script>
<?php include 'footer.php'; ?>