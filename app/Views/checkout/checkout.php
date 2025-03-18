<?php include './app/Views/shares/header.php'; ?>

<div class="checkout-container">
    <h2>Thanh Toán</h2>
    
    <div class="checkout-content">
        <div class="order-summary">
            <h3>Đơn hàng của bạn</h3>
            <div class="order-items">
                <?php if (!empty($cartItems)): ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <span class="item-name"><?php echo $item['name']; ?></span>
                            <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                            <span class="item-price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-amount"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                    </div>
                <?php else: ?>
                    <p class="empty-cart">Không có sản phẩm nào để thanh toán</p>
                <?php endif; ?>
            </div>
        </div>

        <form class="checkout-form" action="/process-checkout" method="POST">
            <h3>Thông tin thanh toán</h3>
            
            <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="payment_method">Phương thức thanh toán:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cod">Thanh toán khi nhận hàng</option>
                    <option value="bank">Chuyển khoản ngân hàng</option>
                    <option value="momo">Ví MoMo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="note">Ghi chú:</label>
                <textarea id="note" name="note"></textarea>
            </div>
            
            <button type="submit" class="place-order-btn">Đặt hàng</button>
        </form>
    </div>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
}

.order-summary {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-items {
    margin-top: 15px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.order-total {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 2px solid #eee;
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    font-size: 1.1em;
}

.checkout-form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.place-order-btn {
    width: 100%;
    padding: 15px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.1em;
}

.place-order-btn:hover {
    background: #45a049;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include './app/Views/shares/footer.php'; ?> 