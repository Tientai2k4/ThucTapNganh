<?php
// Lấy dữ liệu bài viết từ biến $data truyền từ Controller
$post = $data['post'];
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Cập nhật bài viết</h5>
        <small class="text-white-50">ID: <?= $post['id'] ?></small>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/post/update/<?= $post['id'] ?>" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" value="<?= htmlspecialchars($post['title']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết</label>
                        <textarea name="content" class="form-control" rows="15" required style="font-family: Arial, sans-serif; line-height: 1.6;"><?= htmlspecialchars($post['content']) ?></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" value="1" <?= $post['status'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status">Hiển thị công khai</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn (Excerpt)</label>
                        <textarea name="excerpt" class="form-control" rows="4" placeholder="Viết mô tả ngắn gọn cho SEO..."><?= htmlspecialchars($post['excerpt']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                        <div class="border rounded p-2 text-center bg-white mb-2">
                            <?php if($post['thumbnail']): ?>
                                <img src="<?= BASE_URL ?>public/uploads/posts/<?= $post['thumbnail'] ?>" 
                                     class="img-fluid rounded" 
                                     style="max-height: 200px;">
                                <div class="small text-muted mt-1 fst-italic"><?= $post['thumbnail'] ?></div>
                            <?php else: ?>
                                <div class="py-4 text-muted">
                                    <i class="fas fa-image fa-2x mb-2"></i><br>
                                    Chưa có ảnh
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1"><i class="fas fa-info-circle"></i> Tải ảnh mới để thay thế ảnh cũ.</small>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save me-2"></i> Lưu thay đổi</button>
                        <a href="<?= BASE_URL ?>admin/post" class="btn btn-outline-secondary">Quay lại danh sách</a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>