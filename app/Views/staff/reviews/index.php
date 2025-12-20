<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-warning m-0"><i class="fas fa-star me-2"></i>Đánh giá Sản phẩm</h4>
            <small class="text-muted">Quản lý phản hồi và tương tác với người mua.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3" style="width: 25%;">Sản phẩm</th>
                            <th class="py-3">Khách hàng</th>
                            <th class="py-3">Đánh giá</th>
                            <th class="py-3" style="width: 30%;">Nội dung</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="text-end pe-4 py-3">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['reviews'])): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có đánh giá nào.</td></tr>
                        <?php endif; ?>

                        <?php foreach($data['reviews'] as $r): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <?php $img = $r['product_image'] ?? 'default.png'; ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($img) ?>" class="rounded border me-3" width="48" height="48" style="object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 180px;">
                                            <?= htmlspecialchars($r['product_name'] ?? 'Sản phẩm đã xóa') ?>
                                        </div>
                                        <small class="text-muted" style="font-size: 0.7rem;">ID: #<?= $r['product_id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">
                                    <?= htmlspecialchars($r['user_name'] ?? 'Khách ẩn danh') ?>
                                </div>
                            </td>
                            <td>
                                <div class="text-warning small">
                                    <?php for($i=1; $i<=5; $i++) echo ($i <= $r['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-secondary text-opacity-25"></i>'; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fst-italic text-secondary bg-light p-2 rounded small mb-1 border-start border-3 border-warning">
                                    "<?= htmlspecialchars($r['comment'] ?? '') ?>"
                                </div>
                                
                                <?php if(!empty($r['reply_content'])): ?>
                                    <div class="text-primary small ms-2">
                                        <i class="fas fa-reply me-1 transform-flip-x"></i> 
                                        <strong>Admin:</strong> <?= htmlspecialchars($r['reply_content'] ?? '') ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($r['status'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Đang ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <?php if($r['status'] == 0): ?>
                                        <a href="<?= BASE_URL ?>staff/review/toggleStatus/<?= $r['id'] ?>/1" class="btn btn-outline-success btn-sm" title="Duyệt hiển thị">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>staff/review/toggleStatus/<?= $r['id'] ?>/0" class="btn btn-outline-secondary btn-sm" title="Ẩn đi">
                                            <i class="fas fa-eye-slash"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#replyModal<?= $r['id'] ?>" title="Trả lời">
                                        <i class="fas fa-comment-dots"></i>
                                    </button>
                                </div>

                                <div class="modal fade text-start" id="replyModal<?= $r['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title fw-bold"><i class="fas fa-reply me-2"></i>Trả lời đánh giá</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= BASE_URL ?>staff/review/reply/<?= $r['id'] ?>" method="POST">
                                                <div class="modal-body bg-light">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-uppercase text-muted">Nội dung khách hàng:</label>
                                                        <div class="bg-white p-2 rounded border mb-3 text-muted fst-italic">
                                                            "<?= htmlspecialchars($r['comment'] ?? '') ?>"
                                                        </div>
                                                        
                                                        <label class="form-label fw-bold small text-uppercase text-primary">Phản hồi của shop:</label>
                                                        <textarea name="reply_content" class="form-control" rows="4" placeholder="Nhập câu trả lời lịch sự và chuyên nghiệp..." required><?= htmlspecialchars($r['reply_content'] ?? '') ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-white">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy bỏ</button>
                                                    <button type="submit" class="btn btn-primary fw-bold px-4">Gửi phản hồi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>