<?php
// db_config.php - cấu hình kết nối CSDL cho toàn bộ website

$servername = "localhost";
$username   = "root";
$password   = ""; // mặc định XAMPP
$dbname     = "shop_quan_ao";

// Nếu có lỗi SQL sẽ ném Exception (dễ debug)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // *** QUAN TRỌNG: phải là $conn ***
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Không thể kết nối CSDL: " . $e->getMessage());
}
