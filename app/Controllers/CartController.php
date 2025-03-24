<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/ProductVariant.php';

class CartController {
    private $product;
    private $productVariant;

    public function __construct($db) {
        $this->product = new Product($db);
        $this->productVariant = new ProductVariant($db);
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    // Hiển thị giỏ hàng
    public function index() {
        require_once __DIR__ . '/../Views/cart/cart.php';
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /menu");
            exit();
        }

        // Lấy dữ liệu từ form
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        $variant_id = isset($_POST['variant_id']) ? filter_var($_POST['variant_id'], FILTER_VALIDATE_INT) : null;

        if ($product_id === false || $quantity === false || $quantity < 1) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            header("Location: /menu");
            exit();
        }

        // Kiểm tra sản phẩm
        $this->product->product_id = $product_id;
        if (!$this->product->readOne()) {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            header("Location: /menu");
            exit();
        }

        // Kiểm tra biến thể nếu có
        $price = $this->product->base_price;
        $size = null;
        if ($variant_id) {
            $this->productVariant->variant_id = $variant_id;
            if ($this->productVariant->readOne() && $this->productVariant->product_id == $product_id) {
                $price = $this->productVariant->price;
                $size = $this->productVariant->size;
            } else {
                $_SESSION['error'] = "Biến thể sản phẩm không hợp lệ";
                header("Location: /menu");
                exit();
            }
        }

        $cart_item = [
            'product_id' => $product_id,
            'variant_id' => $variant_id,
            'name' => $this->product->name,
            'price' => $price,
            'size' => $size,
            'image_url' => $this->product->image_url ?: '/public/images/default-product.jpg',
            'quantity' => $quantity
        ];

        // Kiểm tra sản phẩm hoặc biến thể đã tồn tại trong giỏ hàng
        $key = $this->findCartItem($product_id, $variant_id);
        if ($key !== false) {
            $_SESSION['cart'][$key]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][] = $cart_item;
        }

        $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng";
        header("Location: /menu");
        exit();
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không được phép";
            header("Location: /cart");
            exit();
        }

        if (!isset($_POST['index']) || !isset($_POST['change'])) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            header("Location: /cart");
            exit();
        }

        $index = filter_var($_POST['index'], FILTER_VALIDATE_INT);
        $change = filter_var($_POST['change'], FILTER_VALIDATE_INT);

        if ($index === false || $change === false || !isset($_SESSION['cart'][$index])) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            header("Location: /cart");
            exit();
        }

        $newQuantity = $_SESSION['cart'][$index]['quantity'] + $change;
        if ($newQuantity < 1) {
            $_SESSION['error'] = "Số lượng không thể nhỏ hơn 1";
            header("Location: /cart");
            exit();
        }

        $_SESSION['cart'][$index]['quantity'] = $newQuantity;
        $_SESSION['success'] = "Đã cập nhật số lượng sản phẩm";
        header("Location: /cart");
        exit();
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không được phép";
            header("Location: /cart");
            exit();
        }

        if (!isset($_POST['index'])) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            header("Location: /cart");
            exit();
        }

        $index = filter_var($_POST['index'], FILTER_VALIDATE_INT);
        if ($index === false || !isset($_SESSION['cart'][$index])) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            header("Location: /cart");
            exit();
        }

        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng";
        header("Location: /cart");
        exit();
    }

    // Xóa toàn bộ giỏ hàng
    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không được phép";
            header("Location: /cart");
            exit();
        }

        unset($_SESSION['cart']);
        $_SESSION['cart'] = [];
        $_SESSION['success'] = "Đã xóa toàn bộ giỏ hàng";
        header("Location: /cart");
        exit();
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
}