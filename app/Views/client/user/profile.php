<div class="row my-4">
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm">
            <a href="<?= BASE_URL ?>user/profile" class="list-group-item list-group-item-action active fw-bold">
                <i class="fas fa-user-circle me-2"></i> Hồ sơ & Địa chỉ
            </a>
            <a href="<?= BASE_URL ?>user/history" class="list-group-item list-group-item-action">
                <i class="fas fa-history me-2"></i> Lịch sử đơn hàng
            </a>
            <a href="<?= BASE_URL ?>client/auth/logout" class="list-group-item list-group-item-action text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card p-4 shadow-sm border-0">
            
            <div class="text-center mb-4">
                <form action="<?= BASE_URL ?>user/updateAvatar" method="POST" enctype="multipart/form-data">
                    <div class="position-relative d-inline-block">
                        <?php 
                            // Xử lý hiển thị ảnh: Nếu có ảnh trong session/data thì dùng, không thì dùng ảnh mặc định
                            $avatarUrl = !empty($data['user']['avatar']) ? $data['user']['avatar'] : BASE_URL . 'public/assets/images/default-user.png';
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="Avatar" class="rounded-circle img-thumbnail object-fit-cover shadow-sm" style="width: 120px; height: 120px;">
                        
                        <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer shadow-sm" style="cursor: pointer;" title="Đổi ảnh">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" onchange="this.form.submit()" accept="image/*">
                    </div>
                    <div class="small text-muted mt-2">Nhấn vào icon máy ảnh để đổi avatar</div>
                </form>
            </div>

            <hr>

            <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-info-circle me-2"></i>Thông tin tài khoản</h5>
            
            <form action="<?= BASE_URL ?>user/updateInfo" method="POST">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($data['user']['full_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Số điện thoại</label>
                        <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($data['user']['phone_number'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email (Tên đăng nhập)</label>
                    <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($data['user']['email'] ?? '') ?>" disabled readonly>
                    <small class="text-muted fst-italic">* Email không thể thay đổi.</small>
                </div>
                
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Cập nhật thông tin</button>
                    
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#changePassForm">
                        <i class="fas fa-key me-1"></i> Đổi mật khẩu
                    </button>
                </div>
            </form>

            <div class="collapse mt-4" id="changePassForm">
                <div class="card card-body bg-light border-0">
                    <h6 class="fw-bold text-danger mb-3">Đổi mật khẩu</h6>
                    <form action="<?= BASE_URL ?>user/changePassword" method="POST">
                        <div class="mb-2">
                            <input type="password" name="old_password" class="form-control" placeholder="Mật khẩu hiện tại" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="password" name="new_password" class="form-control" placeholder="Mật khẩu mới" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu mới" required>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-danger btn-sm">Xác nhận đổi</button>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="my-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-primary fw-bold m-0"><i class="fas fa-map-marker-alt me-2"></i>Sổ địa chỉ nhận hàng</h5>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addAddrForm">
                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                </button>
            </div>

            <div class="collapse mb-4 bg-light p-3 rounded border" id="addAddrForm">
                <h6 class="fw-bold mb-3">Thêm địa chỉ mới</h6>
                <form action="<?= BASE_URL ?>user/addAddress" method="POST">
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <input type="text" name="recipient_name" class="form-control" placeholder="Tên người nhận" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại người nhận" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="address" class="form-control" placeholder="Địa chỉ cụ thể (Số nhà, Đường, Phường/Xã, Quận/Huyện, Tỉnh/Thành)" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i> Lưu địa chỉ</button>
                </form>
            </div>

            <?php if (!empty($data['addresses'])): ?>
                <?php foreach ($data['addresses'] as $addr): ?>
                <div class="border rounded p-3 mb-3 position-relative <?= $addr['is_default'] ? 'border-success bg-light shadow-sm' : '' ?>">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong><?= htmlspecialchars($addr['recipient_name']) ?></strong> 
                            <span class="text-muted ms-2 small">| <?= htmlspecialchars($addr['phone']) ?></span>
                            <?php if($addr['is_default']): ?>
                                <span class="badge bg-success ms-2">Mặc định</span>
                            <?php endif; ?>
                            <p class="mb-1 mt-2 text-dark"><i class="fas fa-home text-secondary me-2"></i><?= htmlspecialchars($addr['address']) ?></p>
                        </div>
                        
                        <div class="d-flex flex-column justify-content-center align-items-end gap-2">
                            <?php if(!$addr['is_default']): ?>
                                <a href="<?= BASE_URL ?>user/setDefaultAddress/<?= $addr['id'] ?>" class="btn btn-outline-primary btn-sm py-0" style="font-size: 12px;">
                                    Đặt mặc định
                                </a>
                                <a href="<?= BASE_URL ?>user/deleteAddress/<?= $addr['id'] ?>" class="btn btn-outline-danger btn-sm py-0" style="font-size: 12px;" onclick="return confirm('Bạn chắc chắn muốn xóa địa chỉ này?')">
                                    Xóa
                                </a>
                            <?php else: ?>
                                <span class="text-success small"><i class="fas fa-check-circle"></i> Đang dùng</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-map-marked-alt fa-2x mb-2 d-block"></i>
                    Bạn chưa lưu địa chỉ nhận hàng nào. Hãy thêm mới để đặt hàng nhanh hơn.
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>