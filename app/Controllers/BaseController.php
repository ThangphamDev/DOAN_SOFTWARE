<?php

namespace App\Controllers;

require_once __DIR__ . '/../config/Database.php';

class BaseController {
    protected $db;
    
    public function __construct() {
        $database = new \Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Tải model
     */
    protected function model($model) {
        $modelFile = __DIR__ . '/../Models/' . $model . '.php';
        $modelClass = "\\App\\Models\\" . $model;
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelClass($this->db);
        }
        
        return null;
    }
    
    /**
     * Hiển thị view
     */
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../Views/' . $view . '.php';
    }
    
    /**
     * Chuyển hướng
     */
    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
} 