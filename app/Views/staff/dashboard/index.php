<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-2">
        <div>
            <h4 class="fw-bold text-dark m-0">Bàn làm việc Nhân viên</h4>
            <small class="text-muted">Xin chào, chúc bạn ngày làm việc hiệu quả.</small>
        </div>
        <a href="<?= BASE_URL ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="fas fa-home me-1"></i> Trang chủ Web
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #fff3cd;">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3"><i class="fas fa-clipboard-list fa-2x text-warning"></i></div>
                        <div>
                            <h6 class="card-title mb-0 text-muted small text-uppercase">Đơn chờ duyệt</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['pending_orders'] ?? 0 ?></h3>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order?status=pending" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #cff4fc;">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3"><i class="fas fa-box-open fa-2x text-info"></i></div>
                        <div>
                            <h6 class="card-title mb-0 text-muted small text-uppercase">Đang xử lý</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['processing_orders'] ?? 0 ?></h3>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #f8d7da;">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3"><i class="fas fa-envelope fa-2x text-danger"></i></div>
                        <div>
                            <h6 class="card-title mb-0 text-muted small text-uppercase">Liên hệ mới</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['unread_contacts'] ?? 0 ?></h3>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/contact" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #d1e7dd;">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3"><i class="fas fa-star fa-2x text-success"></i></div>
                        <div>
                            <h6 class="card-title mb-0 text-muted small text-uppercase">Review mới</h6>
                            <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['pending_reviews'] ?? 0 ?></h3>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/review" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-exclamation-circle text-danger me-2"></i>Cần xử lý ngay</h5>
            <a href="<?= BASE_URL ?>staff/order" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['recent_orders'])): ?>
                            <?php foreach ($data['recent_orders'] as $order): ?>
                            <tr>
                                <td class="ps-3 align-middle"><strong>#<?= $order['order_code'] ?></strong></td>
                                <td class="align-middle">
                                    <?= htmlspecialchars($order['customer_name']) ?><br>
                                    <small class="text-muted"><?= $order['customer_phone'] ?></small>
                                </td>
                                <td class="align-middle text-danger fw-bold"><?= number_format($order['total_money']) ?>đ</td>
                                <td class="align-middle"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td class="align-middle"><span class="badge bg-warning text-dark">Chờ xử lý</span></td>
                                <td class="text-end pe-3 align-middle">
                                    <a href="<?= BASE_URL ?>staff/order/detail/<?= $order['order_code'] ?>" class="btn btn-primary btn-sm">
                                        Xử lý <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">Hiện tại không có đơn hàng nào cần xử lý.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>