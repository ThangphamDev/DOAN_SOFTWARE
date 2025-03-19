<!-- HTML với link CSS -->
<link rel="stylesheet" href="/public/css/header.css">
<link rel="stylesheet" href="/public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<header>
    <div class="logo-container">
        <img src="/public/images/home/logo.png" alt="Logo Quán Coffee T&2K" class="logo" >
        <h1 class="fade-in">Coffee T&2K</h1>
    </div>
    <nav>
        <ul>
            <li class="item"><a href="/">Trang Chủ</a></li>
            <li class="item"><a href="/menu">Menu</a></li>
            <li class="item">
                <a href="/cart" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Giỏ Hàng</span>
                    <?php
                    $cart_count = 0;
                    if (isset($_SESSION['cart'])) {
                        $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
                    }
                    if ($cart_count > 0):
                    ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="item"><a href="/checkout">Thanh Toán</a></li>
                <li class="item"><a href="/login">Đăng Nhập</a></li>
            <?php else: ?>
                <li class="item"><a href="/orders">Đơn Hàng</a></li>
                <li class="item"><a href="/profile">Tài Khoản</a></li>
                <li class="item"><a href="/logout">Đăng Xuất</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>