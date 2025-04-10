<?php

namespace App\Models;

class BaseModel {
    protected $db;
    
    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            // Tự động kết nối nếu không được cung cấp
            require_once __DIR__ . '/../config/Database.php';
            $database = new \Database();
            $this->db = $database->getConnection();
        }
    }
    
    /**
     * Lấy đối tượng kết nối database
     * @return PDO
     */
    public function getDb() {
        return $this->db;
    }
    
    /**
     * Thực hiện truy vấn và trả về kết quả
     * @param string $query Câu truy vấn SQL
     * @param array $params Tham số cho câu truy vấn
     * @return PDOStatement
     */
    protected function query($query, $params = []) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Thực hiện truy vấn và trả về một bản ghi
     * @param string $query Câu truy vấn SQL
     * @param array $params Tham số cho câu truy vấn
     * @return array|false
     */
    protected function fetchOne($query, $params = []) {
        $stmt = $this->query($query, $params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Thực hiện truy vấn và trả về tất cả bản ghi
     * @param string $query Câu truy vấn SQL
     * @param array $params Tham số cho câu truy vấn
     * @return array
     */
    protected function fetchAll($query, $params = []) {
        $stmt = $this->query($query, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 