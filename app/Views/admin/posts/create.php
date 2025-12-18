<div class="card shadow border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-pen-nib me-2"></i> Viết bài mới</h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>admin/post/store" method="POST" enctype="multipart/form-data">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề bài viết <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="Nhập tiêu đề tại đây..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết</label>
                        <textarea name="content" class="form-control" rows="15" required style="font-family: Arial, sans-serif; line-height: 1.6;" placeholder="Viết nội dung bài viết..."></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" checked value="1">
                                <label class="form-check-label" for="status">Hiển thị công khai</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả ngắn (Excerpt)</label>
                        <textarea name="excerpt" class="form-control" rows="4" placeholder="Tóm tắt ngắn gọn cho SEO và hiển thị trang chủ..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1">Nên chọn ảnh kích thước chữ nhật ngang.</small>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg shadow-sm">
                            <i class="fas fa-save me-2"></i> Đăng bài viết
                        </button>
                        <a href="<?= BASE_URL ?>admin/post" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>