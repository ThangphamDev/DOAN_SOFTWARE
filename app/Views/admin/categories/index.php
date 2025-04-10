<?php
$page_title = "Quản lý danh mục";
$currentPage = 'categories';

// Bắt đầu output buffering
ob_start();

require_once __DIR__ . '/../../../Models/Category.php';
require_once __DIR__ . '/../../../config/Database.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /');
    exit;
}

// Khởi tạo database connection
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$categories = $category->read();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý danh mục</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lý danh mục</li>
    </ol>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-th-large me-1"></i>
                Danh sách danh mục
            </div>
            <a href="/admin/categories/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm danh mục
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="categoriesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Hình ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($categories->rowCount() > 0): ?>
                            <?php while($category = $categories->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $category['category_id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                                    <td>
                                        <?php if(!empty($category['image_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($category['image_url']); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" width="50">
                                        <?php else: ?>
                                            <span class="text-muted">Không có hình ảnh</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/admin/categories/<?php echo $category['category_id']; ?>/edit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $category['category_id']; ?>)">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu danh mục</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Form ẩn để xóa danh mục -->
<form id="deleteForm" action="" method="POST" style="display:none;">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#categoriesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            order: [[0, 'desc']]
        });
    });
    
    function confirmDelete(categoryId) {
        if (confirm('Bạn có chắc chắn muốn xóa danh mục này? Lưu ý: Không thể xóa danh mục đang có sản phẩm.')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/categories/${categoryId}/delete`;
            form.submit();
        }
    }
</script>

<?php
// Lấy nội dung đã buffer và gán vào biến $content
$content = ob_get_clean();

// Include layout
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 