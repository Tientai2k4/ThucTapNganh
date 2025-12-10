<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h3 mb-0 text-gray-800">Quản lý Thương hiệu</h3>
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
                        <th class="text-center">ID</th>
                        <th class="text-center">Logo</th>
                        <th>Tên thương hiệu</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['brands'])): ?>
                        <?php foreach($data['brands'] as $brand): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $brand['id'] ?></td>
                            
                          <td class="text-center">
    <?php if (!empty($brand['logo'])): ?>
        <img src="<?= BASE_URL ?>uploads/<?= $brand['logo'] ?>" 
             class="border rounded bg-white" 
             style="height: 40px; width: auto; object-fit: contain;" 
             alt="Logo">
    <?php else: ?>
        <span class="badge bg-secondary">No Logo</span>
    <?php endif; ?>
</td>

                            <td class="fw-bold"><?= htmlspecialchars($brand['name']) ?></td>
                            
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/brand/edit/<?= $brand['id'] ?>" class="btn btn-sm btn-outline-warning mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/brand/delete/<?= $brand['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger mx-1"
                                   onclick="return confirm('Xóa thương hiệu này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                Chưa có thương hiệu nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>