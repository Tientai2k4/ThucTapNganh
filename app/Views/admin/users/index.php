<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Quản lý Người Dùng</h3>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Thông tin</th>
                <th>Liên hệ</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['users'] as $u): ?>
            <tr>
                <td><strong>#<?= $u['id'] ?></strong></td>
                <td>
                    <div class="d-flex align-items-center">
                        <?php $avatar = !empty($u['avatar']) ? $u['avatar'] : BASE_URL . 'public/images/default-avatar.png'; ?>
                        <img src="<?= htmlspecialchars($avatar) ?>" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                        <div>
                            <strong><?= htmlspecialchars($u['full_name'] ?? 'Chưa đặt tên') ?></strong><br>
                            <small class="text-muted">Đăng ký: <?= date('d/m/Y', strtotime($u['created_at'])) ?></small>
                        </div>
                    </div>
                </td>
                <td>
                    <i class="fas fa-envelope text-muted"></i> <?= htmlspecialchars($u['email']) ?><br>
                    <i class="fas fa-phone text-muted"></i> <?= htmlspecialchars($u['phone_number'] ?? '---') ?>
                </td>
                <td>
                    <?php 
                        // Định nghĩa màu sắc và tên hiển thị cho từng quyền mới
                        $roleConfig = [
                            'admin'         => ['color' => 'danger',  'name' => 'Admin'],
                            'sales_staff'   => ['color' => 'success', 'name' => 'Kinh doanh'],
                            'content_staff' => ['color' => 'info',    'name' => 'Nội dung'],
                            'care_staff'    => ['color' => 'warning', 'name' => 'CSKH'],
                            'member'        => ['color' => 'primary', 'name' => 'Khách hàng']
                        ];
                        
                        $currentRole = $u['role'];
                        $color = $roleConfig[$currentRole]['color'] ?? 'secondary';
                        $name  = $roleConfig[$currentRole]['name'] ?? ucfirst($currentRole);
                    ?>
                    <span class="badge bg-<?= $color ?>"><?= $name ?></span>
                </td>
                <td>
                    <?php if($u['status'] == 1): ?>
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Hoạt động</span>
                    <?php else: ?>
                        <span class="badge bg-secondary"><i class="fas fa-lock"></i> Đã khóa</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="<?= BASE_URL ?>admin/user/edit/<?= $u['id'] ?>" class="btn btn-info text-white" title="Sửa quyền & Trạng thái">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <a href="<?= BASE_URL ?>admin/user/delete/<?= $u['id'] ?>" class="btn btn-danger" 
                           onclick="return confirm('CẢNH BÁO: Xóa vĩnh viễn tài khoản này?')" title="Xóa vĩnh viễn">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>