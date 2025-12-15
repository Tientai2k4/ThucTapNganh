<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quên mật khẩu - Swimming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 450px;">
        <h3 class="text-center text-primary mb-4">KHÔI PHỤC MẬT KHẨU</h3>
        
        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger text-center"><?= $data['error'] ?></div>
        <?php endif; ?>
        
        <?php if(isset($data['success'])): ?>
            <div class="alert alert-success text-center">
                <?= $data['success'] ?> <br>Vui lòng kiểm tra hộp thư đến của bạn.
            </div>
            <a href="<?= BASE_URL ?>client/auth/login" class="btn btn-secondary w-100">Quay lại Đăng nhập</a>
        <?php else: ?>
            <p class="text-center text-muted">Vui lòng nhập email tài khoản của bạn. Chúng tôi sẽ gửi link đặt lại mật khẩu.</p>
            
            <form action="<?= BASE_URL ?>client/auth/sendResetLink" method="POST">
                <div class="mb-4">
                    <label>Địa chỉ Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@gmail.com">
                </div>
                
                <button class="btn btn-primary w-100 mb-3">Gửi Link Khôi Phục</button>
                <a href="<?= BASE_URL ?>client/auth/login" class="d-block text-center">Quay lại Đăng nhập</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>