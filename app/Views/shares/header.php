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
            <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
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
            <?php endif; ?>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="item"><a href="/checkout">Thanh Toán</a></li>
                <li class="item"><a href="/login">Đăng Nhập</a></li>
            <?php else: ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="item dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-cogs"></i>
                        <span>Quản Lý</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="/admin/categories">
                                <i class="fas fa-list"></i>
                                Danh Mục
                            </a>
                        </li>
                        <li>
                            <a href="/admin/products">
                                <i class="fas fa-coffee"></i>
                                Sản Phẩm
                            </a>
                        </li>
                        <li>
                            <a href="/admin/orders">
                                <i class="fas fa-shopping-bag"></i>
                                Đơn Hàng
                            </a>
                        </li>
                        <li>
                            <a href="/admin/promotions">
                                <i class="fas fa-percent"></i>
                                Khuyến Mãi
                            </a>
                        </li>
                        <li>
                            <a href="/admin/feedback">
                                <i class="fas fa-comments"></i>
                                Phản Hồi
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="item dropdown">
                    <a href="#" class="dropdown-toggle">
                        <i class="fas fa-user"></i>
                        <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/profile"><i class="fas fa-user-circle"></i> Tài Khoản</a></li>
                        <li><a href="/orders"><i class="fas fa-list"></i> Đơn Hàng</a></li>
                        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<style>
.dropdown {
    position: relative;
}

.dropdown-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.3s ease;
    color: white;
}

.dropdown-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
}

.dropdown-toggle i {
    color: white;
}

.dropdown-toggle i.fa-chevron-down {
    font-size: 0.8em;
    transition: transform 0.3s ease;
}

.dropdown:hover .dropdown-toggle i.fa-chevron-down {
    transform: rotate(180deg);
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 5px);
    right: 0;
    background: #8B4513;
    border-radius: 12px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    min-width: 220px;
    padding: 8px 0;
    z-index: 1000;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.1);
}

.dropdown:hover .dropdown-menu {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.dropdown-menu::before {
    content: '';
    position: absolute;
    top: -8px;
    right: 20px;
    width: 16px;
    height: 16px;
    background: #8B4513;
    transform: rotate(45deg);
    border-left: 1px solid rgba(255,255,255,0.1);
    border-top: 1px solid rgba(255,255,255,0.1);
}

.dropdown-menu li {
    display: block;
    margin: 2px 0;
}

.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    position: relative;
    overflow: hidden;
}

.dropdown-menu a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: #FFA500;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.dropdown-menu a:hover {
    background: rgba(255,255,255,0.1);
    color: #FFA500;
    padding-left: 25px;
}

.dropdown-menu a:hover::before {
    transform: scaleY(1);
}

.dropdown-menu i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    transition: color 0.2s ease;
}

.dropdown-menu a:hover i {
    color: #FFA500;
}

@media (max-width: 768px) {
    .dropdown-menu {
        position: static;
        box-shadow: none;
        background: rgba(0,0,0,0.2);
        border-radius: 0;
        margin-top: 0;
        transform: none;
        opacity: 1;
        border: none;
    }
    
    .dropdown-menu::before {
        display: none;
    }
    
    .dropdown-menu a {
        padding: 15px 20px;
        font-size: 1rem;
    }

    .dropdown-toggle {
        padding: 10px 15px;
    }
}
</style>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tính tổng số lượng sản phẩm trong giỏ hàng
$cartCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>