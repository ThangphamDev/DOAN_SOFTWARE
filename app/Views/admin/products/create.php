<?php
$pageTitle = "Thêm sản phẩm mới";
$currentPage = "products";

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Thêm sản phẩm mới</h1>
    <a href="/admin/products" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Thông tin sản phẩm</h5>
    </div>
    <div class="card-body">
        <form action="/admin/products/store" method="POST" enctype="multipart/form-data">
            <div class="row mb-4">
                <!-- Thông tin cơ bản -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="base_price" class="form-label">Giá cơ bản <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="base_price" name="base_price" min="0" required>
                            <span class="input-group-text">VNĐ</span>
                        </div>
                    </div>
                </div>
                
                <!-- Hình ảnh và trạng thái -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh sản phẩm</label>
                        <div class="mt-2 mb-3">
                            <img id="imagePreview" src="/public/images/default-product.jpg" class="img-fluid product-image-preview border rounded" 
                                 alt="Product preview" style="max-height: 200px; max-width: 100%;">
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-pane" type="button" role="tab" aria-controls="upload-pane" aria-selected="true">
                                            Tải lên
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="url-tab" data-bs-toggle="tab" data-bs-target="#url-pane" type="button" role="tab" aria-controls="url-pane" aria-selected="false">
                                            Đường dẫn
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="upload-pane" role="tabpanel" aria-labelledby="upload-tab">
                                        <input type="file" class="form-control" id="image_file" name="image_file" accept="image/*">
                                        <small class="form-text text-muted">Chọn file hình ảnh (JPG, PNG, GIF).</small>
                                    </div>
                                    <div class="tab-pane fade" id="url-pane" role="tabpanel" aria-labelledby="url-tab">
                                        <input type="text" class="form-control image-url-input" id="image_url" name="image_url" 
                                               placeholder="Nhập đường dẫn hình ảnh">
                                        <small class="form-text text-muted">Ví dụ: /public/images/products/ten-anh.jpg</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available" checked>
                            <label class="form-check-label" for="is_available">Đang bán</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                            <label class="form-check-label" for="is_featured">Sản phẩm nổi bật</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần biến thể sản phẩm -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Biến thể sản phẩm</h5>
                    <button type="button" class="btn btn-sm btn-success" id="addVariantBtn">
                        <i class="fas fa-plus"></i> Thêm biến thể
                    </button>
                </div>
                <div class="card-body">
                    <div id="variantsContainer">
                        <!-- JS sẽ thêm các hàng biến thể vào đây -->
                    </div>
                    <div class="text-muted small">
                        <p class="mb-0">
                            <i class="fas fa-info-circle"></i> 
                            Biến thể giúp bạn định nghĩa các tùy chọn cho sản phẩm như kích thước, màu sắc, v.v.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary me-2" onclick="window.location.href='/admin/products'">Hủy</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

$customScript = "
    // Show image preview when URL is entered
    document.getElementById('image_url').addEventListener('input', function() {
        const preview = document.getElementById('imagePreview');
        if (this.value.trim()) {
            preview.src = this.value;
            preview.style.display = 'block';
        } else {
            preview.src = '/public/images/default-product.jpg';
        }
    });
    
    // Show image preview when file is selected
    document.getElementById('image_file').addEventListener('change', function() {
        const preview = document.getElementById('imagePreview');
        const file = this.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
            // Reset URL input when file is selected
            document.getElementById('image_url').value = '';
        }
    });
    
    // Toggle between upload methods
    document.getElementById('upload-tab').addEventListener('click', function() {
        document.getElementById('image_url').value = '';
    });
    
    document.getElementById('url-tab').addEventListener('click', function() {
        document.getElementById('image_file').value = '';
    });
    
    // Variant management
    let variantIndex = 0;
    
    // Add variant row
    document.getElementById('addVariantBtn').addEventListener('click', function() {
        const container = document.getElementById('variantsContainer');
        const row = document.createElement('div');
        row.className = 'row variant-row mb-3 align-items-end';
        row.innerHTML = `
            <div class=\"col-md-4\">
                <label class=\"form-label\">Loại biến thể</label>
                <input type=\"text\" class=\"form-control\" name=\"variants[\${variantIndex}][type]\" placeholder=\"VD: Size\" required>
            </div>
            <div class=\"col-md-4\">
                <label class=\"form-label\">Giá trị</label>
                <input type=\"text\" class=\"form-control\" name=\"variants[\${variantIndex}][value]\" placeholder=\"VD: Nhỏ\" required>
            </div>
            <div class=\"col-md-3\">
                <label class=\"form-label\">Giá thêm</label>
                <input type=\"number\" class=\"form-control\" name=\"variants[\${variantIndex}][price]\" value=\"0\" min=\"0\" required>
            </div>
            <div class=\"col-md-1\">
                <button type=\"button\" class=\"btn btn-danger remove-variant-btn\">
                    <i class=\"fas fa-trash\"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        
        // Add event listener to the remove button
        row.querySelector('.remove-variant-btn').addEventListener('click', function() {
            container.removeChild(row);
        });
        
        variantIndex++;
    });
";

require_once __DIR__ . '/../layouts/admin_layout.php';
?> 