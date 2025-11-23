<?php
require_once 'db_config.php';
require_once 'functions.php';

include 'header.php';

// Lấy danh sách danh mục
$categories = [];
$resultCat = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");
while ($row = $resultCat->fetch_assoc()) {
    $categories[] = $row;
}

// Lấy một số sản phẩm mới
$products = [];
$sqlProducts = "SELECT id, name, price, image_url FROM products 
                WHERE status = 'available' 
                ORDER BY created_at DESC 
                LIMIT 8";
$resultPro = $conn->query($sqlProducts);
while ($row = $resultPro->fetch_assoc()) {
    $products[] = $row;
}
?>

<h2>Danh mục sản phẩm</h2>
<ul>
    <?php foreach ($categories as $cat): ?>
        <li>
            <a href="products.php?category_id=<?php echo $cat['id']; ?>">
                <?php echo esc($cat['name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<h2>Sản phẩm mới</h2>
<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>Chưa có sản phẩm nào.</p>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <?php if (!empty($p['image_url'])): ?>
                    <img src="<?php echo esc($p['image_url']); ?>" alt="<?php echo esc($p['name']); ?>">
                <?php else: ?>
                    <div style="background:#eee; height:150px; display:flex; align-items:center; justify-content:center;">No image</div>
                <?php endif; ?>
                <h3><?php echo esc($p['name']); ?></h3>
                <p><strong><?php echo number_format($p['price']); ?> VND</strong></p>
                <a class="btn" href="product_detail.php?id=<?php echo $p['id']; ?>">Xem chi tiết</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
