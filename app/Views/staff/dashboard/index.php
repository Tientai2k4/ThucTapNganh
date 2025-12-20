<style>
    /* CSS bổ sung để tạo hiệu ứng đẹp hơn */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid p-4 bg-light" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-primary m-0"><i class="fas fa-laptop-house me-2"></i>Bàn làm việc Nhân viên</h4>
            <small class="text-muted">Xin chào, chúc bạn ngày làm việc hiệu quả.</small>
        </div>
        <a href="<?= BASE_URL ?>" class="btn btn-sm btn-white border shadow-sm hover-lift text-dark" target="_blank">
            <i class="fas fa-home me-1 text-primary"></i> Trang chủ Web
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Đơn chờ duyệt</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['pending_orders'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-clipboard-list fa-lg"></i>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order?status=pending" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Đang xử lý</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['processing_orders'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Liên hệ mới</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['unread_contacts'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-envelope fa-lg"></i>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/contact" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase mb-1">Review mới</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['pending_reviews'] ?? 0 ?></h2>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fas fa-star fa-lg"></i>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/review" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="fas fa-exclamation-circle text-danger me-2"></i>Cần xử lý ngay
            </h6>
            <a href="<?= BASE_URL ?>staff/order" class="btn btn-sm btn-light text-primary fw-bold">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">Mã đơn</th>
                            <th class="py-3">Khách hàng</th>
                            <th class="py-3">Tổng tiền</th>
                            <th class="py-3">Ngày đặt</th>
                            <th class="py-3">Trạng thái</th>
                            <th class="text-end pe-4 py-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recent_orders'])): ?>
                            <?php foreach ($data['recent_orders'] as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-dark border">#<?= $order['order_code'] ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($order['customer_name']) ?></div>
                                    <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size:10px"></i><?= $order['customer_phone'] ?></small>
                                </td>
                                <td class="text-danger fw-bold"><?= number_format($order['total_money']) ?>đ</td>
                                <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td><span class="badge rounded-pill bg-warning text-dark px-3">Chờ xử lý</span></td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>staff/order/detail/<?= $order['order_code'] ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                        Xử lý <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-clipboard-check fa-3x text-light"></i></div>
                                    <span class="text-muted">Tuyệt vời! Hiện tại không có đơn hàng nào cần xử lý.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>