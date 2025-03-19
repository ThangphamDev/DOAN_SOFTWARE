<?php
require_once __DIR__.'/../../Config/Database.php';
require_once __DIR__.'/../../Models/Menu.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $menu = new Menu($db);

    // Lấy dữ liệu món ăn theo danh mục
    $coffees = $menu->getItemsByCategory('coffee')->fetchAll(PDO::FETCH_ASSOC);
    $teas = $menu->getItemsByCategory('tea')->fetchAll(PDO::FETCH_ASSOC);
    $smoothies = $menu->getItemsByCategory('smoothie')->fetchAll(PDO::FETCH_ASSOC);
    $desserts = $menu->getItemsByCategory('dessert')->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    $coffees = [];
    $teas = [];
    $smoothies = [];
    $desserts = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Quán Cafe</title>
    <link rel="stylesheet" href="/public/css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <?php include __DIR__.'/../shares/header.php'; ?>
</head>
<body>
    <main class="container">
        <h1>Menu Quán Cafe</h1>
        
        <div class="menu-categories">
            <div class="category">
                <h2>Cà Phê</h2>
                <div class="menu-items">
                    <?php foreach ($coffees as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                <button class="add-to-cart" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category">
                <h2>Trà & Trà Sữa</h2>
                <div class="menu-items">
                    <?php foreach ($teas as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                <button class="add-to-cart" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="category">
                <h2>Smoothie</h2>
                <div class="menu-items">
                    <?php foreach ($smoothies as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                <button class="add-to-cart" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="category">
                <h2>Bánh & Tráng Miệng</h2>
                <div class="menu-items">
                    <?php foreach ($desserts as $item): ?>
                        <div class="menu-item">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="item-info">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                                <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</p>
                                <button class="add-to-cart" data-product-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const toast = document.getElementById('toast');

    // Hàm hiển thị toast
    function showToast(message, type = 'success') {
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Hàm cập nhật số lượng giỏ hàng trên header
    function updateCartCount(count) {
        let cartCount = document.querySelector('.cart-count');

        // Nếu .cart-count chưa tồn tại (giỏ hàng trống ban đầu), tạo mới
        if (!cartCount) {
            const cartLink = document.querySelector('.cart-link');
            if (cartLink) {
                cartCount = document.createElement('span');
                cartCount.className = 'cart-count';
                cartLink.appendChild(cartCount);
            }
        }

        // Cập nhật số lượng và hiển thị
        if (cartCount) {
            cartCount.textContent = count;
            cartCount.style.display = count > 0 ? 'inline-flex' : 'none';
        }
    }

    // Xử lý sự kiện nhấn nút "Thêm vào giỏ"
    addToCartButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1`
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    showToast(data.message);
                    // Cập nhật số lượng giỏ hàng ngay lập tức
                    updateCartCount(data.cart_count);
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        });
    });
});
</script>

    
    <?php include __DIR__.'/../shares/footer.php'; ?>
</body>
</html>