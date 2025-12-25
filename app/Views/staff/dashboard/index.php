<style>
    /* CSS hiệu ứng */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .quick-action-btn {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e9ecef;
        text-decoration: none;
        color: #495057;
        transition: all 0.2s;
    }
    .quick-action-btn:hover {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #0d6efd;
    }
</style>

<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-primary m-0"><i class="fas fa-edit me-2"></i>Bàn làm việc Biên tập viên</h4>
            <small class="text-muted">Quản lý nội dung Blog & Slider quảng cáo</small>
        </div>
        <a href="<?= BASE_URL ?>" class="btn btn-sm btn-white border shadow-sm hover-lift text-dark" target="_blank">
            <i class="fas fa-external-link-alt me-1 text-primary"></i> Xem Website
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Tổng bài viết</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['total_posts'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-newspaper fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i><?= $data['stats']['active_posts'] ?? 0 ?></span> công khai
                    </div>
                    <a href="<?= BASE_URL ?>staff/post" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Slider Quảng cáo</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['total_sliders'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="fas fa-images fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        Quản lý banner trang chủ
                    </div>
                    <a href="<?= BASE_URL ?>staff/slider" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Hộp thư liên hệ</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['unread_contacts'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-envelope fa-lg"></i>
                        </div>
                    </div>
                    <div class="mt-3 small">
                        <span class="text-danger fw-bold">Chưa đọc</span>
                    </div>
                    <a href="<?= BASE_URL ?>staff/contact" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold text-uppercase small text-muted border-bottom">
                    Tác vụ nhanh
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="<?= BASE_URL ?>staff/post/create" class="quick-action-btn shadow-sm">
                            <div class="icon-box bg-success text-white me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-pen"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Viết bài mới</div>
                                <div class="small text-muted">Tạo nội dung blog, tin tức</div>
                            </div>
                        </a>

                        <a href="<?= BASE_URL ?>staff/slider/create" class="quick-action-btn shadow-sm">
                            <div class="icon-box bg-info text-white me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Thêm Slider</div>
                                <div class="small text-muted">Tải lên banner quảng cáo mới</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Bài viết gần đây</h6>
                    <a href="<?= BASE_URL ?>staff/post" class="btn btn-sm btn-outline-primary rounded-pill px-3">Xem tất cả</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Tiêu đề</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['recent_posts'])): ?>
                                <?php foreach ($data['recent_posts'] as $post): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <?php if(!empty($post['thumbnail'])): ?>
                                                <img src="<?= BASE_URL ?>public/uploads/posts/<?= $post['thumbnail'] ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;"><i class="fas fa-image"></i></div>
                                            <?php endif; ?>
                                            <div class="text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($post['title']) ?>">
                                                <?= htmlspecialchars($post['title']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small"><?= date('d/m/Y', strtotime($post['created_at'])) ?></td>
                                    <td>
                                        <?php if($post['status'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Công khai</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">Nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= BASE_URL ?>staff/post/edit/<?= $post['id'] ?>" class="btn btn-sm btn-light text-primary hover-lift">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-file-alt fa-2x mb-2 text-light"></i><br>Chưa có bài viết nào.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>