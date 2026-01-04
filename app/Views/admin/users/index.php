<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-primary fw-bold">Quản lý Người Dùng</h3>
</div>

<div class="card border-0 shadow-sm mb-4 bg-light">
    <div class="card-body py-3">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                           placeholder="Nhập tên, email hoặc SĐT..." 
                           value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                </div>
            </div>

            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value=""> Tất cả vai trò </option>
                    <?php 
                        $roles = [
                            'admin'         => 'Admin',
                            'sales_staff'   => 'Kinh doanh',
                            'content_staff' => 'Nội dung',
                            'care_staff'    => 'CSKH',
                            'member'        => 'Khách hàng'
                        ];
                        $currentRole = $data['filters']['role'] ?? '';
                    ?>
                    <?php foreach($roles as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $currentRole == $key ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="fas fa-filter me-1"></i> Lọc
                </button>
                <a href="<?= BASE_URL ?>admin/user" class="btn btn-outline-secondary px-3" title="Xóa lọc">
                    <i class="fas fa-undo"></i> Đặt lại
                </a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Thông tin</th>
                <th>Liên hệ</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th width="120">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data['users'])): ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                        Không tìm thấy người dùng nào phù hợp.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach($data['users'] as $u): ?>
                <tr>
                    <td><strong>#<?= $u['id'] ?></strong></td>
                    
                    <td>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($u['full_name'] ?? 'Chưa đặt tên') ?></div>
                        <small class="text-muted"><i class="far fa-clock me-1"></i><?= date('d/m/Y', strtotime($u['created_at'])) ?></small>
                    </td>
                    
                    <td>
                        <div class="small">
                            <i class="fas fa-envelope text-muted me-2" style="width:15px"></i> <?= htmlspecialchars($u['email']) ?><br>
                            <i class="fas fa-phone text-muted me-2" style="width:15px"></i> <?= htmlspecialchars($u['phone_number'] ?? '---') ?>
                        </div>
                    </td>
                    
                    <td>
                        <?php 
                            $roleConfig = [
                                'admin'         => ['color' => 'danger',  'name' => 'Admin'],
                                'sales_staff'   => ['color' => 'success', 'name' => 'Kinh doanh'],
                                'content_staff' => ['color' => 'info',    'name' => 'Nội dung'],
                                'care_staff'    => ['color' => 'warning', 'name' => 'CSKH'],
                                'member'        => ['color' => 'primary', 'name' => 'Khách hàng']
                            ];
                            $role = $u['role'];
                            $color = $roleConfig[$role]['color'] ?? 'secondary';
                            $name  = $roleConfig[$role]['name'] ?? ucfirst($role);
                        ?>
                        <span class="badge bg-<?= $color ?> rounded-pill px-3"><?= $name ?></span>
                    </td>
                    
                    <td>
                        <?php if($u['status'] == 1): ?>
                            <span class="badge bg-soft-success text-success border border-success">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-soft-secondary text-secondary border">Đã khóa</span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="<?= BASE_URL ?>admin/user/edit/<?= $u['id'] ?>" class="btn btn-outline-info" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>admin/user/delete/<?= $u['id'] ?>" class="btn btn-outline-danger" 
                               onclick="return confirm('CẢNH BÁO: Xóa vĩnh viễn tài khoản này?')" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .bg-soft-success { background-color: #d1e7dd; }
    .bg-soft-secondary { background-color: #e2e3e5; }
    /* Giúp bảng trông thoáng hơn khi không có avatar */
    .table td { padding: 12px 8px; }
</style>