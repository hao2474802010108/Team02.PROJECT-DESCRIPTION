<?php
// header.php
require_once 'functions.php';
require_once 'db_config.php';

// Lấy cart count cho user đã đăng nhập
$cart_count = 0;
if (isLoggedIn()) {
    $user_id = currentUserId();
    $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $cart_count = $row['total'] ?? 0;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>1NT3Rnet - Thời trang trẻ trung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="1NT3Rnet - Cửa hàng thời trang trẻ trung với những sản phẩm chất lượng và phong cách độc đáo">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/style.css"> 
    
    <!-- JS Files -->
    <script src="js/header.js" defer></script>
</head>
<body>
<header class="site-header" role="banner">
    <!-- Top Header -->
    <div class="header-top">
        <!-- Logo -->
        <a href="index.php" class="header-logo" aria-label="1NT3Rnet - Trang chủ">
            <i class="fas fa-tshirt logo-icon" aria-hidden="true"></i>
            1NT3Rnet
        </a>

        <!-- Search Bar -->
        <div class="header-search">
            <input type="text" 
                   class="search-input" 
                   placeholder="Tìm kiếm sản phẩm..." 
                   value="<?php echo esc($_GET['search'] ?? ''); ?>"
                   aria-label="Tìm kiếm sản phẩm"
                   autocomplete="off">
            <button class="search-btn" aria-label="Tìm kiếm">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-toggle" 
                aria-label="Toggle menu" 
                aria-expanded="false"
                aria-controls="navContainer">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <!-- User Actions -->
        <div class="header-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-welcome" 
                     role="button" 
                     aria-label="Thông tin người dùng"
                     tabindex="0">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <?php echo esc($_SESSION['username']); ?>
                    <?php if (currentUserRole() === 'admin'): ?>
                        <span class="user-role" aria-label="Quản trị viên">Admin</span>
                    <?php endif; ?>
                </div>
                <a href="logout.php" 
                   class="header-btn btn-secondary"
                   aria-label="Đăng xuất">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Đăng xuất
                </a>
            <?php else: ?>
                <a href="register.php" 
                   class="action-link"
                   aria-label="Đăng ký tài khoản">
                    <i class="fas fa-user-plus" aria-hidden="true"></i> Đăng ký
                </a>
                <a href="login.php" 
                   class="action-link"
                   aria-label="Đăng nhập">
                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Đăng nhập
                </a>
            <?php endif; ?>
            
            <a href="cart.php" 
               class="action-link cart-link" 
               aria-label="Giỏ hàng"
               aria-describedby="cart-count-desc">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i> Giỏ hàng
                <span class="cart-count" 
                      aria-live="polite"
                      data-previous="<?php echo $cart_count; ?>">
                    <?php echo $cart_count; ?>
                </span>
                <span id="cart-count-desc" class="visually-hidden">
                </span>
            </a>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav" aria-label="Main navigation">
        <div class="nav-container" id="navContainer">
            <ul class="nav-menu" role="menubar">
                <li class="nav-item" role="none">
                    <a href="index.php" 
                       class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" 
                       role="menuitem">
                        <i class="fas fa-home" aria-hidden="true"></i> TRANG CHỦ
                    </a>
                </li>
                <li class="nav-item" role="none">
                    <a href="products.php" 
                       class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" 
                       role="menuitem">
                        <i class="fas fa-gem" aria-hidden="true"></i> BỘ SƯU TẬP
                    </a>
                </li>
                <li class="nav-item" role="none">
                    <a href="my_orders.php" 
                       class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_orders.php' ? 'active' : ''; ?>" 
                       role="menuitem">
                        <i class="fas fa-box" aria-hidden="true"></i> ĐƠN HÀNG
                    </a>
                </li>
                <?php if (isLoggedIn() && currentUserRole() === 'admin'): ?>
                <li class="nav-item" role="none">
                    <a href="add_product.php" 
                       class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>" 
                       role="menuitem">
                        <i class="fas fa-plus" aria-hidden="true"></i> THÊM SẢN PHẨM
                        <span class="admin-badge" aria-label="Quản trị viên">Admin</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<!-- Main Content Container -->
<div class="container" role="main">