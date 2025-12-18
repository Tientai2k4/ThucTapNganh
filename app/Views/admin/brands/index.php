<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800"><i class="fas fa-tags me-2"></i> Quản lý Thương hiệu</h3>
    <a href="<?= BASE_URL ?>admin/brand/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="10%">ID</th>
                        <th class="text-center" width="20%">Logo</th>
                        <th width="50%">Tên thương hiệu</th>
                        <th class="text-center" width="20%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['brands'])): ?>
                        <?php foreach($data['brands'] as $brand): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $brand['id'] ?></td>
                            
                            <td class="text-center">
                                <?php if (!empty($brand['logo'])): ?>
                                    <div class="p-1 bg-white border rounded d-inline-block">
                                        <img src="<?= BASE_URL ?>public/uploads/brands/<?= $brand['logo'] ?>" 
                                             style="height: 40px; width: auto; object-fit: contain;" 
                                             alt="Logo">
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Logo</span>
                                <?php endif; ?>
                            </td>

                            <td class="fw-bold"><?= htmlspecialchars($brand['name']) ?></td>
                            
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/brand/edit/<?= $brand['id'] ?>" class="btn btn-sm btn-outline-warning mx-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/brand/delete/<?= $brand['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger mx-1"
                                   onclick="return confirm('Bạn có chắc muốn xóa thương hiệu này?')" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-3"></i><br>
                                Chưa có thương hiệu nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>