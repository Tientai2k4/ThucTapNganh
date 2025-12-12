<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Quản lý Đánh giá</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đánh giá khách hàng</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Bình luận</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                    // Đảm bảo $reviews được lấy ra nếu nó được truyền qua $data
                        $reviews = $data['reviews'] ?? []; 
                        ?>
                        <?php if (!empty($reviews)): ?>
                            <?php foreach ($reviews as $review): ?>
                                <tr>
                                    <td><?= $review['id'] ?></td>
                                    
                                    <td>
                                        <strong><?= htmlspecialchars($review['user_name'] ?? 'Khách vãng lai') ?></strong><br>
                                        <?php if (!empty($review['user_id'])): ?>
                                            <small class="text-primary">Thành viên (ID: <?= $review['user_id'] ?>)</small>
                                        <?php else: ?>
                                            <small class="text-muted">Ẩn danh</small>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($review['product_name']) ?><br>
                                        <?php if($review['product_image']): ?>
                                            <img src="<?= BASE_URL ?>public/uploads/<?= $review['product_image'] ?>" width="50">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-warning">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php if($i <= $review['rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </td>
                                    <td>
                                        <p class="mb-1"><?= htmlspecialchars($review['comment']) ?></p>
                                        <?php if($review['reply_content']): ?>
                                            <div class="alert alert-secondary p-2 mt-2">
                                                <small><strong>Admin:</strong> <?= htmlspecialchars($review['reply_content']) ?></small>
                                            </div>
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
                                            <?php if ($review['status'] == 0): ?>
                                                <a href="<?= BASE_URL ?>admin/review/toggleStatus/<?= $review['id'] ?>/1" 
                                                   class="btn btn-success btn-sm">
                                                   <i class="fas fa-check"></i> Duyệt
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= BASE_URL ?>admin/review/toggleStatus/<?= $review['id'] ?>/0" 
                                                   class="btn btn-warning btn-sm">
                                                   <i class="fas fa-eye-slash"></i> Ẩn
                                                </a>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-primary btn-sm mt-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#replyModal<?= $review['id'] ?>">
                                                <i class="fas fa-reply"></i> Trả lời
                                            </button>

                                            <a href="<?= BASE_URL ?>admin/review/delete/<?= $review['id'] ?>" 
                                               class="btn btn-danger btn-sm mt-1"
                                               onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                               <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">Chưa có đánh giá nào.</td></tr>
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
                    <form action="<?= BASE_URL ?>admin/review/reply/<?= $review['id'] ?>" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Trả lời khách: <?= htmlspecialchars($review['user_name'] ?? 'Khách vãng lai') ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Nội dung trả lời:</label>
                                <textarea name="reply_content" class="form-control" rows="3" required><?= $review['reply_content'] ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Gửi trả lời</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>