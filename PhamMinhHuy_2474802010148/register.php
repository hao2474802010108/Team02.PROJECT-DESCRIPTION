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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - 1NT3Rnet</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Kết nối CSS -->
    <link rel="stylesheet" href="css/register.css">
    
    <!-- Kết nối JS -->
    <script src="js/register.js" defer></script>
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
                    <li><a href="services.php"><i class="fas fa-concierge-bell"></i> Dịch vụ</a></li>
                    <li><a href="contact.php"><i class="fas fa-envelope"></i> Liên hệ</a></li>
                    <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
                    <li><a href="register.php" class="active"><i class="fas fa-user-plus"></i> Đăng ký</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="register-card">
                <div class="logo">
                    <i class="fas fa-tshirt"></i>
                    InT3rNet
                </div>
                <div class="subtitle">
                    <i class="fas fa-user-plus"></i>
                    ĐĂNG KÝ TÀI KHOẢN
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php foreach ($errors as $e): ?>
                            <p><?php echo esc($e); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <p><?php echo esc($success); ?></p>
                    </div>
                <?php endif; ?>

                <form method="post" id="registerForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username">
                                <i class="fas fa-user"></i>
                                Tên đăng nhập *
                            </label>
                            <div class="input-with-icon">
                                <input type="text" name="username" id="username" class="form-control" 
                                       placeholder="Nhập tên đăng nhập" value="<?php echo isset($_POST['username']) ? esc($_POST['username']) : ''; ?>" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                            <div class="error-message" id="usernameError"></div>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <div class="input-with-icon">
                                <input type="email" name="email" id="email" class="form-control" 
                                       placeholder="Nhập email" value="<?php echo isset($_POST['email']) ? esc($_POST['email']) : ''; ?>">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            <div class="error-message" id="emailError"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Mật khẩu *
                            </label>
                            <div class="input-with-icon">
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Nhập mật khẩu" required>
                                <i class="fas fa-lock input-icon"></i>
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="error-message" id="passwordError"></div>
                        </div>

                        <div class="form-group">
                            <label for="password2">
                                <i class="fas fa-lock"></i>
                                Nhập lại mật khẩu *
                            </label>
                            <div class="input-with-icon">
                                <input type="password" name="password2" id="password2" class="form-control" 
                                       placeholder="Nhập lại mật khẩu" required>
                                <i class="fas fa-lock input-icon"></i>
                                <button type="button" class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="error-message" id="password2Error"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">
                                <i class="fas fa-id-card"></i>
                                Họ tên
                            </label>
                            <div class="input-with-icon">
                                <input type="text" name="full_name" id="full_name" class="form-control" 
                                       placeholder="Nhập họ tên" value="<?php echo isset($_POST['full_name']) ? esc($_POST['full_name']) : ''; ?>">
                                <i class="fas fa-user-circle input-icon"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i>
                                Số điện thoại
                            </label>
                            <div class="input-with-icon">
                                <input type="text" name="phone" id="phone" class="form-control" 
                                       placeholder="Nhập số điện thoại" value="<?php echo isset($_POST['phone']) ? esc($_POST['phone']) : ''; ?>">
                                <i class="fas fa-mobile-alt input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">
                            <i class="fas fa-map-marker-alt"></i>
                            Địa chỉ
                        </label>
                        <div class="input-with-icon">
                            <textarea name="address" id="address" class="form-control" 
                                      placeholder="Nhập địa chỉ" rows="3"><?php echo isset($_POST['address']) ? esc($_POST['address']) : ''; ?></textarea>
                            <i class="fas fa-home input-icon textarea-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-register" id="registerBtn">
                        <i class="fas fa-user-plus"></i>
                        ĐĂNG KÝ
                    </button>
                </form>

                <div class="divider"></div>

                <div class="login-link-section">
                    <i class="fas fa-sign-in-alt"></i>
                    Đã có tài khoản? 
                    <a href="login.php" class="login-link">
                        <i class="fas fa-arrow-right"></i>
                        Đăng nhập ngay
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>
                <i class="far fa-copyright"></i>
                &copy;2025 InT3rNet.
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