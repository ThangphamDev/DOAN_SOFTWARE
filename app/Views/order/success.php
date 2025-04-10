<?php include 'app/Views/components/header.php'; ?>

<div class="success-section">
    <div class="container">
        <div class="success-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Đặt Hàng Thành Công!</h1>
            <p class="message"><?php echo $_SESSION['success']; ?></p>
            <div class="buttons">
                <a href="/menu" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/orders" class="btn btn-outline">Xem Đơn Hàng</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.success-section {
    padding: 80px 0;
    min-height: calc(100vh - 60px);
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.success-content {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.success-icon {
    font-size: 80px;
    color: #28a745;
    margin-bottom: 24px;
}

.success-icon i {
    animation: scaleIn 0.5s ease-out;
}

h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 16px;
    font-weight: 600;
}

.message {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 32px;
    line-height: 1.6;
}

.buttons {
    display: flex;
    gap: 16px;
    justify-content: center;
}

.btn {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

.btn-outline {
    border: 2px solid #007bff;
    color: #007bff;
    background: transparent;
}

.btn-outline:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .success-section {
        padding: 40px 0;
    }

    .success-content {
        padding: 30px 20px;
    }

    h1 {
        font-size: 2rem;
    }

    .buttons {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>

<?php
// Clear success message after displaying
unset($_SESSION['success']);
?>

<?php include 'app/Views/components/footer.php'; ?> 