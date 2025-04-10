<?php

namespace App\Models;

class Promotion extends BaseModel {
    private $table = 'promotions';
    
    public function __construct($db = null) {
        parent::__construct($db);
    }
    
    /**
     * Tìm mã khuyến mãi theo code
     * @param string $code Mã khuyến mãi cần tìm
     * @return array|false Thông tin khuyến mãi hoặc false nếu không tìm thấy
     */
    public function findByCode($code) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE code = :code 
                  AND start_date <= NOW() 
                  AND end_date >= NOW() 
                  AND is_active = 1 
                  AND (max_uses = 0 OR uses < max_uses)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    
    /**
     * Kiểm tra điều kiện áp dụng mã khuyến mãi
     * @param array $promotion Thông tin khuyến mãi
     * @param float $subtotal Tổng giá trị đơn hàng
     * @return bool Kết quả kiểm tra
     */
    public function validatePromotion($promotion, $subtotal) {
        // Kiểm tra giá trị đơn hàng tối thiểu
        if (isset($promotion['min_order_value']) && $promotion['min_order_value'] > 0) {
            if ($subtotal < $promotion['min_order_value']) {
                return false;
            }
        }
        
        // Có thể thêm các điều kiện khác ở đây
        
        return true;
    }
    
    /**
     * Tính toán giá trị giảm giá
     * @param array $promotion Thông tin khuyến mãi
     * @param float $subtotal Tổng giá trị đơn hàng
     * @return float Giá trị giảm giá
     */
    public function calculateDiscount($promotion, $subtotal) {
        $discount = 0;
        
        if ($promotion['discount_type'] === 'percentage') {
            $discount = $subtotal * ($promotion['discount_value'] / 100);
            
            // Áp dụng giới hạn giảm giá tối đa nếu có
            if (isset($promotion['max_discount']) && $promotion['max_discount'] > 0) {
                $discount = min($discount, $promotion['max_discount']);
            }
        } 
        elseif ($promotion['discount_type'] === 'fixed') {
            $discount = $promotion['discount_value'];
            
            // Đảm bảo không giảm quá tổng giá trị đơn hàng
            $discount = min($discount, $subtotal);
        }
        
        return $discount;
    }
    
    /**
     * Cập nhật số lần sử dụng của mã khuyến mãi
     * @param int $promotion_id ID của mã khuyến mãi
     * @return bool Kết quả cập nhật
     */
    public function incrementUses($promotion_id) {
        $query = "UPDATE " . $this->table . " 
                  SET uses = uses + 1 
                  WHERE promotion_id = :promotion_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':promotion_id', $promotion_id);
        
        return $stmt->execute();
    }
    
    /**
     * Tạo mới mã khuyến mãi
     * @param array $data Thông tin mã khuyến mãi
     * @return bool Kết quả thêm mới
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (code, discount_type, discount_value, max_discount, min_order_value, 
                   start_date, end_date, max_uses, is_active, description) 
                  VALUES 
                  (:code, :discount_type, :discount_value, :max_discount, :min_order_value, 
                   :start_date, :end_date, :max_uses, :is_active, :description)";
        
        $stmt = $this->db->prepare($query);
        
        // Bind các tham số
        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':max_discount', $data['max_discount']);
        $stmt->bindParam(':min_order_value', $data['min_order_value']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':max_uses', $data['max_uses']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':description', $data['description']);
        
        return $stmt->execute();
    }
    
    /**
     * Cập nhật thông tin mã khuyến mãi
     * @param array $data Thông tin mã khuyến mãi
     * @return bool Kết quả cập nhật
     */
    public function update($data) {
        $query = "UPDATE " . $this->table . " 
                  SET code = :code, 
                      discount_type = :discount_type, 
                      discount_value = :discount_value, 
                      max_discount = :max_discount, 
                      min_order_value = :min_order_value, 
                      start_date = :start_date, 
                      end_date = :end_date, 
                      max_uses = :max_uses, 
                      is_active = :is_active, 
                      description = :description 
                  WHERE promotion_id = :promotion_id";
        
        $stmt = $this->db->prepare($query);
        
        // Bind các tham số
        $stmt->bindParam(':promotion_id', $data['promotion_id']);
        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':max_discount', $data['max_discount']);
        $stmt->bindParam(':min_order_value', $data['min_order_value']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':max_uses', $data['max_uses']);
        $stmt->bindParam(':is_active', $data['is_active']);
        $stmt->bindParam(':description', $data['description']);
        
        return $stmt->execute();
    }
    
    /**
     * Xóa mã khuyến mãi
     * @param int $promotion_id ID của mã khuyến mãi
     * @return bool Kết quả xóa
     */
    public function delete($promotion_id) {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE promotion_id = :promotion_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':promotion_id', $promotion_id);
        
        return $stmt->execute();
    }
    
    /**
     * Lấy danh sách tất cả mã khuyến mãi
     * @return array Danh sách khuyến mãi
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " 
                  ORDER BY is_active DESC, end_date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy danh sách mã khuyến mãi đang hoạt động
     * @return array Danh sách khuyến mãi
     */
    public function getActive() {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE is_active = 1 
                  AND start_date <= NOW() 
                  AND end_date >= NOW() 
                  AND (max_uses = 0 OR uses < max_uses) 
                  ORDER BY end_date ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 