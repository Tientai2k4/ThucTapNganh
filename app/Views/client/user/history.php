<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="fas fa-history me-2"></i>Lịch sử đơn hàng</h3>
        <a href="<?= BASE_URL ?>user/profile" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại Hồ sơ
        </a>
    </div>

    <?php 
    if (!function_exists('renderStatusBadge')) {
        function renderStatusBadge($status) {
            $colors = [
                'pending' => 'secondary',
                'pending_payment' => 'warning text-dark',
                'processing' => 'info text-dark',
                'shipping' => 'primary',
                'completed' => 'success',
                'cancelled' => 'danger'
            ];
            $names = [
                'pending' => 'Chờ xác nhận',
                'pending_payment' => 'Chờ thanh toán',
                'processing' => 'Đang chuẩn bị',
                'shipping' => 'Đang giao',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy'
            ];
            $c = $colors[$status] ?? 'secondary';
            $n = $names[$status] ?? $status;
            return "<span class='badge bg-$c'>$n</span>";
        }
    }
    ?>

    <?php if(empty($data['orders'])): ?>
        <div class="text-center py-5 bg-light rounded">
            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" alt="Empty" style="width: 100px; opacity: 0.5">
            <p class="mt-3 text-muted">Bạn chưa có đơn hàng nào.</p> 
            <a href="<?= BASE_URL ?>" class="btn btn-primary mt-2">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 ps-4">Mã đơn</th>
                                <th class="py-3">Ngày đặt</th>
                                <th class="py-3">Người nhận</th>
                                <th class="py-3">Tổng tiền</th>
                                <th class="py-3">Trạng thái</th>
                                <th class="py-3 text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['orders'] as $order): ?>
                            <tr>
                                <td class="ps-4">
                                    <a href="<?= BASE_URL ?>user/orderDetail/<?= $order['order_code'] ?>" class="fw-bold text-decoration-none text-primary">
                                        #<?= $order['order_code'] ?>
                                    </a>
                                </td>
                                <td class="text-muted small">
                                    <i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </td>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td class="fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</td>
                                <td>
                                    <?= renderStatusBadge($order['status']) ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>user/orderDetail/<?= $order['order_code'] ?>" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                        Chi tiết
                                    </a>
                                    
                                    <?php if(in_array($order['status'], ['completed', 'cancelled'])): ?>
                                        <a href="<?= BASE_URL ?>user/repurchase/<?= $order['order_code'] ?>" class="btn btn-sm btn-warning text-dark ms-1" onclick="return confirm('Thêm các sản phẩm trong đơn này vào giỏ hàng?')" title="Mua lại đơn này">
                                            <i class="fas fa-cart-plus"></i> Mua lại
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>