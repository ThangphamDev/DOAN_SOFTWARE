<?php
require_once __DIR__ . '/../../../Models/Category.php';
require_once __DIR__ . '/../../../config/Database.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: /');
    exit;
}

// Khởi tạo database connection
$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$categories = $category->getAll();

include __DIR__ . '/../../shares/header.php';
?>

<div class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h2>Quản Lý Danh Mục</h2>
            <button class="btn btn-primary" onclick="showAddModal()">
                <i class="fas fa-plus"></i> Thêm Danh Mục
            </button>
        </div>

        <div class="admin-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Mô Tả</th>
                            <th>Thứ Tự</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td><?php echo $cat['category_id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td><?php echo htmlspecialchars($cat['description']); ?></td>
                            <td><?php echo $cat['display_order']; ?></td>
                            <td>
                                <span class="status-badge <?php echo $cat['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $cat['is_active'] ? 'Hoạt động' : 'Ẩn'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-edit" onclick="showEditModal(<?php echo htmlspecialchars(json_encode($cat)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-delete" onclick="deleteCategory(<?php echo $cat['category_id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Danh Mục -->
<div class="modal" id="categoryModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Thêm Danh Mục</h3>
        <form id="categoryForm" onsubmit="saveCategory(event)">
            <input type="hidden" id="categoryId" name="category_id">
            <div class="form-group">
                <label for="name">Tên Danh Mục:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Mô Tả:</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="displayOrder">Thứ Tự Hiển Thị:</label>
                <input type="number" id="displayOrder" name="display_order" min="1">
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="isActive" name="is_active">
                    Hiển thị danh mục
                </label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<style>
.admin-section {
    padding: 30px 0;
    min-height: calc(100vh - 60px);
    background-color: #f8f9fa;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.admin-header h2 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.8rem;
}

.table-responsive {
    overflow-x: auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e9ecef;
}

.table th {
    background: #f8f9fa;
    color: #2c3e50;
    font-weight: 600;
}

.table tr:hover {
    background: #f8f9fa;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.875rem;
}

.btn-edit {
    background: #ffc107;
    color: #000;
}

.btn-edit:hover {
    background: #e0a800;
}

.btn-delete {
    background: #dc3545;
    color: white;
}

.btn-delete:hover {
    background: #c82333;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background: white;
    margin: 50px auto;
    padding: 20px;
    width: 90%;
    max-width: 500px;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
}

.close {
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.close:hover {
    color: #343a40;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2c3e50;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 1rem;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .admin-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .table th,
    .table td {
        padding: 10px;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>

<script>
function showAddModal() {
    document.getElementById('modalTitle').textContent = 'Thêm Danh Mục';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryModal').style.display = 'block';
}

function showEditModal(category) {
    document.getElementById('modalTitle').textContent = 'Sửa Danh Mục';
    document.getElementById('categoryId').value = category.category_id;
    document.getElementById('name').value = category.name;
    document.getElementById('description').value = category.description;
    document.getElementById('displayOrder').value = category.display_order;
    document.getElementById('isActive').checked = category.is_active;
    document.getElementById('categoryModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

function saveCategory(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    
    // Thêm is_active nếu checkbox không được check
    data.is_active = formData.get('is_active') ? 1 : 0;

    fetch('/admin/categories/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Lưu danh mục thành công');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('error', data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Có lỗi xảy ra');
    });
}

function deleteCategory(categoryId) {
    if (confirm('Bạn có chắc muốn xóa danh mục này?')) {
        fetch(`/admin/categories/delete/${categoryId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('success', 'Xóa danh mục thành công');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast('error', data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Có lỗi xảy ra');
        });
    }
}

function showToast(type, message) {
    // Remove any existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    // Add toast to page
    document.body.appendChild(toast);

    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('categoryModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php include __DIR__ . '/../../shares/footer.php'; ?> 