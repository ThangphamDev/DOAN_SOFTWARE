<?php
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../config/Database.php';

// Khởi tạo database connection
$database = new Database();
$db = $database->getConnection();

$order = new Order($db);

// Lấy danh sách đơn hàng của user hiện tại
$orders = [];
if (isset($_SESSION['user_id'])) {
    $orders = $order->getOrdersByUserId($_SESSION['user_id']);
}

include __DIR__ . '/../shares/header.php';
?>

<div class="orders-section">
    <div class="container">
        <h2>Đơn Hàng Của Bạn</h2>
        
        <?php if(empty($orders)): ?>
            <div class="empty-orders">
                <p>Bạn chưa có đơn hàng nào</p>
                <a href="/menu" class="btn btn-primary">Đặt Hàng Ngay</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach($orders as $order): ?>
                    <div class="order-item">
                        <div class="order-header">
                            <div class="order-info">
                                <span class="order-id">Đơn hàng #<?php echo $order['order_id']; ?></span>
                                <span class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></span>
                            </div>
                            <div class="order-status <?php echo strtolower($order['status']); ?>">
                                <?php echo $order['status']; ?>
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="order-items">
                                <?php foreach($order['items'] as $item): ?>
                                    <div class="order-item-detail">
                                        <div class="item-image">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                 onerror="this.onerror=null; this.src='/public/images/default-product.jpg'">
                                        </div>
                                        <div class="item-info">
                                            <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                            <?php if(isset($item['size'])): ?>
                                                <p class="item-size">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                            <?php endif; ?>
                                            <p class="item-quantity">Số lượng: <?php echo $item['quantity']; ?></p>
                                            <p class="item-price"><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="order-summary">
                                <div class="total">
                                    <span>Tổng cộng:</span>
                                    <span class="amount"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="payment-info">
                                    <p>Phương thức thanh toán: <?php echo $order['payment_method']; ?></p>
                                    <p>Trạng thái thanh toán: <?php echo $order['payment_status']; ?></p>
                                </div>
                                <?php if($order['notes']): ?>
                                    <div class="order-notes">
                                        <p>Ghi chú: <?php echo htmlspecialchars($order['notes']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.orders-section {
    padding: 50px 0;
    min-height: calc(100vh - 60px);
    background-color: #f8f9fa;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

h2 {
    color: #2c3e50;
    font-size: 2rem;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 600;
}

.empty-orders {
    text-align: center;
    padding: 50px 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.empty-orders p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.orders-list {
    max-width: 900px;
    margin: 0 auto;
}

.order-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.order-info {
    display: flex;
    gap: 20px;
    align-items: center;
}

.order-id {
    font-weight: 600;
    color: #2c3e50;
}

.order-date {
    color: #6c757d;
}

.order-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.order-status.pending {
    background: #fff3cd;
    color: #856404;
}

.order-status.processing {
    background: #cce5ff;
    color: #004085;
}

.order-status.completed {
    background: #d4edda;
    color: #155724;
}

.order-status.cancelled {
    background: #f8d7da;
    color: #721c24;
}

.order-details {
    padding: 20px;
}

.order-items {
    margin-bottom: 20px;
}

.order-item-detail {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.order-item-detail:last-child {
    border-bottom: none;
}

.item-image {
    width: 80px;
    height: 80px;
    margin-right: 20px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-info {
    flex: 1;
}

.item-info h4 {
    margin: 0 0 5px;
    font-size: 1.1rem;
    color: #2c3e50;
}

.item-size, .item-quantity {
    color: #6c757d;
    margin: 3px 0;
}

.item-price {
    color: #e74c3c;
    font-weight: 600;
    margin: 5px 0 0;
}

.order-summary {
    padding-top: 20px;
    border-top: 2px solid #f1f3f5;
}

.total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.total span {
    font-size: 1.1rem;
    color: #2c3e50;
}

.amount {
    color: #e74c3c;
    font-weight: 700;
    font-size: 1.3rem;
}

.payment-info p {
    color: #6c757d;
    margin: 5px 0;
}

.order-notes {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.order-notes p {
    color: #6c757d;
    font-style: italic;
}

.btn-primary {
    display: inline-block;
    padding: 12px 24px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .order-info {
        flex-direction: column;
        gap: 5px;
    }

    .order-item-detail {
        flex-direction: column;
        text-align: center;
    }

    .item-image {
        margin: 0 auto 15px;
    }

    .total {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?> 