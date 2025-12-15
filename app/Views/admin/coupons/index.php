<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Mã Giảm Giá (Coupons)</h1>
        <a href="<?= BASE_URL ?>admin/coupon/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm Mã Mới
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã Code</th>
                            <th>Giá trị giảm</th>
                            <th>Đơn tối thiểu</th>
                            <th>Số lượng còn</th>
                            <th>Thời gian hiệu lực</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $coupons = $data['coupons'] ?? []; 
                        $currentTime = time();
                        ?>
                        
                        <?php if (!empty($coupons)): ?>
                            <?php foreach ($coupons as $coupon): 
                                $startDate = strtotime($coupon['start_date']);
                                $endDate = strtotime($coupon['end_date']);
                                
                                // Logic xác định trạng thái
                                $statusClass = 'secondary';
                                $statusText = 'Tắt';

                                if ($coupon['status'] == 1) {
                                    if ($currentTime < $startDate) {
                                        $statusClass = 'info';
                                        $statusText = 'Sắp chạy';
                                    } elseif ($currentTime >= $startDate && $currentTime <= $endDate && $coupon['quantity'] > 0) {
                                        $statusClass = 'success';
                                        $statusText = 'Đang chạy';
                                    } elseif ($currentTime > $endDate) {
                                        $statusClass = 'danger';
                                        $statusText = 'Hết hạn';
                                    } elseif ($coupon['quantity'] <= 0) {
                                        $statusClass = 'warning';
                                        $statusText = 'Hết lượt dùng';
                                    }
                                }
                            ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($coupon['code']) ?></td>
                                    <td>
                                        <?php if ($coupon['discount_type'] == 'percent'): ?>
                                            <span class="text-danger fw-bold">-<?= $coupon['discount_value'] ?>%</span>
                                        <?php else: ?>
                                            <span class="text-danger fw-bold">-<?= number_format($coupon['discount_value']) ?> VNĐ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($coupon['min_order_value']) ?> VNĐ</td>
                                    <td>
                                        <span class="badge bg-primary"><?= number_format($coupon['quantity']) ?></span>
                                    </td>
                                    <td>
                                        Từ: <?= date('H:i d/m/Y', $startDate) ?><br>
                                        Đến: <?= date('H:i d/m/Y', $endDate) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>admin/coupon/edit/<?= $coupon['id'] ?>" 
                                           class="btn btn-info btn-sm mx-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>admin/coupon/delete/<?= $coupon['id'] ?>" 
                                           class="btn btn-danger btn-sm mx-1"
                                           onclick="return confirm('Xóa mã giảm giá này? Mã sẽ không thể phục hồi!')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center">Chưa có mã giảm giá nào được tạo.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>