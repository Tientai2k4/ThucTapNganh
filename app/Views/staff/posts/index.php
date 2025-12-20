<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-info m-0"><i class="fas fa-newspaper me-2"></i>Quản lý Tin tức</h4>
            <small class="text-muted">Biên tập và xuất bản nội dung website.</small>
        </div>
        <a href="<?= BASE_URL ?>staff/post/create" class="btn btn-info text-white fw-bold shadow-sm hover-lift">
            <i class="fas fa-pen-nib me-2"></i>Viết bài mới
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary me-3 rounded-circle" style="width: 48px; height: 48px; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-file-alt fa-lg"></i>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase small fw-bold mb-0">Tổng bài viết</h6>
                    <h4 class="fw-bold mb-0 text-dark"><?= count($data['posts']) ?></h4>
                </div>
            </div>
        </div>
        </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3" style="width: 100px;">Hình ảnh</th>
                            <th class="py-3" style="width: 35%;">Tiêu đề</th>
                            <th class="py-3">Tác giả</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="py-3">Ngày đăng</th>
                            <th class="text-end pe-4 py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['posts'])): ?>
                            <?php foreach($data['posts'] as $post): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="rounded shadow-sm overflow-hidden" style="width: 80px; height: 50px;">
                                        <img src="<?= BASE_URL ?>public/uploads/posts/<?= $post['thumbnail'] ?>" class="w-100 h-100 object-fit-cover" alt="Thumb">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 350px;">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </div>
                                    <small class="text-muted text-truncate d-block" style="max-width: 350px;">
                                        <?= htmlspecialchars(substr($post['excerpt'], 0, 60)) ?>...
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle text-secondary me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <span class="small fw-bold text-secondary"><?= $post['author_name'] ?? 'Admin' ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if($post['status'] == 1): ?>
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">
                                            <i class="fas fa-check-circle me-1"></i>Công khai
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3">
                                            <i class="fas fa-lock me-1"></i>Bản nháp
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small">
                                    <i class="far fa-clock me-1"></i><?= date('d/m/Y', strtotime($post['created_at'])) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>staff/post/edit/<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>staff/post/delete/<?= $post['id'] ?>" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" onclick="return confirm('Bạn chắc chắn muốn xóa bài viết này?')" title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có bài viết nào. Hãy thêm mới ngay!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>