<?php
require_once 'db_config.php';
require_once 'functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
    } else {
        // Dùng prepared statement cho an toàn
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng.";
        } else {
            // Đăng nhập thành công
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Nếu có tham số redirect thì quay lại trang đó, không thì về trang chủ
            $redirect = $_GET['redirect'] ?? 'index.php';
            redirect($redirect);
        }
    }
}

include 'header.php';
?>

<h2>Đăng nhập</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $e): ?>
            <p><?php echo esc($e); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post">
    <label>Tên đăng nhập:</label><br>
    <input type="text" name="username"><br><br>

    <label>Mật khẩu:</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit" class="btn">Đăng nhập</button>
</form>

<?php include 'footer.php';
