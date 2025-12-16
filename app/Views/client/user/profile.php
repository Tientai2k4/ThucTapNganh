<div class="row my-4">
    <div class="col-md-3">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action active">Hồ sơ & Địa chỉ</a>
            <a href="<?= BASE_URL ?>user/history" class="list-group-item list-group-item-action">Lịch sử đơn hàng</a>
            <a href="<?= BASE_URL ?>client/auth/logout" class="list-group-item list-group-item-action text-danger">Đăng xuất</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card p-4 shadow-sm">
            <h4 class="mb-3 text-primary">Thông tin tài khoản</h4>
            <form>
                <div class="row mb-3">
                    <div class="col">
                        <label>Họ tên</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['user']['full_name'] ?? '') ?>" disabled>
                    </div>
                    <div class="col">
                        <label>Email</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($data['user']['email'] ?? '') ?>" disabled>
                    </div>
                </div>
            </form>

            <hr class="my-4">
            
            <h4 class="mb-3 text-primary d-flex justify-content-between align-items-center">
                <span>Sổ địa chỉ nhận hàng</span>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addAddrForm">
                    <i class="fas fa-plus"></i> Thêm mới
                </button>
            </h4>

            <div class="collapse mb-4 bg-light p-3 rounded" id="addAddrForm">
                <form action="<?= BASE_URL ?>user/addAddress" method="POST">
                    <div class="row mb-2">
                        <div class="col">
                            <input type="text" name="recipient_name" class="form-control" placeholder="Tên người nhận" required>
                        </div>
                        <div class="col">
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="address" class="form-control" placeholder="Địa chỉ cụ thể (Số nhà, Phường, Quận...)" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">Lưu địa chỉ</button>
                </form>
            </div>

            <?php if (!empty($data['addresses'])): ?>
                <?php foreach ($data['addresses'] as $addr): ?>
                <div class="border rounded p-3 mb-3 position-relative <?= $addr['is_default'] ? 'border-success bg-light' : '' ?>">
                    <strong><?= htmlspecialchars($addr['recipient_name']) ?></strong> 
                    <span class="text-muted ms-2">| <?= htmlspecialchars($addr['phone']) ?></span>
                    
                    <?php if($addr['is_default']): ?>
                        <span class="badge bg-success ms-2">Mặc định</span>
                    <?php endif; ?>
                    
                    <p class="mb-1 mt-1"><?= htmlspecialchars($addr['address']) ?></p>
                    
                    <div class="mt-2">
                        <?php if(!$addr['is_default']): ?>
                            <a href="<?= BASE_URL ?>user/setDefaultAddress/<?= $addr['id'] ?>" class="text-primary small me-3 text-decoration-none">Đặt làm mặc định</a>
                            <a href="<?= BASE_URL ?>user/deleteAddress/<?= $addr['id'] ?>" class="text-danger small text-decoration-none" onclick="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?')">Xóa</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">Bạn chưa lưu địa chỉ nhận hàng nào.</div>
            <?php endif; ?>
        </div>
    </div>
</div>