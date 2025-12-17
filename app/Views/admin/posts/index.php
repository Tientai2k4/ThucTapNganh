<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý Tin tức / Blog</h3>
    <a href="<?= BASE_URL ?>admin/post/create" class="btn btn-primary"><i class="fas fa-plus"></i> Viết bài mới</a>
</div>

<table class="table table-bordered bg-white shadow-sm">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Hình ảnh</th>
            <th>Tiêu đề</th>
            <th>Tác giả</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($data['posts'] as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td>
                <?php if($p['thumbnail']): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $p['thumbnail'] ?>" width="60" height="40" style="object-fit:cover">
                <?php else: ?>
                    <span class="text-muted">Không có ảnh</span>
                <?php endif; ?>
            </td>
            <td>
                <strong><?= htmlspecialchars($p['title']) ?></strong><br>
                <small class="text-muted">Slug: <?= $p['slug'] ?></small>
            </td>
            <td><?= htmlspecialchars($p['author_name'] ?? 'Admin') ?></td>
            <td>
                <?= $p['status'] ? '<span class="badge bg-success">Hiện</span>' : '<span class="badge bg-secondary">Ẩn</span>' ?>
            </td>
            <td>
                <a href="<?= BASE_URL ?>admin/post/edit/<?= $p['id'] ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                <a href="<?= BASE_URL ?>admin/post/delete/<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa bài này?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>