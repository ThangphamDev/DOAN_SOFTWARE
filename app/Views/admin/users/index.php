<?php
$page_title = "Quản lý người dùng";
$currentPage = 'users';

// Bắt đầu output buffering
ob_start();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý người dùng</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
        <li class="breadcrumb-item active">Quản lý người dùng</li>
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
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Danh sách người dùng
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users->rowCount() > 0): ?>
                            <?php while($user = $users->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $user['user_id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['full_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['phone_number']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $user['role'] === 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                            <?php echo $user['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $user['status'] === 'hoạt động' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $user['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/users/<?php echo $user['user_id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                        
                                        <?php if($user['user_id'] != $_SESSION['user_id']): ?>
                                            <?php if($user['status'] === 'hoạt động'): ?>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        onclick="confirmStatusChange(<?php echo $user['user_id']; ?>, 'vô hiệu hóa')">
                                                    <i class="fas fa-ban"></i> Vô hiệu hóa
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-success btn-sm" 
                                                        onclick="confirmStatusChange(<?php echo $user['user_id']; ?>, 'hoạt động')">
                                                    <i class="fas fa-check"></i> Kích hoạt
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu người dùng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Form ẩn để cập nhật trạng thái -->
<form id="statusForm" action="" method="POST" style="display:none;">
    <input type="hidden" id="statusValue" name="status" value="">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#usersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Vietnamese.json'
            },
            order: [[0, 'desc']]
        });
    });
    
    function confirmStatusChange(userId, status) {
        const action = status === 'hoạt động' ? 'kích hoạt' : 'vô hiệu hóa';
        if (confirm(`Bạn có chắc chắn muốn ${action} người dùng này?`)) {
            const form = document.getElementById('statusForm');
            form.action = `/admin/users/${userId}/status`;
            document.getElementById('statusValue').value = status;
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