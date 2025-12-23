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
        <?php if (!empty($order['tracking_code'])): ?>
            <hr>
            <div class="mt-3 p-3 bg-light border rounded">
                <h6 class="text-primary"><i class="fas fa-shipping-fast me-2"></i>Thông tin vận chuyển</h6>
                <p class="mb-1"><strong>Hãng vận chuyển:</strong> Giao Hàng Nhanh (GHN)</p>
                <p class="mb-2"><strong>Mã vận đơn:</strong> <span class="badge bg-dark"><?= $order['tracking_code'] ?></span></p>
                <a href="https://ghn.vn/blogs/trang-thai-don-hang?order_code=<?= $order['tracking_code'] ?>"
                   target="_blank" class="btn btn-sm btn-primary">
                   <i class="fas fa-search-location me-1"></i> Theo dõi lộ trình trên GHN
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="m-0 fw-bold text-primary"><i class="fas fa-box-open me-2"></i>Danh sách sản phẩm</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 ps-4">Sản phẩm</th>
                        <th class="text-center py-3">Số lượng</th>
                        <th class="text-end py-3">Đơn giá</th>
                        <th class="text-end py-3 pe-4">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subtotal = 0;
                    foreach($details as $item): 
                        $subtotal += $item['total_price'];
                    ?>
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-dark"><?= htmlspecialchars($item['product_name']) ?></span>
                            <div class="text-muted small mt-1">
                                <span class="badge bg-light text-dark border">Size: <?= $item['size'] ?></span>
                                <span class="badge bg-light text-dark border ms-1">Màu: <?= $item['color'] ?></span>
                            </div>
                        </td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end text-muted"><?= number_format($item['price']) ?>đ</td>
                        <td class="text-end fw-bold text-dark pe-4"><?= number_format($item['total_price']) ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-light">
                    <tr>
                        <td colspan="3" class="text-end text-muted pt-3">Tổng tiền hàng (Tạm tính):</td>
                        <td class="text-end pe-4 pt-3 fw-bold"><?= number_format($subtotal) ?>đ</td>
                    </tr>
                    
                    <?php if ($order['discount_amount'] > 0): ?>
                    <tr>
                        <td colspan="3" class="text-end text-success">
                            <i class="fas fa-ticket-alt me-1"></i> Giảm giá (<?= $order['coupon_code'] ?? 'Mã' ?>):
                        </td>
                        <td class="text-end text-success pe-4">- <?= number_format($order['discount_amount']) ?>đ</td>
                    </tr>
                    <?php endif; ?>

                    <tr>
                        <td colspan="3" class="text-end align-middle pb-4">
                            <span class="fs-5 fw-bold text-dark">TỔNG CỘNG THANH TOÁN:</span>
                        </td>
                        <td class="text-end pe-4 pb-4">
                            <span class="fs-4 fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</span>
                        </td>
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