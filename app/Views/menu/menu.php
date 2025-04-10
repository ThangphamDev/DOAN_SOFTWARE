<?php
require_once __DIR__ . '/../../Models/Product.php';
require_once __DIR__ . '/../../Models/Category.php';
require_once __DIR__ . '/../../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$categories = $category->read();
$products = $product->read();

// Tạo mảng sản phẩm theo danh mục
$productsByCategory = [];
while ($prod = $products->fetch(PDO::FETCH_ASSOC)) {
    $productsByCategory[$prod['category_id']][] = $prod;
}

// Xử lý lọc sản phẩm nếu có
$filterCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Xử lý sắp xếp
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'default';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - CAFET2K</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/menu.css">
</head>
<body>
    <?php include_once('app/Views/components/header.php'); ?>

    <div class="menu-hero">
        <div class="container">
            <h1>Thực Đơn CAFET2K</h1>
            <p>Khám phá đa dạng lựa chọn đồ uống và món ăn nhẹ của chúng tôi</p>
        </div>
    </div>

    <div class="menu-section">
        <div class="container">
            <!-- Filters and Search Bar -->
            <div class="menu-filters">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <form class="search-form" action="" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Tìm kiếm món..." name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                <button class="btn btn-search" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <select class="form-select sort-select" aria-label="Sắp xếp" id="sortOptions">
                                <option value="default" <?php echo $sortOption == 'default' ? 'selected' : ''; ?>>Mặc định</option>
                                <option value="price_asc" <?php echo $sortOption == 'price_asc' ? 'selected' : ''; ?>>Giá: Thấp đến cao</option>
                                <option value="price_desc" <?php echo $sortOption == 'price_desc' ? 'selected' : ''; ?>>Giá: Cao đến thấp</option>
                                <option value="name_asc" <?php echo $sortOption == 'name_asc' ? 'selected' : ''; ?>>Tên: A-Z</option>
                                <option value="name_desc" <?php echo $sortOption == 'name_desc' ? 'selected' : ''; ?>>Tên: Z-A</option>
                            </select>
                            <div class="view-toggle btn-group" role="group" aria-label="Kiểu hiển thị">
                                <button type="button" class="btn btn-outline-primary active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="category-sidebar" style="position: sticky; top: 80px;">
                        <div class="sidebar-header">
                            <h3>Danh Mục</h3>
                            <button class="d-lg-none btn btn-link category-toggle" type="button">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        
                        <div class="category-content">
                            <ul class="category-list">
                                <li>
                                    <a href="?<?php echo !empty($searchTerm) ? 'search='.urlencode($searchTerm).'&' : ''; ?><?php echo !empty($sortOption) && $sortOption != 'default' ? 'sort='.urlencode($sortOption).'&' : ''; ?>" 
                                       class="category-link <?php echo $filterCategory == 0 ? 'active' : ''; ?>">
                                        <i class="fas fa-coffee"></i> Tất cả sản phẩm
                                    </a>
                                </li>
                                <?php 
                                // Reset con trỏ của categories
                                $categories->execute();
                                while($cat = $categories->fetch(PDO::FETCH_ASSOC)): 
                                    if (isset($productsByCategory[$cat['category_id']])):
                                ?>
                                    <li>
                                        <a href="#category-<?php echo $cat['category_id']; ?>" 
                                           class="category-link"
                                           data-category="<?php echo $cat['category_id']; ?>">
                                            <i class="<?php echo !empty($cat['icon']) ? $cat['icon'] : 'fas fa-tag'; ?>"></i>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                            <span class="badge bg-secondary ms-2"><?php echo count($productsByCategory[$cat['category_id']]); ?></span>
                                        </a>
                                    </li>
                                <?php 
                                    endif;
                                endwhile; 
                                ?>
                            </ul>
                            
                            <!-- Featured Product -->
                            <div class="featured-product">
                                <h4>Sản phẩm nổi bật</h4>
                                <?php
                                // Giả sử có một sản phẩm nổi bật, có thể thay bằng logic thực tế
                                $featuredProducts = [];
                                foreach ($productsByCategory as $catProds) {
                                    foreach ($catProds as $prod) {
                                        if (!empty($prod['featured']) && $prod['featured'] == 1) {
                                            $featuredProducts[] = $prod;
                                            break;
                                        }
                                    }
                                    if (!empty($featuredProducts)) break;
                                }
                                
                                // Nếu không có sản phẩm nổi bật, lấy sản phẩm đầu tiên
                                if (empty($featuredProducts) && !empty($productsByCategory)) {
                                    $firstCatId = array_key_first($productsByCategory);
                                    if (!empty($productsByCategory[$firstCatId])) {
                                        $featuredProducts[] = $productsByCategory[$firstCatId][0];
                                    }
                                }
                                
                                if (!empty($featuredProducts)):
                                    $featuredProduct = $featuredProducts[0];
                                ?>
                                <div class="featured-product-card">
                                    <div class="featured-product-image">
                                        <img src="<?php echo htmlspecialchars($featuredProduct['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($featuredProduct['name']); ?>"
                                             onerror="handleImageError(this)"
                                             loading="lazy">
                                    </div>
                                    <div class="featured-product-info">
                                        <h5><?php echo htmlspecialchars($featuredProduct['name']); ?></h5>
                                        <p class="price"><?php echo number_format($featuredProduct['base_price'], 0, ',', '.'); ?>đ</p>
                                        <a href="/menu/product/<?php echo $featuredProduct['product_id']; ?>" class="btn btn-sm btn-primary">Xem ngay</a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <div class="products-container" id="products-grid-view">
                        <?php 
                        // Reset con trỏ của categories
                        $categories->execute();
                        while($cat = $categories->fetch(PDO::FETCH_ASSOC)): 
                            if (isset($productsByCategory[$cat['category_id']])): 
                        ?>
                            <div id="category-<?php echo $cat['category_id']; ?>" class="category-section">
                                <div class="category-header-wrapper">
                                    <h2 class="category-header">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </h2>
                                    <?php if (!empty($cat['description'])): ?>
                                        <p class="category-description"><?php echo htmlspecialchars($cat['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="products-grid">
                                    <?php foreach($productsByCategory[$cat['category_id']] as $prod): ?>
                                        <div class="product-card" data-price="<?php echo $prod['base_price']; ?>" data-name="<?php echo htmlspecialchars($prod['name']); ?>">
                                            <div class="product-badges">
                                                <?php if (!empty($prod['is_new']) && $prod['is_new']): ?>
                                                    <span class="badge bg-success">Mới</span>
                                                <?php endif; ?>
                                                <?php if (!empty($prod['is_popular']) && $prod['is_popular']): ?>
                                                    <span class="badge bg-danger">Hot</span>
                                                <?php endif; ?>
                                                <?php if (!empty($prod['discount_percent']) && $prod['discount_percent'] > 0): ?>
                                                    <span class="badge bg-warning">-<?php echo $prod['discount_percent']; ?>%</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="product-image">
                                                <img src="<?php echo htmlspecialchars($prod['image_url']); ?>" 
                                                     alt="<?php echo htmlspecialchars($prod['name']); ?>"
                                                     onerror="handleImageError(this)"
                                                     loading="lazy">
                                                <div class="product-quick-actions">
                                                    <button class="btn-quick-view" data-product-id="<?php echo $prod['product_id']; ?>" title="Xem nhanh">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn-quick-add" data-product-id="<?php echo $prod['product_id']; ?>" title="Thêm vào giỏ">
                                                        <i class="fas fa-cart-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="product-info">
                                                <h4><?php echo htmlspecialchars($prod['name']); ?></h4>
                                                <div class="price-container">
                                                    <?php if (!empty($prod['discount_price']) && $prod['discount_price'] < $prod['base_price']): ?>
                                                        <span class="original-price"><?php echo number_format($prod['base_price'], 0, ',', '.'); ?>đ</span>
                                                        <span class="price"><?php echo number_format($prod['discount_price'], 0, ',', '.'); ?>đ</span>
                                                    <?php else: ?>
                                                        <span class="price"><?php echo number_format($prod['base_price'], 0, ',', '.'); ?>đ</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="product-rating">
                                                    <?php 
                                                    $rating = !empty($prod['rating']) ? $prod['rating'] : 5;
                                                    for ($i = 1; $i <= 5; $i++): 
                                                        if ($i <= $rating): 
                                                    ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php 
                                                        endif;
                                                    endfor; 
                                                    ?>
                                                </div>
                                                
                                                <p class="description"><?php echo htmlspecialchars($prod['description']); ?></p>
                                                
                                                <div class="btn-group product-actions">
                                                    <a href="/menu/product/<?php echo $prod['product_id']; ?>" class="btn btn-primary">Chi Tiết</a>
                                                    <form action="/cart/add" method="POST" class="add-to-cart-form">
                                                        <input type="hidden" name="product_id" value="<?php echo $prod['product_id']; ?>">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <input type="hidden" name="ajax" value="1">
                                                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($prod['name']); ?>">
                                                        <input type="hidden" name="price" value="<?php echo $prod['base_price']; ?>">
                                                        <input type="hidden" name="image_url" value="<?php echo htmlspecialchars($prod['image_url']); ?>">
                                                        <button type="submit" class="btn btn-add-cart">
                                                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endwhile; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewModalLabel">Xem nhanh sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="product-quick-view">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="quick-view-image">
                                    <img src="" alt="Product Image" id="quickViewImage">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="quick-view-info">
                                    <h3 id="quickViewTitle"></h3>
                                    <div class="price-container">
                                        <span class="price" id="quickViewPrice"></span>
                                        <span class="original-price" id="quickViewOriginalPrice"></span>
                                    </div>
                                    <div class="product-rating" id="quickViewRating"></div>
                                    <p id="quickViewDescription"></p>
                                    <form action="/cart/add" method="POST" class="quick-view-form">
                                        <input type="hidden" name="product_id" id="quickViewProductId">
                                        <input type="hidden" name="ajax" value="1">
                                        <input type="hidden" name="name" id="quickViewNameHidden">
                                        <input type="hidden" name="price" id="quickViewPriceHidden">
                                        <input type="hidden" name="image_url" id="quickViewImageHidden">
                                        <div class="quantity-selector">
                                            <label for="quickViewQuantity">Số lượng:</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary minus-btn">-</button>
                                                <input type="number" id="quickViewQuantity" name="quantity" class="form-control text-center" value="1" min="1">
                                                <button type="button" class="btn btn-outline-secondary plus-btn">+</button>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-add-cart">
                                                <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                            </button>
                                            <a href="" id="quickViewDetailLink" class="btn btn-primary">Xem chi tiết</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <?php include_once('app/Views/components/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý lỗi hình ảnh
        window.handleImageError = function(img) {
            img.onerror = null; // Prevent infinite error loop
            img.src = '/public/images/default-product.jpg';
        };

        // Xử lý smooth scroll khi click vào danh mục
        document.querySelectorAll('.category-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        // Scroll mượt đến phần tử
                        targetElement.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Highlight mục đang active
                        document.querySelectorAll('.category-link').forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                    }
                }
            });
        });

        // Xử lý các form thêm vào giỏ hàng
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const productId = this.querySelector('input[name="product_id"]').value;
                const quantity = this.querySelector('input[name="quantity"]').value;
                const product = this.closest('.product-card');
                const productName = product.querySelector('h4').innerText;
                
                // Tạo FormData object từ form
                const formData = new FormData(this);
                
                // Sử dụng Fetch API để gửi AJAX request
                fetch('/cart/add', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', `Đã thêm ${productName} vào giỏ hàng!`);
                        // Cập nhật số lượng sản phẩm trong giỏ hàng trên header
                        updateCartBadge(data.cart_count || 1);
                    } else {
                        showToast('error', data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                });
            });
        });

        // Highlight danh mục khi scroll
        const observerOptions = {
            root: null,
            rootMargin: '-80px 0px 0px 0px', // Offset để tính toán khi nào section được coi là visible
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const categoryId = entry.target.id;
                    document.querySelectorAll('.category-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + categoryId) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, observerOptions);

        // Observe tất cả các category sections
        document.querySelectorAll('.category-section').forEach(section => {
            observer.observe(section);
        });

        // Toggle giữa grid và list view
        document.querySelectorAll('.view-toggle .btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.view-toggle .btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.getAttribute('data-view');
                const productsContainer = document.querySelector('.products-container');
                
                if (view === 'grid') {
                    productsContainer.classList.remove('list-view');
                    productsContainer.classList.add('grid-view');
                } else {
                    productsContainer.classList.remove('grid-view');
                    productsContainer.classList.add('list-view');
                }
            });
        });

        // Sắp xếp sản phẩm
        document.getElementById('sortOptions').addEventListener('change', function() {
            const sortValue = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);
            window.location.href = url.toString();
        });

        // Xử lý Quick View
        document.querySelectorAll('.btn-quick-view').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('h4').innerText;
                const productDescription = productCard.querySelector('.description').innerText;
                const productImage = productCard.querySelector('.product-image img').src;
                
                let productPrice = productCard.querySelector('.price').innerText;
                let originalPrice = productCard.querySelector('.original-price') ? 
                                    productCard.querySelector('.original-price').innerText : '';
                
                // Update modal content
                document.getElementById('quickViewTitle').innerText = productName;
                document.getElementById('quickViewPrice').innerText = productPrice;
                document.getElementById('quickViewOriginalPrice').innerText = originalPrice;
                document.getElementById('quickViewDescription').innerText = productDescription;
                document.getElementById('quickViewImage').src = productImage;
                document.getElementById('quickViewProductId').value = productId;
                document.getElementById('quickViewNameHidden').value = productName;
                document.getElementById('quickViewPriceHidden').value = productPrice;
                document.getElementById('quickViewImageHidden').value = productImage;
                document.getElementById('quickViewDetailLink').href = `/menu/product/${productId}`;
                
                // Show the rating
                const ratingHTML = productCard.querySelector('.product-rating').innerHTML;
                document.getElementById('quickViewRating').innerHTML = ratingHTML;
                
                // Show modal
                const quickViewModal = new bootstrap.Modal(document.getElementById('quickViewModal'));
                quickViewModal.show();
            });
        });

        // Xử lý Quick Add to Cart
        document.querySelectorAll('.btn-quick-add').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productCard = this.closest('.product-card');
                const productName = productCard.querySelector('h4').innerText;
                const productImage = productCard.querySelector('.product-image img').src;
                const productPrice = productCard.querySelector('.price').innerText.replace(/\D/g, '');
                
                // Create form data
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', 1);
                formData.append('ajax', 1);
                formData.append('name', productName);
                formData.append('price', productPrice);
                formData.append('image_url', productImage);
                
                // Send AJAX request
                fetch('/cart/add', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', `Đã thêm ${productName} vào giỏ hàng!`);
                        // Update cart count if applicable
                        updateCartBadge(data.cart_count || 1);
                    } else {
                        showToast('error', data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                });
            });
        });

        // Hàm cập nhật số lượng trên biểu tượng giỏ hàng
        function updateCartBadge(count) {
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.classList.add('animate__animated', 'animate__rubberBand');
                setTimeout(() => {
                    cartBadge.classList.remove('animate__animated', 'animate__rubberBand');
                }, 1000);
            }
        }

        // Xử lý form "Thêm vào giỏ" trong modal Quick View
        document.querySelector('.quick-view-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = document.getElementById('quickViewProductId').value;
            const quantity = document.getElementById('quickViewQuantity').value;
            const productName = document.getElementById('quickViewTitle').innerText;
            
            // Create form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('/cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', `Đã thêm ${productName} vào giỏ hàng!`);
                    // Đóng modal
                    bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();
                    // Update cart count
                    updateCartBadge(data.cart_count || 1);
                } else {
                    showToast('error', data.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
            });
        });

        // Xử lý tăng giảm số lượng
        document.querySelectorAll('.minus-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input[type="number"]');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });

        document.querySelectorAll('.plus-btn').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input[type="number"]');
                input.value = parseInt(input.value) + 1;
            });
        });

        // Toggle sidebar on mobile
        document.querySelector('.category-toggle').addEventListener('click', function() {
            const content = document.querySelector('.category-content');
            content.classList.toggle('show');
        });

        // Toast notifications
        function showToast(type, message) {
            const toastContainer = document.querySelector('.toast-container');
            
            const toastElement = document.createElement('div');
            toastElement.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'}`;
            toastElement.setAttribute('role', 'alert');
            toastElement.setAttribute('aria-live', 'assertive');
            toastElement.setAttribute('aria-atomic', 'true');
            
            toastElement.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toastElement);
            
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 3000
            });
            
            toast.show();
            
            // Remove after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
    });
    </script>
</body>
</html>