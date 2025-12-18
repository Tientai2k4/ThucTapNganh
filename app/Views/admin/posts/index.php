<div class="d-flex justify-content-between mb-3 align-items-center">
    <h3><i class="fas fa-blog me-2"></i> Quản lý Tin tức / Blog</h3>
    <a href="<?= BASE_URL ?>admin/post/create" class="btn btn-primary shadow-sm"><i class="fas fa-plus"></i> Viết bài mới</a>
</div>

<div class="card shadow border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th width="5%" class="text-center">ID</th>
                        <th width="10%" class="text-center">Hình ảnh</th>
                        <th width="35%">Tiêu đề</th>
                        <th width="15%">Tác giả</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="15%" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['posts'])): ?>
                        <?php foreach($data['posts'] as $p): ?>
                        <tr>
                            <td class="text-center text-muted">#<?= $p['id'] ?></td>
                            <td class="text-center">
                                <?php if($p['thumbnail']): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/posts/<?= $p['thumbnail'] ?>" 
                                         class="rounded shadow-sm" 
                                         width="60" height="40" 
                                         style="object-fit:cover; border: 1px solid #dee2e6;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 300px;"><?= htmlspecialchars($p['title']) ?></div>
                                <small class="text-muted d-block text-truncate" style="max-width: 250px;">/<?= $p['slug'] ?></small>
                            </td>
                            <td>
                                <i class="fas fa-user-circle text-secondary me-1"></i>
                                <?= htmlspecialchars($p['author_name'] ?? 'Admin') ?>
                            </td>
                            <td class="text-center">
                                <?= $p['status'] 
                                    ? '<span class="badge bg-success bg-opacity-75"><i class="fas fa-check-circle me-1"></i>Hiện</span>' 
                                    : '<span class="badge bg-secondary bg-opacity-75"><i class="fas fa-eye-slash me-1"></i>Ẩn</span>' 
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>admin/post/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary mx-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= BASE_URL ?>admin/post/delete/<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger mx-1" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                Chưa có bài viết nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>