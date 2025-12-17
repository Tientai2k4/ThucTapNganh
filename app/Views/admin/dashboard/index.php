<div class="container-fluid">
    <h2 class="mb-4 text-gray-800">Tổng quan hệ thống</h2>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-primary text-white">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Tổng doanh thu</div>
                    <div class="h5 mb-0 font-weight-bold"><?= number_format($data['counters']['total_revenue']) ?>đ</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 bg-success text-white">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Đơn hàng</div>
                    <div class="h5 mb-0 font-weight-bold"><?= $data['counters']['total_orders'] ?> đơn</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 bg-info text-white">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Khách hàng</div>
                    <div class="h5 mb-0 font-weight-bold"><?= $data['counters']['total_users'] ?> thành viên</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 bg-warning text-white">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">Sản phẩm</div>
                    <div class="h5 mb-0 font-weight-bold"><?= $data['counters']['total_products'] ?> mặt hàng</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-chart-line me-1"></i> Biểu đồ doanh thu 7 ngày gần nhất
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th class="text-end">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['revenue'] as $r): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($r['date'])) ?></td>
                                <td class="fw-bold text-success text-end"><?= number_format($r['total']) ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-fire me-1"></i> Top 5 Sản phẩm bán chạy nhất
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php foreach($data['top_products'] as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-truncate" style="max-width: 70%;"><?= htmlspecialchars($p['product_name']) ?></span>
                            <span class="badge bg-danger rounded-pill"><?= $p['total_sold'] ?> đã bán</span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-exclamation-triangle me-1"></i> Cảnh báo: Sản phẩm sắp hết hàng (Stock < 5)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Tên sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th class="text-center">Tồn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($data['low_stock'])): ?>
                                    <tr><td colspan="3" class="text-center py-3">Hiện tại kho hàng vẫn đầy đủ.</td></tr>
                                <?php else: ?>
                                    <?php foreach($data['low_stock'] as $ls): ?>
                                    <tr>
                                        <td class="ps-3 small"><?= htmlspecialchars($ls['name']) ?></td>
                                        <td class="small"><?= $ls['size'] ?> - <?= $ls['color'] ?></td>
                                        <td class="text-center fw-bold text-danger"><?= $ls['stock_quantity'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-users me-1"></i> Top 10 Khách hàng thân thiết
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Khách hàng</th>
                                    <th class="text-center">Đơn</th>
                                    <th class="text-end pe-3">Tổng chi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['top_customers'] as $tc): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small"><?= htmlspecialchars($tc['customer_name']) ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= $tc['customer_email'] ?></div>
                                    </td>
                                    <td class="text-center small"><?= $tc['total_orders'] ?></td>
                                    <td class="text-end pe-3 fw-bold text-primary small"><?= number_format($tc['total_spent']) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>