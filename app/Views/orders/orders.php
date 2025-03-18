<?php include '../app/Views/Shares/header.php'; ?>

<div class="orders-container">
    <h2>Đơn Hàng Của Bạn</h2>
    
    <?php if (empty($orders)): ?>
        <p class="empty-orders">Bạn chưa có đơn hàng nào</p>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <span class="order-id">Đơn hàng #<?php echo $order['id']; ?></span>
                            <span class="order-date"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
                        </div>
                        <span class="order-status <?php echo strtolower($order['status']); ?>">
                            <?php echo $order['status']; ?>
                        </span>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                <div class="item-details">
                                    <h4><?php echo $item['name']; ?></h4>
                                    <p>Số lượng: <?php echo $item['quantity']; ?></p>
                                    <p class="item-price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <span>Tổng cộng:</span>
                            <span class="total-amount"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="order-actions">
                            <a href="/orders/<?php echo $order['id']; ?>" class="view-details-btn">Xem chi tiết</a>
                            <?php if ($order['status'] === 'Chờ xác nhận'): ?>
                                <button class="cancel-btn" onclick="cancelOrder(<?php echo $order['id']; ?>)">Hủy đơn</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.orders-container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.order-header {
    padding: 15px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}

.order-info {
    display: flex;
    flex-direction: column;
}

.order-id {
    font-weight: bold;
}

.order-date {
    color: #666;
    font-size: 0.9em;
}

.order-status {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 0.9em;
}

.order-status.pending {
    background: #fff3cd;
    color: #856404;
}

.order-status.confirmed {
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

.order-items {
    padding: 15px;
}

.order-item {
    display: flex;
    gap: 15px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    margin: 0 0 5px 0;
}

.item-price {
    color: #e44d26;
    font-weight: bold;
}

.order-footer {
    padding: 15px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-total {
    font-weight: bold;
}

.total-amount {
    color: #e44d26;
    margin-left: 10px;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.view-details-btn,
.cancel-btn {
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
}

.view-details-btn {
    background: #4CAF50;
    color: white;
    border: none;
}

.cancel-btn {
    background: #dc3545;
    color: white;
    border: none;
}

.empty-orders {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        // Implement cancel order logic
    }
}
</script>

<?php include '../app/Views/Shares/footer.php'; ?> 