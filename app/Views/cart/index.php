<?php 
$page = 'cart';
include_once 'app/Views/components/header.php'; 
?>

<!-- Add link to cart.css file -->
<link rel="stylesheet" href="/public/css/cart.css">

<style>
body {
    padding-top: 90px !important;
}

header {
    position: fixed !important;
    width: 100% !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1000 !important;
    transform: none !important;
    height: auto !important;
    max-height: none !important;
}

header .container {
    max-width: 1200px !important;
    margin: 0 auto !important;
}

header .navbar {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
}

header .logo h1 {
    font-size: 24px !important;
    margin: 0 !important;
}

.nav-links {
    display: flex !important;
    align-items: center !important;
}

.nav-actions {
    display: flex !important;
    align-items: center !important;
}
</style>

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
                                    <form action="/cart/update" method="post" class="update-form">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <div class="quantity-control">
                                            <button type="button" class="btn-quantity decrease">-</button>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" readonly>
                                            <button type="button" class="btn-quantity increase">+</button>
                                        </div>
                                        <button type="submit" class="btn-update update-cart"><i class="fas fa-sync-alt"></i></button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons
    const decreaseBtns = document.querySelectorAll('.decrease');
    const increaseBtns = document.querySelectorAll('.increase');
    const quantityInputs = document.querySelectorAll('.quantity-control input');
    const updateBtns = document.querySelectorAll('.btn-update');
    
    decreaseBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            if (parseInt(quantityInputs[index].value) > 1) {
                quantityInputs[index].value = parseInt(quantityInputs[index].value) - 1;
                updateBtns[index].classList.add('pending');
            }
        });
    });
    
    increaseBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            if (parseInt(quantityInputs[index].value) < 10) {
                quantityInputs[index].value = parseInt(quantityInputs[index].value) + 1;
                updateBtns[index].classList.add('pending');
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