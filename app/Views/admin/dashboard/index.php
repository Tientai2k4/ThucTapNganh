<h2 class="mb-4">Tổng quan hệ thống</h2>
<div class="alert alert-info">
    Chào mừng quay trở lại trang quản trị! Hệ thống đang hoạt động bình thường.
</div>
<h3 class="mb-4">Thống kê kinh doanh</h3>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <i class="fas fa-chart-line"></i> Doanh thu 7 ngày gần nhất
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['revenue'] as $r): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($r['date'])) ?></td>
                            <td class="fw-bold text-success"><?= number_format($r['total']) ?>đ</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-crown"></i> Top 5 Sản phẩm bán chạy
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach($data['top_products'] as $p): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($p['product_name']) ?>
                        <span class="badge bg-primary rounded-pill"><?= $p['total_sold'] ?> đã bán</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>