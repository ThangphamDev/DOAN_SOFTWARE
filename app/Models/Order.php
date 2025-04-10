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
    
    // Read all orders (for admin)
    public function readAll() {
        $query = "SELECT o.*, u.username, u.email, COUNT(oi.order_item_id) as item_count
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.user_id
                LEFT JOIN orderitems oi ON o.order_id = oi.order_id
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read one order by ID
    public function readOne() {
        $query = "SELECT o.*, u.username, u.email, u.full_name, u.phone_number as phone
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.user_id
                WHERE o.order_id = ?
                LIMIT 0,1";
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
            
            // Additional user info
            $this->user_name = $row['username'];
            $this->user_email = $row['email'];
            $this->user_full_name = $row['full_name'];
            $this->user_phone = $row['phone'];
            
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
            // Đầu tiên, lấy tất cả đơn hàng của người dùng
            $query = "SELECT * FROM Orders WHERE user_id = :user_id ORDER BY order_date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();
            
            $orders = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $order = [
                    'order_id' => $row['order_id'],
                    'order_date' => $row['order_date'],
                    'total_amount' => $row['total_amount'],
                    'status' => $row['status'],
                    'payment_method' => $row['payment_method'],
                    'payment_status' => $row['payment_status'],
                    'notes' => $row['notes'],
                    'items' => []
                ];
                
                // Lấy các sản phẩm trong đơn hàng
                $queryItems = "SELECT oi.*, p.name, p.image_url, pv.variant_value 
                               FROM OrderItems oi 
                               LEFT JOIN Products p ON oi.product_id = p.product_id 
                               LEFT JOIN ProductVariants pv ON oi.variant_id = pv.variant_id 
                               WHERE oi.order_id = :order_id";
                $stmtItems = $this->conn->prepare($queryItems);
                $stmtItems->bindParam(":order_id", $row['order_id']);
                $stmtItems->execute();
                
                // Thêm các sản phẩm vào đơn hàng
                while ($itemRow = $stmtItems->fetch(PDO::FETCH_ASSOC)) {
                    $order['items'][] = [
                        'order_item_id' => $itemRow['order_item_id'],
                        'product_id' => $itemRow['product_id'],
                        'variant_id' => $itemRow['variant_id'],
                        'name' => $itemRow['name'],
                        'variant_type' => 'Kích thước',  // Mặc định là "Kích thước"
                        'variant_value' => $itemRow['variant_value'],
                        'image_url' => $itemRow['image_url'],
                        'quantity' => $itemRow['quantity'],
                        'unit_price' => $itemRow['unit_price']
                    ];
                }
                
                $orders[] = $order;
            }
            
            // Ghi log để kiểm tra
            error_log("Orders for user $userId: " . print_r($orders, true));
            
            return $orders;
        } catch(PDOException $e) {
            error_log("Error in getOrdersByUserId: " . $e->getMessage());
            return [];
        }
    }
    
    // Get order items
    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, p.name, p.image_url, pv.variant_value as size_name
                FROM orderitems oi
                LEFT JOIN products p ON oi.product_id = p.product_id
                LEFT JOIN productvariants pv ON oi.variant_id = pv.variant_id
                WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Count all orders
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Get total revenue
    public function getTotalRevenue() {
        $query = "SELECT SUM(total_amount) as total FROM " . $this->table_name . " WHERE status != 'cancelled'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Get recent orders (limit by count)
    public function getRecentOrders($limit = 5) {
        $query = "SELECT o.*, u.username, u.email
                FROM " . $this->table_name . " o
                LEFT JOIN users u ON o.user_id = u.user_id
                ORDER BY o.order_date DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Get revenue by month (last n months)
    public function getRevenueByMonth($months = 6) {
        $query = "SELECT 
                    DATE_FORMAT(order_date, '%Y-%m') as month,
                    SUM(total_amount) as revenue,
                    COUNT(*) as order_count
                FROM " . $this->table_name . "
                WHERE status != 'cancelled'
                AND order_date >= DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH)
                GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                ORDER BY month ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $months, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get revenue by day in a specific month
    public function getRevenueByDay($month, $year) {
        $query = "SELECT 
                    DAY(order_date) as day,
                    SUM(total_amount) as revenue,
                    COUNT(*) as order_count
                FROM " . $this->table_name . "
                WHERE status != 'cancelled'
                AND MONTH(order_date) = ?
                AND YEAR(order_date) = ?
                GROUP BY DAY(order_date)
                ORDER BY day ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $month, PDO::PARAM_INT);
        $stmt->bindParam(2, $year, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get revenue by product for a specific month
    public function getRevenueByProduct($month, $year) {
        $query = "SELECT 
                    p.product_id,
                    p.name as product_name,
                    p.image_url,
                    c.name as category_name,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.quantity * oi.unit_price) as revenue
                FROM orderitems oi
                JOIN products p ON oi.product_id = p.product_id
                LEFT JOIN categories c ON p.category_id = c.category_id
                JOIN " . $this->table_name . " o ON oi.order_id = o.order_id
                WHERE o.status != 'cancelled'
                AND MONTH(o.order_date) = ?
                AND YEAR(o.order_date) = ?
                GROUP BY p.product_id
                ORDER BY revenue DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $month, PDO::PARAM_INT);
        $stmt->bindParam(2, $year, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get revenue by category for a specific month
    public function getRevenueByCategory($month, $year) {
        $query = "SELECT 
                    c.category_id,
                    c.name as category_name,
                    COUNT(DISTINCT oi.order_id) as order_count,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.quantity * oi.unit_price) as revenue
                FROM orderitems oi
                JOIN products p ON oi.product_id = p.product_id
                JOIN categories c ON p.category_id = c.category_id
                JOIN " . $this->table_name . " o ON oi.order_id = o.order_id
                WHERE o.status != 'cancelled'
                AND MONTH(o.order_date) = ?
                AND YEAR(o.order_date) = ?
                GROUP BY c.category_id
                ORDER BY revenue DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $month, PDO::PARAM_INT);
        $stmt->bindParam(2, $year, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get orders by user (for admin user details)
    public function getOrdersByUser($userId) {
        $query = "SELECT o.*,
                    (SELECT COUNT(*) FROM orderitems WHERE order_id = o.order_id) as item_count
                FROM " . $this->table_name . " o
                WHERE o.user_id = ?
                ORDER BY o.order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng số đơn hàng của người dùng
    public function getTotalOrdersByUserId($userId) {
        try {
            $query = "SELECT COUNT(*) as total_orders FROM Orders WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total_orders'];
        } catch(PDOException $e) {
            error_log("Error in getTotalOrdersByUserId: " . $e->getMessage());
            return 0;
        }
    }
    
    // Lấy tổng tiền đã chi của người dùng
    public function getTotalSpentByUserId($userId) {
        try {
            $query = "SELECT SUM(total_amount) as total_spent FROM Orders WHERE user_id = :user_id AND status != 'cancelled'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $userId);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total_spent'] ? $row['total_spent'] : 0;
        } catch(PDOException $e) {
            error_log("Error in getTotalSpentByUserId: " . $e->getMessage());
            return 0;
        }
    }
} 