<?php
class ProductVariant {
    private $conn;
    private $table_name = "ProductVariants";

    public $variant_id;
    public $product_id;
    public $size;
    public $price_adjustment;
    public $is_available;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả biến thể của sản phẩm
    public function readByProduct($product_id) {
        $query = "SELECT * FROM " . $this->table_name . "
                WHERE product_id = :product_id
                ORDER BY size";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $product_id);
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
            $this->size = $row['size'];
            $this->price_adjustment = $row['price_adjustment'];
            $this->is_available = $row['is_available'];
            return true;
        }
        return false;
    }

    // Tạo biến thể mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (product_id, size, price_adjustment, is_available)
                VALUES
                (:product_id, :size, :price_adjustment, :is_available)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->size = htmlspecialchars(strip_tags($this->size));

        // Bind các giá trị
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":size", $this->size);
        $stmt->bindParam(":price_adjustment", $this->price_adjustment);
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
                    size = :size,
                    price_adjustment = :price_adjustment,
                    is_available = :is_available
                WHERE
                    variant_id = :variant_id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->size = htmlspecialchars(strip_tags($this->size));

        // Bind các giá trị
        $stmt->bindParam(":size", $this->size);
        $stmt->bindParam(":price_adjustment", $this->price_adjustment);
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
} 