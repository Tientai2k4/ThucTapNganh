<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0"><i class="fas fa-clipboard-list me-2"></i>Quản lý Đơn hàng</h3>
            <small class="text-muted">Dành cho nhân viên kinh doanh</small>
        </div>
        <div class="d-flex gap-2">
            <div class="card bg-warning text-dark border-0 shadow-sm px-3 py-2">
                <span class="small fw-bold">Chờ xử lý</span>
                <span class="h5 mb-0 fw-bold"><?= $data['stats']['pending'] ?></span>
            </div>
            <div class="card bg-info text-white border-0 shadow-sm px-3 py-2">
                <span class="small fw-bold">Đang xử lý</span>
                <span class="h5 mb-0 fw-bold"><?= $data['stats']['processing'] ?></span>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded">
            <form action="" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Mã đơn, Tên khách, SĐT..." value="<?= htmlspecialchars($data['filters']['keyword']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="pending" <?= $data['filters']['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="processing" <?= $data['filters']['status'] == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                        <option value="shipped" <?= $data['filters']['status'] == 'shipped' ? 'selected' : '' ?>>Đang giao</option>
                        <option value="completed" <?= $data['filters']['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="cancelled" <?= $data['filters']['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="newest" <?= $data['filters']['sort'] == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="oldest" <?= $data['filters']['sort'] == 'oldest' ? 'selected' : '' ?>>Cũ nhất</option>
                        <option value="total_desc" <?= $data['filters']['sort'] == 'total_desc' ? 'selected' : '' ?>>Giá trị cao nhất</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Lọc dữ liệu</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $order): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#<?= $order['order_code'] ?></td>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($order['customer_name']) ?></div>
                                    <small class="text-muted"><i class="fas fa-phone-alt me-1"></i><?= $order['customer_phone'] ?></small>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td class="fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $order['payment_method'] ?>
                                    </span>
                                    <?php if($order['payment_status'] == 1): ?>
                                        <i class="fas fa-check-circle text-success ms-1" title="Đã thanh toán"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $statusMap = [
                                            'pending' => ['label' => 'Chờ xử lý', 'class' => 'bg-warning text-dark'],
                                            'processing' => ['label' => 'Đang chuẩn bị', 'class' => 'bg-info text-white'],
                                            'shipped' => ['label' => 'Đang giao', 'class' => 'bg-primary'],
                                            'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-success'],
                                            'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-danger']
                                        ];
                                        $st = $statusMap[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-secondary'];
                                    ?>
                                    <span class="badge <?= $st['class'] ?>"><?= $st['label'] ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>staff/order/detail/<?= $order['order_code'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Chi tiết
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">Không tìm thấy đơn hàng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>