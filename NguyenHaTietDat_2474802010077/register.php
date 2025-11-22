<?php
require_once 'db_config.php';
require_once 'functions.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $email     = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $address   = trim($_POST['address'] ?? '');

    if ($username === '' || $password === '' || $password2 === '') {
        $errors[] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    }

    if ($password !== $password2) {
        $errors[] = "Mật khẩu nhập lại không khớp.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Tên đăng nhập hoặc email đã tồn tại.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role   = 'customer';

            $stmtIns = $conn->prepare("INSERT INTO users (username, password, email, full_name, phone, address, role) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtIns->bind_param("sssssss", $username, $hashed, $email, $full_name, $phone, $address, $role);
            $stmtIns->execute();

            $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
        }
        $stmt->close();
    }
}

include 'header.php';
?>

<h2>Đăng ký</h2>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $e): ?>
        <p class="error"><?php echo esc($e); ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($success): ?>
    <p class="success"><?php echo esc($success); ?></p>
<?php endif; ?>

<form method="post">
    <label>Tên đăng nhập:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Mật khẩu:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Nhập lại mật khẩu:</label><br>
    <input type="password" name="password2" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br><br>

    <label>Họ tên:</label><br>
    <input type="text" name="full_name"><br><br>

    <label>Số điện thoại:</label><br>
    <input type="text" name="phone"><br><br>

    <label>Địa chỉ:</label><br>
    <textarea name="address" rows="3"></textarea><br><br>

    <button type="submit" class="btn">Đăng ký</button>
</form>

<?php
include 'footer.php';
