<?php
class Category {
    private $conn;
    private $table_name = "categories";

    public $category_id;
    public $name;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy một danh mục theo ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->category_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->description = $row['description'];
            return true;
        }
        return false;
    }

    // Tạo danh mục mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật danh mục
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = ?, description = ? WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->category_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa danh mục
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $stmt->bindParam(1, $this->category_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
} 