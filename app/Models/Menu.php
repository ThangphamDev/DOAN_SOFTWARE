<?php
class Menu {
    private $conn;
    private $table_name = "menu_items";

    public $id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả món ăn theo danh mục
    public function getItemsByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = :category";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        return $stmt;
    }

    // Lấy tất cả món ăn
    public function getAllItems() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
} 