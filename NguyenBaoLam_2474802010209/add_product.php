<?php
require_once 'db_config.php';
require_once 'functions.php';

// CHẶN: chỉ cho admin dùng trang này
if (!isLoggedIn() || currentUserRole() !== 'admin') {
    redirect('login.php');
}

// Lấy danh mục & brand cho dropdown
$categories = [];
$resultCat = $conn->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name");
while ($row = $resultCat->fetch_assoc()) {
    $categories[] = $row;
}

$brands = [];
$resultBrand = $conn->query("SELECT id, name FROM brands ORDER BY name");
while ($row = $resultBrand->fetch_assoc()) {
    $brands[] = $row;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name           = trim($_POST['name'] ?? '');
    $description    = trim($_POST['description'] ?? '');
    $price          = (float)($_POST['price'] ?? 0);
    $original_price = (float)($_POST['original_price'] ?? 0);
    $stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
    $category_id    = (int)($_POST['category_id'] ?? 0);
    $brand_id       = (int)($_POST['brand_id'] ?? 0);
    $size           = $_POST['size'] ?? '';  // enum: S, M, L, XL, XXL hoặc để trống
    $color          = trim($_POST['color'] ?? '');
    $material       = trim($_POST['material'] ?? '');
    $gender         = $_POST['gender'] ?? 'unisex'; // enum: nam, nu, unisex
    $image_url      = trim($_POST['image_url'] ?? '');
    $status         = $_POST['status'] ?? 'available';

    // Validate đơn giản
    if ($name === '') {
        $errors[] = "Vui lòng nhập tên sản phẩm.";
    }
    if ($price <= 0) {
        $errors[] = "Vui lòng nhập giá bán hợp lệ.";
    }
    if ($original_price <= 0) {
        // nếu chưa nhập thì cho bằng price
        $original_price = $price;
    }
    if ($stock_quantity < 0) {
        $errors[] = "Tồn kho không được âm.";
    }

    // category_id, brand_id = 0 thì cho về NULL
    if ($category_id <= 0) {
        $category_id = null;
    }
    if ($brand_id <= 0) {
        $brand_id = null;
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO products 
                    (name, description, price, original_price, stock_quantity,
                     category_id, brand_id, size, color, material,
                     gender, image_url, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            // ssddiiissssss = 13 tham số
            $stmt->bind_param(
                "ssddiiissssss",
                $name,
                $description,
                $price,
                $original_price,
                $stock_quantity,
                $category_id,
                $brand_id,
                $size,
                $color,
                $material,
                $gender,
                $image_url,
                $status
            );

            $stmt->execute();

            if ($stmt->affected_rows === 1) {
                $success = "Thêm sản phẩm thành công!";
                // Xoá dữ liệu form sau khi thêm
                $name = $description = $color = $material = $image_url = '';
                $price = $original_price = 0;
                $stock_quantity = 0;
                $category_id = $brand_id = 0;
                $size = '';
                $gender = 'unisex';
                $status = 'available';
            } else {
                $errors[] = "Không thêm được sản phẩm (không có dòng nào được chèn).";
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $errors[] = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}

include 'header.php';
?>

<h2>Thêm sản phẩm mới</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
            <p><?php echo esc($e); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <p><?php echo esc($success); ?></p>
    </div>
<?php endif; ?>

<form method="post" class="form">
    <label>Tên sản phẩm *</label><br>
    <input type="text" name="name" value="<?php echo esc($name ?? ''); ?>" required><br><br>

    <label>Mô tả</label><br>
    <textarea name="description" rows="4"><?php echo esc($description ?? ''); ?></textarea><br><br>

    <label>Giá bán *</label><br>
    <input type="number" step="0.01" name="price" value="<?php echo esc($price ?? ''); ?>" required><br><br>

    <label>Giá gốc (có thể bỏ trống, sẽ lấy bằng giá bán)</label><br>
    <input type="number" step="0.01" name="original_price" value="<?php echo esc($original_price ?? ''); ?>"><br><br>

    <label>Tồn kho</label><br>
    <input type="number" name="stock_quantity" value="<?php echo esc($stock_quantity ?? '0'); ?>"><br><br>

    <label>Danh mục</label><br>
    <select name="category_id">
        <option value="0">-- Chọn danh mục --</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo $c['id']; ?>"
                <?php if (!empty($category_id) && $category_id == $c['id']) echo 'selected'; ?>>
                <?php echo esc($c['name']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>


    <label>Size (tùy chọn)</label><br>
    <select name="size">
        <option value="">-- Không đặt cố định --</option>
        <option value="S"  <?php if (($size ?? '') === 'S')  echo 'selected'; ?>>S</option>
        <option value="M"  <?php if (($size ?? '') === 'M')  echo 'selected'; ?>>M</option>
        <option value="L"  <?php if (($size ?? '') === 'L')  echo 'selected'; ?>>L</option>
        <option value="XL" <?php if (($size ?? '') === 'XL') echo 'selected'; ?>>XL</option>
        <option value="XXL"<?php if (($size ?? '') === 'XXL') echo 'selected'; ?>>XXL</option>
    </select><br><br>

    <label>Màu (tùy chọn)</label><br>
    <input type="text" name="color" value="<?php echo esc($color ?? ''); ?>"><br><br>

    <label>Chất liệu (tùy chọn)</label><br>
    <input type="text" name="material" value="<?php echo esc($material ?? ''); ?>"><br><br>

    <label>Giới tính</label><br>
    <select name="gender">
        <option value="unisex" <?php if (($gender ?? '') === 'unisex') echo 'selected'; ?>>Unisex</option>
        <option value="nam"    <?php if (($gender ?? '') === 'nam') echo 'selected'; ?>>Nam</option>
        <option value="nu"     <?php if (($gender ?? '') === 'nu') echo 'selected'; ?>>Nữ</option>
    </select><br><br>

    <label>Link ảnh (image_url)</label><br>
    <input type="text" name="image_url" value="<?php echo esc($image_url ?? ''); ?>" placeholder="ví dụ: images/ao-thun-1.jpg"><br><br>

    <label>Trạng thái</label><br>
    <select name="status">
        <option value="available"    <?php if (($status ?? '') === 'available')    echo 'selected'; ?>>Đang bán</option>
        <option value="out_of_stock" <?php if (($status ?? '') === 'out_of_stock') echo 'selected'; ?>>Hết hàng</option>
        <option value="discontinued" <?php if (($status ?? '') === 'discontinued') echo 'selected'; ?>>Ngừng bán</option>
    </select><br><br>

    <button type="submit" class="btn">Thêm sản phẩm</button>
</form>

<?php include 'footer.php';
