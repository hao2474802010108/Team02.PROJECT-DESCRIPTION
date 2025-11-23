<?php
require_once 'db_config.php';
require_once 'functions.php';

include 'header.php';

// Lọc theo danh mục (tùy chọn)
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Tìm kiếm (tùy chọn)
$keyword = trim($_GET['q'] ?? '');

// Lấy danh sách danh mục để hiện sidebar/filter
$categories = [];
$resultCat = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");
while ($row = $resultCat->fetch_assoc()) {
    $categories[] = $row;
}

// Build SQL sản phẩm
$sql = "SELECT id, name, price, image_url FROM products WHERE status = 'available'";
$params = [];
$types  = "";

// lọc theo danh mục
if ($category_id > 0) {
    $sql .= " AND category_id = ?";
    $types .= "i";
    $params[] = $category_id;
}

// lọc theo từ khóa
if ($keyword !== '') {
    $sql .= " AND name LIKE ?";
    $types .= "s";
    $params[] = "%{$keyword}%";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- Thêm CSS và Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/products.css">

<div class="products-container">
    <h1 class="products-title">
        <i class="fas fa-tshirt"></i> TẤT CẢ SẢN PHẨM
    </h1>

    <form method="get" class="filter-form">
        <div class="filter-group">
            <select name="category">
                <option value="0">-- Tất cả danh mục --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>"
                        <?php if ($category_id == $c['id']) echo 'selected'; ?>>
                        <?php echo esc($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="search-wrapper">
                <input type="text" name="q" placeholder="Tìm tên sản phẩm..." value="<?php echo esc($keyword); ?>">
                <button type="button" class="clear-search">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-filter"></i> LỌC
            </button>
        </div>
    </form>

    <?php if (!empty($products)): ?>
        <div class="results-count">
            Tìm thấy <strong><?php echo count($products); ?></strong> sản phẩm phù hợp
        </div>
    <?php endif; ?>

    <div class="product-grid">
        <?php if (empty($products)): ?>
            <div class="no-products">
                <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                <p>Không tìm thấy sản phẩm nào.</p>
                <?php if ($keyword || $category_id): ?>
                    <p style="margin-top: 10px; font-size: 0.9rem;">
                        <a href="products.php" style="color: #e50010; text-decoration: none;">
                            <i class="fas fa-undo"></i> Xem tất cả sản phẩm
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <?php if (!empty($p['image_url'])): ?>
                        <div class="product-image">
                            <img src="<?php echo esc($p['image_url']); ?>" alt="<?php echo esc($p['name']); ?>">
                        </div>
                    <?php else: ?>
                        <div class="product-image">
                            <div class="no-image">
                                <i class="fas fa-tshirt"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-info">
                        <h3 class="product-name"><?php echo esc($p['name']); ?></h3>
                        <p class="product-price"><?php echo number_format($p['price']); ?>₫</p>
                    </div>
                    
                    <div class="product-actions">
                        <a class="btn-view" href="product_detail.php?id=<?php echo $p['id']; ?>">
                            <i class="fas fa-eye"></i>
                            <span>XEM CHI TIẾT</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
        <div class="section-footer">
            <a href="categories.php" class="btn-view-all">
                <i class="fas fa-grid"></i>
                XEM TẤT CẢ DANH MỤC
            </a>
        </div>
    <?php endif; ?>
</div>

<script src="js/products.js"></script>
<?php include 'footer.php'; ?>