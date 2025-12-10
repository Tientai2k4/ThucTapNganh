<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Quản lý Danh mục</h2>
    <a href="<?= BASE_URL ?>admin/category/create" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Thêm mới
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Danh sách danh mục hiện có</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="text-center" style="width: 60px;">ID</th>
                        <th style="width: 25%;">Tên danh mục</th>
                        <th>Mô tả</th>
                        <th class="text-center" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['categories'])): ?>
                        <?php foreach($data['categories'] as $cat): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $cat['id'] ?></td>
                            
                            <td class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></td>
                            
                            <td class="text-secondary small"><?= htmlspecialchars($cat['description']) ?></td>
                            
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= BASE_URL ?>admin/category/edit/<?= $cat['id'] ?>" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="<?= BASE_URL ?>admin/category/delete/<?= $cat['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục: <?= htmlspecialchars($cat['name']) ?>?')"
                                       title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-gray-300"></i><br>
                                <span class="h6">Chưa có danh mục nào</span><br>
                                <small>Hãy bấm nút "Thêm mới" ở góc trên để bắt đầu.</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>