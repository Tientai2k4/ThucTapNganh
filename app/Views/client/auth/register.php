<?php require_once ROOT_PATH . '/app/Views/client/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center text-primary mb-4 fw-bold text-uppercase">Đăng Ký Thành Viên</h3>
                    
                    <?php if(isset($data['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fa-solid fa-triangle-exclamation"></i> <?= $data['error'] ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>auth/processRegister" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Đăng Ký</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="mb-1">Đã có tài khoản?</p>
                        <a href="<?= BASE_URL ?>auth/login" class="text-decoration-none fw-bold">Đăng nhập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/Views/client/layouts/footer.php'; ?>