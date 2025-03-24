<?php
session_start();
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Khởi tạo database connection
require_once __DIR__.'/app/config/Database.php';
$database = new Database();
$db = $database->getConnection();

// Load models
require_once __DIR__.'/app/Models/Order.php';
require_once __DIR__.'/app/Models/OrderItem.php';

// Load controllers
require_once __DIR__.'/app/Controllers/CartController.php';
$cartController = new CartController($db);

switch ($path) {
    case '/':
        require __DIR__.'/app/Views/home.php';
        break;
        
    case '/menu':
        require __DIR__.'/app/Views/menu/menu.php';
        break;
        
    case '/products':
        require __DIR__.'/app/Views/product/product.php';
        break;
        
    case '/cart':
        $cartController->index();
        break;
        
    case '/cart/add':
        $cartController->add();
        break;
        
    case '/cart/update':
        $cartController->update();
        break;
        
    case '/cart/remove':
        $cartController->remove();
        break;
        
    case '/login':
        require __DIR__.'/app/Views/auth/login.php';
        break;
        
    case '/login/process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__.'/app/Controllers/AuthController.php';
            $authController = new AuthController($db);
            $authController->login();
        } else {
            header("Location: /login");
            exit();
        }
        break;
        
    case '/register':
        require __DIR__.'/app/Views/auth/register.php';
        break;
        
    case '/register/process':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__.'/app/Controllers/AuthController.php';
            $authController = new AuthController($db);
            $authController->register();
        } else {
            header("Location: /register");
            exit();
        }
        break;
        
    case '/checkout':
        // Kiểm tra giỏ hàng có trống không
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống";
            header("Location: /cart");
            exit();
        }
        require __DIR__.'/app/Views/checkout/checkout.php';
        break;
        
    case '/checkout/process':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không được phép";
            header("Location: /checkout");
            exit();
        }

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống";
            header("Location: /cart");
            exit();
        }

        require_once __DIR__.'/app/Models/Order.php';
        require_once __DIR__.'/app/Models/OrderItem.php';
        require_once __DIR__.'/app/config/Database.php';

        $database = new Database();
        $db = $database->getConnection();

        // Tạo đơn hàng mới
        $order = new Order($db);
        $order->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $order->total_amount = array_reduce($_SESSION['cart'], function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
        $order->payment_method = $_POST['payment_method'] ?? 'tiền mặt';
        $order->notes = $_POST['notes'] ?? null;

        if ($order->create()) {
            // Thêm các sản phẩm vào chi tiết đơn hàng
            $orderItem = new OrderItem($db);
            $success = true;

            foreach ($_SESSION['cart'] as $item) {
                $orderItem->order_id = $order->order_id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->variant_id = $item['variant_id'] ?? null;
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $item['price'];
                
                if (!$orderItem->create()) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                // Tùy chỉnh thông báo dựa trên phương thức thanh toán
                switch($order->payment_method) {
                    case 'tiền mặt':
                        $_SESSION['success'] = "Đặt hàng thành công! Vui lòng đến quầy để thanh toán và nhận đồ uống của bạn.";
                        break;
                    case 'chuyển khoản':
                        $_SESSION['success'] = "Đặt hàng thành công! Vui lòng chuyển khoản theo thông tin đã cung cấp. Đơn hàng sẽ được xử lý sau khi nhận được thanh toán.";
                        break;
                    case 'momo':
                        $_SESSION['success'] = "Đặt hàng thành công! Vui lòng thanh toán qua Momo theo thông tin đã cung cấp. Đơn hàng sẽ được xử lý sau khi nhận được thanh toán.";
                        break;
                }
                
                header("Location: /order/success");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xử lý đơn hàng";
                header("Location: /checkout");
                exit();
            }
        } else {
            $_SESSION['error'] = "Không thể tạo đơn hàng";
            header("Location: /checkout");
            exit();
        }
        break;
        
    case '/order/success':
        if (!isset($_SESSION['success'])) {
            header("Location: /");
            exit();
        }
        require __DIR__.'/app/Views/order/success.php';
        break;
        
    case '/orders':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require __DIR__.'/app/Views/orders/orders.php';
        break;
        
    case '/profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require __DIR__.'/app/Views/profile/profile.php';
        break;
        
    case '/logout':
        session_destroy();
        header('Location: /');
        exit;
        break;
        
    default:
        http_response_code(404);
        require __DIR__.'/app/Views/404.php';
        break;
} 