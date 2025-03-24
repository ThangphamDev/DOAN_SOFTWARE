<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/ProductVariant.php';

class ProductController {
    private $product;
    private $productVariant;

    public function __construct($db) {
        $this->product = new Product($db);
        $this->productVariant = new ProductVariant($db);
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $products = $this->product->read();
        require_once __DIR__ . '/../Views/products/index.php';
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id) {
        $this->product->product_id = $id;
        if($this->product->readOne()) {
            $variants = $this->productVariant->readByProduct($id);
            require_once __DIR__ . '/../Views/products/show.php';
        } else {
            header("Location: /products");
        }
    }

    // Hiển thị form tạo sản phẩm mới
    public function create() {
        require_once __DIR__ . '/../Views/products/create.php';
    }

    // Xử lý tạo sản phẩm mới
    public function store() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->category_id = $_POST['category_id'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->base_price = $_POST['base_price'];
            $this->product->image_url = $_POST['image_url'];
            $this->product->is_available = isset($_POST['is_available']) ? 1 : 0;
            $this->product->is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if($this->product->create()) {
                header("Location: /products");
            } else {
                $error = "Không thể tạo sản phẩm mới.";
                require_once __DIR__ . '/../Views/products/create.php';
            }
        }
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id) {
        $this->product->product_id = $id;
        if($this->product->readOne()) {
            require_once __DIR__ . '/../Views/products/edit.php';
        } else {
            header("Location: /products");
        }
    }

    // Xử lý cập nhật sản phẩm
    public function update($id) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->product_id = $id;
            $this->product->category_id = $_POST['category_id'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->base_price = $_POST['base_price'];
            $this->product->image_url = $_POST['image_url'];
            $this->product->is_available = isset($_POST['is_available']) ? 1 : 0;
            $this->product->is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if($this->product->update()) {
                header("Location: /products");
            } else {
                $error = "Không thể cập nhật sản phẩm.";
                require_once __DIR__ . '/../Views/products/edit.php';
            }
        }
    }

    // Xử lý xóa sản phẩm
    public function destroy($id) {
        $this->product->product_id = $id;
        if($this->product->delete()) {
            header("Location: /products");
        } else {
            $error = "Không thể xóa sản phẩm.";
            require_once __DIR__ . '/../Views/products/index.php';
        }
    }

    // Hiển thị sản phẩm theo danh mục
    public function byCategory($category_id) {
        $products = $this->product->readByCategory($category_id);
        require_once __DIR__ . '/../Views/products/index.php';
    }

    // Hiển thị sản phẩm nổi bật
    public function featured() {
        $products = $this->product->readFeatured();
        require_once __DIR__ . '/../Views/products/featured.php';
    }
} 