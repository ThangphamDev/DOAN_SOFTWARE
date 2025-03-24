<?php
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../../Models/ProductVariant.php';
require_once __DIR__ . '/../../config/Database.php';

// Khởi tạo database connection
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$productVariant = new ProductVariant($db);

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>
<?php include __DIR__ . '/../shares/header.php'; ?>
<div class="cart-section">
    <div class="container">
        <h2>Giỏ Hàng</h2>
        
        <?php if(empty($cart)): ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống</p>
                <a href="/menu" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach($cart as $index => $item): ?>
                    <div class="cart-item">
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
                                <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                            <?php endif; ?>
                            <p class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</p>
                            <div class="quantity-control">
                                <button onclick="updateQuantity(<?php echo $index; ?>, -1)" class="quantity-btn">-</button>
                                <input type="number" 
                                       class="quantity-input" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1"
                                       onchange="updateQuantityDirect(<?php echo $index; ?>, this.value)"
                                       onkeyup="if(event.key === 'Enter') { event.preventDefault(); updateQuantityDirect(<?php echo $index; ?>, this.value); }"
                                       onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                <button onclick="updateQuantity(<?php echo $index; ?>, 1)" class="quantity-btn">+</button>
                            </div>
                            <button onclick="removeItem(<?php echo $index; ?>)" class="btn btn-sm btn-danger">Xóa</button>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="cart-summary">
                    <div class="total">
                        <span>Tổng cộng:</span>
                        <span class="amount"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                    </div>
                    <a href="/checkout" class="btn btn-primary">Thanh Toán</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function updateQuantity(index, change) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/cart/update';

    const indexInput = document.createElement('input');
    indexInput.type = 'hidden';
    indexInput.name = 'index';
    indexInput.value = index;

    const changeInput = document.createElement('input');
    changeInput.type = 'hidden';
    changeInput.name = 'change';
    changeInput.value = change;

    form.appendChild(indexInput);
    form.appendChild(changeInput);
    document.body.appendChild(form);
    form.submit();
}

function updateQuantityDirect(index, newValue) {
    // Convert to integer and ensure it's at least 1
    newValue = Math.max(1, parseInt(newValue) || 1);
    
    // Get current quantity from the input
    const currentInput = document.querySelector(`.cart-item:nth-child(${index + 1}) .quantity-input`);
    if (!currentInput) return;
    
    const currentQuantity = parseInt(currentInput.value) || 1;
    
    // If the new value is different from current value
    if (newValue !== currentQuantity) {
        // Calculate the change needed
        const change = newValue - currentQuantity;
        updateQuantity(index, change);
    }
}

function removeItem(index) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/cart/remove';

        const indexInput = document.createElement('input');
        indexInput.type = 'hidden';
        indexInput.name = 'index';
        indexInput.value = index;

        form.appendChild(indexInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function showToast(type, message) {
    // Remove any existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    // Add toast to page
    document.body.appendChild(toast);

    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add a debugging helper function to show errors in console
function debugLog(message, data) {
    console.log('%c' + message, 'background: #222; color: #bada55');
    if (data) {
        console.log(data);
    }
}
</script>

<style>
.cart-section {
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

.empty-cart {
    text-align: center;
    padding: 50px 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.empty-cart p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.cart-items {
    max-width: 900px;
    margin: 0 auto;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 25px;
    margin-bottom: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.item-image {
    width: 120px;
    height: 120px;
    margin-right: 25px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    flex-shrink: 0;
    background-color: #f8f9fa;
    position: relative;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
    display: block;
}

.item-image img:hover {
    transform: scale(1.05);
}

.item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.item-info h4 {
    margin: 0;
    font-size: 1.25rem;
    color: #2c3e50;
    font-weight: 600;
}

.price {
    color: #e74c3c;
    font-weight: 600;
    font-size: 1.2rem;
    margin: 5px 0;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 10px 0;
}

.quantity-control button {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quantity-control button:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

.quantity {
    min-width: 40px;
    text-align: center;
    font-size: 1.1rem;
    font-weight: 500;
    color: #495057;
}

.btn-danger {
    background-color: #dc3545;
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
    align-self: flex-start;
}

.btn-danger:hover {
    background-color: #c82333;
}

.cart-summary {
    margin-top: 30px;
    padding: 25px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.05);
}

.total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f3f5;
}

.total span {
    font-size: 1.25rem;
    color: #2c3e50;
}

.amount {
    color: #e74c3c;
    font-weight: 700;
    font-size: 1.5rem;
}

.btn-primary {
    display: block;
    width: 100%;
    padding: 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }

    .item-image {
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
    }

    .item-info {
        align-items: center;
    }

    .quantity-control {
        justify-content: center;
    }

    .btn-danger {
        align-self: center;
    }

    .cart-summary {
        margin: 20px 15px;
    }
}

/* Animation cho các nút */
.btn {
    position: relative;
    overflow: hidden;
}

.btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s ease, height 0.3s ease;
}

.btn:active::after {
    width: 200px;
    height: 200px;
    opacity: 0;
}

/* Thêm hiệu ứng loading khi cập nhật */
.loading {
    opacity: 0.7;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Toast styles */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    max-width: 80%;
}

.toast.success {
    background-color: #28a745;
}

.toast.error {
    background-color: #dc3545;
}

.toast.fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Disabled button styles */
.quantity-control button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    font-size: 14px;
    margin: 0 5px;
}

.quantity-input:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
}

/* Remove spinner buttons from number input */
.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?>