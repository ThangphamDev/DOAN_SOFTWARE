<?php
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$categories = $category->read();
$products = $product->read();

// Tạo mảng sản phẩm theo danh mục
$productsByCategory = [];
while ($prod = $products->fetch(PDO::FETCH_ASSOC)) {
    $productsByCategory[$prod['category_id']][] = $prod;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - CAFET2K</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .menu-section {
            padding: 50px 0;
            background-color: #f8f9fa;
            min-height: calc(100vh - 60px);
        }

        .category-sidebar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list li {
            margin-bottom: 10px;
        }

        .category-list li:last-child {
            margin-bottom: 0;
        }

        .category-list a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .category-list a:hover,
        .category-list a.active {
            background-color: #007bff;
            color: white;
        }

        .category-header {
            scroll-margin-top: 80px; /* Để khi scroll có khoảng cách với top */
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .category-header:first-child {
            margin-top: 0;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
            background-color: #f0f0f0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: none;
            will-change: transform;
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        .product-image.error {
            background-color: #f8f8f8;
        }

        .product-image.error img {
            display: none; /* Hide the broken image */
        }

        .product-image.error::after {
            content: attr(data-error);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #666;
            font-size: 0.9rem;
            text-align: center;
            width: 100%;
            padding: 1rem;
        }

        .product-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-info h4 {
            margin: 0 0 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .price {
            color: #e44d26;
            font-weight: bold;
            font-size: 1.2rem;
            margin: 10px 0;
        }

        .description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn {
            flex: 1;
            text-align: center;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-cart {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            color: white;
        }

        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background-color: rgba(40, 167, 69, 0.9);
            color: white;
            padding: 15px 25px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .category-sidebar {
                position: relative;
                top: 0;
                margin-bottom: 20px;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                padding: 10px;
            }
        }
    </style>
    <script>
        function handleImageError(img) {
            const container = img.parentElement;
            container.classList.remove('loading');
            container.classList.add('error');
            container.setAttribute('data-error', img.alt || 'Hình ảnh không khả dụng');
            sessionStorage.setItem('img_error_' + img.src, 'true');
        }

        function handleImageLoad(img) {
            const container = img.parentElement;
            container.classList.remove('loading');
            container.classList.remove('error');
            sessionStorage.removeItem('img_error_' + img.src);
        }

        // Check for cached error states on page load
        window.addEventListener('load', () => {
            document.querySelectorAll('.product-image img').forEach(img => {
                if (sessionStorage.getItem('img_error_' + img.src) === 'true') {
                    handleImageError(img);
                }
            });
        });

        function showToast(type, message) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;

            const container = document.querySelector('.toast-container') || createToastContainer();
            container.appendChild(toast);

            // Hiển thị toast
            requestAnimationFrame(() => {
                toast.style.display = 'block';
                toast.style.opacity = '1';
            });

            // Xóa toast sau 3 giây
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.remove();
                    // Xóa container nếu không còn toast
                    if (!container.hasChildNodes()) {
                        container.remove();
                    }
                }, 300);
            }, 3000);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
            return container;
        }
    </script>
</head>
<body>
    <?php include __DIR__ . '/../shares/header.php'; ?>

    <div class="menu-section">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3">
                    <div class="category-sidebar">
                        <h3>Danh Mục</h3>
                        <ul class="category-list">
                            <?php 
                            // Reset con trỏ của categories
                            $categories->execute();
                            while($cat = $categories->fetch(PDO::FETCH_ASSOC)): 
                                if (isset($productsByCategory[$cat['category_id']])):
                            ?>
                                <li>
                                    <a href="#category-<?php echo $cat['category_id']; ?>" 
                                       class="category-link"
                                       data-category="<?php echo $cat['category_id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endif;
                            endwhile; 
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-md-9">
                    <?php 
                    // Reset con trỏ của categories
                    $categories->execute();
                    while($cat = $categories->fetch(PDO::FETCH_ASSOC)): 
                        if (isset($productsByCategory[$cat['category_id']])): 
                    ?>
                        <div id="category-<?php echo $cat['category_id']; ?>" class="category-section">
                            <h2 class="category-header"><?php echo htmlspecialchars($cat['name']); ?></h2>
                            <div class="products-grid">
                                <?php foreach($productsByCategory[$cat['category_id']] as $prod): ?>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($prod['name']); ?>"
                                                 onerror="handleImageError(this)"
                                                 loading="lazy">
                                        </div>
                                        <div class="product-info">
                                            <h4><?php echo htmlspecialchars($prod['name']); ?></h4>
                                            <p class="price"><?php echo number_format($prod['base_price'], 0, ',', '.'); ?>đ</p>
                                            <p class="description"><?php echo htmlspecialchars($prod['description']); ?></p>
                                            <div class="btn-group">
                                                <a href="/menu/product/<?php echo $prod['product_id']; ?>" class="btn btn-primary">Chi Tiết</a>
                                                <form action="/cart/add" method="POST" style="flex: 1;">
                                                    <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-add-cart">Thêm vào giỏ</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php 
                        endif;
                    endwhile; 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý smooth scroll khi click vào danh mục
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                // Scroll mượt đến phần tử
                targetElement.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });

                // Highlight mục đang active
                document.querySelectorAll('.category-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Highlight danh mục khi scroll
        const observerOptions = {
            root: null,
            rootMargin: '-80px 0px 0px 0px', // Offset để tính toán khi nào section được coi là visible
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const categoryId = entry.target.id;
                    document.querySelectorAll('.category-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + categoryId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, observerOptions);

        // Observe tất cả các category sections
        document.querySelectorAll('.category-section').forEach(section => {
            observer.observe(section);
        });
    });
    </script>

    <?php include __DIR__ . '/../shares/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>