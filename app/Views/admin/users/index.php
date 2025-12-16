<h3>Quản lý Người dùng</h3>
<table class="table table-striped bg-white">
    <thead>
        <tr>
            <th>ID</th>
            <th>Họ Tên</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['users'] as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['id']) ?></td>
            <td><?= htmlspecialchars($u['full_name'] ?? 'Chưa đặt tên') ?></td>
            <td><?= htmlspecialchars($u['email'] ?? 'Không rõ') ?></td>
            
            <td><?= htmlspecialchars($u['phone_number'] ?? '---') ?></td>
            
            <td>
                <?php 
                    $role = $u['role'] ?? 'guest'; // Mặc định là guest nếu role NULL
                    if($role == 'admin'): 
                ?>
                    <span class="badge bg-danger">Admin</span>
                <?php elseif($role == 'staff'): ?>
                    <span class="badge bg-warning text-dark">Staff</span>
                <?php else: ?>
                    <span class="badge bg-primary">Khách</span>
                <?php endif; ?>
            </td>
            <td>
                <?php 
                    $status = $u['status'] ?? 0; // Mặc định là 0 nếu status NULL
                    echo $status == 1 ? '<span class="text-success">Hoạt động</span>' : '<span class="text-danger">Đã khóa</span>'; 
                ?>
            </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>