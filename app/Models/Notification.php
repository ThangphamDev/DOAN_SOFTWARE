<?php
class Notification {
    private $conn;
    public $notification_id;
    public $user_id;
    public $title;
    public $message;
    public $type;
    public $related_id;
    public $is_read;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả thông báo của một người dùng
    public function getByUserId($userId) {
        $query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        
        $notifications = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notifications[] = $row;
        }
        
        return $notifications;
    }

    // Lấy tất cả thông báo (dành cho admin)
    public function getAll() {
        $query = "SELECT n.*, u.full_name, u.email 
                 FROM notifications n 
                 JOIN users u ON n.user_id = u.user_id 
                 ORDER BY n.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $notifications = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notifications[] = $row;
        }
        
        return $notifications;
    }

    // Tạo thông báo mới
    public function create() {
        $query = "INSERT INTO notifications (user_id, title, message, type, related_id, is_read) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->message = htmlspecialchars(strip_tags($this->message));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->is_read = 0; // Mặc định là chưa đọc
        
        // Bind parameters
        $stmt->bindParam(1, $this->user_id);
        $stmt->bindParam(2, $this->title);
        $stmt->bindParam(3, $this->message);
        $stmt->bindParam(4, $this->type);
        $stmt->bindParam(5, $this->related_id);
        $stmt->bindParam(6, $this->is_read);
        
        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Đánh dấu thông báo là đã đọc
    public function markAsRead() {
        $query = "UPDATE notifications SET is_read = 1 
                 WHERE notification_id = ? AND user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->notification_id);
        $stmt->bindParam(2, $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Đánh dấu tất cả thông báo của người dùng là đã đọc
    public function markAllAsRead($userId) {
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Xóa thông báo
    public function delete() {
        $query = "DELETE FROM notifications WHERE notification_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->notification_id);
        $stmt->bindParam(2, $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    // Đếm số thông báo chưa đọc
    public function getUnreadCount($userId) {
        $query = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $userId);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
} 