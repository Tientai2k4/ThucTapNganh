<style>
    /* Modern Dashboard CSS */
    .dashboard-container {
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    
    .card-stat {
        border: none;
        border-radius: 15px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        position: relative;
    }
    
    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    /* Icon Box đẹp hơn */
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    /* Màu sắc Gradient nhẹ */
    .bg-gradient-primary-soft { background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); color: #5e72e4; }
    .bg-gradient-warning-soft { background: linear-gradient(135deg, #fccb90 0%, #d57eeb 100%); color: #fb6340; }
    .bg-gradient-info-soft    { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #11cdef; }
    .bg-gradient-success-soft { background: linear-gradient(135deg, #a8ff78 0%, #78ffd6 100%); color: #2dce89; }

    /* Table đẹp */
    .table-modern thead th {
        background-color: #f6f9fc;
        color: #8898aa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .avatar-circle {
        width: 35px;
        height: 35px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #525f7f;
    }
</style>

<div class="container-fluid p-4 dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-bold text-dark m-0">Tổng quan</h3>
            <p class="text-muted mb-0">Chào mừng trở lại, chúc bạn một ngày làm việc hiệu quả!</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>" class="btn btn-white shadow-sm border rounded-pill px-3 fw-bold text-primary" target="_blank">
                <i class="fas fa-globe me-2"></i>Xem Website
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Đơn chờ duyệt</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['pending_orders'] ?? 0 ?></h2>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order?status=pending" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Đang xử lý</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['processing_orders'] ?? 0 ?></h2>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/order" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Liên hệ mới</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['unread_contacts'] ?? 0 ?></h2>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/contact" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <h6 class="text-muted text-uppercase fw-bold small mb-1">Đánh giá mới</h6>
                            <h2 class="fw-bold text-dark mb-0"><?= $data['stats']['pending_reviews'] ?? 0 ?></h2>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>staff/review" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-dark mb-0">Đơn hàng cần xử lý</h5>
                <small class="text-muted">Danh sách các đơn hàng mới nhất chưa hoàn thành</small>
            </div>
            <a href="<?= BASE_URL ?>staff/order" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold">
                Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0 table-hover">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">Mã đơn</th>
                        <th class="py-3">Khách hàng</th>
                        <th class="py-3">Tổng tiền</th>
                        <th class="py-3">Ngày đặt</th>
                        <th class="py-3">Trạng thái</th>
                        <th class="text-end pe-4 py-3">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['recent_orders'])): ?>
                        <?php foreach ($data['recent_orders'] as $order): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">#<?= $order['order_code'] ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        <?= strtoupper(substr($order['customer_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small"><?= htmlspecialchars($order['customer_name']) ?></div>
                                        <div class="text-muted small" style="font-size: 0.75rem;"><?= $order['customer_phone'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-dark"><?= number_format($order['total_money']) ?>đ</td>
                            <td class="text-muted small">
                                <i class="far fa-clock me-1"></i><?= date('d/m H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td>
                                <?php 
                                    $sttClass = 'bg-warning text-dark';
                                    $sttText = 'Chờ xử lý';
                                    if($order['status'] == 'processing') { $sttClass = 'bg-info text-white'; $sttText = 'Đang chuẩn bị'; }
                                    if($order['status'] == 'shipping') { $sttClass = 'bg-primary text-white'; $sttText = 'Đang giao'; }
                                ?>
                                <span class="badge rounded-pill <?= $sttClass ?> px-3 py-2 fw-normal">
                                    <?= $sttText ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="<?= BASE_URL ?>staff/order/detail/<?= $order['order_code'] ?>" 
                                   class="btn btn-light btn-sm rounded-circle shadow-sm hover-lift" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty" style="width: 150px; opacity: 0.6">
                                <p class="text-muted mt-3">Hiện tại chưa có đơn hàng nào cần xử lý.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>