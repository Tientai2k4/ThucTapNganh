<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Thế Giới Bơi Lội</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; height: 100vh; display: flex; align-items: center; }
        .login-container { max-width: 400px; width: 100%; margin: auto; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .btn-google { background: #fff; border: 1px solid #ddd; color: #555; font-weight: 600; transition: 0.3s; }
        .btn-google:hover { background: #f8f9fa; border-color: #ccc; }
        .btn-primary-custom { background: #007bff; border: none; font-weight: 600; padding: 12px; transition: 0.3s; }
        .btn-primary-custom:hover { background: #0056b3; transform: translateY(-2px); }
        .divider { display: flex; align-items: center; text-align: center; color: #999; margin: 20px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid #eee; }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">ĐĂNG NHẬP</h3>
            <p class="text-muted">Chào mừng bạn đến với Thế Giới Bơi Lội</p>
        </div>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger small p-2 text-center"><?= $data['error'] ?></div>
        <?php endif; ?>
        <?php if(isset($_GET['success']) && $_GET['success'] == 'register_ok'): ?>
            <div class="alert alert-success small p-2 text-center">
                <i class="fas fa-check-circle me-1"></i> Đăng ký thành công! Mời bạn đăng nhập.
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['success']) && $_GET['success'] == 'reset_ok'): ?>
            <div class="alert alert-success small p-2 text-center">Đổi mật khẩu thành công!</div>
        <?php endif; ?>

        <?php 
            $googleUrl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id=' . GOOGLE_CLIENT_ID . '&redirect_uri=' . urlencode(GOOGLE_REDIRECT_URL) . '&scope=email profile';
        ?>
        <a href="<?= $googleUrl ?>" class="btn btn-google w-100 py-2 mb-3">
            Tiếp tục với Google
        </a>

        <div class="divider small text-uppercase">Hoặc email của bạn</div>

        <form action="<?= BASE_URL ?>client/auth/processLogin" method="POST">
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                <label for="email">Địa chỉ Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu" required>
                <label for="password">Mật khẩu</label>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
                </div>
                <a href="<?= BASE_URL ?>client/auth/forgotPassword" class="text-decoration-none small">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-primary-custom w-100 mb-3">ĐĂNG NHẬP</button>
        </form>

        <div class="text-center">
            <p class="small text-muted mb-0">Chưa có tài khoản? 
                <a href="<?= BASE_URL ?>client/auth/register" class="fw-bold text-decoration-none">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>