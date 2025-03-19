<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Models/Menu.php';

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo kết nối database
$db = new Database();
$conn = $db->getConnection();

// Khởi tạo model Menu
$menu = new Menu($conn);

// Lấy thông tin giỏ hàng từ session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

// Chuyển đổi dữ liệu giỏ hàng để hiển thị
$display_items = [];
foreach ($cart_items as $product_id => $item) {
    $product = $menu->getItemById($product_id);
    if ($product) {
        $display_items[] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $item['quantity'],
            'image' => $product['image']
        ];
        $total += $product['price'] * $item['quantity'];
    }
}
?>

<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="checkout-container">
    <h2>Thanh Toán</h2>
    
    <div class="checkout-content">
        <div class="order-summary">
            <h3>Đơn hàng của bạn</h3>
            <div class="order-items">
                <?php if (!empty($display_items)): ?>
                    <?php foreach ($display_items as $item): ?>
                        <div class="order-item">
                            <div class="item-info">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                                <div class="item-details">
                                    <span class="item-name"><?php echo htmlspecialchars($item['name']); ?></span>
                                    <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                                </div>
                            </div>
                            <span class="item-price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-amount"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                    </div>
                <?php else: ?>
                    <p class="empty-cart">Không có sản phẩm nào để thanh toán</p>
                    <a href="/menu" class="continue-shopping">Tiếp tục mua sắm</a>
                <?php endif; ?>
            </div>
        </div>

        <form class="checkout-form" action="/checkout/success" method="POST">
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
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.item-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    display: flex;
    flex-direction: column;
}

.item-name {
    font-weight: 500;
}

.item-quantity {
    color: #666;
    font-size: 0.9em;
}

.item-price {
    font-weight: 500;
    color: #4CAF50;
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

.empty-cart {
    text-align: center;
    color: #666;
    margin: 20px 0;
}

.continue-shopping {
    display: block;
    text-align: center;
    padding: 10px;
    background: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
}

.continue-shopping:hover {
    background: #45a049;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include './app/Views/shares/footer.php'; ?> 