<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderItem.php';

class OrderController {
    private $db;
    private $order;
    private $orderItem;

    public function __construct($db) {
        $this->db = $db;
        $this->order = new Order($db);
        $this->orderItem = new OrderItem($db);
    }

    // Hiển thị danh sách đơn hàng của người dùng
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $orders = $this->order->readByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../Views/orders/index.php';
    }

    // Hiển thị chi tiết đơn hàng
    public function show($id) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        $this->order->order_id = $id;
        if($this->order->readOne()) {
            $orderItems = $this->orderItem->readByOrder($id);
            require_once __DIR__ . '/../Views/orders/show.php';
        } else {
            header("Location: /orders");
        }
    }

    // Hiển thị trang thanh toán
    public function checkout() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }

        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: /cart");
            exit();
        }

        require_once __DIR__ . '/../Views/checkout/checkout.php';
    }

    // Xử lý đặt hàng
    public function store() {
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header("Location: /cart");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->order->user_id = $_SESSION['user_id'];
            $this->order->total_amount = $_POST['total_amount'];
            $this->order->status = 'pending';
            $this->order->payment_method = $_POST['payment_method'];
            
            // Nếu phương thức thanh toán là chuyển khoản, tự động đặt trạng thái thanh toán thành "đã thanh toán"
            if($this->order->payment_method === 'chuyển khoản') {
                $this->order->payment_status = 'paid';
            } else {
                $this->order->payment_status = 'pending';
            }
            
            $this->order->shipping_address = $_POST['shipping_address'];
            $this->order->phone_number = $_POST['phone_number'];

            if($this->order->create()) {
                // Thêm các sản phẩm vào đơn hàng
                foreach($_SESSION['cart'] as $item) {
                    $this->orderItem->order_id = $this->order->order_id;
                    $this->orderItem->product_id = $item['product_id'];
                    $this->orderItem->variant_id = $item['variant_id'];
                    $this->orderItem->quantity = $item['quantity'];
                    $this->orderItem->price = $item['price'];
                    $this->orderItem->special_instructions = $item['special_instructions'] ?? '';
                    $this->orderItem->create();
                }

                // Ghi log tạo đơn hàng
                $log_query = "INSERT INTO order_logs (order_id, user_id, action, notes, created_at) 
                         VALUES (?, ?, 'create_order', ?, NOW())";
                $notes = "Đặt hàng mới với phương thức " . $this->order->payment_method;
                if($this->order->payment_method === 'chuyển khoản') {
                    $notes .= " (Đã thanh toán)";
                }
                $log_stmt = $this->db->prepare($log_query);
                $log_stmt->execute([$this->order->order_id, $_SESSION['user_id'], $notes]);

                // Xóa giỏ hàng
                unset($_SESSION['cart']);
                header("Location: /orders/" . $this->order->order_id);
            } else {
                $error = "Không thể tạo đơn hàng.";
                require_once __DIR__ . '/../Views/checkout/checkout.php';
            }
        }
    }

    // Cập nhật trạng thái đơn hàng (cho admin)
    public function updateStatus($id) {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->order->order_id = $id;
            $this->order->status = $_POST['status'];

            if($this->order->updateStatus()) {
                header("Location: /admin/orders");
            } else {
                $error = "Không thể cập nhật trạng thái đơn hàng.";
                require_once __DIR__ . '/../Views/admin/orders.php';
            }
        }
    }

    // Hiển thị danh sách đơn hàng (cho admin)
    public function adminIndex() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        $orders = $this->order->readAll();
        require_once __DIR__ . '/../Views/admin/orders.php';
    }

    // Hiển thị trang chi tiết đơn hàng
    public function viewOrder($order_id) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $this->order->order_id = $order_id;
        
        // Kiểm tra quyền truy cập đơn hàng
        if($_SESSION['role'] !== 'admin') {
            // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
            $query = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $order_id);
            $stmt->bindParam(2, $_SESSION['user_id']);
            $stmt->execute();
            
            if($stmt->rowCount() === 0) {
                $_SESSION['error'] = "Bạn không có quyền truy cập đơn hàng này.";
                header("Location: /profile");
                exit();
            }
        }
        
        // Đọc thông tin đơn hàng
        if($this->order->readOne()) {
            // Lấy danh sách sản phẩm trong đơn hàng
            $order_items = $this->order->getOrderItems($order_id);
            
            // Hiển thị trang chi tiết đơn hàng
            $order = [
                'order_id' => $this->order->order_id,
                'user_id' => $this->order->user_id,
                'order_date' => $this->order->order_date,
                'total_amount' => $this->order->total_amount,
                'status' => $this->order->status,
                'payment_method' => $this->order->payment_method,
                'payment_status' => $this->order->payment_status,
                'notes' => $this->order->notes,
                'items' => $order_items
            ];
            
            // Lấy thông tin người dùng nếu cần
            if($_SESSION['role'] === 'admin') {
                // Lấy thông tin chi tiết người dùng
                $query = "SELECT * FROM users WHERE user_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $this->order->user_id);
                $stmt->execute();
                $customer = $stmt->fetch(PDO::FETCH_ASSOC);
                
                require_once __DIR__ . '/../Views/orders/admin_view.php';
            } else {
                require_once __DIR__ . '/../Views/orders/detail.php';
            }
        } else {
            $_SESSION['error'] = "Không tìm thấy đơn hàng.";
            header("Location: /profile");
            exit();
        }
    }
    
    // Hủy đơn hàng
    public function cancelOrder($order_id) {
        if(!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit();
        }
        
        $this->order->order_id = $order_id;
        
        // Kiểm tra quyền truy cập đơn hàng
        if($_SESSION['role'] !== 'admin') {
            // Kiểm tra xem đơn hàng có thuộc về người dùng hiện tại không
            $query = "SELECT * FROM orders WHERE order_id = ? AND user_id = ? AND (status = 'pending' OR status = 'đang chờ')";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $order_id);
            $stmt->bindParam(2, $_SESSION['user_id']);
            $stmt->execute();
            
            if($stmt->rowCount() === 0) {
                $_SESSION['error'] = "Bạn không thể hủy đơn hàng này.";
                header("Location: /profile");
                exit();
            }
        }
        
        // Cập nhật trạng thái đơn hàng
        $this->order->status = 'cancelled';
        if($this->order->updateStatus()) {
            // Ghi log hủy đơn hàng
            $log_query = "INSERT INTO order_logs (order_id, user_id, action, notes, created_at) 
                         VALUES (?, ?, 'cancel', ?, NOW())";
            $notes = $_SESSION['role'] === 'admin' ? 'Hủy bởi admin' : 'Hủy bởi khách hàng';
            $log_stmt = $this->db->prepare($log_query);
            $log_stmt->execute([$order_id, $_SESSION['user_id'], $notes]);
            
            $_SESSION['success'] = "Đơn hàng đã được hủy thành công.";
        } else {
            $_SESSION['error'] = "Không thể hủy đơn hàng. Vui lòng thử lại sau.";
        }
        
        // Chuyển hướng về trang phù hợp
        if($_SESSION['role'] === 'admin') {
            header("Location: /admin/orders");
        } else {
            header("Location: /profile");
        }
        exit();
    }
    
    // Liệt kê tất cả đơn hàng (cho admin)
    public function listOrders() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }
        
        $orders = $this->order->readAll();
        require_once __DIR__ . '/../Views/admin/orders/index.php';
    }
    
    // Cập nhật trạng thái đơn hàng (cho admin)
    public function updateOrderStatus() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order_id = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $payment_status = $_POST['payment_status'] ?? null;
            
            if(!$order_id || !$status) {
                $_SESSION['error'] = "Thiếu thông tin cập nhật.";
                header("Location: /admin/orders");
                exit();
            }
            
            // Lấy thông tin đơn hàng
            $this->order->order_id = $order_id;
            if (!$this->order->readOne()) {
                $_SESSION['error'] = "Không tìm thấy đơn hàng.";
                header("Location: /admin/orders");
                exit();
            }
            
            // Nếu đơn hàng được đánh dấu là hoàn thành và phương thức thanh toán là tiền mặt, 
            // tự động cập nhật trạng thái thanh toán thành "đã thanh toán"
            if ($status === 'completed' && $this->order->payment_method === 'tiền mặt' && !$payment_status) {
                $payment_status = 'paid';
            }
            
            // Nếu phương thức thanh toán là chuyển khoản và không có trạng thái thanh toán được chỉ định
            if ($this->order->payment_method === 'chuyển khoản' && !$payment_status) {
                $payment_status = 'paid';
            }
            
            $this->order->status = $status;
            
            // Cập nhật trạng thái đơn hàng
            $status_updated = $this->order->updateStatus();
            
            // Cập nhật trạng thái thanh toán nếu có
            $payment_updated = true;
            if($payment_status) {
                $this->order->payment_status = $payment_status;
                $payment_updated = $this->order->updatePaymentStatus();
            }
            
            if($status_updated && $payment_updated) {
                // Ghi log cập nhật trạng thái
                $log_query = "INSERT INTO order_logs (order_id, user_id, action, notes, created_at) 
                             VALUES (?, ?, 'update_status', ?, NOW())";
                $notes = "Cập nhật trạng thái: $status";
                if($payment_status) {
                    $notes .= ", thanh toán: $payment_status";
                }
                $log_stmt = $this->db->prepare($log_query);
                $log_stmt->execute([$order_id, $_SESSION['user_id'], $notes]);
                
                $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công.";
            } else {
                $_SESSION['error'] = "Không thể cập nhật trạng thái đơn hàng.";
            }
            
            header("Location: /admin/orders/" . $order_id);
            exit();
        }
        
        header("Location: /admin/orders");
        exit();
    }
} 