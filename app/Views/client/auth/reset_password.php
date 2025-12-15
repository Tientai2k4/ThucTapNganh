<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đặt lại mật khẩu - Swimming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 450px;">
        <h3 class="text-center text-primary mb-4">ĐẶT MẬT KHẨU MỚI</h3>
        
        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger text-center"><?= $data['error'] ?></div>
        <?php endif; ?>

        <p class="text-center text-muted">Nhập mật khẩu mới cho tài khoản của bạn.</p>
        
        <form action="<?= BASE_URL ?>client/auth/processResetPassword" method="POST">
            <input type="hidden" name="token" value="<?= $data['token'] ?? '' ?>"> 

            <div class="mb-3">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="mb-4">
                <label>Nhập lại mật khẩu mới</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button class="btn btn-success w-100">Đặt lại mật khẩu</button>
        </form>
    </div>
</body>
</html>