<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý Danh mục</h3>
    <a href="<?= BASE_URL ?>admin/category/create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-bordered table-hover bg-white mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">ID</th>
                    <th style="width: 25%;">Tên danh mục</th>
                    <th>Mô tả</th>
                    <th class="text-center" style="width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['categories'])): ?>
                    <?php foreach($data['categories'] as $cat): ?>
                    <tr>
                        <td class="text-center"><?= $cat['id'] ?></td>
                        
                        <td class="fw-bold"><?= htmlspecialchars($cat['name']) ?></td>
                        
                        <td class="text-muted"><?= htmlspecialchars($cat['description']) ?></td>
                        
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>admin/category/edit/<?= $cat['id'] ?>" 
                               class="btn btn-sm btn-warning text-dark me-1" 
                               title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="<?= BASE_URL ?>admin/category/delete/<?= $cat['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục: <?= htmlspecialchars($cat['name']) ?>?')"
                               title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fas fa-box-open fa-2x mb-2"></i><br>
                            Chưa có danh mục nào. Hãy bấm "Thêm mới" để bắt đầu.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>