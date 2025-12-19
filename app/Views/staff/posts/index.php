<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Tin tức & Blog</h3>
    <a href="<?= BASE_URL ?>staff/post/create" class="btn btn-success"><i class="fas fa-plus"></i> Viết bài mới</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Hình</th>
                    <th>Tiêu đề</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['posts'] as $p): ?>
                <tr>
                    <td class="ps-3"><img src="<?= BASE_URL ?>public/uploads/posts/<?= $p['thumbnail'] ?>" width="50" class="rounded"></td>
                    <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                    <td><?= $p['status'] ? '<span class="text-success">Hiện</span>' : '<span class="text-muted">Ẩn</span>' ?></td>
                    <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>staff/post/edit/<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>