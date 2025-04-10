<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/ProductVariant.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Feedback.php';

class ProductController {
    private $product;
    private $productVariant;
    private $category;
    private $feedback;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->product = new Product($db);
        $this->productVariant = new ProductVariant($db);
        $this->category = new Category($db);
        $this->feedback = new Feedback($db);
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $products = $this->product->read();
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/products/index.php';
    }

    // Hiển thị chi tiết sản phẩm (old method)
    public function show($id) {
        $this->product->product_id = $id;
        if($this->product->readOne()) {
            $variants = $this->productVariant->readByProduct($id);
            require_once __DIR__ . '/../Views/products/show.php';
        } else {
            header("Location: /products");
        }
    }
    
    // Hiển thị chi tiết sản phẩm với biến thể
    public function detail($id) {
        $this->product->product_id = $id;
        
        // Khởi tạo giá trị mặc định cho các biến
        $reviews = null;
        $averageRating = 0;
        $reviewCount = 0;
        $variants = null;
        $relatedProducts = null;
        
        // Tìm và đọc sản phẩm
        $productExists = $this->product->readOne();
        
        if($productExists) {
            // Lấy thêm thông tin về danh mục
            if ($this->product->category_id) {
                $category = new Category($this->db);
                $category->category_id = $this->product->category_id;
                $category->readOne();
                $this->product->category_name = $category->name;
            }
            
            // Đảm bảo đường dẫn ảnh đúng
            if ($this->product->image_url && !str_starts_with($this->product->image_url, '/')) {
                $this->product->image_url = '/' . $this->product->image_url;
            }
            
            // Thiết lập giá mặc định nếu chưa có
            if (!isset($this->product->price)) {
                $this->product->price = $this->product->base_price;
            }
            
            // Get variants for this product
            $variants = $this->productVariant->readByProduct($id);
            
            // Get related products in the same category - loại trừ sản phẩm hiện tại
            $relatedProducts = $this->product->readByCategory($this->product->category_id, 5, $id);
            
            // Get reviews/feedback for this product - với xử lý lỗi
            try {
                $reviews = $this->feedback->readByProduct($id);
                
                // Calculate average rating if there are reviews
                if ($reviews && $reviews->rowCount() > 0) {
                    $totalRating = 0;
                    $reviewCount = $reviews->rowCount();
                    
                    while ($review = $reviews->fetch(PDO::FETCH_ASSOC)) {
                        $totalRating += $review['rating'];
                    }
                    
                    if ($reviewCount > 0) {
                        $averageRating = $totalRating / $reviewCount;
                    }
                    
                    // Reset the pointer to the beginning of the result set
                    $reviews->execute();
                }
            } catch (PDOException $e) {
                // Log error
                error_log('Lỗi khi lấy đánh giá sản phẩm: ' . $e->getMessage());
                // No feedback available
                $reviews = null;
            }
            
            // Assign product object to a local variable for the view
            $product = $this->product;
        } else {
            // Nếu không tìm thấy sản phẩm, ghi log lỗi
            error_log("Sản phẩm ID {$id} không tồn tại");
            $_SESSION['error'] = "Sản phẩm không tồn tại";
        }
        
        // Luôn hiển thị view, ngay cả khi sản phẩm không tồn tại (view sẽ xử lý hiển thị)
        require_once __DIR__ . '/../Views/products/show.php';
    }

    // Hiển thị form tạo sản phẩm mới
    public function create() {
        $categories = $this->category->read();
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
                // Handle variants if they exist
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variant) {
                        $this->productVariant->product_id = $this->product->product_id;
                        $this->productVariant->variant_type = $variant['type'];
                        $this->productVariant->variant_value = $variant['value'];
                        $this->productVariant->additional_price = $variant['price'] ?? 0;
                        $this->productVariant->is_available = 1;
                        
                        $this->productVariant->create();
                    }
                }
                
                $_SESSION['success'] = "Sản phẩm đã được tạo thành công";
                header("Location: /products");
                exit();
            } else {
                $_SESSION['error'] = "Không thể tạo sản phẩm mới";
                $categories = $this->category->read();
                require_once __DIR__ . '/../Views/products/create.php';
            }
        }
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id) {
        $this->product->product_id = $id;
        if($this->product->readOne()) {
            $categories = $this->category->read();
            $variants = $this->productVariant->readByProduct($id);
            require_once __DIR__ . '/../Views/products/edit.php';
        } else {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            header("Location: /products");
            exit();
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
                // Handle variants if they exist
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    // Delete existing variants first
                    $this->productVariant->deleteByProduct($id);
                    
                    // Add new variants
                    foreach ($_POST['variants'] as $variant) {
                        $this->productVariant->product_id = $id;
                        $this->productVariant->variant_type = $variant['type'];
                        $this->productVariant->variant_value = $variant['value'];
                        $this->productVariant->additional_price = $variant['price'] ?? 0;
                        $this->productVariant->is_available = 1;
                        
                        $this->productVariant->create();
                    }
                }
                
                $_SESSION['success'] = "Sản phẩm đã được cập nhật thành công";
                header("Location: /products");
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật sản phẩm";
                $categories = $this->category->read();
                require_once __DIR__ . '/../Views/products/edit.php';
            }
        }
    }

    // Xử lý xóa sản phẩm
    public function destroy($id) {
        $this->product->product_id = $id;
        
        // Delete variants first (to maintain referential integrity)
        $this->productVariant->deleteByProduct($id);
        
        if($this->product->delete()) {
            $_SESSION['success'] = "Sản phẩm đã được xóa thành công";
            header("Location: /products");
            exit();
        } else {
            $_SESSION['error'] = "Không thể xóa sản phẩm";
            header("Location: /products");
            exit();
        }
    }

    // Hiển thị sản phẩm theo danh mục
    public function byCategory($category_id) {
        $products = $this->product->readByCategory($category_id);
        $category = $this->category->readOne($category_id);
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/products/index.php';
    }

    // Hiển thị sản phẩm nổi bật
    public function featured() {
        $products = $this->product->readFeatured();
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/products/featured.php';
    }
    
    // Tìm kiếm sản phẩm
    public function search() {
        if(isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $products = $this->product->search($keyword);
            $categories = $this->category->read();
            require_once __DIR__ . '/../Views/products/search.php';
        } else {
            header("Location: /products");
            exit();
        }
    }
} 