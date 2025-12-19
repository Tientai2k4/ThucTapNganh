<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success"><i class="fas fa-chart-line me-2"></i>Báo Cáo Doanh Thu (30 Ngày Qua)</h3>
        <a href="/ThucTapNganh/admin/dashboard" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>Ngày Giao Dịch</th>
                                    <th class="text-center">Số lượng đơn hàng</th>
                                    <th class="text-end">Doanh thu tổng</th>
                                    <th class="text-end">Đơn thấp nhất</th>
                                    <th class="text-end">Đơn cao nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grandTotal = 0;
                                foreach($data['revenue'] as $r): 
                                    $grandTotal += $r['total'];
                                ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= date('d/m/Y', strtotime($r['date'])) ?></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $r['total_orders'] ?></span></td>
                                    <td class="text-end fw-bold text-primary fs-5"><?= number_format($r['total']) ?>đ</td>
                                    <td class="text-end text-muted small"><?= number_format($r['min_order']) ?>đ</td>
                                    <td class="text-end text-muted small"><?= number_format($r['max_order']) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="2" class="text-end text-uppercase py-3">Tổng doanh thu 30 ngày:</td>
                                    <td class="text-end text-danger fs-4 py-3"><?= number_format($grandTotal) ?>đ</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>