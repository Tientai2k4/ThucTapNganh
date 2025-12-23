<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Quản lý Danh mục</h2>
    <a href="<?= BASE_URL ?>admin/category/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Danh sách danh mục</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="text-center" style="width: 60px;">ID</th>
                        <th style="width: 25%;">Tên danh mục</th>
                        <th style="width: 20%;">Cấp độ (Cha)</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $cat['id'] ?></td>
                            
                            <td class="fw-bold text-dark">
                                <?php if(empty($cat['parent_id'])): ?>
                                    <i class="fas fa-folder text-warning me-1"></i> 
                                <?php else: ?>
                                    <i class="fas fa-level-up-alt fa-rotate-90 ms-3 text-secondary me-1"></i> 
                                <?php endif; ?>
                                <?= htmlspecialchars($cat['name']) ?>
                            </td>
                            
                            <td>
                                <?php if(!empty($cat['parent_name'])): ?>
                                    <span class="badge bg-light text-dark border">
                                        Con của: <strong><?= htmlspecialchars($cat['parent_name']) ?></strong>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary">Gốc (Root)</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-secondary small">
                                <?= htmlspecialchars($cat['description'] ?? '') ?>
                            </td>
                            
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= BASE_URL ?>admin/category/edit/<?= $cat['id'] ?>" class="btn btn-sm btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                    <a href="<?= BASE_URL ?>admin/category/delete/<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" title="Xóa"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Chưa có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>