<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/ProductVariant.php';
require_once __DIR__ . '/../Models/BaseModel.php';
require_once __DIR__ . '/../Models/Promotion.php';
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../config/Database.php';

class CartController extends BaseController {
    private $product;
    private $productVariant;
    private $promotionModel;
    private $cart;
    protected $db;

    public function __construct($db = null) {
        parent::__construct();
        $this->product = new \Product($this->db);
        $this->productVariant = new \ProductVariant($this->db);
        $this->promotionModel = new \App\Models\Promotion($this->db);
        $database = new \Database();
        $this->db = $database->getConnection();
        $this->cart = new \Cart($this->db);
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Hiển thị trang giỏ hàng
     */
    public function index() {
        $this->ensureCartSession();
        $this->view('cart/index');
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add() {
        $this->ensureCartSession();
        
        // Kiểm tra request Ajax
        $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        // Hỗ trợ cả JSON từ Ajax và form truyền thống
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($isAjaxRequest || isset($_POST['ajax'])) {
                // Xử lý dữ liệu JSON hoặc form data cho AJAX
                $data = [];
                
                if ($isAjaxRequest && $this->isJsonRequest()) {
                    $data = $this->getJsonInput();
                } else {
                    $data = $_POST;
                }
                
                $product_id = $data['product_id'] ?? null;
                $quantity = isset($data['quantity']) ? (int)$data['quantity'] : 1;
                
                // Lấy thông tin sản phẩm từ database
                $this->product->product_id = $product_id;
                if ($this->product->readOne()) {
                    $name = $this->product->name;
                    $price = $this->product->base_price;
                    $image_url = $this->product->image_url;
                    
                    // Tính giá khi có biến thể
                    $additionalPrice = 0;
                    $variants = [];
                    
                    if (isset($data['variants']) && is_array($data['variants'])) {
                        foreach ($data['variants'] as $variant) {
                            if (isset($variant['type']) && isset($variant['value'])) {
                                $variantType = $variant['type'];
                                $variantValue = $variant['value'];
                                $variantPrice = isset($variant['price']) ? (float)$variant['price'] : 0;
                                
                                $variants[$variantType] = $variantValue;
                                $additionalPrice += $variantPrice;
                            }
                        }
                    }
                    
                    // Tính tổng giá
                    $totalPrice = $price + $additionalPrice;
                    
                    // Thêm vào giỏ hàng
                    $this->addItemToCart($product_id, $name, $totalPrice, $quantity, $image_url, $variants);
                    
                    // Trả về kết quả
                    $response = [
                        'success' => true,
                        'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                        'cart_count' => $this->getCartCount(),
                        'cart_total' => $this->getTotal()
                    ];
                    
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Sản phẩm không tồn tại'
                    ];
                    
                    header('Content-Type: application/json');
                    http_response_code(404);
                    echo json_encode($response);
                    exit;
                }
            } else {
                // Xử lý form truyền thống
                $product_id = $_POST['product_id'] ?? null;
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                $name = $_POST['name'] ?? '';
                $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
                $image_url = $_POST['image_url'] ?? '';
                
                // Kiểm tra dữ liệu hợp lệ
                if (!$product_id || !$name || $price <= 0) {
                    $_SESSION['error'] = 'Thông tin sản phẩm không hợp lệ';
                    $this->redirect('/products');
                    return;
                }
                
                // Xử lý biến thể
                $variants = [];
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $type => $value) {
                        $variants[$type] = $value;
                    }
                }
                
                // Thêm vào giỏ hàng
                $this->addItemToCart($product_id, $name, $price, $quantity, $image_url, $variants);
                
                // Xác định chuyển hướng
                $redirect = $_POST['redirect'] ?? 'cart';
                if ($redirect === 'checkout') {
                    $this->redirect('/checkout');
                } else {
                    $this->redirect('/cart');
                }
            }
        } else {
            // Phương thức không hợp lệ
            if ($isAjaxRequest) {
                $response = [
                    'success' => false,
                    'message' => 'Phương thức không hợp lệ'
                ];
                
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode($response);
                exit;
            } else {
                $this->redirect('/products');
            }
        }
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng (helper method)
     */
    private function addItemToCart($product_id, $name, $price, $quantity, $image_url, $variants = []) {
        // Giới hạn số lượng tối thiểu
        if ($quantity < 1) $quantity = 1;
        
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                // Kiểm tra biến thể
                $variantsMatch = true;
                if (!empty($variants) && !empty($item['variants'])) {
                    // So sánh từng loại biến thể
                    foreach ($variants as $type => $value) {
                        if (!isset($item['variants'][$type]) || $item['variants'][$type] != $value) {
                            $variantsMatch = false;
                            break;
                        }
                    }
                    
                    // Kiểm tra số lượng biến thể có khớp không
                    if ($variantsMatch && count($variants) != count($item['variants'])) {
                        $variantsMatch = false;
                    }
                    
                    // Nếu các biến thể khác, coi là sản phẩm khác
                    if (!$variantsMatch) {
                        continue;
                    }
                } else if ((!empty($variants) && empty($item['variants'])) || 
                           (empty($variants) && !empty($item['variants']))) {
                    // Một có biến thể, một không => khác nhau
                    continue;
                }
                
                // Cập nhật số lượng
                $newQuantity = $item['quantity'] + $quantity;
                $item['quantity'] = $newQuantity;
                $found = true;
                break;
            }
        }
        
        // Thêm mới nếu chưa có
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image_url' => $image_url,
                'variants' => $variants
            ];
        }
        
        $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng';
    }
    
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update() {
        $this->ensureCartSession();
        
        // Kiểm tra request Ajax
        $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjaxRequest) {
                $this->sendJsonResponse(false, 'Phương thức không hợp lệ', 405);
                return;
            } else {
                $this->redirect('/cart');
                return;
            }
        }
        
        $index = isset($_POST['index']) ? (int)$_POST['index'] : -1;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        
        // Kiểm tra dữ liệu hợp lệ
        if ($index < 0 || !isset($_SESSION['cart'][$index])) {
            if ($isAjaxRequest) {
                $this->sendJsonResponse(false, 'Sản phẩm không tồn tại trong giỏ hàng', 404);
                return;
            } else {
                $_SESSION['error'] = 'Sản phẩm không tồn tại trong giỏ hàng';
                $this->redirect('/cart');
                return;
            }
        }
        
        // Lưu số lượng ban đầu cho trường hợp cần khôi phục
        $originalQuantity = $_SESSION['cart'][$index]['quantity'];
        
        // Giới hạn số lượng thấp nhất là 1
        if ($quantity < 1) $quantity = 1;
        
        // Cập nhật số lượng
        $_SESSION['cart'][$index]['quantity'] = $quantity;
        $_SESSION['success'] = 'Đã cập nhật giỏ hàng';
        
        // Tính toán tiền cho sản phẩm cụ thể và tổng đơn hàng
        $itemSubtotal = $_SESSION['cart'][$index]['price'] * $quantity;
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        if ($isAjaxRequest) {
            $response = [
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng',
                'item_subtotal' => number_format($itemSubtotal, 0, ',', '.'),
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($subtotal, 0, ',', '.'),
                'cart_count' => $this->getCartCount(),
                'original_quantity' => $originalQuantity
            ];
            
            $this->sendJsonResponse(true, 'Đã cập nhật giỏ hàng', 200, $response);
            return;
        } else {
            $this->redirect('/cart');
        }
    }
    
    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove() {
        $this->ensureCartSession();
        
        // Kiểm tra request Ajax
        $isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                         strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjaxRequest) {
                $this->sendJsonResponse(false, 'Phương thức không hợp lệ', 405);
                return;
            } else {
                $this->redirect('/cart');
                return;
            }
        }
        
        $index = isset($_POST['index']) ? (int)$_POST['index'] : -1;
        
        // Kiểm tra dữ liệu hợp lệ
        if ($index < 0 || !isset($_SESSION['cart'][$index])) {
            if ($isAjaxRequest) {
                $this->sendJsonResponse(false, 'Sản phẩm không tồn tại trong giỏ hàng', 404);
                return;
            } else {
                $_SESSION['error'] = 'Sản phẩm không tồn tại trong giỏ hàng';
                $this->redirect('/cart');
                return;
            }
        }
        
        // Xóa sản phẩm
        array_splice($_SESSION['cart'], $index, 1);
        $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        
        // Tính toán tổng đơn hàng mới
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        if ($isAjaxRequest) {
            $response = [
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
                'subtotal' => number_format($subtotal, 0, ',', '.'),
                'total' => number_format($subtotal, 0, ',', '.'),
                'cart_count' => $this->getCartCount()
            ];
            
            $this->sendJsonResponse(true, 'Đã xóa sản phẩm khỏi giỏ hàng', 200, $response);
            return;
        } else {
            $this->redirect('/cart');
        }
    }
    
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        $this->ensureCartSession();
        
        $_SESSION['cart'] = [];
        $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng';
        
        $this->redirect('/cart');
    }
    
    /**
     * Áp dụng mã khuyến mãi
     */
    public function applyPromotion() {
        $this->ensureCartSession();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart');
            return;
        }
        
        $code = $_POST['promo_code'] ?? '';
        
        if (empty($code)) {
            $_SESSION['error'] = 'Vui lòng nhập mã khuyến mãi';
            $this->redirect('/cart');
            return;
        }
        
        // Tính tổng giá trị đơn hàng
        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Lấy thông tin khuyến mãi
        $promotion = $this->promotionModel->findByCode($code);
        
        if (!$promotion) {
            $_SESSION['error'] = 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn';
            $this->redirect('/cart');
            return;
        }
        
        // Kiểm tra điều kiện áp dụng
        if (!$this->promotionModel->validatePromotion($promotion, $subtotal)) {
            $_SESSION['error'] = 'Đơn hàng không đủ điều kiện áp dụng mã khuyến mãi này';
            $this->redirect('/cart');
            return;
        }
        
        // Lưu mã khuyến mãi vào session
        $_SESSION['promotion'] = [
            'id' => $promotion['promotion_id'],
            'code' => $promotion['code'],
            'discount_type' => $promotion['discount_type'],
            'discount_value' => $promotion['discount_value'],
            'max_discount' => $promotion['max_discount']
        ];
        
        $_SESSION['success'] = 'Đã áp dụng mã khuyến mãi ' . $code;
        $this->redirect('/cart');
    }
    
    /**
     * Xóa mã khuyến mãi đã áp dụng
     */
    public function removePromotion() {
        if (isset($_SESSION['promotion'])) {
            unset($_SESSION['promotion']);
            $_SESSION['success'] = 'Đã xóa mã khuyến mãi';
        }
        
        $this->redirect('/cart');
    }
    
    /**
     * Đảm bảo session giỏ hàng đã được khởi tạo
     */
    private function ensureCartSession() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Tính tổng tiền giỏ hàng
    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    // Tính tổng số lượng sản phẩm trong giỏ hàng
    private function getCartCount() {
        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    // Kiểm tra giỏ hàng có trống không
    public function isEmpty() {
        return empty($_SESSION['cart']);
    }

    // Tìm sản phẩm trong giỏ hàng dựa trên product_id và variant_id
    private function findCartItem($product_id, $variant_id = null) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id && $item['variant_id'] == $variant_id) {
                return $key;
            }
        }
        return false;
    }

    // Hàm hỗ trợ gửi phản hồi JSON
    private function sendJsonResponse($success, $message, $statusCode = 200, $extraData = []) {
        // Đảm bảo không có output nào trước khi gửi JSON
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message
        ], $extraData));
        exit;
    }

    // Hàm lấy dữ liệu JSON từ request body
    private function getJsonInput() {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    public function addToCart() {
        try {
            // Kiểm tra user đã đăng nhập chưa
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Vui lòng đăng nhập để thêm vào giỏ hàng']);
                return;
            }

            // Lấy dữ liệu từ request
            $data = json_decode(file_get_contents("php://input"));
            
            if (!isset($data->product_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Thiếu thông tin sản phẩm']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $productId = $data->product_id;
            $variantId = $data->variant_id ?? null;
            $quantity = $data->quantity ?? 1;

            if ($this->cart->addToCart($userId, $productId, $variantId, $quantity)) {
                $cartItems = $this->cart->getCartItems($userId);
                $cartTotal = $this->cart->getCartTotal($userId);
                
                echo json_encode([
                    'message' => 'Thêm vào giỏ hàng thành công',
                    'cart_items' => $cartItems,
                    'cart_total' => $cartTotal
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Không thể thêm vào giỏ hàng']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateCartItem() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Vui lòng đăng nhập']);
                return;
            }

            $data = json_decode(file_get_contents("php://input"));
            
            if (!isset($data->cart_item_id) || !isset($data->quantity)) {
                http_response_code(400);
                echo json_encode(['error' => 'Thiếu thông tin cần thiết']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $cartItemId = $data->cart_item_id;
            $quantity = $data->quantity;

            if ($this->cart->updateCartItemQuantity($cartItemId, $quantity)) {
                $cartItems = $this->cart->getCartItems($userId);
                $cartTotal = $this->cart->getCartTotal($userId);
                
                echo json_encode([
                    'message' => 'Cập nhật giỏ hàng thành công',
                    'cart_items' => $cartItems,
                    'cart_total' => $cartTotal
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Không thể cập nhật giỏ hàng']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function removeFromCart() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Vui lòng đăng nhập']);
                return;
            }

            $data = json_decode(file_get_contents("php://input"));
            
            if (!isset($data->cart_item_id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Thiếu thông tin cần thiết']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $cartItemId = $data->cart_item_id;

            if ($this->cart->removeFromCart($cartItemId, $userId)) {
                $cartItems = $this->cart->getCartItems($userId);
                $cartTotal = $this->cart->getCartTotal($userId);
                
                echo json_encode([
                    'message' => 'Xóa sản phẩm thành công',
                    'cart_items' => $cartItems,
                    'cart_total' => $cartTotal
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Không thể xóa sản phẩm']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getCart() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Vui lòng đăng nhập']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $cartItems = $this->cart->getCartItems($userId);
            $cartTotal = $this->cart->getCartTotal($userId);

            echo json_encode([
                'cart_items' => $cartItems,
                'cart_total' => $cartTotal
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function clearCart() {
        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Vui lòng đăng nhập']);
                return;
            }

            $userId = $_SESSION['user_id'];

            if ($this->cart->clearCart($userId)) {
                echo json_encode([
                    'message' => 'Đã xóa toàn bộ giỏ hàng',
                    'cart_items' => [],
                    'cart_total' => 0
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Không thể xóa giỏ hàng']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    // Phương thức xử lý đặt hàng
    public function checkout() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu giỏ hàng
            $cart_items = $this->cart->getCartItems($_SESSION['user_id']);
            
            if (empty($cart_items)) {
                $_SESSION['error'] = "Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi đặt hàng.";
                header("Location: /cart");
                exit();
            }
            
            // Tính tổng tiền
            $total_amount = 0;
            foreach ($cart_items as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            
            // Tạo đơn hàng mới
            $this->order->user_id = $_SESSION['user_id'];
            $this->order->total_amount = $total_amount;
            $this->order->payment_method = $_POST['payment_method'];
            $this->order->notes = isset($_POST['notes']) ? $_POST['notes'] : '';
            
            if ($this->order->create()) {
                $order_id = $this->order->order_id;
                
                // Thêm từng sản phẩm vào chi tiết đơn hàng
                foreach ($cart_items as $item) {
                    $query = "INSERT INTO orderitems (order_id, product_id, variant_id, quantity, unit_price) 
                             VALUES (:order_id, :product_id, :variant_id, :quantity, :unit_price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $item['product_id']);
                    $stmt->bindParam(':variant_id', $item['variant_id']);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':unit_price', $item['price']);
                    $stmt->execute();
                }
                
                // Tính điểm tích lũy từ đơn hàng (1 điểm cho mỗi 10,000 VNĐ)
                $points_earned = floor($total_amount / 10000);
                
                // Cập nhật tổng điểm tích lũy của người dùng
                try {
                    // Lấy điểm hiện tại
                    $query = "SELECT total_points FROM users WHERE user_id = :user_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $current_points = $stmt->fetch(PDO::FETCH_ASSOC)['total_points'] ?? 0;
                    
                    // Cập nhật điểm mới
                    $new_points = $current_points + $points_earned;
                    $query = "UPDATE users SET total_points = :points WHERE user_id = :user_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':points', $new_points);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->execute();
                    
                    // Thêm vào bảng lịch sử điểm
                    $query = "INSERT INTO point_history (user_id, order_id, points, action_type, created_at) 
                             VALUES (:user_id, :order_id, :points, 'earned', NOW())";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':points', $points_earned);
                    $stmt->execute();
                    
                    error_log("Đã cộng {$points_earned} điểm cho user_id {$_SESSION['user_id']} từ đơn hàng {$order_id}");
                } catch (PDOException $e) {
                    error_log("Lỗi khi cập nhật điểm tích lũy: " . $e->getMessage());
                }
                
                // Xóa giỏ hàng sau khi đặt hàng thành công
                $this->cart->clearCart($_SESSION['user_id']);
                
                $_SESSION['success'] = "Đặt hàng thành công! Mã đơn hàng của bạn là #" . $order_id;
                header("Location: /orders");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau.";
                header("Location: /cart");
                exit();
            }
        }
        
        header("Location: /cart");
        exit();
    }

    // Kiểm tra nếu request là JSON
    private function isJsonRequest() {
        return (isset($_SERVER['CONTENT_TYPE']) && 
                (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false));
    }
}