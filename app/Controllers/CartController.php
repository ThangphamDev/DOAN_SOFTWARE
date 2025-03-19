<?php
class CartController {
    public function addToCart() {
        // Đảm bảo không có output nào trước khi gửi JSON
        ob_clean();
        
        // Đảm bảo header là JSON
        header('Content-Type: application/json');
        
        // Đảm bảo session đã được khởi tạo
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        try {
            // Lấy và validate dữ liệu
            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            if (!$productId) {
                throw new Exception('Thiếu thông tin sản phẩm');
            }
            
            if ($quantity < 1) {
                throw new Exception('Số lượng không hợp lệ');
            }

            // Thêm hoặc cập nhật sản phẩm trong giỏ hàng
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = [
                    'quantity' => $quantity,
                    'product_id' => $productId
                ];
            }

            // Tính tổng số sản phẩm trong giỏ hàng
            $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));

            // Trả về response thành công
            echo json_encode([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => $cartCount
            ]);
            exit;

        } catch (Exception $e) {
            // Log lỗi
            error_log("Cart Error: " . $e->getMessage());
            
            // Trả về response lỗi
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function removeFromCart() {
        ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Product ID is required']);
            exit;
        }

        $product_id = $data['product_id'];
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Remove the item from cart
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            
            // Recalculate cart count
            $cart_count = array_sum($_SESSION['cart']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cart_count
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);
        }
        exit;
    }

    public function updateQuantity() {
        ob_clean();
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            echo json_encode(['success' => false, 'message' => 'Product ID and quantity are required']);
            exit;
        }

        $product_id = $data['product_id'];
        $quantity = (int)$data['quantity'];
        
        if ($quantity < 1) {
            echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Update item quantity in cart
        $_SESSION['cart'][$product_id] = [
            'quantity' => $quantity
        ];
        
        // Calculate total items in cart
        $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
        
        echo json_encode([
            'success' => true,
            'message' => 'Cart updated successfully',
            'cart_count' => $cart_count
        ]);
        exit;
    }
} 