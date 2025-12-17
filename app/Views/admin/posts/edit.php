<?php
// Lấy dữ liệu bài viết từ biến $data truyền từ Controller
$post = $data['post'];
?>

<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Cập nhật bài viết: <?= htmlspecialchars($post['title']) ?></h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/post/update/<?= $post['id'] ?>" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề bài viết *</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                <div class="mb-2">
                    <?php if($post['thumbnail']): ?>
                        <p class="small text-muted">Ảnh hiện tại:</p>
                        <img src="<?= BASE_URL ?>public/uploads/<?= $post['thumbnail'] ?>" width="150" class="img-thumbnail mb-2">
                    <?php else: ?>
                        <p class="small text-muted">Chưa có ảnh đại diện.</p>
                    <?php endif; ?>
                </div>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-info">Chọn file mới nếu muốn thay đổi ảnh.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn (Excerpt)</label>
                <textarea name="excerpt" class="form-control" rows="3"><?= htmlspecialchars($post['excerpt']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nội dung chi tiết</label>
                <textarea name="content" class="form-control" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="status" id="status" value="1" <?= $post['status'] == 1 ? 'checked' : '' ?>>
                <label class="form-check-label" for="status">Hiển thị bài viết</label>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>admin/post" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-success px-4">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>