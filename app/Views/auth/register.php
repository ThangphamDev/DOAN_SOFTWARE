<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Quán Coffee T&2K</title>
    <link rel="stylesheet" href="/public/css/auth-style.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php include __DIR__ . '/../shares/header.php'; ?>
    <main>
        <section class="auth-container fade-in">
            <h2>Đăng Ký</h2>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            <form action="/register/process" method="POST" id="register-form" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="full_name">Họ và tên:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Số điện thoại:</label>
                    <input type="tel" id="phone_number" name="phone_number" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="g-recaptcha" data-sitekey="your-site-key"></div>
                
                <button type="submit" class="btn-primary">Đăng Ký</button>
                <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
            </form>
        </section>
    </main>
    <?php include __DIR__ . '/../shares/footer.php'; ?>

    <script>
    function validateForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (password !== confirmPassword) {
            alert('Mật khẩu xác nhận không khớp!');
            return false;
        }
        
        if (password.length < 6) {
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
        
        return true;
    }
    </script>
</body>
</html>