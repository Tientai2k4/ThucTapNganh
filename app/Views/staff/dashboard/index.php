<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 text-primary">Xin chào, <?= $_SESSION['user_name'] ?></h2>
            <p>Đây là khu vực xử lý nghiệp vụ hàng ngày của bạn. Vui lòng kiểm tra các mục bên dưới:</p>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 shadow">
                <div class="card-body text-center p-4">
                    <i class="fas fa-shopping-basket fa-3x mb-2"></i>
                    <h4><?= $data['pending_orders'] ?></h4>
                    <p class="mb-0 fw-bold">ĐƠN HÀNG CHỜ DUYỆT</p>
                    <a href="<?= BASE_URL ?>staff/order" class="btn btn-sm btn-dark mt-3">Xử lý ngay</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card bg-info text-white border-0 shadow">
                <div class="card-body text-center p-4">
                    <i class="fas fa-comment-dots fa-3x mb-2"></i>
                    <h4>0</h4>
                    <p class="mb-0 fw-bold">LIÊN HỆ CHƯA TRẢ LỜI</p>
                    <a href="<?= BASE_URL ?>staff/contact" class="btn btn-sm btn-light mt-3">Xem hộp thư</a>
                </div>
            </div>
        </div>
    </div>
</div>