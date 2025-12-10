<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập - Swimming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center text-primary mb-3">ĐĂNG NHẬP</h3>
        
        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger"><?= $data['error'] ?></div>
        <?php endif; ?>
        <?php if(isset($data['success'])): ?>
            <div class="alert alert-success"><?= $data['success'] ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>auth/processLogin" method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100 mb-2">Đăng nhập</button>
            <a href="<?= BASE_URL ?>client/auth/register" class="d-block text-center">Chưa có tài khoản? Đăng ký</a>
            <a href="<?= BASE_URL ?>" class="d-block text-center mt-2 text-secondary">Về trang chủ</a>
        </form>
    </div>
</body>
</html>