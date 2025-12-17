<?php $prefix = $data['role_prefix'] ?? 'admin'; ?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary fw-bold">Đơn hàng #<?= $data['order']['order_code'] ?></h3>
        
        <a href="<?= BASE_URL . $prefix ?>/order" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Thông tin khách hàng</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Họ tên:</strong> <?= $data['order']['customer_name'] ?></p>
                    <p class="mb-1"><strong>SĐT:</strong> <?= $data['order']['customer_phone'] ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?= $data['order']['customer_email'] ?></p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> <?= $data['order']['shipping_address'] ?></p>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Xử lý đơn hàng</h5>
                    
                    <form action="<?= BASE_URL . $prefix ?>/order/updateStatus" method="POST">
                        <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                        <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Trạng thái:</label>
                            <select name="status" class="form-select">
                                <option value="pending" <?= $data['order']['status']=='pending'?'selected':'' ?>>Chờ xử lý</option>
                                <option value="processing" <?= $data['order']['status']=='processing'?'selected':'' ?>>Đang chuẩn bị</option>
                                <option value="shipping" <?= $data['order']['status']=='shipping'?'selected':'' ?>>Đang giao hàng</option>
                                
                                <?php if($prefix == 'admin'): ?>
                                    <option value="completed" <?= $data['order']['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
                                    <option value="cancelled" <?= $data['order']['status']=='cancelled'?'selected':'' ?>>Hủy đơn (Admin)</option>
                                <?php else: ?>
                                    <option value="completed" <?= $data['order']['status']=='completed'?'selected':'' ?>>Hoàn thành</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="fas fa-save me-2"></i>Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">Chi tiết sản phẩm</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Phân loại</th>
                                <th>Đơn giá</th>
                                <th>SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['details'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><span class="badge bg-secondary"><?= $item['size'] ?> - <?= $item['color'] ?></span></td>
                                <td><?= number_format($item['price']) ?>đ</td>
                                <td>x<?= $item['quantity'] ?></td>
                                <td class="text-end fw-bold"><?= number_format($item['total_price']) ?>đ</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="4" class="text-end">Phí vận chuyển:</td>
                                <td class="text-end"><?= number_format($data['order']['shipping_fee']) ?>đ</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Giảm giá:</td>
                                <td class="text-end">-<?= number_format($data['order']['discount_amount']) ?>đ</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="4" class="text-end fw-bold text-danger fs-5">TỔNG THANH TOÁN:</td>
                                <td class="text-end fw-bold text-danger fs-5"><?= number_format($data['order']['total_money']) ?>đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>