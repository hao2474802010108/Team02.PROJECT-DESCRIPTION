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

<!-- Thêm CSS -->
<link rel="stylesheet" href="css/add_product.css">
<!-- Thêm Font Awesome cho icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="product-form-container">
    <h2 class="page-title">
        <i class="fas fa-plus-circle"></i>
        Thêm sản phẩm mới
    </h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <h4><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</h4>
            <?php foreach ($errors as $e): ?>
                <p><?php echo esc($e); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <h4><i class="fas fa-check-circle"></i> Thành công!</h4>
            <p><?php echo esc($success); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" class="form product-form">
        <div class="form-section">
            <i class="fas fa-info-circle"></i>
            Thông tin cơ bản
        </div>

        <div class="field-group">
            <label class="required">Tên sản phẩm </label>
            <input type="text" name="name" value="<?php echo esc($name ?? ''); ?>" required 
                   placeholder="Nhập tên sản phẩm">
        </div>

        <div class="field-group">
            <label>Mô tả sản phẩm</label>
            <textarea name="description" rows="4" placeholder="Mô tả chi tiết về sản phẩm"><?php echo esc($description ?? ''); ?></textarea>
        </div>

        <div class="form-section">
            <i class="fas fa-tag"></i>
            Giá và Tồn kho
        </div>

        <div class="price-group">
            <div class="field-group">
                <label class="required">Giá bán </label>
                <input type="number" step="0.01" name="price" value="<?php echo esc($price ?? ''); ?>" required 
                       placeholder="0.00">
            </div>

            <div class="field-group">
                <label>Giá gốc</label>
                <input type="number" step="0.01" name="original_price" value="<?php echo esc($original_price ?? ''); ?>" 
                       placeholder="Tự động điền giá bán">
            </div>
        </div>

        <div class="field-group">
            <label>Tồn kho</label>
            <input type="number" name="stock_quantity" value="<?php echo esc($stock_quantity ?? '0'); ?>" 
                   placeholder="0">
        </div>

        <div class="form-section">
            <i class="fas fa-folder"></i>
            Phân loại
        </div>

        <div class="field-group">
            <label>Danh mục</label>
            <select name="category_id">
                <option value="0">-- Chọn danh mục --</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo $c['id']; ?>"
                        <?php if (!empty($category_id) && $category_id == $c['id']) echo 'selected'; ?>>
                        <?php echo esc($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-section">
            <i class="fas fa-tshirt"></i>
            Thông tin chi tiết
        </div>

        <div class="field-group">
            <label>Size (tùy chọn)</label>
            <select name="size">
                <option value="">-- Chọn size --</option>
                <option value="S"  <?php if (($size ?? '') === 'S')  echo 'selected'; ?>>S</option>
                <option value="M"  <?php if (($size ?? '') === 'M')  echo 'selected'; ?>>M</option>
                <option value="L"  <?php if (($size ?? '') === 'L')  echo 'selected'; ?>>L</option>
                <option value="XL" <?php if (($size ?? '') === 'XL') echo 'selected'; ?>>XL</option>
                <option value="XXL"<?php if (($size ?? '') === 'XXL') echo 'selected'; ?>>XXL</option>
            </select>
        </div>

        <div class="field-group">
            <label>Màu sắc (tùy chọn)</label>
            <input type="text" name="color" value="<?php echo esc($color ?? ''); ?>" 
                   placeholder="Ví dụ: Đỏ, Xanh, Đen...">
        </div>

        <div class="field-group">
            <label>Chất liệu (tùy chọn)</label>
            <input type="text" name="material" value="<?php echo esc($material ?? ''); ?>" 
                   placeholder="Ví dụ: Cotton, Polyester...">
        </div>

        <div class="field-group">
            <label>Giới tính</label>
            <select name="gender">
                <option value="unisex" <?php if (($gender ?? '') === 'unisex') echo 'selected'; ?>>Unisex</option>
                <option value="nam"    <?php if (($gender ?? '') === 'nam') echo 'selected'; ?>>Nam</option>
                <option value="nu"     <?php if (($gender ?? '') === 'nu') echo 'selected'; ?>>Nữ</option>
            </select>
        </div>

        <div class="form-section">
            <i class="fas fa-image"></i>
            Hình ảnh & Trạng thái
        </div>

        <div class="field-group">
            <label>URL Hình ảnh</label>
            <input type="text" name="image_url" value="<?php echo esc($image_url ?? ''); ?>" 
                   placeholder="ví dụ: images/ao-thun-1.jpg hoặc https://example.com/image.jpg">
        </div>

        <div class="field-group">
            <label>Trạng thái</label>
            <select name="status">
                <option value="available"    <?php if (($status ?? '') === 'available')    echo 'selected'; ?>>Đang bán</option>
                <option value="out_of_stock" <?php if (($status ?? '') === 'out_of_stock') echo 'selected'; ?>>Hết hàng</option>
                <option value="discontinued" <?php if (($status ?? '') === 'discontinued') echo 'selected'; ?>>Ngừng bán</option>
            </select>
        </div>

        <div class="button-group">
            <button type="submit" class="btn">
                <i class="fas fa-plus-circle"></i>
                Thêm sản phẩm
            </button>
            
            <a href="products.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </form>
</div>

<!-- Thêm JavaScript -->
<script src="js/add_product.js"></script>

<?php include 'footer.php'; ?>