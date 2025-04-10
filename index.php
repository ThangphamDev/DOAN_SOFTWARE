<?php
session_start();
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Khởi tạo database connection
require_once __DIR__.'/app/config/Database.php';
$database = new Database();
$db = $database->getConnection();

// Load models
require_once __DIR__.'/app/Models/BaseModel.php';
require_once __DIR__.'/app/Models/Order.php';
require_once __DIR__.'/app/Models/OrderItem.php';
require_once __DIR__.'/app/Models/Promotion.php';

// Load controllers
require_once __DIR__.'/app/Controllers/BaseController.php';
require_once __DIR__.'/app/Controllers/CartController.php';
require_once __DIR__.'/app/Controllers/ProductController.php';
require_once __DIR__.'/app/Controllers/AdminController.php';
require_once __DIR__.'/app/Controllers/ProfileController.php';
require_once __DIR__.'/app/Controllers/OrderController.php';
require_once __DIR__.'/app/Controllers/NotificationController.php';
$cartController = new App\Controllers\CartController($db);
$adminController = new AdminController($db);
$profileController = new ProfileController($db);
$orderController = new OrderController($db);
$notificationController = new NotificationController($db);

// Load routes configuration
require_once __DIR__.'/routes/routes.php';

switch ($path) {
    case '/':
        require __DIR__.'/app/Views/home.php';
        break;
        
    case '/menu':
        require __DIR__.'/app/Views/menu/menu.php';
        break;
        
    case (preg_match('/^\/menu\/product\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        $productController = new ProductController($db);
        $productController->detail($productId);
        break;
        
    case '/products':
        header("Location: /menu");
        exit();
        break;
        
    case (preg_match('/^\/products\/detail\/(\d+)$/', $path, $matches) ? true : false):
        $productId = $matches[1];
        header("Location: /menu/product/$productId");
        exit();
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
        
    case (preg_match('/^\/orders\/view\/(\d+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $orderId = $matches[1];
        $orderController->viewOrder($orderId);
        break;
        
    case (preg_match('/^\/orders\/cancel\/(\d+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $orderId = $matches[1];
        $orderController->cancelOrder($orderId);
        break;
        
    case '/profile':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $profileController->index();
        break;
        
    case '/profile/update':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $profileController->update();
        break;
        
    case '/user':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $profileController->index();
        break;
        
    case '/notifications':
        $notificationController->index();
        break;
        
    case '/notifications/mark-as-read':
        $notificationController->markAsRead();
        break;
        
    case '/notifications/mark-all-as-read':
        $notificationController->markAllAsRead();
        break;
        
    case '/notifications/delete':
        $notificationController->delete();
        break;
        
    case '/notifications/unread-count':
        $notificationController->getUnreadCount();
        break;
        
    case '/logout':
        session_destroy();
        header('Location: /');
        exit;
        break;
        
    // Admin Routes
    case '/admin':
        // Check admin authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->dashboard();
        break;
        
    // Admin Products
    case '/admin/products':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->products();
        break;
        
    case '/admin/products/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->createProduct();
        break;
        
    case '/admin/products/store':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->storeProduct();
        } else {
            header('Location: /admin/products/create');
            exit;
        }
        break;
        
    case (preg_match('/^\/admin\/products\/(\d+)\/edit$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $productId = $matches[1];
        $adminController->editProduct($productId);
        break;
        
    case (preg_match('/^\/admin\/products\/(\d+)\/update$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $productId = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateProduct($productId);
        } else {
            header("Location: /admin/products/$productId/edit");
            exit;
        }
        break;
        
    case (preg_match('/^\/admin\/products\/(\d+)\/delete$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $productId = $matches[1];
        $adminController->deleteProduct($productId);
        break;
    
    // Admin Categories
    case '/admin/categories':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->categories();
        break;
        
    case '/admin/categories/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->createCategory();
        break;
        
    case '/admin/categories/store':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->storeCategory();
        } else {
            header('Location: /admin/categories/create');
            exit;
        }
        break;
        
    case (preg_match('/^\/admin\/categories\/(\d+)\/edit$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $categoryId = $matches[1];
        $adminController->editCategory($categoryId);
        break;
        
    case (preg_match('/^\/admin\/categories\/(\d+)\/update$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $categoryId = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateCategory($categoryId);
        } else {
            header("Location: /admin/categories/$categoryId/edit");
            exit;
        }
        break;
        
    case (preg_match('/^\/admin\/categories\/(\d+)\/delete$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $categoryId = $matches[1];
        $adminController->deleteCategory($categoryId);
        break;
    
    // Admin Orders
    case '/admin/orders':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->orders();
        break;
        
    case (preg_match('/^\/admin\/orders\/(\d+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $orderId = $matches[1];
        $adminController->orderDetail($orderId);
        break;
        
    case (preg_match('/^\/admin\/orders\/(\d+)\/status$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $orderId = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateOrderStatus($orderId);
        } else {
            header("Location: /admin/orders/$orderId");
            exit;
        }
        break;
    
    // Admin Users
    case '/admin/users':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->users();
        break;
        
    case (preg_match('/^\/admin\/users\/(\d+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $userId = $matches[1];
        $adminController->userDetail($userId);
        break;
        
    case (preg_match('/^\/admin\/users\/(\d+)\/role$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $userId = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateUserRole($userId);
        } else {
            header("Location: /admin/users/$userId");
            exit;
        }
        break;
        
    case (preg_match('/^\/admin\/users\/(\d+)\/status$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $userId = $matches[1];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminController->updateUserStatus($userId);
        } else {
            header("Location: /admin/users/$userId");
            exit;
        }
        break;
    
    // Admin Reports
    case '/admin/reports':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
        $adminController->reports();
        break;
    
    // Admin Notifications
    case '/admin/notifications':
        $notificationController->adminIndex();
        break;
    
    case '/admin/notifications/create':
        $notificationController->create();
        break;
    
    case '/admin/notifications/store':
        $notificationController->store();
        break;
    
    case '/admin/notifications/delete':
        $notificationController->adminDeleteNotification();
        break;
    
    case '/admin/users/search':
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
            $query = $_GET['q'] ?? '';
            $stmt = $db->prepare("SELECT user_id, username, email, fullname, role FROM users WHERE (username LIKE ? OR email LIKE ? OR fullname LIKE ?) LIMIT 10");
            $searchTerm = "%$query%";
            $stmt->bindParam(1, $searchTerm);
            $stmt->bindParam(2, $searchTerm);
            $stmt->bindParam(3, $searchTerm);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($results);
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['error' => 'Unauthorized']);
        }
        break;
    
    default:
        http_response_code(404);
        require __DIR__.'/app/Views/404.php';
        break;
} 