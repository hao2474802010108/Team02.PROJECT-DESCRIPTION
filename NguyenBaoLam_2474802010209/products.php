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

<h2>Tất cả sản phẩm</h2>

<form method="get" style="margin-bottom:16px;">
    <select name="category">
        <option value="0">-- Tất cả danh mục --</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['id']; ?>"
                <?php if ($category_id == $c['id']) echo 'selected'; ?>>
                <?php echo esc($c['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="q" placeholder="Tìm tên sản phẩm..." value="<?php echo esc($keyword); ?>">
    <button type="submit" class="btn">Lọc</button>
</form>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>Không tìm thấy sản phẩm nào.</p>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <?php if (!empty($p['image_url'])): ?>
                    <img src="<?php echo esc($p['image_url']); ?>" alt="<?php echo esc($p['name']); ?>">
                <?php endif; ?>
                <h3><?php echo esc($p['name']); ?></h3>
                <p><strong><?php echo number_format($p['price']); ?> VND</strong></p>
                <a class="btn" href="product_detail.php?id=<?php echo $p['id']; ?>">Xem chi tiết</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php';
