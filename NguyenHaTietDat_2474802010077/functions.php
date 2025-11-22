<?php
// functions.php - các hàm dùng chung cho website

// Bật session nếu chưa bật
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đã đăng nhập chưa
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

// Lấy ID user hiện tại
function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Lấy username hiện tại
function currentUsername() {
    return $_SESSION['username'] ?? null;
}

// Lấy role hiện tại (admin / customer)
function currentUserRole() {
    return $_SESSION['role'] ?? null;
}

// Hàm chuyển trang
function redirect(string $url) {
    header("Location: $url");
    exit;
}

// Escape HTML để chống XSS
function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
