<?php
class ProductVariant {
    private $conn;
    private $table_name = "productvariants";

    public $variant_id;
    public $product_id;
    public $variant_type;
    public $variant_value;
    public $additional_price;
    public $is_available;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả biến thể của sản phẩm
    public function readByProduct($product_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE product_id = :product_id
                ORDER BY variant_type, variant_value";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();

        return $stmt;
    }

    // Lấy tất cả biến thể của sản phẩm theo loại
    public function readByProductAndType($product_id, $variant_type) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE product_id = :product_id AND variant_type = :variant_type
                ORDER BY variant_value";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":variant_type", $variant_type);
        $stmt->execute();

        return $stmt;
    }

    // Lấy một biến thể theo ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE variant_id = :variant_id
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":variant_id", $this->variant_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->product_id = $row['product_id'];
            $this->variant_type = $row['variant_type'];
            $this->variant_value = $row['variant_value'];
            $this->additional_price = $row['additional_price'];
            $this->is_available = $row['is_available'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Tạo biến thể mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (product_id, variant_type, variant_value, additional_price, is_available)
                VALUES
                (:product_id, :variant_type, :variant_value, :additional_price, :is_available)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->variant_type = htmlspecialchars(strip_tags($this->variant_type));
        $this->variant_value = htmlspecialchars(strip_tags($this->variant_value));

        // Bind các giá trị
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":variant_type", $this->variant_type);
        $stmt->bindParam(":variant_value", $this->variant_value);
        $stmt->bindParam(":additional_price", $this->additional_price);
        $stmt->bindParam(":is_available", $this->is_available);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật biến thể
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    variant_type = :variant_type,
                    variant_value = :variant_value,
                    additional_price = :additional_price,
                    is_available = :is_available
                WHERE
                    variant_id = :variant_id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->variant_type = htmlspecialchars(strip_tags($this->variant_type));
        $this->variant_value = htmlspecialchars(strip_tags($this->variant_value));

        // Bind các giá trị
        $stmt->bindParam(":variant_type", $this->variant_type);
        $stmt->bindParam(":variant_value", $this->variant_value);
        $stmt->bindParam(":additional_price", $this->additional_price);
        $stmt->bindParam(":is_available", $this->is_available);
        $stmt->bindParam(":variant_id", $this->variant_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa biến thể
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE variant_id = :variant_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":variant_id", $this->variant_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Xóa tất cả biến thể của một sản phẩm
    public function deleteByProduct($product_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Lấy danh sách các loại biến thể của sản phẩm
    public function getVariantTypes($product_id) {
        $query = "SELECT DISTINCT variant_type FROM " . $this->table_name . "
                WHERE product_id = :product_id
                ORDER BY variant_type";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();

        $types = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $types[] = $row['variant_type'];
        }

        return $types;
    }
    
    // Kiểm tra xem một biến thể có tồn tại không
    public function variantExists($product_id, $variant_type, $variant_value) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . "
                WHERE product_id = :product_id 
                AND variant_type = :variant_type 
                AND variant_value = :variant_value";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":variant_type", $variant_type);
        $stmt->bindParam(":variant_value", $variant_value);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['count'] > 0;
    }
} 