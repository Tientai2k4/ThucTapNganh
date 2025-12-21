<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary m-0"><i class="fas fa-images me-2"></i>Quản lý Banner/Slider</h4>
            <small class="text-muted">Quản lý hình ảnh quảng cáo trên trang chủ.</small>
        </div>
        <a href="<?= BASE_URL ?>staff/slider/create" class="btn btn-primary fw-bold shadow-sm hover-lift">
            <i class="fas fa-plus me-2"></i>Thêm Banner mới
        </a>
    </div>

    <div class="row g-4">
        <?php if(!empty($data['sliders'])): ?>
            <?php foreach($data['sliders'] as $slider): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="<?= BASE_URL ?>public/uploads/sliders/<?= $slider['image'] ?>" class="card-img-top w-100 h-100 object-fit-cover" alt="Banner">
                        
                        <div class="position-absolute top-0 end-0 m-2">
                            <?php if($slider['status'] == 1): ?>
                                <span class="badge bg-success shadow-sm">Hiển thị</span>
                            <?php else: ?>
                                <span class="badge bg-secondary shadow-sm">Đang ẩn</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted text-uppercase fw-bold">Thứ tự: #<?= $slider['sort_order'] ?></small>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($slider['created_at'])) ?></small>
                        </div>
                        
                        <p class="card-text text-truncate small text-muted">
                            <i class="fas fa-link me-1"></i> 
                            <?= !empty($slider['link_url']) ? $slider['link_url'] : 'Không có liên kết' ?>
                        </p>

                        <div class="d-grid gap-2 d-flex justify-content-end mt-3">
                            <a href="<?= BASE_URL ?>staff/slider/delete/<?= $slider['id'] ?>" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Bạn chắc chắn muốn xóa banner này?')">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-center py-5 shadow-sm border-0">
                    <div class="mb-3 text-muted"><i class="far fa-image fa-3x"></i></div>
                    <h5>Chưa có Banner nào</h5>
                    <p class="text-muted">Hãy thêm banner quảng cáo để làm đẹp trang chủ.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>