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

<!-- Thêm CSS -->
<link rel="stylesheet" href="css/index.css">
<!-- Thêm Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="home-container">
    <!-- Hero Section với Slideshow -->
    <section class="hero-section">
        <div class="hero-slideshow">
            <!-- Slide 1 -->
            <div class="hero-slide active">
                <div class="slide-content">
                    <h1 class="hero-title">1NT3Rnet</h1>
                    <p class="hero-subtitle">Chào mừng đến với cửa hàng thời trang của chúng tôi</p>
                    <a href="products.php" class="btn btn-hero">
                        <i class="fas fa-shopping-bag"></i>
                        Mua Sắm Ngay
                    </a>
                </div>
                <div class="slide-image">
                    <img src="images/slide/meme_01.jpg" alt="Fashion Collection">
                </div>
            </div>
            
            <!-- Slide 2 -->
            <div class="hero-slide">
                <div class="slide-content">
                    <h1 class="hero-title">BỘ SƯU TẬP MỚI</h1>
                    <p class="hero-subtitle">Khám phá những xu hướng thời trang mới nhất</p>
                    <a href="products.php" class="btn btn-hero">
                        <i class="fas fa-star"></i>
                        Khám Phá Ngay
                    </a>
                </div>
                <div class="slide-image">
                    <img src="images/slide/meme_02.jpg" alt="New Collection">
                </div>
            </div>
            
            <!-- Slide 3 -->
            <div class="hero-slide">
                <div class="slide-content">
                    <h1 class="hero-title">KHUYẾN MÃI ĐẶC BIỆT</h1>
                    <p class="hero-subtitle">Giảm giá lên đến 50% cho các sản phẩm selected</p>
                    <a href="products.php" class="btn btn-hero">
                        <i class="fas fa-tag"></i>
                        Mua Ngay
                    </a>
                </div>
                <div class="slide-image">
                    <img src="images/slide/meme_03.jpg" alt="Special Offer">
                </div>
            </div>
        </div>
        
        <!-- Navigation Dots -->
        <div class="slide-dots">
            <button class="dot active" data-slide="0"></button>
            <button class="dot" data-slide="1"></button>
            <button class="dot" data-slide="2"></button>
        </div>
        
        <!-- Navigation Arrows -->
        <button class="slide-nav prev">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slide-nav next">
            <i class="fas fa-chevron-right"></i>
        </button>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-th-large"></i>
                Danh Mục Sản Phẩm
            </h2>
        </div>
        
        <div class="categories-list">
            <?php if (empty($categories)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Chưa có danh mục nào.</p>
                </div>
            <?php else: ?>
                <ul class="categories-grid">
                    <?php foreach ($categories as $cat): ?>
                        <li class="category-item">
                            <a href="products.php?category_id=<?php echo $cat['id']; ?>" class="category-link">
                                <i class="fas fa-folder"></i>
                                <?php echo esc($cat['name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <!-- New Products Section -->
    <section class="products-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-newspaper"></i>
                Sản Phẩm Mới
            </h2>
        </div>
        
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>Chưa có sản phẩm nào.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($p['image_url'])): ?>
                                <img src="<?php echo esc($p['image_url']); ?>" alt="<?php echo esc($p['name']); ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-tshirt"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name"><?php echo esc($p['name']); ?></h3>
                            <p class="product-price"><strong><?php echo number_format($p['price']); ?> VND</strong></p>
                        </div>
                        
                        <div class="product-actions">
                            <a class="btn btn-view" href="product_detail.php?id=<?php echo $p['id']; ?>">
                                <i class="fas fa-eye"></i>
                                Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($products)): ?>
            <div class="section-footer">
                <a href="products.php" class="btn btn-view-all">
                    <i class="fas fa-list"></i>
                    Xem Tất Cả Sản Phẩm
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- Thêm JavaScript -->
<script src="js/index.js"></script>

<?php include 'footer.php'; ?>