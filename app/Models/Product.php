<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $product_id;
    public $category_id;
    public $name;
    public $description;
    public $base_price;
    public $image_url;
    public $is_available;
    public $is_featured;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function read() {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.is_available = 1
                ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    // Lấy tất cả sản phẩm (cho admin - bao gồm cả không available)
    public function readAll() {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lấy sản phẩm theo ID
    public function readOne() {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.product_id = :product_id
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->base_price = $row['base_price'];
            $this->image_url = $row['image_url'];
            $this->is_available = $row['is_available'];
            $this->is_featured = $row['is_featured'];
            $this->category_id = $row['category_id'];
            return $this;
        }
        return false;
    }

    // Lấy sản phẩm theo danh mục
    public function readByCategory($category_id, $limit = null, $excludeId = null) {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.category_id = :category_id AND p.is_available = 1";
        
        // Nếu có excludeId, loại trừ sản phẩm hiện tại
        if ($excludeId) {
            $query .= " AND p.product_id != :exclude_id";
        }
        
        $query .= " ORDER BY RAND()";
        
        // Nếu có limit, giới hạn số lượng
        if ($limit) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        
        if ($excludeId) {
            $stmt->bindParam(":exclude_id", $excludeId);
        }
        
        if ($limit) {
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();

        return $stmt;
    }

    // Lấy sản phẩm nổi bật
    public function readFeatured() {
        $query = "SELECT p.*, c.name as category_name
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                WHERE p.is_featured = 1 AND p.is_available = 1
                ORDER BY p.created_at DESC
                LIMIT 0,6";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Tạo sản phẩm mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                (category_id, name, description, base_price, image_url, is_available, is_featured)
                VALUES
                (:category_id, :name, :description, :base_price, :image_url, :is_available, :is_featured)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));

        // Bind các giá trị
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":is_available", $this->is_available);
        $stmt->bindParam(":is_featured", $this->is_featured);

        if($stmt->execute()) {
            $this->product_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    category_id = :category_id,
                    name = :name,
                    description = :description,
                    base_price = :base_price,
                    image_url = :image_url,
                    is_available = :is_available,
                    is_featured = :is_featured
                WHERE
                    product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));

        // Bind các giá trị
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":base_price", $this->base_price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":is_available", $this->is_available);
        $stmt->bindParam(":is_featured", $this->is_featured);
        $stmt->bindParam(":product_id", $this->product_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa sản phẩm
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->product_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Đếm tổng số sản phẩm
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Đếm số sản phẩm theo danh mục
    public function countByCategory($category_id) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }
    
    // Lấy top sản phẩm bán chạy
    public function getTopProducts($limit = 5) {
        $query = "SELECT p.*, c.name as category_name, COUNT(od.product_id) as sold_count
                FROM " . $this->table_name . " p
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN orderitems od ON p.product_id = od.product_id
                GROUP BY p.product_id
                ORDER BY sold_count DESC
                LIMIT :limit";
                
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }
} 