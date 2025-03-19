<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../Models/Menu.php';

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa giỏ hàng sau khi thanh toán thành công
unset($_SESSION['cart']);
?>

<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="success-container">
    <div class="success-content">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Đặt Hàng Thành Công!</h1>
        <p>Cảm ơn bạn đã đặt hàng tại Coffee T&2K</p>
        <p>Mã đơn hàng của bạn là: <span class="order-id">#<?php echo date('YmdHis'); ?></span></p>
        <div class="success-actions">
            <a href="/menu" class="continue-shopping">Tiếp tục mua sắm</a>
            <a href="/orders" class="view-orders">Xem đơn hàng</a>
        </div>
    </div>
</div>

<style>
.success-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background-color: #f8f9fa;
}

.success-content {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.success-icon {
    font-size: 80px;
    color: #4CAF50;
    margin-bottom: 20px;
}

.success-icon i {
    animation: scale-in 0.5s ease-out;
}

h1 {
    color: #333;
    margin-bottom: 15px;
    font-size: 28px;
}

p {
    color: #666;
    margin-bottom: 10px;
    font-size: 16px;
}

.order-id {
    background: #f0f0f0;
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
    color: #333;
}

.success-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.continue-shopping,
.view-orders {
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.continue-shopping {
    background: #4CAF50;
    color: white;
}

.view-orders {
    background: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
}

.continue-shopping:hover {
    background: #45a049;
    transform: translateY(-2px);
}

.view-orders:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

@keyframes scale-in {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 480px) {
    .success-content {
        padding: 20px;
    }
    
    .success-actions {
        flex-direction: column;
    }
    
    .continue-shopping,
    .view-orders {
        width: 100%;
    }
}
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?> 