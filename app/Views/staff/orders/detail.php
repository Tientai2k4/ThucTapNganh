<div class="container py-4">
    <div class="mb-3">
        <a href="<?= BASE_URL ?>staff/order" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-1"></i> Quay lại danh sách</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4" id="invoiceArea">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between">
                    <h5 class="fw-bold m-0 text-primary">CHI TIẾT ĐƠN HÀNG #<?= $data['order']['order_code'] ?></h5>
                    <span class="text-muted small">Ngày đặt: <?= date('d/m/Y H:i', strtotime($data['order']['created_at'])) ?></span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Người nhận</h6>
                            <p class="fw-bold mb-1"><?= htmlspecialchars($data['order']['customer_name']) ?></p>
                            <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i><?= $data['order']['customer_phone'] ?></p>
                            <p class="mb-0"><i class="fas fa-envelope me-2 text-muted"></i><?= $data['order']['customer_email'] ?></p>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Địa chỉ giao hàng</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($data['order']['shipping_address'])) ?></p>
                        </div>
                    </div>

                    <div class="table-responsive border rounded mb-3">
                        <table class="table table-borderless mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="ps-3">Sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end pe-3">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['details'] as $item): ?>
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                        <small class="text-muted">Phân loại: <?= $item['size'] ?> / <?= $item['color'] ?></small>
                                    </td>
                                    <td class="text-center">x<?= $item['quantity'] ?></td>
                                    <td class="text-end pe-3"><?= number_format($item['total_price']) ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="2" class="text-end pt-3">Tạm tính:</td>
                                    <td class="text-end pe-3 pt-3 fw-bold"><?= number_format($data['order']['total_money'] + $data['order']['discount_amount']) ?>đ</td>
                                </tr>
                                <?php if($data['order']['discount_amount'] > 0): ?>
                                <tr>
                                    <td colspan="2" class="text-end text-success">Giảm giá:</td>
                                    <td class="text-end pe-3 text-success">-<?= number_format($data['order']['discount_amount']) ?>đ</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="2" class="text-end pb-3"><h5 class="m-0 fw-bold">TỔNG CỘNG:</h5></td>
                                    <td class="text-end pe-3 pb-3"><h5 class="m-0 fw-bold text-danger"><?= number_format($data['order']['total_money']) ?>đ</h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Cập nhật trạng thái</h6>
                    <form action="<?= BASE_URL ?>staff/order/updateStatus" method="POST">
                        <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
                        <input type="hidden" name="order_code" value="<?= $data['order']['order_code'] ?>">
                        <input type="hidden" name="current_status" value="<?= $data['order']['status'] ?>">

                        <div class="mb-3">
                            <label class="form-label small text-muted">Trạng thái hiện tại</label>
                            <input type="text" class="form-control fw-bold" value="<?= strtoupper($data['order']['status']) ?>" disabled>
                        </div>

                        <?php if ($data['order']['status'] != 'cancelled' && $data['order']['status'] != 'completed'): ?>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Chuyển sang</label>
                                <select name="status" class="form-select border-primary">
                                    <option value="processing">Đang chuẩn bị hàng</option>
                                    <option value="shipped">Giao cho vận chuyển</option>
                                    <option value="completed">Đã giao thành công</option>
                                    <option value="cancelled" class="text-danger">Hủy đơn hàng</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Cập nhật ngay</button>
                        <?php else: ?>
                            <div class="alert alert-warning small"><i class="fas fa-lock me-1"></i>Đơn hàng đã kết thúc, không thể chỉnh sửa.</div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                     <button onclick="window.print()" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-print me-2"></i>In hóa đơn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>