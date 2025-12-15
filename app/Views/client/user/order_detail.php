<?php 
// Truy cập dữ liệu dễ dàng hơn
$order = $data['order'];
$details = $data['details'];
// Gọi OrderModel để sử dụng hàm static
$orderModel = $this->model('OrderModel'); 
?>

<div class="row">
    <div class="col-12">
        <h3 class="mb-4">Chi tiết đơn hàng #<?= $order['order_code'] ?></h3>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="m-0">Tóm tắt đơn hàng</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                <p><strong>Phương thức TT:</strong> <?= $order['payment_method'] ?></p>
            </div>
            <div class="col-md-6 mb-3">
                <p><strong>Trạng thái ĐH:</strong> 
                    <span class="badge bg-secondary"><?= $orderModel::getStatusName($order['status']) ?></span>
                </p>
                <p><strong>Trạng thái TT:</strong> 
                    <?php if ($order['payment_status'] == 1): ?>
                        <span class="badge bg-success">Đã thanh toán</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="m-0">Thông tin giao hàng</h5>
    </div>
    <div class="card-body">
        <p><strong>Người nhận:</strong> <?= $order['customer_name'] ?></p>
        <p><strong>Điện thoại:</strong> <?= $order['customer_phone'] ?></p>
        <p><strong>Email:</strong> <?= $order['customer_email'] ?></p>
        <p><strong>Địa chỉ:</strong> <?= $order['shipping_address'] ?></p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="m-0">Danh sách sản phẩm</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped m-0">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    foreach($details as $item): 
                        $subtotal += $item['total_price'];
                    ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['product_name']) ?> 
                            <small class="text-muted">(<?= $item['size'] ?>/<?= $item['color'] ?>)</small>
                        </td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end"><?= number_format($item['price']) ?>đ</td>
                        <td class="text-end"><?= number_format($item['total_price']) ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Tổng tiền hàng (Tạm tính):</th>
                        <th class="text-end"><?= number_format($subtotal) ?>đ</th>
                    </tr>
                    <?php if ($order['discount_amount'] > 0): ?>
                    <tr>
                        <th colspan="3" class="text-end text-success">Giảm giá (<?= $order['coupon_code'] ?? 'Mã' ?>):</th>
                        <th class="text-end text-success">- <?= number_format($order['discount_amount']) ?>đ</th>
                    </tr>
                    <?php endif; ?>
                    <tr class="table-dark">
                        <th colspan="3" class="text-end h5">TỔNG CỘNG (Thanh toán):</th>
                        <th class="text-end h5"><?= number_format($order['total_money']) ?>đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<p class="mt-4">
    <a href="<?= BASE_URL ?>user/history" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại lịch sử đơn hàng
    </a>
</p>