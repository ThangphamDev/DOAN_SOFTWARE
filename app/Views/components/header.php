<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T&2K Coffee - Hương Vị Đắm Say</title>
    <link rel="stylesheet" href="/public/css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css" />
    <style>
        /* Thêm các font chữ vào head */

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="navbar">
            <div class="logo">
                 <a href="/">
                     <h1>T<span class="ampersand">&</span>2<span class="highlight">K</span>Coffee</h1>
                        </a>
                    </div>
                <ul class="nav-links">
                    <li><a href="/" class="<?= ($page ?? '') === 'home' ? 'active' : '' ?>">Trang chủ</a></li>
                    <li><a href="/menu" class="<?= ($page ?? '') === 'menu' ? 'active' : '' ?>">Menu</a></li>
                    <li><a href="/products" class="<?= ($page ?? '') === 'products' ? 'active' : '' ?>">Sản phẩm</a></li>
                    <li><a href="/about" class="<?= ($page ?? '') === 'about' ? 'active' : '' ?>">Giới thiệu</a></li>
                    <li><a href="/contact" class="<?= ($page ?? '') === 'contact' ? 'active' : '' ?>">Liên hệ</a></li>
                </ul>
                <div class="nav-actions">
                    <div class="search-container">
                        <form action="/menu" method="GET" class="search-form">
                            <input type="text" name="search" placeholder="Tìm sản phẩm..." class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="nav-icons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="icon-wrapper">
                                <a href="/notifications" class="notification-icon">
                                    <i class="fas fa-bell"></i>
                                    <?php 
                                    // Lấy số thông báo chưa đọc
                                    $unread_count = 0;
                                    if (isset($db)) {
                                        require_once __DIR__ . '/../../Models/Notification.php';
                                        $notification = new Notification($db);
                                        $unread_count = $notification->getUnreadCount($_SESSION['user_id']);
                                    }
                                    
                                    if ($unread_count > 0): 
                                    ?>
                                    <span class="badge"><?php echo $unread_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                            
                            <div class="user-dropdown">
                                <div class="icon-wrapper">
                                    <a href="#" class="user-icon dropdown-toggle">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                                <div class="user-dropdown-menu">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                                            <div class="user-role"><?php echo ucfirst($_SESSION['role'] ?? 'Khách hàng'); ?></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <ul class="dropdown-actions">
                                        <li><a href="/profile"><i class="fas fa-user-circle"></i> Hồ sơ</a></li>
                                        <li><a href="/orders"><i class="fas fa-shopping-bag"></i> Đơn hàng</a></li>
                                        <li><a href="/notifications"><i class="fas fa-bell"></i> Thông báo</a></li>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                            <li><a href="/admin"><i class="fas fa-tachometer-alt"></i> Admin</a></li>
                                        <?php endif; ?>
                                        <li><a href="/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="user-dropdown">
                                <div class="icon-wrapper">
                                    <a href="#" class="user-icon dropdown-toggle">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                                <div class="user-dropdown-menu">
                                    <div class="login-dropdown-form">
                                        <h4>Đăng nhập</h4>
                                        <?php if(isset($_SESSION['error'])): ?>
                                            <div class="login-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                        <?php endif; ?>
                                        <form action="/login/process" method="POST">
                                            <div class="form-group">
                                                <input type="text" name="username" placeholder="Tên đăng nhập" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" placeholder="Mật khẩu" required>
                                            </div>
                                            <button type="submit" class="login-btn">Đăng nhập</button>
                                        </form>
                                        <div class="dropdown-footer">
                                            <span>Chưa có tài khoản?</span>
                                            <a href="/register">Đăng ký</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="icon-wrapper">
                            <a href="/cart" class="cart-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-badge"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0' ?></span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Overlay cho mobile dropdown -->
    <div class="dropdown-overlay"></div>

    <!-- Mobile Menu Toggle & Header Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Header Scroll
            const header = document.querySelector('header');
            
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
            
            // Mobile Menu Toggle
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');
            const navActions = document.querySelector('.nav-actions');
            
            hamburger.addEventListener('click', function() {
                this.classList.toggle('active');
                navLinks.classList.toggle('active');
                navActions.classList.toggle('active');
            });
            
            // User Dropdown Toggle cho Mobile
            const userIcons = document.querySelectorAll('.user-icon');
            const userDropdowns = document.querySelectorAll('.user-dropdown');
            const dropdownOverlay = document.querySelector('.dropdown-overlay');
            
            function isMobile() {
                return window.innerWidth <= 768;
            }
            
            userIcons.forEach((userIcon, index) => {
                userIcon.addEventListener('click', function(e) {
                    if (isMobile()) {
                        e.preventDefault();
                        userDropdowns[index].classList.toggle('show-dropdown');
                        dropdownOverlay.classList.toggle('active');
                    }
                });
            });
            
            // Đóng dropdown khi click vào overlay
            dropdownOverlay.addEventListener('click', function() {
                userDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show-dropdown');
                });
                dropdownOverlay.classList.remove('active');
            });
            
            // Đóng menu và dropdown khi resize window
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    userDropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show-dropdown');
                    });
                    dropdownOverlay.classList.remove('active');
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                    navActions.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>