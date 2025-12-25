<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản lý Đánh giá (<?= ucfirst($prefix) ?>)</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá khách hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Bình luận & Phản hồi</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $reviews = $data['reviews'] ?? []; 
                        ?>
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td><?= $review['id'] ?></td>
                                    
                                    <td>
                                        <strong><?= htmlspecialchars($review['user_name'] ?? 'Khách vãng lai') ?></strong><br>
                                        <?php if (!empty($review['user_id'])): ?>
                                            <small class="text-primary">User ID: <?= $review['user_id'] ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">Ẩn danh</small>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($review['created_at'])) ?></small>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($review['product_name']) ?><br>
                                        <?php if($review['product_image']): ?>
                                            <img src="<?= BASE_URL ?>public/uploads/<?= $review['product_image'] ?>" width="50" class="img-thumbnail mt-1">
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="text-warning text-nowrap">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <i class="<?= ($i <= $review['rating']) ? 'fas' : 'far' ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </td>
                                    
                                    <td>
                                        <p class="mb-1 text-dark fw-bold">"<?= htmlspecialchars($review['comment']) ?>"</p>
                                        <?php if($review['reply_content']): ?>
                                            <div class="alert alert-secondary p-2 mt-2 mb-0 border-start border-4 border-primary">
                                                <small><i class="fas fa-reply me-1"></i> <strong>Admin:</strong> <?= htmlspecialchars($review['reply_content']) ?></small>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted font-italic">Chưa có phản hồi</small>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <?php if ($review['status'] == 1): ?>
                                            <span class="badge bg-success">Đang hiện</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Đang ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <?php if ($prefix == 'admin'): ?>
                                                <form action="<?= BASE_URL ?>admin/review/updateStatus" method="POST">
                                                    <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                                    
                                                    <?php if ($review['status'] == 0): ?>
                                                        <input type="hidden" name="status" value="1">
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="fas fa-check"></i> Duyệt
                                                        </button>
                                                    <?php else: ?>
                                                        <input type="hidden" name="status" value="0">
                                                        <button type="submit" class="btn btn-warning btn-sm w-100">
                                                            <i class="fas fa-eye-slash"></i> Ẩn
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-primary btn-sm w-100" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#replyModal<?= $review['id'] ?>">
                                                <i class="fas fa-reply"></i> Trả lời
                                            </button>

                                            <?php if ($prefix == 'admin'): ?>
                                                <a href="<?= BASE_URL ?>admin/review/delete/<?= $review['id'] ?>" 
                                                   class="btn btn-danger btn-sm w-100"
                                                   onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                   <i class="fas fa-trash"></i> Xóa
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có đánh giá nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($reviews)): ?>
    <?php foreach ($reviews as $review): ?>
        <div class="modal fade" id="replyModal<?= $review['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?= BASE_URL . $prefix ?>/review/reply/<?= $review['id'] ?>" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Trả lời: <?= htmlspecialchars($review['user_name'] ?? 'Khách') ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label class="fw-bold mb-2">Nội dung bình luận:</label>
                                <div class="p-3 bg-light rounded fst-italic mb-3">
                                    "<?= htmlspecialchars($review['comment']) ?>"
                                </div>

                                <label class="fw-bold mb-2">Câu trả lời của Shop:</label>
                                <textarea name="reply_content" class="form-control" rows="4" required placeholder="Nhập câu trả lời..."><?= $review['reply_content'] ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>