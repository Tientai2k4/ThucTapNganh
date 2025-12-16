<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập - Swimming Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4 fw-bold text-primary">ĐĂNG NHẬP</h3>
                
                <?php if(isset($data['error'])): ?>
                    <div class="alert alert-danger"><?= $data['error'] ?></div>
                <?php endif; ?>

                <?php 
                    $googleUrl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&access_type=online&client_id=' . GOOGLE_CLIENT_ID . '&redirect_uri=' . urlencode(GOOGLE_REDIRECT_URL) . '&scope=email profile';
                ?>
                <a href="<?= $googleUrl ?>" class="btn btn-google w-100 mb-3 py-2 fw-bold text-uppercase">
                    <i class="fab fa-google me-2"></i> Đăng nhập bằng Google
                </a>

                <div class="text-center text-muted mb-3 position-relative">
                    <hr> <span class="bg-white px-2 position-absolute top-50 start-50 translate-middle small">HOẶC DÙNG EMAIL</span>
                </div>

                <form action="<?= BASE_URL ?>client/auth/processLogin" method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="emailInput" placeholder="name@example.com" required>
                        <label for="emailInput">Địa chỉ Email</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                        <label for="passInput">Mật khẩu</label>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fw-bold text-uppercase">Đăng nhập</button>
                </form>
                
                <div class="text-center mt-3 d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>client/auth/forgotPassword" class="text-decoration-none small">Quên mật khẩu?</a>
                    <a href="<?= BASE_URL ?>client/auth/register" class="text-decoration-none fw-bold small">Đăng ký tài khoản</a>
                </div>
    </div>
</body>
</html>