<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Quán Coffee T&2K</title>
    <link rel="stylesheet" href="/public/css/auth-style.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php include __DIR__ . '/../shares/header.php'; ?>
    <main>
        <section class="auth-container fade-in">
            <h2>Đăng Nhập</h2>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            <form id="login-form" action="/login/process" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="g-recaptcha" data-sitekey="your-site-key"></div>
                
                <button type="submit" class="btn-primary">Đăng Nhập</button>
                <p>Chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
            </form>
        </section>
    </main>
    <?php include __DIR__ . '/../shares/footer.php'; ?>
</body>
</html>