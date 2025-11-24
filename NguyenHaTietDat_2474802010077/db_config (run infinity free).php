<?php
// db_config.php - kết nối CSDL trên InfinityFree

$servername = "sql104.infinityfree.com"; // MySQL Hostname
$username   = "if0_40494757";            // MySQL Username
$password   = "Shop4TL12345";            // MySQL Password (vPanel password)
$dbname     = "if0_40494757_shop_quan_ao"; // MySQL Database name

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
    echo "Kết nối CSDL thành công!";
} catch (Exception $e) {
    die("Không thể kết nối CSDL: " . $e->getMessage());
}
