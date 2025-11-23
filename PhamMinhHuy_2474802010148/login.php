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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - 1NT3Rnet</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Kết nối CSS -->
    <link rel="stylesheet" href="css/login.css">
    
    <!-- Kết nối JS -->
    <script src="js/login.js" defer></script>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="header-logo">
                <i class="fas fa-tshirt"></i>
                1NT3Rnet
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php"><i class="fas fa-home"></i> Trang chủ</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> Giới thiệu</a></li>
                    <li><a href="services.php"><i class="fas fa-tshirt"></i> Sản phẩm</a></li>
                    <li><a href="contact.php"><i class="fas fa-envelope"></i> Liên hệ</a></li>
                    <li><a href="login.php" class="active"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
                    <li><a href="register.php"><i class="fas fa-user-plus"></i> Đăng ký</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="login-card">
                <div class="logo">
                    <i class="fas fa-tshirt"></i>
                    1NT3Rnet
                </div>
                <div class="subtitle">
                    <i class="fas fa-sign-in-alt"></i>
                    ĐĂNG NHẬP TÀI KHOẢN
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php foreach ($errors as $e): ?>
                            <p><?php echo esc($e); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" id="loginForm">
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i>
                            Tên đăng nhập
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-user input-icon left-icon"></i>
                            <input type="text" name="username" id="username" class="form-control" 
                                   placeholder="Nhập tên đăng nhập" value="<?php echo isset($_POST['username']) ? esc($_POST['username']) : ''; ?>">
                        </div>
                        <div class="error-message" id="usernameError"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Mật khẩu
                        </label>
                        <div class="input-with-icon password-field">
                            <i class="fas fa-lock input-icon left-icon"></i>
                            <input type="password" name="password" id="password" class="form-control password-input" 
                                   placeholder="Nhập mật khẩu">
                            <button type="button" class="toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="passwordError"></div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" value="1" 
                               <?php echo isset($_POST['remember']) ? 'checked' : ''; ?>>
                        <label for="remember">
                            <i class="fas fa-remember"></i>
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        ĐĂNG NHẬP
                    </button>
                </form>

                <div class="divider"></div>

                <div class="register-link-section">
                    <i class="fas fa-user-plus"></i>
                    Chưa có tài khoản? 
                    <a href="register.php" class="register-link">
                        <i class="fas fa-arrow-right"></i>
                        Đăng ký ngay
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>
                <i class="fas fa-tshirt"></i>
                2024 1NT3Rnet. Tất cả các quyền được bảo lưu.
            </p>
            <div class="footer-links">
                <a href="privacy.php">
                    <i class="fas fa-shield-alt"></i>
                    Chính sách bảo mật
                </a>
                <a href="terms.php">
                    <i class="fas fa-file-contract"></i>
                    Điều khoản sử dụng
                </a>
                <a href="help.php">
                    <i class="fas fa-question-circle"></i>
                    Trợ giúp
                </a>
            </div>
        </div>
    </footer>
</body>
</html>