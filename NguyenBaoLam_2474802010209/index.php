<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Quần Áo</title>
    <link rel="stylesheet" href="style.css">
    <style>
        ul.menu {
            margin: 0px;
            padding: 0px;
        }

        li.menu {
            display: inline;
            font-size: 20px;
        }

        a.menu {
            color: blue;
            margin-right: 5px;
            padding: 5px;
            text-decoration: none;
            border-style: solid;
            border-width: 1px;
            border-radius: 10px;
        }

        a.menu:hover {
            background-color: blue;
            color: white;
        }

        a.active {
            background-color: red;
            color: yellow;
        }

        #content {
            margin-top: 20px;
        }
        #action {
            margin-top: 20px;
        }
        
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php
    // Xử lý thông báo
    $message = '';
    $messageType = '';
    if(isset($_GET['message'])) {
        $message = $_GET['message'];
        $messageType = isset($_GET['type']) ? $_GET['type'] : 'success';
    }

    // Lấy danh mục
    $id = null;
    if(isset($_GET["id"])){
        $id = $_GET["id"];
    }
    
    include_once("db_config.php");

    $cn = new mysqli($servername, $username, $password, $dbname);
    if($cn->connect_error){
        die("Lỗi kết nối: ". $cn->connect_error);
    }

    // Lấy danh sách danh mục
    $sql = "SELECT MaDM, TenDM FROM danhmuc WHERE TrangThai = 1 ORDER BY MaDM";
    $result = $cn->query($sql);

    echo "<ul class='menu'>";
    echo "<a class='menu " . ($id == null ? "active" : "") . "' href='?'><li class='menu'>Tất Cả</li></a>";
    
    while($row = $result->fetch_assoc()){
        $active = "";
        if($row["MaDM"] == $id){
            $active = "active";
        }
        echo "<a class='menu {$active}' href='?id={$row['MaDM']}'><li class='menu'>{$row['TenDM']}</li></a>";
    }
    echo "</ul>";

    // Hiển thị thông báo
    if($message != '') {
        echo "<div class='message {$messageType}'>{$message}</div>";
    }

    // Hiển thị sản phẩm
    echo "<div id='content'>";
    
    if($id != null){
        // Hiển thị sản phẩm theo danh mục
        $sql = "SELECT sp.*, th.TenTH FROM sanpham sp 
                JOIN thuonghieu th ON sp.MaTH = th.MaTH 
                WHERE sp.MaDM = '{$id}' AND sp.TrangThai = 'con_hang' 
                ORDER BY sp.NgayTao DESC";
    } else {
        // Hiển thị tất cả sản phẩm
        $sql = "SELECT sp.*, th.TenTH FROM sanpham sp 
                JOIN thuonghieu th ON sp.MaTH = th.MaTH 
                WHERE sp.TrangThai = 'con_hang' 
                ORDER BY sp.NgayTao DESC";
    }
    
    $result = $cn->query($sql);
    
    if($result->num_rows > 0){
        echo "<h2>Danh Sách Sản Phẩm</h2>";
        echo "<div class='product-list'>";
        
        while($row = $result->fetch_assoc()){
            $formattedPrice = number_format($row['GiaBan'], 0, ',', '.');
            
            echo "<div class='product-item'>";
            echo "<h3>{$row['TenSP']}</h3>";
            echo "<p><strong>Thương hiệu:</strong> {$row['TenTH']}</p>";
            echo "<p><strong>Giá:</strong> {$formattedPrice} VNĐ</p>";
            echo "<p><strong>Màu sắc:</strong> {$row['MauSac']}</p>";
            echo "<p><strong>Kích thước:</strong> {$row['KichThuoc']}</p>";
            echo "<p><strong>Tình trạng:</strong> " . ($row['TrangThai'] == 'con_hang' ? 'Còn hàng' : 'Hết hàng') . "</p>";
            
            if(!empty($row['MoTa'])){
                echo "<p><strong>Mô tả:</strong> {$row['MoTa']}</p>";
            }
            
            echo "<div class='product-actions'>";
            echo "<a href='edit.php?id={$row['MaSP']}'><button>Sửa</button></a>";
            echo "<a href='delete.php?id={$row['MaSP']}' onclick=\"return confirm('Có chắc xóa sản phẩm này?');\"><button>Xóa</button></a>";
            echo "</div>";
            echo "</div>";
        }
        
        echo "</div>";
    } else {
        echo "<p>Không có sản phẩm nào.</p>";
    }
    
    echo "</div>";

    $cn->close();
    ?>

    <div id="action">
        <a href="addnew.php"><button>Thêm Sản Phẩm Mới</button></a>
    </div>

</body>
</html>