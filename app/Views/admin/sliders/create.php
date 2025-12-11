<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Thêm Slider Mới</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/slider/store" method="POST" enctype="multipart/form-data">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Chọn hình ảnh <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                            <small class="text-muted">Khuyên dùng kích thước: 1920x600px</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Đường dẫn liên kết (Link khi click vào ảnh)</label>
                            <input type="text" class="form-control" name="link_url" placeholder="VD: https://shop.com/khuyen-mai">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Thứ tự hiển thị</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                            <small class="text-muted">Số nhỏ hiện trước</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" checked>
                                <label class="form-check-label" for="status">Hiển thị ngay</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu Slider</button>
                    <a href="<?= BASE_URL ?>admin/slider" class="btn btn-secondary">Quay lại</a>
                </div>

            </form>
        </div>
    </div>
</div>