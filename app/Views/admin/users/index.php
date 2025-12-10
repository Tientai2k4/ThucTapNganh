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
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone_number']) ?></td>
            <td>
                <?php if($u['role'] == 'admin'): ?>
                    <span class="badge bg-danger">Admin</span>
                <?php else: ?>
                    <span class="badge bg-primary">Khách</span>
                <?php endif; ?>
            </td>
            <td><?= $u['status'] == 1 ? '<span class="text-success">Hoạt động</span>' : '<span class="text-danger">Đã khóa</span>' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>