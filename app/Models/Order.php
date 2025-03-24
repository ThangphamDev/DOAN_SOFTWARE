<?php
class Order {
    private $conn;
    private $table_name = "orders";

    // Properties
    public $order_id;
    public $user_id;
    public $order_date;
    public $total_amount;
    public $status;
    public $payment_method;
    public $payment_status;
    public $notes;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new order
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (user_id, order_date, total_amount, status, payment_method, payment_status, notes)
                VALUES
                (:user_id, NOW(), :total_amount, :status, :payment_method, :payment_status, :notes)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->user_id = isset($this->user_id) ? htmlspecialchars(strip_tags($this->user_id)) : null;
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->payment_method = htmlspecialchars(strip_tags($this->payment_method));
        $this->payment_status = htmlspecialchars(strip_tags($this->payment_status));
        $this->notes = isset($this->notes) ? htmlspecialchars(strip_tags($this->notes)) : null;

        // Set default status values
        $this->status = 'pending';
        $this->payment_status = 'pending';

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":payment_method", $this->payment_method);
        $stmt->bindParam(":payment_status", $this->payment_status);
        $stmt->bindParam(":notes", $this->notes);

        // Execute query
        if($stmt->execute()) {
            $this->order_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Read all orders
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read single order
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->order_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->user_id = $row['user_id'];
            $this->order_date = $row['order_date'];
            $this->total_amount = $row['total_amount'];
            $this->status = $row['status'];
            $this->payment_method = $row['payment_method'];
            $this->payment_status = $row['payment_status'];
            $this->notes = $row['notes'];
            return true;
        }
        return false;
    }

    // Update order status
    public function updateStatus() {
        $query = "UPDATE " . $this->table_name . "
                SET status = :status
                WHERE order_id = :order_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->order_id = htmlspecialchars(strip_tags($this->order_id));

        // Bind values
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":order_id", $this->order_id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read orders by user
    public function readByUser() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getOrdersByUserId($userId) {
        try {
            $query = "SELECT o.*, oi.*, p.name, p.image_url, pv.size 
                     FROM Orders o 
                     LEFT JOIN OrderItems oi ON o.order_id = oi.order_id 
                     LEFT JOIN Products p ON oi.product_id = p.product_id 
                     LEFT JOIN ProductVariants pv ON oi.variant_id = pv.variant_id 
                     WHERE o.user_id = :user_id 
                     ORDER BY o.order_date DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();

            $orders = [];
            $currentOrderId = null;
            $currentOrder = null;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($currentOrderId !== $row['order_id']) {
                    // Nếu là đơn hàng mới
                    if ($currentOrder !== null) {
                        $orders[] = $currentOrder;
                    }
                    
                    $currentOrderId = $row['order_id'];
                    $currentOrder = [
                        'order_id' => $row['order_id'],
                        'order_date' => $row['order_date'],
                        'total_amount' => $row['total_amount'],
                        'status' => $row['status'],
                        'payment_method' => $row['payment_method'],
                        'payment_status' => $row['payment_status'],
                        'notes' => $row['notes'],
                        'items' => []
                    ];
                }

                // Thêm sản phẩm vào đơn hàng hiện tại
                if ($row['product_id']) {
                    $currentOrder['items'][] = [
                        'product_id' => $row['product_id'],
                        'variant_id' => $row['variant_id'],
                        'name' => $row['name'],
                        'image_url' => $row['image_url'],
                        'size' => $row['size'],
                        'quantity' => $row['quantity'],
                        'unit_price' => $row['unit_price']
                    ];
                }
            }

            // Thêm đơn hàng cuối cùng vào mảng
            if ($currentOrder !== null) {
                $orders[] = $currentOrder;
            }

            return $orders;
        } catch(PDOException $e) {
            error_log("Error in getOrdersByUserId: " . $e->getMessage());
            return [];
        }
    }
} 