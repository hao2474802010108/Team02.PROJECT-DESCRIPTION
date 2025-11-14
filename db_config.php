<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shop_quan_ao";

$cn = new mysqli($servername, $username, $password, $dbname);

if ($cn->connect_error) {
    die("Kết nối thất bại: " . $cn->connect_error);
}
?>
