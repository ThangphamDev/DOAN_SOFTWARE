<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/OrderItem.php';

class OrderController {
    private $order;
    private $orderItem;

    public function __construct($db) {
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
} 