<?php 
$page = 'cart';
include_once 'app/Views/components/header.php'; 
?>

<!-- Add link to cart.css file -->
<link rel="stylesheet" href="/public/css/cart.css">

<style>
/* Sử dụng namespace để tránh xung đột */
.cart-page {
    padding-top: 30px;
    background-color: #f8f9fa;
}

/* Chỉ áp dụng fixed header cho trang cart */
.cart-page header {
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    transform: none;
    height: auto;
    max-height: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.cart-page header .container {
    max-width: 1200px;
    padding: 0 15px;
    margin: 0 auto;
}

.cart-page header .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
}

.cart-page header .logo h1 {
    font-size: 24px;
    margin: 0;
}

.cart-page .nav-links {
    display: flex;
    align-items: center;
}

.cart-page .nav-actions {
    display: flex;
    align-items: center;
}

/* Toast container không liên quan đến header nên có thể giữ nguyên */
.toast-container {
    z-index: 9999;
}

/* Trang giỏ hàng styles */
.page-title {
    font-size: 28px;
    margin-bottom: 30px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
}

.cart-items {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.cart-item {
    padding: 20px;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-content {
    display: flex;
    align-items: center;
}

.item-image {
    width: 80px;
    height: 80px;
    margin-right: 15px;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    flex: 1;
}

.product-title {
    margin: 0 0 5px;
    font-weight: 600;
    font-size: 16px;
}

.variant-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #f0f0f0;
    border-radius: 4px;
    font-size: 12px;
    margin-right: 5px;
    margin-bottom: 5px;
    color: #555;
}

.item-price {
    font-size: 15px;
    font-weight: 600;
    color: #888;
    margin-top: 5px;
}

.item-quantity {
    margin: 0 20px;
}

.update-form.loading .quantity-control {
    opacity: 0.6;
    pointer-events: none;
}

.quantity-control {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.btn-quantity {
    width: 35px;
    height: 35px;
    background: #f5f5f5;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-quantity:hover {
    background: #e0e0e0;
    color: #333;
}

.quantity-input {
    width: 40px;
    height: 35px;
    border: none;
    text-align: center;
    font-size: 14px;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.item-total {
    text-align: right;
    min-width: 100px;
}

.total-price {
    font-size: 16px;
    font-weight: 700;
    color: #e53935;
    margin-bottom: 8px;
}

.btn-remove {
    background: none;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-remove i {
    color: #888;
    transition: all 0.2s;
}

.btn-remove:hover i {
    color: #e53935;
}

.cart-item.removing {
    opacity: 0.5;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn-continue, .btn-clear {
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-continue {
    background: #fff;
    color: #333;
    border: 1px solid #ddd;
    transition: all 0.3s ease;
}

.btn-continue:hover {
    background: #f0f0f0;
    border-color: #8B4513;
    color: #8B4513;
}

.btn-clear {
    background: none;
    color: #e53935;
    border: 1px solid #e53935;
    transition: all 0.2s;
}

.btn-clear:hover {
    background: #e53935;
    color: #fff;
}

.cart-summary {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.summary-header {
    padding: 15px 20px;
    background: #f5f5f5;
    border-bottom: 1px solid #eee;
}

.summary-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 18px;
}

.summary-body {
    padding: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    font-size: 15px;
}

.promotion-code {
    margin: 20px 0;
}

.promotion-code label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.promo-input-group {
    display: flex;
}

#promo-code {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
}

#apply-promo {
    padding: 8px 15px;
    background: #333;
    color: #fff;
    border: none;
    border-radius: 0 4px 4px 0;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}

#apply-promo:hover {
    background: #000;
}

.promo-message {
    margin-top: 8px;
    font-size: 13px;
}

.promo-message.error {
    color: #e53935;
}

.promo-message.success {
    color: #43a047;
}

.discount-row {
    display: flex;
    justify-content: space-between;
    color: #43a047;
    font-weight: 500;
}

hr {
    margin: 20px 0;
    border: none;
    border-top: 1px solid #eee;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
}

.btn-checkout {
    display: block;
    width: 100%;
    padding: 12px 0;
    background: #e53935;
    color: #fff;
    text-align: center;
    border-radius: 4px;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 15px;
    transition: all 0.2s;
}

.btn-checkout:hover {
    background: #d32f2f;
    color: #fff;
}

.payment-methods {
    margin-top: 20px;
}

.payment-methods p {
    font-size: 14px;
    margin-bottom: 10px;
    color: #666;
}

.payment-icons {
    display: flex;
    gap: 10px;
    font-size: 24px;
    color: #888;
}

.login-prompt, .member-benefits {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 20px;
}

.login-prompt h5, .member-benefits h5 {
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 16px;
}

.login-prompt p, .member-benefits p {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
}

.btn-login, .btn-rewards {
    display: inline-block;
    padding: 8px 15px;
    background: #333;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-login:hover, .btn-rewards:hover {
    background: #000;
    color: #fff;
}

.points-highlight {
    font-weight: 700;
    color: #e53935;
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.empty-cart i {
    font-size: 50px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-cart h3 {
    font-weight: 600;
    margin-bottom: 10px;
}

.empty-cart p {
    color: #666;
    margin-bottom: 20px;
}

/* Responsive cho trang giỏ hàng */
@media (max-width: 767px) {
    .cart-item-content {
        flex-wrap: wrap;
    }
    
    .item-details {
        width: calc(100% - 95px);
    }
    
    .item-quantity {
        margin: 15px 0;
        width: 100%;
    }
    
    .item-total {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-align: left;
    }
}
</style>
<body class="cart-page">
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Giỏ Hàng</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Giỏ hàng của bạn đang trống</h3>
                    <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                    <a href="/products" class="btn-continue">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php 
                    $subtotal = 0;
                    foreach ($_SESSION['cart'] as $index => $item): 
                        $itemSubtotal = $item['price'] * $item['quantity'];
                        $subtotal += $itemSubtotal;
                    ?>
                        <div class="cart-item">
                            <div class="cart-item-content">
                                <div class="item-image">
                                    <img src="<?php echo !empty($item['image_url']) ? $item['image_url'] : '/public/images/no-image.jpg'; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                <div class="item-details">
                                    <h5 class="product-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <div class="variants">
                                        <?php if (isset($item['variants']) && !empty($item['variants'])): ?>
                                            <?php foreach ($item['variants'] as $type => $value): ?>
                                                <span class="variant-badge"><?php echo htmlspecialchars($type); ?>: <?php echo htmlspecialchars($value); ?></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</div>
                                </div>
                                <div class="item-quantity">
                                    <form action="/cart/update" method="post" class="update-form" data-index="<?php echo $index; ?>">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <div class="quantity-control">
                                            <button type="button" class="btn-quantity decrease">-</button>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="0" class="quantity-input">
                                            <button type="button" class="btn-quantity increase">+</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="item-total">
                                    <div class="total-price"><?php echo number_format($itemSubtotal, 0, ',', '.'); ?> đ</div>
                                    <form action="/cart/remove" method="post" class="d-inline">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" class="btn-remove"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-actions">
                    <a href="/products" class="btn-continue"><i class="fas fa-arrow-left mr-1"></i> Tiếp tục mua sắm</a>
                    <form action="/cart/clear" method="post">
                        <button type="submit" class="btn-clear"><i class="fas fa-trash mr-1"></i> Xóa giỏ hàng</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="cart-summary">
                    <div class="summary-header">
                        <h5>Tóm Tắt Đơn Hàng</h5>
                    </div>
                    <div class="summary-body">
                        <div class="summary-item">
                            <span>Tạm tính:</span>
                            <span id="cart-subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</span>
                        </div>
                        
                        <div class="promotion-code">
                            <label for="promo-code">Mã giảm giá:</label>
                            <div class="promo-input-group">
                                <input type="text" id="promo-code" placeholder="Nhập mã giảm giá">
                                <button id="apply-promo" type="button">Áp dụng</button>
                            </div>
                            <div id="promo-message" class="promo-message"></div>
                        </div>
                        
                        <div class="promotion-applied d-none" id="promotion-details">
                            <div class="discount-row">
                                <span>Giảm giá:</span>
                                <span id="discount-amount">0 đ</span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="summary-total">
                            <span>Tổng cộng:</span>
                            <span id="cart-total"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</span>
                        </div>
                        
                        <a href="/checkout" class="btn-checkout">
                            Tiến hành thanh toán <i class="fas fa-arrow-right"></i>
                        </a>
                        
                        <div class="payment-methods">
                            <p>Phương thức thanh toán:</p>
                            <div class="payment-icons">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-paypal"></i>
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="login-prompt">
                        <h5>Đăng nhập để tích điểm</h5>
                        <p>Đăng nhập để tích điểm và nhận ưu đãi khi mua hàng.</p>
                        <a href="/login" class="btn-login">Đăng nhập ngay</a>
                    </div>
                <?php else: ?>
                    <div class="member-benefits">
                        <h5>Thành viên</h5>
                        <p>Bạn sẽ nhận được <span class="points-highlight"><?php echo floor($subtotal / 10000); ?> điểm</span> từ đơn hàng này.</p>
                        <a href="/account/rewards" class="btn-rewards">Xem ưu đãi</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thêm toast container
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }

    // Tạo hàm hiển thị toast
    function showToast(type, message) {
        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        
        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toastElement);
        
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Function to update cart item quantity
    function updateCartItemQuantity(index, quantity) {
        // Nếu số lượng là 0, hỏi xác nhận xóa
        if (quantity === 0) {
            if (confirm('Bạn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                // Nếu đồng ý xóa, gọi hàm xóa sản phẩm
                removeCartItem(index);
                return;
            } else {
                // Nếu không đồng ý, đặt lại giá trị là 1
                const form = document.querySelector(`.update-form[data-index="${index}"]`);
                if (form) {
                    const inputElement = form.querySelector('input[name="quantity"]');
                    inputElement.value = 1;
                }
                return;
            }
        }
        
        // Create FormData with index and quantity
        const formData = new FormData();
        formData.append('index', index);
        formData.append('quantity', quantity);
        
        // Add a loading indicator
        const form = document.querySelector(`.update-form[data-index="${index}"]`);
        if (form) {
            form.classList.add('loading');
        }
        
        // Send AJAX request
        fetch('/cart/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update subtotal and total in the cart summary
                const subtotalElement = document.getElementById('cart-subtotal');
                const totalElement = document.getElementById('cart-total');
                
                if (subtotalElement && data.subtotal) {
                    subtotalElement.textContent = data.subtotal + ' đ';
                }
                
                if (totalElement && data.total) {
                    totalElement.textContent = data.total + ' đ';
                }
                
                // Update item subtotal
                const itemElement = form.closest('.cart-item');
                if (itemElement && data.item_subtotal) {
                    const totalPriceElement = itemElement.querySelector('.total-price');
                    if (totalPriceElement) {
                        totalPriceElement.textContent = data.item_subtotal + ' đ';
                    }
                }
                
                // Show success toast
                showToast('success', 'Giỏ hàng đã được cập nhật');
            } else {
                // Show error toast
                showToast('error', data.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng');
                
                // Revert to previous quantity
                const inputElement = form.querySelector('input[name="quantity"]');
                if (inputElement && data.original_quantity) {
                    inputElement.value = data.original_quantity;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra khi cập nhật giỏ hàng');
        })
        .finally(() => {
            // Remove loading indicator
            if (form) {
                form.classList.remove('loading');
            }
        });
    }

    // Function to remove cart item
    function removeCartItem(index) {
        // Create FormData with index
        const formData = new FormData();
        formData.append('index', index);
        
        // Add a loading indicator
        const form = document.querySelector(`.update-form[data-index="${index}"]`);
        const itemElement = form.closest('.cart-item');
        if (itemElement) {
            itemElement.classList.add('removing');
        }
        
        // Send AJAX request
        fetch('/cart/remove', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge && data.cart_count !== undefined) {
                    cartBadge.textContent = data.cart_count;
                }
                
                // Remove item from DOM
                if (itemElement) {
                    itemElement.remove();
                }
                
                // Update subtotal and total
                const subtotalElement = document.getElementById('cart-subtotal');
                const totalElement = document.getElementById('cart-total');
                
                if (subtotalElement && data.subtotal) {
                    subtotalElement.textContent = data.subtotal + ' đ';
                }
                
                if (totalElement && data.total) {
                    totalElement.textContent = data.total + ' đ';
                }
                
                // Show success toast
                showToast('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
                
                // If cart is empty, reload page to show empty cart message
                if (data.cart_count === 0) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showToast('error', data.message || 'Có lỗi xảy ra khi xóa sản phẩm');
                if (itemElement) {
                    itemElement.classList.remove('removing');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra khi xóa sản phẩm');
            if (itemElement) {
                itemElement.classList.remove('removing');
            }
        });
    }

    // Quantity buttons and inputs
    const decreaseBtns = document.querySelectorAll('.decrease');
    const increaseBtns = document.querySelectorAll('.increase');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    // Handle decrease button click
    decreaseBtns.forEach((btn) => {
        btn.addEventListener('click', function() {
            const form = this.closest('.update-form');
            const inputElement = form.querySelector('input[name="quantity"]');
            const index = form.dataset.index;
            
            let newValue = parseInt(inputElement.value) - 1;
            if (newValue < 0) newValue = 0;
            
            inputElement.value = newValue;
            // Auto update on change
            updateCartItemQuantity(index, newValue);
        });
    });
    
    // Handle increase button click
    increaseBtns.forEach((btn) => {
        btn.addEventListener('click', function() {
            const form = this.closest('.update-form');
            const inputElement = form.querySelector('input[name="quantity"]');
            const index = form.dataset.index;
            
            let newValue = parseInt(inputElement.value) + 1;
            inputElement.value = newValue;
            // Auto update on change
            updateCartItemQuantity(index, newValue);
        });
    });
    
    // Handle direct input change
    quantityInputs.forEach((input) => {
        // When focus is lost (blur)
        input.addEventListener('blur', function() {
            const form = this.closest('.update-form');
            const index = form.dataset.index;
            let value = parseInt(this.value);
            
            // Validate input
            if (isNaN(value) || value < 0) {
                value = 0;
                this.value = 0;
            }
            
            // Auto update on blur
            updateCartItemQuantity(index, value);
        });
        
        // When Enter key is pressed
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                this.blur(); // Trigger the blur event
            }
        });
    });
    
    // Promotion code
    const promoCodeInput = document.getElementById('promo-code');
    const applyPromoBtn = document.getElementById('apply-promo');
    const promoMessage = document.getElementById('promo-message');
    const promotionDetails = document.getElementById('promotion-details');
    const discountAmount = document.getElementById('discount-amount');
    const cartTotal = document.getElementById('cart-total');
    const subtotalAmount = parseFloat('<?php echo $subtotal ?? 0; ?>');
    
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            const promoCode = promoCodeInput.value.trim().toUpperCase();
            
            if (!promoCode) {
                promoMessage.textContent = 'Vui lòng nhập mã giảm giá';
                promoMessage.classList.add('error');
                return;
            }
            
            // Mã khuyến mãi mẫu
            const promotions = {
                'WELCOME10': { type: 'percentage', value: 10, minOrder: 50000, maxDiscount: 30000 },
                'FREESHIP': { type: 'fixed', value: 15000, minOrder: 100000 },
                'SUMMER23': { type: 'percentage', value: 15, minOrder: 0, maxDiscount: 50000 },
                'THANKYOU': { type: 'fixed', value: 30000, minOrder: 200000 }
            };
            
            if (promotions[promoCode]) {
                const promo = promotions[promoCode];
                
                if (subtotalAmount < promo.minOrder) {
                    promoMessage.textContent = `Đơn hàng tối thiểu ${new Intl.NumberFormat('vi-VN').format(promo.minOrder)}đ để áp dụng mã này`;
                    promoMessage.classList.add('error');
                    promoMessage.classList.remove('success');
                    return;
                }
                
                let discount = 0;
                if (promo.type === 'percentage') {
                    discount = subtotalAmount * (promo.value / 100);
                    if (promo.maxDiscount && discount > promo.maxDiscount) {
                        discount = promo.maxDiscount;
                    }
                } else {
                    discount = promo.value;
                }
                
                const totalAfterDiscount = subtotalAmount - discount;
                
                // Update UI
                discountAmount.textContent = `-${new Intl.NumberFormat('vi-VN').format(discount)}đ`;
                cartTotal.textContent = `${new Intl.NumberFormat('vi-VN').format(totalAfterDiscount)}đ`;
                promotionDetails.classList.remove('d-none');
                
                promoMessage.textContent = `Mã "${promoCode}" đã được áp dụng`;
                promoMessage.classList.add('success');
                promoMessage.classList.remove('error');
                
                // Store promo code in session
                localStorage.setItem('promoCode', promoCode);
                localStorage.setItem('discount', discount);
            } else {
                promoMessage.textContent = 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn';
                promoMessage.classList.add('error');
                promoMessage.classList.remove('success');
            }
        });
    }
});
</script>

<?php include_once 'app/Views/components/footer.php'; ?> 