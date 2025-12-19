<div class="container-fluid p-4">
    <h3 class="fw-bold mb-4 text-success"><i class="fas fa-shopping-bag me-2"></i>Quản lý Đơn hàng (Staff)</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['orders'] as $order): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-primary">#<?= $order['order_code'] ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($order['customer_name']) ?></div>
                            <small class="text-muted"><?= $order['customer_phone'] ?></small>
                        </td>
                        <td class="text-danger fw-bold"><?= number_format($order['total_money']) ?>đ</td>
                        <td>
                            <?php 
                                $badge = ['pending'=>'warning', 'shipping'=>'info', 'completed'=>'success', 'cancelled'=>'danger'];
                                $color = $badge[$order['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?>"><?= strtoupper($order['status']) ?></span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="<?= BASE_URL ?>staff/order/detail/<?= $order['order_code'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Xử lý
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>