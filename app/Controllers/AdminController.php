<?php
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/ProductVariant.php';

class AdminController {
    private $product;
    private $category;
    private $order;
    private $user;
    private $productVariant;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->product = new Product($db);
        $this->category = new Category($db);
        $this->order = new Order($db);
        $this->user = new User($db);
        $this->productVariant = new ProductVariant($db);
    }

    // Kiểm tra quyền admin
    private function checkAdminAccess() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }

    // Trang dashboard admin
    public function dashboard() {
        $this->checkAdminAccess();
        
        // Thống kê tổng quan
        $totalOrders = $this->order->countAll();
        $totalProducts = $this->product->countAll();
        $totalUsers = $this->user->countAll();
        $totalRevenue = $this->order->getTotalRevenue();
        
        // Doanh thu theo tháng (6 tháng gần nhất)
        $revenueByMonth = $this->order->getRevenueByMonth(6);
        
        // Top 5 sản phẩm bán chạy
        $topProducts = $this->product->getTopProducts(5);
        
        // Đơn hàng gần đây
        $recentOrders = $this->order->getRecentOrders(5);
        
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }
    
    // Quản lý sản phẩm
    public function products() {
        $this->checkAdminAccess();
        
        $products = $this->product->readAll(); // Đọc tất cả sản phẩm, kể cả không available
        require_once __DIR__ . '/../Views/admin/products/index.php';
    }
    
    // Form tạo sản phẩm mới
    public function createProduct() {
        $this->checkAdminAccess();
        
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/admin/products/create.php';
    }
    
    // Xử lý lưu sản phẩm mới
    public function storeProduct() {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->category_id = $_POST['category_id'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->base_price = $_POST['base_price'];
            $this->product->is_available = isset($_POST['is_available']) ? 1 : 0;
            $this->product->is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            // Xử lý hình ảnh
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                // Có file upload
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/products/';
                // Tạo thư mục nếu nó không tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = basename($_FILES['image_file']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Chỉ cho phép các file hình ảnh
                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileExt, $allowedTypes)) {
                    // Tạo tên file mới để tránh trùng lặp
                    $newFileName = 'product_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
                    $targetPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                        $this->product->image_url = '/public/images/products/' . $newFileName;
                    } else {
                        $_SESSION['error'] = "Không thể tải lên hình ảnh. Vui lòng kiểm tra quyền thư mục.";
                        // Sử dụng URL hiện tại nếu có
                        $this->product->image_url = $_POST['image_url'] ?? '';
                    }
                } else {
                    $_SESSION['error'] = "Chỉ cho phép tải lên các file hình ảnh (jpg, jpeg, png, gif).";
                    // Sử dụng URL hiện tại nếu có
                    $this->product->image_url = $_POST['image_url'] ?? '';
                }
            } else {
                // Không có file, sử dụng URL nếu có
                $this->product->image_url = $_POST['image_url'] ?? '';
            }

            if($this->product->create()) {
                // Xử lý biến thể nếu có
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
                header("Location: /admin/products");
                exit();
            } else {
                $_SESSION['error'] = "Không thể tạo sản phẩm mới";
                $categories = $this->category->read();
                require_once __DIR__ . '/../Views/admin/products/create.php';
            }
        }
    }
    
    // Form chỉnh sửa sản phẩm
    public function editProduct($id) {
        $this->checkAdminAccess();
        
        $this->product->product_id = $id;
        $product = $this->product->readOne();
        if($product) {
            $categories = $this->category->read();
            $variants = $this->productVariant->readByProduct($id);
            require_once __DIR__ . '/../Views/admin/products/edit.php';
        } else {
            $_SESSION['error'] = "Sản phẩm không tồn tại";
            header("Location: /admin/products");
            exit();
        }
    }
    
    // Xử lý cập nhật sản phẩm
    public function updateProduct($id) {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->product->product_id = $id;
            $this->product->category_id = $_POST['category_id'];
            $this->product->name = $_POST['name'];
            $this->product->description = $_POST['description'];
            $this->product->base_price = $_POST['base_price'];
            $this->product->is_available = isset($_POST['is_available']) ? 1 : 0;
            $this->product->is_featured = isset($_POST['is_featured']) ? 1 : 0;
            
            // Xử lý hình ảnh
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                // Có file upload
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/products/';
                // Tạo thư mục nếu nó không tồn tại
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = basename($_FILES['image_file']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Chỉ cho phép các file hình ảnh
                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileExt, $allowedTypes)) {
                    // Tạo tên file mới để tránh trùng lặp
                    $newFileName = 'product_' . $id . '_' . time() . '.' . $fileExt;
                    $targetPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                        $this->product->image_url = '/public/images/products/' . $newFileName;
                    } else {
                        $_SESSION['error'] = "Không thể tải lên hình ảnh. Vui lòng kiểm tra quyền thư mục.";
                        // Sử dụng URL hiện tại nếu có
                        $this->product->image_url = $_POST['image_url'] ?? '';
                    }
                } else {
                    $_SESSION['error'] = "Chỉ cho phép tải lên các file hình ảnh (jpg, jpeg, png, gif).";
                    // Sử dụng URL hiện tại nếu có
                    $this->product->image_url = $_POST['image_url'] ?? '';
                }
            } else {
                // Không có file, sử dụng URL nếu có
                $this->product->image_url = $_POST['image_url'] ?? '';
            }

            if($this->product->update()) {
                // Xử lý biến thể nếu có
                if (isset($_POST['variants']) && is_array($_POST['variants'])) {
                    // Xóa biến thể cũ
                    $this->productVariant->deleteByProduct($id);
                    
                    // Thêm biến thể mới
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
                header("Location: /admin/products");
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật sản phẩm";
                // Đọc lại thông tin sản phẩm để hiển thị form
                $this->product->product_id = $id;
                $product = $this->product->readOne();
                $categories = $this->category->read();
                $variants = $this->productVariant->readByProduct($id);
                require_once __DIR__ . '/../Views/admin/products/edit.php';
            }
        } else {
            // Nếu không phải POST request, chuyển hướng đến trang edit
            header("Location: /admin/products/{$id}/edit");
            exit();
        }
    }
    
    // Xóa sản phẩm
    public function deleteProduct($id) {
        $this->checkAdminAccess();
        
        $this->product->product_id = $id;
        
        // Xóa tất cả biến thể của sản phẩm
        $this->productVariant->deleteByProduct($id);
        
        // Xóa sản phẩm
        if($this->product->delete()) {
            $_SESSION['success'] = "Sản phẩm đã được xóa";
        } else {
            $_SESSION['error'] = "Không thể xóa sản phẩm";
        }
        
        header("Location: /admin/products");
        exit();
    }
    
    // Quản lý danh mục
    public function categories() {
        $this->checkAdminAccess();
        
        $categories = $this->category->read();
        require_once __DIR__ . '/../Views/admin/categories/index.php';
    }
    
    // Form tạo danh mục mới
    public function createCategory() {
        $this->checkAdminAccess();
        
        require_once __DIR__ . '/../Views/admin/categories/create.php';
    }
    
    // Xử lý lưu danh mục mới
    public function storeCategory() {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            $this->category->image_url = $_POST['image_url'] ?? null;

            if($this->category->create()) {
                $_SESSION['success'] = "Danh mục đã được tạo thành công";
                header("Location: /admin/categories");
                exit();
            } else {
                $_SESSION['error'] = "Không thể tạo danh mục mới";
                require_once __DIR__ . '/../Views/admin/categories/create.php';
            }
        }
    }
    
    // Form chỉnh sửa danh mục
    public function editCategory($id) {
        $this->checkAdminAccess();
        
        $this->category->category_id = $id;
        if($this->category->readOne()) {
            require_once __DIR__ . '/../Views/admin/categories/edit.php';
        } else {
            $_SESSION['error'] = "Danh mục không tồn tại";
            header("Location: /admin/categories");
            exit();
        }
    }
    
    // Xử lý cập nhật danh mục
    public function updateCategory($id) {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->category->category_id = $id;
            $this->category->name = $_POST['name'];
            $this->category->description = $_POST['description'];
            $this->category->image_url = $_POST['image_url'] ?? null;

            if($this->category->update()) {
                $_SESSION['success'] = "Danh mục đã được cập nhật thành công";
                header("Location: /admin/categories");
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật danh mục";
                require_once __DIR__ . '/../Views/admin/categories/edit.php';
            }
        }
    }
    
    // Xóa danh mục
    public function deleteCategory($id) {
        $this->checkAdminAccess();
        
        $this->category->category_id = $id;
        
        // Kiểm tra xem danh mục có sản phẩm không
        if($this->product->countByCategory($id) > 0) {
            $_SESSION['error'] = "Không thể xóa danh mục đang có sản phẩm";
        } else {
            if($this->category->delete()) {
                $_SESSION['success'] = "Danh mục đã được xóa";
            } else {
                $_SESSION['error'] = "Không thể xóa danh mục";
            }
        }
        
        header("Location: /admin/categories");
        exit();
    }
    
    // Quản lý đơn hàng
    public function orders() {
        $this->checkAdminAccess();
        
        $orders = $this->order->readAll();
        require_once __DIR__ . '/../Views/admin/orders/index.php';
    }
    
    // Chi tiết đơn hàng
    public function orderDetail($id) {
        $this->checkAdminAccess();
        
        $this->order->order_id = $id;
        $order = $this->order->readOne();
        $orderItems = $this->order->getOrderItems($id);
        
        if($order) {
            require_once __DIR__ . '/../Views/admin/orders/detail.php';
        } else {
            $_SESSION['error'] = "Đơn hàng không tồn tại";
            header("Location: /admin/orders");
            exit();
        }
    }
    
    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($id) {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->order->order_id = $id;
            $this->order->status = $_POST['status'];
            
            if($this->order->updateStatus()) {
                $_SESSION['success'] = "Trạng thái đơn hàng đã được cập nhật";
            } else {
                $_SESSION['error'] = "Không thể cập nhật trạng thái đơn hàng";
            }
            
            header("Location: /admin/orders/" . $id);
            exit();
        }
    }
    
    // Báo cáo doanh thu
    public function reports() {
        $this->checkAdminAccess();
        
        // Mặc định báo cáo tháng hiện tại
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Thống kê doanh thu trong tháng
        $monthlyRevenue = $this->order->getRevenueByDay($month, $year);
        
        // Thống kê doanh thu theo sản phẩm trong tháng
        $productRevenue = $this->order->getRevenueByProduct($month, $year);
        
        // Thống kê doanh thu theo danh mục trong tháng
        $categoryRevenue = $this->order->getRevenueByCategory($month, $year);
        
        require_once __DIR__ . '/../Views/admin/reports/index.php';
    }
    
    // Quản lý người dùng
    public function users() {
        $this->checkAdminAccess();
        
        $users = $this->user->readAll();
        require_once __DIR__ . '/../Views/admin/users/index.php';
    }
    
    // Chi tiết người dùng
    public function userDetail($id) {
        $this->checkAdminAccess();
        
        $this->user->user_id = $id;
        $user = $this->user->readOne();
        
        if($user) {
            // Lấy danh sách đơn hàng của người dùng
            $userOrders = $this->order->getOrdersByUser($id);
            
            // Tính toán thống kê đơn hàng
            $orderCount = count($userOrders);
            $totalSpent = 0;
            $lastOrderDate = null;
            
            if ($orderCount > 0) {
                // Tính tổng chi tiêu
                foreach ($userOrders as $order) {
                    $totalSpent += $order['total_amount'];
                }
                
                // Lấy ngày đặt hàng gần nhất
                $lastOrderDate = $userOrders[0]['order_date'];
            }
            
            require_once __DIR__ . '/../Views/admin/users/detail.php';
        } else {
            $_SESSION['error'] = "Người dùng không tồn tại";
            header("Location: /admin/users");
            exit();
        }
    }
    
    // Thay đổi quyền người dùng
    public function updateUserRole($id) {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->user_id = $id;
            $this->user->role = $_POST['role'];
            
            if($this->user->updateRole()) {
                $_SESSION['success'] = "Quyền người dùng đã được cập nhật";
            } else {
                $_SESSION['error'] = "Không thể cập nhật quyền người dùng";
            }
            
            header("Location: /admin/users/" . $id);
            exit();
        }
    }
    
    // Cập nhật trạng thái người dùng
    public function updateUserStatus($id) {
        $this->checkAdminAccess();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->user_id = $id;
            $this->user->status = $_POST['status'];
            
            if($this->user->updateStatus()) {
                $_SESSION['success'] = "Trạng thái người dùng đã được cập nhật";
            } else {
                $_SESSION['error'] = "Không thể cập nhật trạng thái người dùng";
            }
            
            header("Location: /admin/users/" . $id);
            exit();
        }
    }
} 