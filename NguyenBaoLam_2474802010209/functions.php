<?php
// functions.php - các hàm dùng chung cho website

session_set_cookie_params([
    'lifetime' => 0,         // 0 = chỉ sống đến khi đóng trình duyệt
    'path'     => '/',
    'domain'   => '',        // để trống = domain hiện tại
    'secure'   => false,     // nếu dùng HTTPS thì để true
    'httponly' => true,
    'samesite' => 'Lax',     // hoặc 'Strict'
]);

session_start();

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
function esc(?string $str): string {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
