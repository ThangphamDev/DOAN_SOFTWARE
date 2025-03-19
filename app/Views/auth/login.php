<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quán Coffee T&2K</title>
    <link rel="stylesheet" href="/public/css/auth-style.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="script.js" defer></script>
    <?php include 'App/Views/shares/header.php'; ?>
</head>
<body>

    <main>
        <section class="auth-container fade-in">
            <h2>Đăng Nhập</h2>
            <form id="login-form">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" required>
                
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
                
                <div class="g-recaptcha" data-sitekey="your-site-key"></div>
                
                <button type="submit" class="btn-primary">Đăng Nhập</button>
                <p>Chưa có tài khoản? <a href="register.html">Đăng ký ngay</a></p>
            </form>
        </section>
    </main>
</body>
</html>

<?php include 'App/Views/shares/footer.php'; ?>