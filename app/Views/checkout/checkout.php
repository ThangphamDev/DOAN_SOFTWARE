<?php
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../../Models/ProductVariant.php';
require_once __DIR__ . '/../../Models/Order.php';
require_once __DIR__ . '/../../Models/OrderItem.php';
require_once __DIR__ . '/../../config/Database.php';

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo kết nối database
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$productVariant = new ProductVariant($db);
$order = new Order($db);
$orderItem = new OrderItem($db);

// Lấy thông tin giỏ hàng từ session
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - CAFET2K</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        .checkout-section {
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
        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .order-summary, .checkout-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        h3 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .order-item {
            display: flex;
            gap: 20px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .item-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
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
            margin: 0 0 8px 0;
            font-size: 1.1rem;
            color: #2c3e50;
        }
        .variant {
            color: #6c757d;
            margin: 5px 0;
            font-size: 0.9rem;
        }
        .quantity {
            color: #6c757d;
            margin: 5px 0;
        }
        .price {
            color: #e74c3c;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 5px 0;
        }
        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .payment-method {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
        }
        .payment-method label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            cursor: pointer;
        }
        .payment-method i {
            font-size: 1.2rem;
            width: 24px;
        }
        .qr-code {
            width: 200px;
            height: auto;
            margin: 0 auto 15px;
            display: block;
            object-fit: contain;
            border-radius: 8px;
            padding: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .bank-info, .momo-info {
            font-size: 0.9rem;
        }
        .bank-info p, .momo-info p {
            margin: 5px 0;
        }
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background: #43A047;
            transform: translateY(-2px);
        }
        @media (max-width: 992px) {
            .checkout-content {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .order-item {
                flex-direction: column;
                text-align: center;
            }
            .item-image {
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }
            .payment-method label {
                flex-direction: column;
                text-align: center;
            }
        }
        .notes-group {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .notes-group:focus-within {
            background: #fff;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }
        .notes-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1.1rem;
        }
        .notes-group label i {
            color: #4CAF50;
            font-size: 1rem;
        }
        .notes-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            transition: all 0.3s ease;
            resize: vertical;
            min-height: 100px;
            background-color: white;
        }
        .notes-group textarea:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }
        .notes-group textarea::placeholder {
            color: #adb5bd;
            font-style: italic;
        }
        .notes-helper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            padding: 0 5px;
        }
        .char-count {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .notes-tip {
            color: #868e96;
            font-size: 0.9rem;
            font-style: italic;
        }
        @media (max-width: 768px) {
            .notes-helper {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
        .payment-details {
            margin-top: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'app/Views/components/header.php'; ?>

    <div class="checkout-section">
        <div class="container">
            <h2>Xác Nhận Đơn Hàng</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="checkout-content">
                <div class="order-summary">
                    <h3>Thông Tin Đơn Hàng</h3>
                    <div class="order-items">
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $item): 
                            $total += $item['price'] * $item['quantity'];
                        ?>
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                         class="product-image"
                                         onerror="this.onerror=null; this.src='/public/images/default-product.jpg'"
                                         loading="lazy">
                                </div>
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <?php if(isset($item['variant_id']) && $item['variant_id']): ?>
                                        <p class="variant">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                    <?php endif; ?>
                                    <p class="quantity">Số lượng: <?php echo $item['quantity']; ?></p>
                                    <p class="price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-amount"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                    </div>
                </div>

                <form action="/checkout/process" method="POST" class="checkout-form">
                    <div class="form-group notes-group">
                        <label for="notes">
                            <i class="fas fa-pencil-alt"></i>
                            Ghi chú đơn hàng:
                        </label>
                        <textarea id="notes" 
                                name="notes" 
                                rows="3" 
                                placeholder="Nhập ghi chú đặc biệt cho đơn hàng của bạn (không bắt buộc)..."
                                maxlength="500"></textarea>
                        <div class="notes-helper">
                            <span class="char-count">0/500</span>
                            <span class="notes-tip">Ví dụ: Cho thêm đá riêng, ít đường, nhiều sữa...</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phương thức thanh toán:</label>
                        <div class="payment-methods">
                            <div class="payment-method">
                                <input type="radio" id="cash" name="payment_method" value="tiền mặt" checked>
                                <label for="cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Thanh toán tại quầy
                                </label>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="bank" name="payment_method" value="chuyển khoản">
                                <label for="bank">
                                    <i class="fas fa-university"></i>
                                    Chuyển khoản ngân hàng
                                </label>
                                <div class="payment-details bank-details" style="display: none;">
                                    <img src="/public/images/bank/bank-qr.jpg" alt="Mã QR Chuyển khoản" class="qr-code">
                                    <div class="bank-info">
                                        <p><strong>Ngân hàng:</strong> MB Bank</p>
                                        <p><strong>Số tài khoản:</strong> 0123456789</p>
                                        <p><strong>Chủ tài khoản:</strong> NGUYEN VAN A</p>
                                        <p><strong>Nội dung CK:</strong> <span id="transferContent">CAFET2K [Số điện thoại]</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-method">
                                <input type="radio" id="momo" name="payment_method" value="momo">
                                <label for="momo">
                                    <i class="fas fa-wallet"></i>
                                    Ví MoMo
                                </label>
                                <div class="payment-details momo-details" style="display: none;">
                                    <img src="/public/images/bank/momo-qr.jpg" alt="Mã QR MoMo" class="qr-code">
                                    <div class="momo-info">
                                        <p><strong>Số điện thoại:</strong> 0987654321</p>
                                        <p><strong>Tên tài khoản:</strong> NGUYEN VAN A</p>
                                        <p><strong>Nội dung CK:</strong> <span id="momoContent">CAFET2K [Số điện thoại]</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Xác Nhận Đặt Hàng</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'app/Views/components/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const bankDetails = document.querySelector('.bank-details');
        const momoDetails = document.querySelector('.momo-details');

        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                bankDetails.style.display = 'none';
                momoDetails.style.display = 'none';

                if (this.value === 'chuyển khoản') {
                    bankDetails.style.display = 'block';
                } else if (this.value === 'momo') {
                    momoDetails.style.display = 'block';
                }
            });
        });

        const notesTextarea = document.getElementById('notes');
        const charCount = document.querySelector('.char-count');
        
        notesTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/500`;
            
            // Add visual feedback when approaching limit
            if (length > 450) {
                charCount.style.color = '#dc3545';
            } else if (length > 400) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#6c757d';
            }
        });
    });
    </script>
</body>
</html> 