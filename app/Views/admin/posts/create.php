<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Viết bài mới</h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/post/store" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label class="form-label fw-bold">Tiêu đề bài viết *</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn (Excerpt)</label>
                <textarea name="excerpt" class="form-control" rows="3" placeholder="Tóm tắt nội dung bài viết..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Nội dung chi tiết</label>
                <textarea name="content" class="form-control" rows="10" required></textarea>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="status" id="status" checked value="1">
                <label class="form-check-label" for="status">Hiển thị ngay</label>
            </div>

            <button type="submit" class="btn btn-success">Đăng bài</button>
            <a href="<?= BASE_URL ?>admin/post" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>