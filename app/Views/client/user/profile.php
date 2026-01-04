<?php 
    $userAvatar = $data['user']['avatar'] ?? '';
    // Ưu tiên full_name, nếu không có thì dùng 'User'
    $userName = !empty($data['user']['full_name']) ? $data['user']['full_name'] : 'User';
    
    if (!empty($userAvatar)) {
        $avatarSrc = (strpos($userAvatar, 'http') === 0) ? $userAvatar : BASE_URL . "public/uploads/" . $userAvatar;
    } else {
        $nameParam = urlencode($userName);
        $avatarSrc = "https://ui-avatars.com/api/?name={$nameParam}&background=random&color=fff&size=128&bold=true&length=1";
    }
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="<?= $avatarSrc ?>" 
                            class="rounded-circle img-thumbnail shadow-sm object-fit-cover" 
                            style="width: 100px; height: 100px;">
                        
                        <form action="<?= BASE_URL ?>user/updateAvatar" method="POST" enctype="multipart/form-data" id="avatarForm">
                             <label for="avatarUpload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 shadow border border-white" style="cursor: pointer; width: 32px; height: 32px;">
                                <i class="fas fa-camera small"></i>
                            </label>
                            <input type="file" name="avatar" id="avatarUpload" class="d-none" onchange="document.getElementById('avatarForm').submit()" accept="image/*">
                        </form>
                    </div>
                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($data['user']['full_name']) ?></h6>
                    <small class="text-muted"><?= htmlspecialchars($data['user']['email']) ?></small>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?= BASE_URL ?>user/profile" class="list-group-item list-group-item-action active border-0">
                        <i class="fas fa-user me-2"></i> Hồ sơ của tôi
                    </a>
                    <a href="<?= BASE_URL ?>user/history" class="list-group-item list-group-item-action border-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Đơn mua
                    </a>
                    <a href="<?= BASE_URL ?>client/auth/logout" class="list-group-item list-group-item-action border-0 text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Thông tin tài khoản</h6>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>user/updateInfo" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted">Họ và tên</label>
                               <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars((string)($data['user']['full_name'] ?? '')) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-muted">Số điện thoại</label>
                                <input type="text" name="phone_number" id="phone_profile_modal" class="form-control" 
                                    value="<?= htmlspecialchars((string)($data['user']['phone_number'] ?? '')) ?>" 
                                    maxlength="10" required>
                                <small id="phone-error-modal" class="text-danger fw-bold" style="display: none;"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-link text-decoration-none p-0 text-muted" data-bs-toggle="collapse" data-bs-target="#changePass">
                                <i class="fas fa-key me-1"></i> Đổi mật khẩu?
                            </button>
                            <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                        </div>
                    </form>

                    <div class="collapse mt-3" id="changePass">
                        <div class="card card-body bg-light border-0">
                            <form action="<?= BASE_URL ?>user/changePassword" method="POST">
                                <div class="mb-2">
                                    <input type="password" name="old_password" class="form-control" placeholder="Mật khẩu hiện tại" required>
                                </div>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="password" name="new_password" class="form-control" placeholder="Mật khẩu mới" required>
                                    </div>
                                    <div class="col">
                                        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                                    </div>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-sm btn-dark">Cập nhật mật khẩu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Sổ địa chỉ</h6>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['addresses'])): ?>
                        <?php foreach ($data['addresses'] as $addr): ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div>
                                <div class="d-flex align-items-center mb-1">
                                    <span class="fw-bold"><?= htmlspecialchars((string)($addr['recipient_name'] ?? '')) ?></span>
                                   <span class="text-muted border-start border-2 ms-2 ps-2 small"><?= htmlspecialchars((string)($addr['phone'] ?? '')) ?></span>
                                    <?php if($addr['is_default']): ?>
                                        <span class="badge bg-success ms-2">Mặc định</span>
                                    <?php endif; ?>
                                </div>
                               <div class="text-secondary small"><?= htmlspecialchars((string)($addr['address'] ?? '')) ?></div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php if(!$addr['is_default']): ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>user/setDefaultAddress/<?= $addr['id'] ?>">Thiết lập mặc định</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>user/deleteAddress/<?= $addr['id'] ?>" onclick="return confirm('Xóa địa chỉ này?')">Xóa</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Chưa có địa chỉ nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>user/addAddress" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small">Tên người nhận</label>
                        <input type="text" name="recipient_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label class="form-label small text-muted">Số điện thoại</label>
                    <input type="text" name="phone_number" id="phone_profile" class="form-control" 
                        value="<?= htmlspecialchars((string)$data['user']['phone_number']) ?>" 
                        maxlength="10" required>
                    <small id="phone-error" class="text-danger fw-bold" style="display: none;"></small>
                </div>
                    <div class="mb-3">
                        <label class="form-label small">Địa chỉ chi tiết (Số nhà, đường, phường/xã...)</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const phoneInput = document.getElementById('phone_profile');
    const errorSpan = document.getElementById('phone-error');

    if(phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // 1. LƯU VỊ TRÍ CON TRỎ HIỆN TẠI
            // Để sau khi xóa chữ, ta đặt con trỏ lại đúng chỗ đó chứ không bị nhảy về cuối
            const start = this.selectionStart;
            const originalVal = this.value;

            // 2. CHẶN NHẬP CHỮ (Thay thế ký tự không phải số bằng rỗng)
            const cleanVal = originalVal.replace(/[^0-9]/g, '');

            // Nếu giá trị thay đổi (tức là người dùng vừa nhập chữ)
            if (originalVal !== cleanVal) {
                this.value = cleanVal;
                
                // Tính toán để trả con trỏ về vị trí cũ (trừ đi ký tự vừa bị xóa)
                const diff = originalVal.length - cleanVal.length;
                this.setSelectionRange(start - diff, start - diff);
            }

            // 3. KIỂM TRA FORMAT (Đoạn này giữ nguyên logic cũ)
            const regexPhone = /^0[0-9]{9}$/;
            
            if (this.value.length > 0 && !regexPhone.test(this.value)) {
                if (this.value.length !== 10) {
                    errorSpan.innerText = "SĐT phải đủ 10 chữ số.";
                } else if (!this.value.startsWith('0')) {
                    errorSpan.innerText = "SĐT phải bắt đầu bằng số 0.";
                }
                errorSpan.style.display = 'block';
                phoneInput.classList.add('is-invalid');
            } else {
                errorSpan.style.display = 'none';
                phoneInput.classList.remove('is-invalid');
            }
        });
    }
});
</script>