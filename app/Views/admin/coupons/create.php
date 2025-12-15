<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tạo Mã Giảm Giá Mới</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= BASE_URL ?>admin/coupon/store" method="POST" class="bg-white p-4 shadow rounded">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Mã Code (Ví dụ: SUMMER2025) *</label>
                        <input type="text" name="code" class="form-control" required style="text-transform: uppercase;">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Số lượng mã (Số lượt dùng) *</label>
                        <input type="number" name="quantity" class="form-control" value="100" min="1" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Loại giảm *</label>
                        <select name="discount_type" class="form-control" required>
                            <option value="fixed">Số tiền cố định (VNĐ)</option>
                            <option value="percent">Phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Giá trị giảm *</label>
                        <input type="number" name="discount_value" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Đơn tối thiểu (Áp dụng từ)</label>
                        <input type="number" name="min_order_value" class="form-control" value="0" min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Ngày giờ bắt đầu *</label>
                        <input type="datetime-local" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Ngày giờ kết thúc *</label>
                        <input type="datetime-local" name="end_date" class="form-control" required>
                    </div>
                </div>
                
                <hr>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Lưu mã</button>
                    <a href="<?= BASE_URL ?>admin/coupon" class="btn btn-secondary">Quay lại</a>
                </div>

            </form>
        </div>
    </div>
</div>