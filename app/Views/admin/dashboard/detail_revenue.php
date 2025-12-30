<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-success"><i class="fas fa-chart-line me-2"></i>Báo Cáo Doanh Thu Theo <?= $data['view_type_text'] ?></h3>
        <a href="<?= BASE_URL ?>admin/dashboard" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/dashboard/view_revenue" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Xem theo:</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="date" <?= $data['view_type'] == 'date' ? 'selected' : '' ?>>Từng ngày</option>
                        <option value="month" <?= $data['view_type'] == 'month' ? 'selected' : '' ?>>Từng tháng</option>
                        <option value="year" <?= $data['view_type'] == 'year' ? 'selected' : '' ?>>Từng năm</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Từ ngày:</label>
                    <input type="date" name="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Đến ngày:</label>
                    <input type="date" name="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-filter me-2"></i>Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th>Thời gian</th>
                                    <th class="text-center">Số lượng đơn hàng</th>
                                    <th class="text-end">Doanh thu tổng</th>
                                    <th class="text-end">Đơn thấp nhất</th>
                                    <th class="text-end">Đơn cao nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grandTotal = 0;
                                if(!empty($data['revenue'])):
                                    foreach($data['revenue'] as $r): 
                                        $grandTotal += $r['total'];
                                        // Format lại hiển thị thời gian tùy theo loại xem
                                        $displayDate = $r['date'];
                                        if($data['view_type'] == 'month') $displayDate = "Tháng " . date('m/Y', strtotime($r['date']));
                                        if($data['view_type'] == 'year') $displayDate = "Năm " . date('Y', strtotime($r['date']));
                                ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= $displayDate ?></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $r['total_orders'] ?></span></td>
                                    <td class="text-end fw-bold text-primary fs-5"><?= number_format($r['total']) ?>đ</td>
                                    <td class="text-end text-muted small"><?= number_format($r['min_order']) ?>đ</td>
                                    <td class="text-end text-muted small"><?= number_format($r['max_order']) ?>đ</td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" class="text-center">Không có dữ liệu trong khoảng thời gian này.</td></tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td colspan="2" class="text-end text-uppercase py-3">Tổng cộng:</td>
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