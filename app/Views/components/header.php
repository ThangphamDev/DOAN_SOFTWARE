<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T&2K Coffee - Hương Vị Đắm Say</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css" />
    <style>
        :root {
            --primary: #6F4E37;
            --primary-dark: #5D4030;
            --primary-light: #8B6D4E;
            --secondary: #C0A080;
            --accent: #D4AF37;
            --accent-hover: #BF9C30;
            --dark: #2C1810;
            --light: #F9F6F2;
            --white: #FFFFFF;
            --gray: #EFEFEF;
            --gray-dark: #D8D8D8;
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --rounded-sm: 4px;
            --rounded-md: 8px;
            --rounded-lg: 16px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            background-image: linear-gradient(to right, rgba(247, 247, 247, 0.8), rgba(255, 255, 255, 0.8)), url('/public/images/home/coffee-pattern.png');
            background-size: 200px;
            background-attachment: fixed;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: var(--transition);
            padding: 16px 0;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: var(--shadow);
            border-bottom: 1px solid rgba(111, 78, 55, 0.06);
        }
        
        header.scrolled {
            padding: 10px 0;
            background-color: rgba(255, 255, 255, 0.98);
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Logo Styling */
        .logo {
            display: flex;
            align-items: center;
            position: relative;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
            position: relative;
            padding-left: 12px;
        }
        
        .logo h1::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background-color: var(--accent);
            border-radius: 2px;
        }
        
        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        
        .nav-links a {
            font-weight: 500;
            font-size: 15px;
            color: var(--dark);
            transition: var(--transition);
            position: relative;
            padding: 8px 0;
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary);
            transition: width 0.3s cubic-bezier(0.76, 0, 0.24, 1);
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .nav-links a.active {
            color: var(--primary);
            font-weight: 600;
        }
        
        .nav-links a.active::after {
            width: 100%;
            background-color: var(--accent);
            height: 3px;
        }
        
        /* Action Buttons Group */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        /* Search Box Styling */
        .search-container {
            position: relative;
        }
        
        .search-form {
            display: flex;
            align-items: center;
        }
        
        .search-input {
            border: 1px solid var(--gray);
            border-radius: 24px;
            padding: 8px 16px;
            padding-right: 40px;
            font-size: 14px;
            width: 200px;
            transition: var(--transition);
            background-color: var(--white);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-light);
            width: 240px;
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.12);
        }
        
        .search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            padding: 5px;
            font-size: 14px;
            transition: var(--transition);
        }
        
        .search-btn:hover {
            color: var(--accent);
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Icon Styling */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .icon-wrapper {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(111, 78, 55, 0.05);
            transition: var(--transition);
        }
        
        .icon-wrapper:hover {
            background-color: rgba(111, 78, 55, 0.1);
            transform: translateY(-2px);
        }
        
        .cart-icon, .user-icon, .search-icon, .notification-icon {
            font-size: 16px;
            color: var(--primary);
            transition: var(--transition);
        }
        
        .cart-badge, .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: var(--accent);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        /* User Dropdown Styling */
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 20px;
        }
        
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 15px);
            right: -10px;
            width: 320px;
            background-color: white;
            border-radius: var(--rounded-md);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 24px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(111, 78, 55, 0.08);
        }
        
        .user-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            height: 20px;
        }
        
        .user-dropdown-menu::after {
            content: '';
            position: absolute;
            top: -8px;
            right: 24px;
            width: 16px;
            height: 16px;
            background-color: white;
            transform: rotate(45deg);
            border-top: 1px solid rgba(111, 78, 55, 0.08);
            border-left: 1px solid rgba(111, 78, 55, 0.08);
            z-index: -1;
        }
        
        .user-dropdown:hover .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* User Info in Dropdown */
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .user-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: rgba(111, 78, 55, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary);
            margin-right: 16px;
            border: 2px solid rgba(111, 78, 55, 0.15);
        }
        
        .user-details {
            flex: 1;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
            font-size: 16px;
        }
        
        .user-role {
            font-size: 13px;
            color: var(--primary);
            font-weight: 500;
            background-color: rgba(111, 78, 55, 0.08);
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
        }
        
        .dropdown-divider {
            height: 1px;
            background-color: rgba(111, 78, 55, 0.1);
            margin: 16px 0;
        }
        
        /* Dropdown Actions */
        .dropdown-actions {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .dropdown-actions li {
            margin-bottom: 8px;
        }
        
        .dropdown-actions li:last-child {
            margin-bottom: 0;
        }
        
        .dropdown-actions a {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: var(--dark);
            font-weight: 500;
            border-radius: var(--rounded-sm);
            transition: var(--transition);
        }
        
        .dropdown-actions a:hover {
            background-color: rgba(111, 78, 55, 0.08);
            color: var(--primary);
            transform: translateX(4px);
        }
        
        .dropdown-actions a i {
            margin-right: 12px;
            color: var(--primary);
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        
        /* Login Form in Dropdown */
        .login-dropdown-form h4 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--dark);
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        
        .login-dropdown-form h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background-color: var(--accent);
            border-radius: 2px;
        }
        
        .login-dropdown-form .form-group {
            margin-bottom: 16px;
        }
        
        .login-dropdown-form input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray);
            border-radius: var(--rounded-sm);
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--white);
        }
        
        .login-dropdown-form input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }
        
        .login-btn {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: var(--rounded-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
        }
        
        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 78, 55, 0.2);
        }
        
        .dropdown-footer {
            margin-top: 16px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        
        .dropdown-footer a {
            color: var(--primary);
            font-weight: 600;
            margin-left: 5px;
            transition: var(--transition);
        }
        
        .dropdown-footer a:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        
        .login-error {
            background-color: #FFEBEE;
            color: #D32F2F;
            padding: 10px 16px;
            border-radius: var(--rounded-sm);
            font-size: 13px;
            margin-bottom: 16px;
            border-left: 3px solid #D32F2F;
        }
        
        /* Mobile Menu Toggle */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
            cursor: pointer;
            z-index: 100;
        }
        
        .hamburger span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: var(--primary);
            border-radius: 2px;
            transition: var(--transition);
        }
        
        .hamburger.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }
        
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .nav-links {
                gap: 24px;
            }
            
            .search-input {
                width: 180px;
            }
        }
        
        @media (max-width: 768px) {
            header {
                padding: 12px 0;
            }
            
            .nav-links, .nav-actions {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: var(--white);
                padding: 20px;
                box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
                z-index: 100;
                border-top: 1px solid rgba(111, 78, 55, 0.1);
            }
            
            .nav-links.active, .nav-actions.active {
                display: flex;
                animation: fadeIn 0.3s ease;
            }
            
            .nav-links {
                top: 70px;
            }
            
            .nav-actions {
                top: 300px;
                padding-top: 0;
            }
            
            .search-container {
                width: 100%;
                margin: 15px 0;
            }
            
            .search-input {
                width: 100%;
            }
            
            .nav-icons {
                flex-direction: row;
                justify-content: center;
                gap: 20px;
                margin-top: 15px;
                width: 100%;
            }
            
            .nav-links a {
                padding: 12px 0;
                width: 100%;
                text-align: center;
                border-bottom: 1px solid var(--gray);
            }
            
            .nav-links a:last-child {
                border-bottom: none;
            }
            
            /* Mobile user dropdown */
            .user-dropdown-menu {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 90%;
                max-width: 320px;
                border-radius: var(--rounded-md);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
                z-index: 1001;
            }
            
            .user-dropdown::after,
            .user-dropdown-menu::before,
            .user-dropdown-menu::after {
                display: none;
            }
            
            .user-dropdown:hover .user-dropdown-menu {
                display: none;
                opacity: 0;
                visibility: hidden;
            }
            
            .user-dropdown.show-dropdown .user-dropdown-menu {
                display: block;
                opacity: 1;
                visibility: visible;
                animation: fadeInModal 0.3s ease;
            }
            
            @keyframes fadeInModal {
                from {
                    opacity: 0;
                    transform: translate(-50%, -45%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }
            
            .dropdown-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(5px);
                z-index: 1000;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .dropdown-overlay.active {
                display: block;
                opacity: 1;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .hamburger {
                display: flex;
            }
        }
        
        @media (max-width: 576px) {
            .logo h1 {
                font-size: 20px;
            }
            
            .user-dropdown-menu {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <a href="/">
                        <h1>T&2K Coffee</h1>
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