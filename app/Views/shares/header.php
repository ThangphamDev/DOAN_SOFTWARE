<link rel="stylesheet" href="/public/css/style.css">
<header>
    <div class="logo-container">
        <img src="/asset/image/logo.png" alt="Logo Quán Coffee T&2K" class="logo">
        <h1 class="fade-in">Coffee T&2K</h1>
    </div>
    <nav>
        <ul>
            <li><a href="/">Trang Chủ</a></li>
            <li><a href="/menu">Menu</a></li>
            <li><a href="/cart">Giỏ Hàng</a></li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="/login">Đăng Nhập</a></li>
                <li><a href="/checkout">Thanh Toán</a></li>
            <?php else: ?>
                <li><a href="/orders">Đơn Hàng</a></li>
                <li><a href="/profile">Tài Khoản</a></li>
                <li><a href="/logout">Đăng Xuất</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php
// Thêm session start ở đầu file để sử dụng session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>