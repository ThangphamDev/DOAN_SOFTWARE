<?php
class Feedback {
    private $db;
    private $table = 'feedback';

    public $id;
    public $user_id;
    public $order_id;
    public $rating;
    public $comment;
    public $images;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT f.*, u.username, u.full_name 
                FROM {$this->table} f 
                LEFT JOIN users u ON f.user_id = u.user_id 
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT f.*, u.username, u.full_name 
                FROM {$this->table} f 
                LEFT JOIN users u ON f.user_id = u.user_id 
                WHERE f.feedback_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId) {
        $query = "SELECT f.*, u.username, u.full_name 
                FROM {$this->table} f 
                LEFT JOIN users u ON f.user_id = u.user_id 
                WHERE f.user_id = ? 
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByOrderId($orderId) {
        $query = "SELECT f.*, u.username, u.full_name 
                FROM {$this->table} f 
                LEFT JOIN users u ON f.user_id = u.user_id 
                WHERE f.order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO {$this->table} 
                (user_id, order_id, rating, comment, images, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->user_id,
            $this->order_id,
            $this->rating,
            $this->comment,
            $this->images,
            $this->status ?? 'pending'
        ]);
    }

    public function update() {
        $query = "UPDATE {$this->table} SET 
                rating = ?,
                comment = ?,
                images = ?,
                status = ?
                WHERE feedback_id = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->rating,
            $this->comment,
            $this->images,
            $this->status,
            $this->id
        ]);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE {$this->table} SET status = ? WHERE feedback_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, $id]);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE feedback_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getReplies($feedbackId) {
        $query = "SELECT r.*, u.username, u.full_name 
                FROM feedback_replies r 
                LEFT JOIN users u ON r.user_id = u.user_id 
                WHERE r.feedback_id = ? 
                ORDER BY r.created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$feedbackId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReply($feedbackId, $userId, $replyText) {
        $query = "INSERT INTO feedback_replies (feedback_id, user_id, reply_text) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$feedbackId, $userId, $replyText]);
    }

    public function deleteReply($replyId) {
        $query = "DELETE FROM feedback_replies WHERE reply_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$replyId]);
    }

    public function getStats() {
        $query = "SELECT 
                COUNT(*) as total_feedback,
                AVG(rating) as average_rating,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                COUNT(CASE WHEN status = 'resolved' THEN 1 END) as resolved_count
                FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readByProduct($productId) {
        $query = "SELECT f.*, u.username, u.full_name 
                FROM {$this->table} f 
                LEFT JOIN users u ON f.user_id = u.user_id 
                LEFT JOIN orderitems oi ON f.order_id = oi.order_id 
                WHERE oi.product_id = ? 
                GROUP BY f.feedback_id
                ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$productId]);
        return $stmt;
    }
} 