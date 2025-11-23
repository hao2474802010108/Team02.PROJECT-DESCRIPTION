<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
require_once 'db_config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shop Quần Áo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DÙNG FILE CSS RIÊNG -->
    <link rel="stylesheet" href="style.css">

    <!-- DÙNG FILE JS RIÊNG (có confirmDeleteItem ở trong) -->
    <script src="script.js" defer></script>
</head>
<body>
<header class="clearfix">
    <h1>Shop Quần Áo Online</h1>
    <nav class="top-bar-right">
        <a href="index.php">Trang chủ</a>
        <a href="products.php">Sản phẩm</a>
        <a href="cart.php">Giỏ hàng</a>
        <a href="my_orders.php">Đơn hàng của tôi</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Xin chào, <?php echo esc($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-secondary">Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
