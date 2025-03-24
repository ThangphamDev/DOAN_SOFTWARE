<?php
require_once __DIR__ . '/../Models/Category.php';

class CategoryController {
    private $category;

    public function __construct($db) {
        $this->category = new Category($db);
    }

    // Hiển thị danh sách danh mục
    public function index() {
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/categories/index.php';
    }

    // Hiển thị chi tiết danh mục
    public function show($id) {
        $this->category->category_id = $id;
        if($this->category->readOne()) {
            require_once __DIR__ . '/../Views/categories/show.php';
        } else {
            header("Location: /categories");
        }
    }

    // Hiển thị form tạo danh mục mới
    public function create() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        require_once __DIR__ . '/../Views/categories/create.php';
    }

    // Xử lý tạo danh mục mới
    public function store() {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            $this->category->image_url = $_POST['image_url'];
            $this->category->is_active = isset($_POST['is_active']) ? 1 : 0;

            if($this->category->create()) {
                header("Location: /categories");
            } else {
                $error = "Không thể tạo danh mục mới.";
                require_once __DIR__ . '/../Views/categories/create.php';
            }
        }
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id) {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        $this->category->category_id = $id;
        if($this->category->readOne()) {
            require_once __DIR__ . '/../Views/categories/edit.php';
        } else {
            header("Location: /categories");
        }
    }

    // Xử lý cập nhật danh mục
    public function update($id) {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->category_id = $id;
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            $this->category->image_url = $_POST['image_url'];
            $this->category->is_active = isset($_POST['is_active']) ? 1 : 0;

            if($this->category->update()) {
                header("Location: /categories");
            } else {
                $error = "Không thể cập nhật danh mục.";
                require_once __DIR__ . '/../Views/categories/edit.php';
            }
        }
    }

    // Xử lý xóa danh mục
    public function destroy($id) {
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit();
        }

        $this->category->category_id = $id;
        if($this->category->delete()) {
            header("Location: /categories");
        } else {
            $error = "Không thể xóa danh mục.";
            require_once __DIR__ . '/../Views/categories/index.php';
        }
    }
} 