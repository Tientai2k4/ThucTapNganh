<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Phân Tích Khách Hàng Tiềm Năng</h3>
        <a href="/ThucTapNganh/admin/dashboard" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Khách hàng</th>
                            <th>Liên hệ</th>
                            <th class="text-center">Tổng đơn hàng</th>
                            <th class="text-end">Tổng chi tiêu</th>
                            <th class="text-center">Đơn gần nhất</th>
                            <th class="text-center">Xếp hạng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['customers'] as $c): ?>
                        <tr>
                            <td>
                                <div class="fw-bold text-primary"><?= htmlspecialchars($c['customer_name']) ?></div>
                            </td>
                            <td>
                                <div class="small"><i class="fas fa-envelope me-1 text-muted"></i> <?= $c['customer_email'] ?></div>
                                <div class="small"><i class="fas fa-phone me-1 text-muted"></i> <?= $c['customer_phone'] ?></div>
                            </td>
                            <td class="text-center fw-bold"><?= $c['total_orders'] ?></td>
                            <td class="text-end fw-bold text-success fs-5"><?= number_format($c['total_spent']) ?>đ</td>
                            <td class="text-center small text-muted">
                                <?= date('d/m/Y H:i', strtotime($c['last_order_date'])) ?>
                            </td>
                            <td class="text-center">
                                <?php if($c['total_spent'] >= 5000000): ?>
                                    <span class="badge bg-warning text-dark shadow-sm"><i class="fas fa-crown"></i> Diamond</span>
                                <?php elseif($c['total_spent'] >= 2000000): ?>
                                    <span class="badge bg-info text-dark shadow-sm">Gold</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary shadow-sm">Silver</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>