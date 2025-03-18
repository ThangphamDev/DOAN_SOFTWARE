
<?php include 'App/Views/Shares/header.php'; ?>
<div class="cart-container">
    <h2>Giỏ Hàng</h2>
    <div class="cart-items">
        <?php if (empty($cartItems)): ?>
            <p class="empty-cart">Giỏ hàng trống</p>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                    <div class="item-details">
                        <h3><?php echo $item['name']; ?></h3>
                        <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrease')">-</button>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" 
                                   onchange="updateQuantity(<?php echo $item['id']; ?>, 'set', this.value)">
                            <button class="qty-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, 'increase')">+</button>
                        </div>
                    </div>
                    <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">×</button>
                </div>
            <?php endforeach; ?>
            
            <div class="cart-summary">
                <div class="total">
                    <span>Tổng cộng:</span>
                    <span class="total-amount"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                </div>
                <a href="/checkout" class="checkout-btn">Thanh Toán</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.cart-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
}

.cart-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.cart-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    flex: 1;
    margin-left: 15px;
}

.price {
    color: #e44d26;
    font-weight: bold;
    font-size: 1.1em;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 30px;
    height: 30px;
    border: none;
    background: #f0f0f0;
    border-radius: 4px;
    cursor: pointer;
}

.remove-btn {
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
}

.cart-summary {
    margin-top: 20px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.total {
    display: flex;
    justify-content: space-between;
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 15px;
}

.checkout-btn {
    display: block;
    width: 100%;
    padding: 15px;
    background: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 4px;
}

.checkout-btn:hover {
    background: #45a049;
}

.empty-cart {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
function updateQuantity(itemId, action, value = null) {
    // Implement quantity update logic
}

function removeItem(itemId) {
    // Implement remove item logic
}
</script>

<?php include './App/Views/Shares/footer.php'; ?>
