<?php
require_once __DIR__.'/../../Config/Database.php';
require_once __DIR__.'/../../Models/Menu.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $menu = new Menu($db);
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Quán Cafe</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <?php include __DIR__.'/../shares/header.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Giỏ Hàng</h1>
        
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <div class="cart-container">
                <div class="cart-items">
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $productId => $item): 
                        // Lấy thông tin sản phẩm từ database
                        $product = $menu->getItemById($productId);
                        if ($product):
                            $subtotal = $product['price'] * $item['quantity'];
                            $total += $subtotal;
                    ?>
                        <div class="cart-item" data-product-id="<?php echo $productId; ?>">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> đ</p>
                                <div class="quantity-controls">
                                    <button class="quantity-btn minus">-</button>
                                    <span class="quantity"><?php echo $item['quantity']; ?></span>
                                    <button class="quantity-btn plus">+</button>
                                </div>
                                <p class="subtotal">Tổng: <?php echo number_format($subtotal, 0, ',', '.'); ?> đ</p>
                            </div>
                            <button class="remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <div class="cart-summary">
                    <h2>Tổng Đơn Hàng</h2>
                    <div class="summary-item">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div class="summary-item">
                        <span>Phí vận chuyển:</span>
                        <span>Miễn phí</span>
                    </div>
                    <div class="summary-item total">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                    <a href="/checkout" class="checkout-btn">Tiến hành đặt hàng</a>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p>Giỏ hàng của bạn đang trống</p>
                <a href="/menu" class="continue-shopping">Tiếp tục mua sắm</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for quantity buttons
            const quantityBtns = document.querySelectorAll('.quantity-btn');
            quantityBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    const productId = item.dataset.productId;
                    const isPlus = this.classList.contains('plus');
                    updateQuantity(productId, isPlus ? 1 : -1);
                });
            });

            // Add event listeners for remove buttons
            const removeBtns = document.querySelectorAll('.remove-item');
            removeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    const productId = item.dataset.productId;
                    removeFromCart(productId);
                });
            });
        });

        function updateQuantity(productId, change) {
            const item = document.querySelector(`[data-product-id="${productId}"]`);
            const quantityElement = item.querySelector('.quantity');
            const priceElement = item.querySelector('.price');
            const subtotalElement = item.querySelector('.subtotal');
            
            let currentQuantity = parseInt(quantityElement.textContent);
            let newQuantity = currentQuantity + change;
            
            if (newQuantity < 1) {
                removeFromCart(productId);
            } else {
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update quantity display
                        quantityElement.textContent = newQuantity;
                        
                        // Update subtotal for this item
                        const price = parseInt(priceElement.textContent.replace(/[^0-9]/g, ''));
                        const subtotal = price * newQuantity;
                        subtotalElement.textContent = 'Tổng: ' + formatCurrency(subtotal);
                        
                        // Update cart summary
                        updateCartSummary();
                        
                        // Update cart count in header if exists
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cart_count;
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi cập nhật số lượng');
                });
            }
        }

        function removeFromCart(productId) {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the item from DOM
                        const itemElement = document.querySelector(`[data-product-id="${productId}"]`);
                        itemElement.remove();
                        
                        // Update cart count in header
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cart_count;
                        }
                        
                        // Update cart summary
                        updateCartSummary();
                        
                        // Show success message
                        alert('Đã xóa sản phẩm khỏi giỏ hàng');
                        
                        // If cart is empty, reload page
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                });
            }
        }

        function updateCartSummary() {
            // Calculate new totals
            let subtotal = 0;
            const items = document.querySelectorAll('.cart-item');
            
            items.forEach(item => {
                const price = parseInt(item.querySelector('.price').textContent.replace(/[^0-9]/g, ''));
                const quantity = parseInt(item.querySelector('.quantity').textContent);
                subtotal += price * quantity;
            });
            
            // Update summary display
            const subtotalElement = document.querySelector('.summary-item:first-child span:last-child');
            const totalElement = document.querySelector('.summary-item.total span:last-child');
            
            if (subtotalElement) subtotalElement.textContent = formatCurrency(subtotal);
            if (totalElement) totalElement.textContent = formatCurrency(subtotal);
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                maximumFractionDigits: 0
            }).format(amount);
        }
    </script>

    <?php include __DIR__.'/../shares/footer.php'; ?>
</body>
</html>
