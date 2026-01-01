<?php 
    $order = $data['order'];
    $details = $data['details'];
    
    // Logic thanh tiến trình đơn hàng
    $statusStep = 0;
    switch($order['status']) {
        // Cả pending (chờ xử lý) và processing (đang chuẩn bị) đều tính là Bước 1
        case 'pending': 
        case 'pending_payment': // Thêm trạng thái chờ thanh toán nếu có
        case 'processing': 
            $statusStep = 1; 
            break;
            
        // Khi nào sang 'shipped' (đang vận chuyển) mới nhảy sang Bước 2
        case 'shipped': 
        case 'shipping': // Đề phòng bạn dùng từ shipping
            $statusStep = 2; 
            break;
            
        // Hoàn thành là Bước 3
        case 'completed': 
            $statusStep = 4; // Số 4 để full thanh tiến trình (vì logic view ở dưới dùng số 4)
            break;
            
        case 'cancelled': 
            $statusStep = 0; 
            break;
    }
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Chi tiết đơn hàng #<?= $order['order_code'] ?></h4>
            <span class="text-muted small">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
        </div>
        <div>
            <?php if($order['status'] != 'cancelled'): ?>
               
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>user/history" class="btn btn-secondary btn-sm">Quay lại</a>
        </div>
    </div>

    <?php if($order['status'] != 'cancelled'): ?>
    <div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <div class="position-relative m-4">
            <div class="progress" style="height: 2px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($statusStep - 1) * 50 ?>%;"></div>
            </div>
            
            <div class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-<?= $statusStep >= 1 ? 'success' : 'light border' ?> rounded-pill" style="width: 2rem; height:2rem;">1</div>
            
            <div class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-<?= $statusStep >= 2 ? 'success' : 'light border' ?> rounded-pill" style="width: 2rem; height:2rem;">2</div>
            
            <div class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-<?= $statusStep >= 3 ? 'success' : 'light border' ?> rounded-pill" style="width: 2rem; height:2rem;">3</div>
            
            <div class="position-absolute top-100 start-0 translate-middle-x mt-2 text-center small fw-bold">Đã đặt hàng</div>
            <div class="position-absolute top-100 start-50 translate-middle-x mt-2 text-center small fw-bold">Đang giao hàng</div>
            <div class="position-absolute top-100 start-100 translate-middle-x mt-2 text-center small fw-bold">Hoàn thành</div>
        </div>
    </div>
</div>
    <?php else: ?>
        <div class="alert alert-danger d-flex align-items-center mb-4">
            <i class="fas fa-times-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Đơn hàng đã bị hủy</h5>
                <p class="mb-0">Đơn hàng này đã ngưng xử lý. Bạn có thể đặt mua lại các sản phẩm này.</p>
            </div>
            <div class="ms-auto">
                <a href="<?= BASE_URL ?>user/repurchase/<?= $order['order_code'] ?>" class="btn btn-light fw-bold text-danger">Mua lại ngay</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-cube me-2"></i>Sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($details as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                                <small class="text-muted">Phân loại: <?= $item['size'] ?> / <?= $item['color'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">x<?= $item['quantity'] ?></td>
                                    <td class="text-end pe-4 fw-bold"><?= number_format($item['total_price']) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="text-end pt-3">Tổng tiền hàng:</td>
                                    <td class="text-end pe-4 pt-3 fw-bold"><?= number_format($order['total_money'] + $order['discount_amount']) ?>đ</td>
                                </tr>
                                <?php if($order['discount_amount'] > 0): ?>
                                <tr>
                                    <td colspan="2" class="text-end text-success">Giảm giá:</td>
                                    <td class="text-end pe-4 text-success">- <?= number_format($order['discount_amount']) ?>đ</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="2" class="text-end pb-3 align-middle fs-5 fw-bold">Thành tiền:</td>
                                    <td class="text-end pe-4 pb-3 fs-4 fw-bold text-danger"><?= number_format($order['total_money']) ?>đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <?php if($order['status'] == 'pending' || $order['status'] == 'pending_payment'): ?>
                    <button type="button" class="btn btn-outline-danger flex-grow-1" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Hủy đơn hàng
                    </button>
                <?php endif; ?>
                
                <?php if($order['status'] == 'completed'): ?>
                    <a href="<?= BASE_URL ?>user/repurchase/<?= $order['order_code'] ?>" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-shopping-cart me-2"></i> Mua lại đơn này
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ nhận hàng</h6>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold"><?= htmlspecialchars($order['customer_name']) ?></h6>
                    <p class="mb-1 text-muted small">SĐT: <?= $order['customer_phone'] ?></p>
                    <p class="mb-0 text-dark"><?= htmlspecialchars($order['shipping_address']) ?></p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phương thức TT:</span>
                        <span class="fw-bold"><?= $order['payment_method'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Trạng thái TT:</span>
                        <?php if($order['payment_status']): ?>
                            <span class="badge bg-success">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!empty($order['tracking_code'])): ?>
                    <hr>
                    <div class="mb-2">
                        <span class="text-muted d-block mb-1">Mã vận đơn (GHN):</span>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm bg-white" value="<?= $order['tracking_code'] ?>" readonly>
                            <a href="https://ghn.vn/blogs/trang-thai-don-hang?order_code=<?= $order['tracking_code'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>user/cancelOrder" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-danger">Xác nhận hủy đơn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đơn hàng <strong>#<?= $order['order_code'] ?></strong> không?</p>
                    <input type="hidden" name="order_code" value="<?= $order['order_code'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Lý do hủy (Không bắt buộc):</label>
                        <select name="cancel_reason" class="form-select">
                            <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                            <option value="Muốn thay đổi sản phẩm/địa chỉ">Muốn thay đổi sản phẩm/địa chỉ</option>
                            <option value="Tìm thấy giá rẻ hơn">Tìm thấy giá rẻ hơn</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger">Xác nhận Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>