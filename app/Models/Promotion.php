<?php
class Promotion {
    private $db;
    private $table = 'promotions';

    public $id;
    public $code;
    public $name;
    public $description;
    public $discount_type;
    public $discount_value;
    public $min_order_amount;
    public $max_discount;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $used_count;
    public $is_active;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive() {
        $query = "SELECT * FROM {$this->table} 
                 WHERE is_active = 1 
                 AND start_date <= NOW() 
                 AND end_date >= NOW() 
                 AND (usage_limit IS NULL OR used_count < usage_limit)
                 ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE promotion_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCode($code) {
        $query = "SELECT * FROM {$this->table} WHERE code = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO {$this->table} 
                (code, name, description, discount_type, discount_value, 
                min_order_amount, max_discount, start_date, end_date, 
                usage_limit, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->code,
            $this->name,
            $this->description,
            $this->discount_type,
            $this->discount_value,
            $this->min_order_amount,
            $this->max_discount,
            $this->start_date,
            $this->end_date,
            $this->usage_limit,
            $this->is_active
        ]);
    }

    public function update() {
        $query = "UPDATE {$this->table} SET 
                code = ?, 
                name = ?,
                description = ?,
                discount_type = ?,
                discount_value = ?,
                min_order_amount = ?,
                max_discount = ?,
                start_date = ?,
                end_date = ?,
                usage_limit = ?,
                is_active = ?
                WHERE promotion_id = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->code,
            $this->name,
            $this->description,
            $this->discount_type,
            $this->discount_value,
            $this->min_order_amount,
            $this->max_discount,
            $this->start_date,
            $this->end_date,
            $this->usage_limit,
            $this->is_active,
            $this->id
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE promotion_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function incrementUsage($id) {
        $query = "UPDATE {$this->table} SET used_count = used_count + 1 WHERE promotion_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function isValid($code, $orderAmount) {
        $promotion = $this->getByCode($code);
        if (!$promotion) return false;

        $now = new DateTime();
        $startDate = new DateTime($promotion['start_date']);
        $endDate = new DateTime($promotion['end_date']);

        return $promotion['is_active'] == 1 &&
               $now >= $startDate &&
               $now <= $endDate &&
               ($promotion['usage_limit'] === null || $promotion['used_count'] < $promotion['usage_limit']) &&
               ($promotion['min_order_amount'] === null || $orderAmount >= $promotion['min_order_amount']);
    }

    public function calculateDiscount($code, $orderAmount) {
        $promotion = $this->getByCode($code);
        if (!$promotion || !$this->isValid($code, $orderAmount)) return 0;

        $discount = 0;
        if ($promotion['discount_type'] === 'percentage') {
            $discount = $orderAmount * ($promotion['discount_value'] / 100);
            if ($promotion['max_discount'] !== null) {
                $discount = min($discount, $promotion['max_discount']);
            }
        } else {
            $discount = $promotion['discount_value'];
        }

        return $discount;
    }
} 