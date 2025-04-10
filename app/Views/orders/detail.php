<?php
// File: app/Views/orders/detail.php
// View hiển thị chi tiết đơn hàng cho khách hàng

// Kiểm tra xem biến $order đã được truyền từ controller
if (!isset($order)) {
    header("Location: /profile");
    exit();
}

// Header và CSS
include_once __DIR__ . '/../../Views/components/header.php';
?>

<div class="container order-detail-container">
    <div class="page-header">
        <h1>Chi tiết đơn hàng #<?php echo $order['order_id']; ?></h1>
        <a href="/profile" class="btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    
    <div class="order-detail-wrapper">
        <!-- Order summary -->
        <div class="order-summary card">
            <div class="card-header">
                <h2>Thông tin đơn hàng</h2>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span class="item-label">Mã đơn hàng:</span>
                    <span class="item-value">#<?php echo $order['order_id']; ?></span>
                </div>
                <div class="summary-item">
                    <span class="item-label">Ngày đặt hàng:</span>
                    <span class="item-value"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></span>
                </div>
                <div class="summary-item">
                    <span class="item-label">Tổng tiền:</span>
                    <span class="item-value price"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</span>
                </div>
                <div class="summary-item">
                    <span class="item-label">Trạng thái:</span>
                    <span class="item-value status status-<?php echo strtolower($order['status']); ?>">
                        <?php 
                        $status_text = $order['status'];
                        switch($order['status']) {
                            case 'pending':
                            case 'đang chờ':
                                $status_text = 'Đang chờ';
                                break;
                            case 'processing':
                                $status_text = 'Đang xử lý';
                                break;
                            case 'completed':
                                $status_text = 'Hoàn thành';
                                break;
                            case 'cancelled':
                                $status_text = 'Đã hủy';
                                break;
                        }
                        echo $status_text;
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="item-label">Phương thức thanh toán:</span>
                    <span class="item-value"><?php echo $order['payment_method']; ?></span>
                </div>
                <div class="summary-item">
                    <span class="item-label">Trạng thái thanh toán:</span>
                    <span class="item-value status status-<?php echo strtolower($order['payment_status']); ?>">
                        <?php 
                        $payment_status_text = $order['payment_status'];
                        switch($order['payment_status']) {
                            case 'pending':
                            case 'đang chờ':
                                $payment_status_text = 'Chờ thanh toán';
                                break;
                            case 'paid':
                                $payment_status_text = 'Đã thanh toán';
                                break;
                            case 'failed':
                                $payment_status_text = 'Thanh toán thất bại';
                                break;
                        }
                        echo $payment_status_text;
                        ?>
                    </span>
                </div>
                <?php if (!empty($order['notes'])): ?>
                <div class="summary-item">
                    <span class="item-label">Ghi chú:</span>
                    <span class="item-value"><?php echo htmlspecialchars($order['notes']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Order items -->
        <div class="order-items card">
            <div class="card-header">
                <h2>Sản phẩm</h2>
            </div>
            <div class="card-body">
                <div class="items-table">
                    <div class="items-header">
                        <div class="item-col item-product">Sản phẩm</div>
                        <div class="item-col item-price">Đơn giá</div>
                        <div class="item-col item-quantity">Số lượng</div>
                        <div class="item-col item-total">Thành tiền</div>
                    </div>
                    
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="item-row">
                        <div class="item-col item-product">
                            <div class="product-info">
                                <span class="product-name"><?php echo htmlspecialchars($item['name'] ?? 'Không có tên'); ?></span>
                                <?php if(!empty($item['size_name'])): ?>
                                <span class="product-variant"><?php echo htmlspecialchars($item['size_name']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="item-col item-price"><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>đ</div>
                        <div class="item-col item-quantity"><?php echo $item['quantity']; ?></div>
                        <div class="item-col item-total"><?php echo number_format($item['unit_price'] * $item['quantity'], 0, ',', '.'); ?>đ</div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-amount"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action buttons -->
        <div class="order-actions">
            <?php if ($order['status'] === 'pending' || $order['status'] === 'đang chờ'): ?>
            <a href="/orders/cancel/<?php echo $order['order_id']; ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">Hủy đơn hàng</a>
            <?php endif; ?>
            
            <?php if ($order['status'] === 'completed'): ?>
            <a href="/feedback/create/<?php echo $order['order_id']; ?>" class="btn btn-primary">Đánh giá</a>
            <?php endif; ?>
            
            <a href="javascript:window.print();" class="btn btn-outline">In đơn hàng</a>
        </div>
    </div>
</div>

<style>
    .order-detail-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 0 15px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .btn-back {
        padding: 8px 15px;
        background-color: #f5f5f5;
        border-radius: 4px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
    }
    
    .btn-back:hover {
        background-color: #e0e0e0;
    }
    
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .card-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .card-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .summary-item {
        display: flex;
        margin-bottom: 15px;
    }
    
    .item-label {
        width: 200px;
        font-weight: 500;
        color: #666;
    }
    
    .price {
        font-weight: 600;
        color: #e53935;
    }
    
    .status {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-block;
    }
    
    .status-pending, .status-đang-chờ {
        background-color: #ffecb3;
        color: #ff8f00;
    }
    
    .status-processing, .status-đang-xử-lý {
        background-color: #b3e5fc;
        color: #0277bd;
    }
    
    .status-completed, .status-hoàn-thành {
        background-color: #c8e6c9;
        color: #2e7d32;
    }
    
    .status-cancelled, .status-đã-hủy {
        background-color: #ffcdd2;
        color: #c62828;
    }
    
    .status-paid, .status-đã-thanh-toán {
        background-color: #c8e6c9;
        color: #2e7d32;
    }
    
    .items-table {
        width: 100%;
    }
    
    .items-header {
        display: flex;
        background-color: #f5f5f5;
        padding: 15px;
        font-weight: 600;
        border-radius: 4px;
    }
    
    .item-row {
        display: flex;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .item-col {
        display: flex;
        align-items: center;
    }
    
    .item-product {
        flex: 2;
    }
    
    .item-price, .item-quantity, .item-total {
        flex: 1;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
    }
    
    .product-variant {
        font-size: 14px;
        color: #757575;
        margin-top: 5px;
    }
    
    .order-total {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 20px 15px;
        font-weight: 600;
    }
    
    .total-amount {
        margin-left: 15px;
        font-size: 20px;
        color: #e53935;
    }
    
    .order-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        border: none;
    }
    
    .btn-primary {
        background-color: #1976d2;
        color: white;
    }
    
    .btn-cancel {
        background-color: #e53935;
        color: white;
    }
    
    .btn-outline {
        background-color: transparent;
        border: 1px solid #ddd;
        color: #666;
    }
    
    .btn:hover {
        opacity: 0.9;
    }
    
    @media print {
        .btn, .btn-back, .order-actions {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .items-header {
            display: none;
        }
        
        .item-row {
            flex-direction: column;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        
        .item-col {
            padding: 5px 0;
        }
        
        .item-col:before {
            content: attr(data-label);
            font-weight: 600;
            width: 120px;
            display: inline-block;
        }
        
        .order-total {
            flex-direction: column;
            align-items: flex-end;
        }
        
        .total-amount {
            margin-top: 5px;
            margin-left: 0;
        }
    }
</style>

<?php
// Footer
include_once __DIR__ . '/../../Views/components/footer.php';
?> 