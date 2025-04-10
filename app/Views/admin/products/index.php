<?php
$pageTitle = "Quản lý sản phẩm";
$currentPage = "products";

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Quản lý sản phẩm</h1>
    <a href="/admin/products/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm mới sản phẩm
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách sản phẩm</h5>
        <div class="input-group w-50">
            <input type="text" id="searchProduct" class="form-control" placeholder="Tìm kiếm sản phẩm...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Hình ảnh</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Nổi bật</th>
                        <th scope="col">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($products->rowCount() > 0): ?>
                        <?php while($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <th scope="row"><?php echo $product['product_id']; ?></th>
                                <td>
                                    <?php 
                                    $imageUrl = !empty($product['image_url']) ? $product['image_url'] : '/public/images/default-product.jpg';
                                    // Ensure the image URL has the correct format
                                    if(!str_starts_with($imageUrl, '/')) {
                                        $imageUrl = '/' . $imageUrl;
                                    }
                                    if(!str_starts_with($imageUrl, '/public')) {
                                        $imageUrl = '/public' . $imageUrl;
                                    }
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="product-thumbnail" width="50" height="50"
                                         style="object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($product['category_name'] ?? 'Không có danh mục'); ?>
                                </td>
                                <td>
                                    <?php echo number_format($product['base_price'], 0, ',', '.'); ?> đ
                                </td>
                                <td>
                                    <?php if($product['is_available']): ?>
                                        <span class="badge bg-success">Đang bán</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ngừng bán</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($product['is_featured']): ?>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-star"></i> Nổi bật
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/products/<?php echo $product['product_id']; ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/products/<?php echo $product['product_id']; ?>/edit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/admin/products/<?php echo $product['product_id']; ?>/delete" class="btn btn-sm btn-outline-danger btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted">Không có sản phẩm nào.</p>
                                <a href="/admin/products/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Thêm sản phẩm mới
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

$customScript = "
    // Filter table on search
    document.getElementById('searchProduct').addEventListener('keyup', function() {
        const searchText = this.value.toLowerCase();
        const table = document.querySelector('table');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const categoryName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            
            if (productName.includes(searchText) || categoryName.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
";

require_once __DIR__ . '/../layouts/admin_layout.php';
?> 