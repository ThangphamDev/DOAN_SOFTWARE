<?php
$pageTitle = "Tạo thông báo mới";
$currentPage = 'notifications';
$content = ob_start();
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo thông báo mới</h1>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin thông báo</h6>
        </div>
        <div class="card-body">
            <form action="/admin/notifications/store" method="post">
                <!-- Tiêu đề thông báo -->
                <div class="form-group">
                    <label for="title">Tiêu đề</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <!-- Nội dung thông báo -->
                <div class="form-group">
                    <label for="message">Nội dung</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                
                <!-- Loại thông báo -->
                <div class="form-group">
                    <label for="type">Loại thông báo</label>
                    <select class="form-control" id="type" name="type">
                        <option value="general">Thông báo chung</option>
                        <option value="promotion">Khuyến mãi</option>
                        <option value="order">Đơn hàng</option>
                        <option value="product">Sản phẩm</option>
                        <option value="system">Hệ thống</option>
                    </select>
                </div>
                
                <!-- ID liên quan (nếu có) -->
                <div class="form-group">
                    <label for="related_id">ID liên quan (nếu có)</label>
                    <input type="number" class="form-control" id="related_id" name="related_id" placeholder="Ví dụ: ID đơn hàng, ID sản phẩm,...">
                    <small class="form-text text-muted">Nhập ID đơn hàng nếu là thông báo đơn hàng, hoặc ID sản phẩm nếu là thông báo sản phẩm.</small>
                </div>
                
                <!-- Người nhận thông báo -->
                <div class="form-group">
                    <label>Người nhận</label>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="recipient_all" name="recipient_type" value="all" class="custom-control-input" checked>
                        <label class="custom-control-label" for="recipient_all">Tất cả người dùng</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="recipient_role" name="recipient_type" value="role" class="custom-control-input">
                        <label class="custom-control-label" for="recipient_role">Theo vai trò</label>
                    </div>
                    <div class="role-select" style="display: none; margin-left: 20px;">
                        <select class="form-control" name="role">
                            <option value="user">Người dùng thường</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="staff">Nhân viên</option>
                        </select>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="recipient_specific" name="recipient_type" value="specific" class="custom-control-input">
                        <label class="custom-control-label" for="recipient_specific">Người dùng cụ thể</label>
                    </div>
                    <div class="specific-users" style="display: none; margin-left: 20px; margin-top: 10px;">
                        <div class="form-group">
                            <label for="user_search">Tìm kiếm người dùng:</label>
                            <input type="text" class="form-control" id="user_search" placeholder="Nhập tên hoặc email...">
                            <div id="user_search_results" class="mt-2"></div>
                        </div>
                        <div class="selected-users">
                            <h6>Người dùng đã chọn:</h6>
                            <ul id="selected_users_list" class="list-group"></ul>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Gửi thông báo</button>
                <a href="/admin/notifications" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hiển thị/ẩn phần chọn vai trò
    const roleRadio = document.getElementById('recipient_role');
    const roleSelect = document.querySelector('.role-select');
    
    roleRadio.addEventListener('change', function() {
        if(this.checked) {
            roleSelect.style.display = 'block';
            specificUsersDiv.style.display = 'none';
        }
    });
    
    // Hiển thị/ẩn phần chọn người dùng cụ thể
    const specificRadio = document.getElementById('recipient_specific');
    const specificUsersDiv = document.querySelector('.specific-users');
    
    specificRadio.addEventListener('change', function() {
        if(this.checked) {
            specificUsersDiv.style.display = 'block';
            roleSelect.style.display = 'none';
        }
    });
    
    // Ẩn cả hai khi chọn "tất cả"
    const allRadio = document.getElementById('recipient_all');
    allRadio.addEventListener('change', function() {
        if(this.checked) {
            roleSelect.style.display = 'none';
            specificUsersDiv.style.display = 'none';
        }
    });
    
    // Tìm kiếm người dùng
    const userSearchInput = document.getElementById('user_search');
    const userSearchResults = document.getElementById('user_search_results');
    const selectedUsersList = document.getElementById('selected_users_list');
    let selectedUsers = [];
    
    userSearchInput.addEventListener('input', debounce(function() {
        const searchValue = this.value.trim();
        if(searchValue.length < 2) {
            userSearchResults.innerHTML = '';
            return;
        }
        
        fetch(`/admin/users/search?q=${encodeURIComponent(searchValue)}`)
            .then(response => response.json())
            .then(users => {
                userSearchResults.innerHTML = '';
                
                if(users.length === 0) {
                    userSearchResults.innerHTML = '<div class="alert alert-info">Không tìm thấy người dùng</div>';
                    return;
                }
                
                const userList = document.createElement('div');
                userList.className = 'list-group';
                
                users.forEach(user => {
                    // Kiểm tra nếu người dùng đã được chọn rồi thì bỏ qua
                    if(selectedUsers.some(selected => selected.id === user.user_id)) {
                        return;
                    }
                    
                    const userItem = document.createElement('button');
                    userItem.type = 'button';
                    userItem.className = 'list-group-item list-group-item-action';
                    userItem.innerHTML = `
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">${user.full_name || user.username}</h5>
                            <small>${user.role}</small>
                        </div>
                        <p class="mb-1">${user.email}</p>
                    `;
                    userItem.addEventListener('click', function() {
                        addSelectedUser(user);
                        userSearchResults.innerHTML = '';
                        userSearchInput.value = '';
                    });
                    userList.appendChild(userItem);
                });
                
                userSearchResults.appendChild(userList);
            })
            .catch(error => {
                console.error('Error:', error);
                userSearchResults.innerHTML = '<div class="alert alert-danger">Lỗi khi tìm kiếm người dùng</div>';
            });
    }, 500));
    
    function addSelectedUser(user) {
        // Thêm vào danh sách đã chọn
        selectedUsers.push({
            id: user.user_id,
            name: user.full_name || user.username,
            email: user.email
        });
        
        updateSelectedUsersList();
    }
    
    function removeSelectedUser(userId) {
        selectedUsers = selectedUsers.filter(user => user.id !== userId);
        updateSelectedUsersList();
    }
    
    function updateSelectedUsersList() {
        selectedUsersList.innerHTML = '';
        
        if(selectedUsers.length === 0) {
            selectedUsersList.innerHTML = '<li class="list-group-item">Chưa có người dùng nào được chọn</li>';
            return;
        }
        
        selectedUsers.forEach(user => {
            const userItem = document.createElement('li');
            userItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            userItem.innerHTML = `
                <div>
                    <strong>${user.name}</strong>
                    <br>
                    <small>${user.email}</small>
                    <input type="hidden" name="user_ids[]" value="${user.id}">
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-user" data-id="${user.id}">
                    <i class="fas fa-times"></i>
                </button>
            `;
            selectedUsersList.appendChild(userItem);
        });
        
        // Thêm event listener cho nút xóa
        document.querySelectorAll('.remove-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = parseInt(this.getAttribute('data-id'));
                removeSelectedUser(userId);
            });
        });
    }
    
    // Helper function để debounce input events
    function debounce(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func.apply(this, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Khởi tạo danh sách người dùng đã chọn
    updateSelectedUsersList();
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . "/../layouts/admin_layout.php";
?> 