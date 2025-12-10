<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Dụng cụ bơi lội chính hãng' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/client.css">
</head>
<body>


    <header class="main-header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>">
                    <i class="fas fa-swimmer"></i> SWIMMING STORE
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>about">Giới thiệu</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>product">Sản phẩm</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tin tức</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Liên hệ</a></li>
                    </ul>
                    
                        <div class="d-flex align-items-center">
                             <a href="<?= BASE_URL ?>cart" class="btn btn-outline-primary position-relative me-3 border-0">
                             <i class="fas fa-shopping-cart fa-lg"></i>
                             <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                             </a>
                             <?php if (isset($_SESSION['user_id'])): ?>
                               <div class="dropdown">
                              <button class="btn btn-outline-primary dropdown-toggle fw-bold" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="fas fa-user-circle me-1"></i> Xin chào, <?= htmlspecialchars($_SESSION['user_name']) ?>
                               </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                 <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                                   <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/dashboard"><i class="fas fa-cog me-2"></i>Trang quản trị</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Hồ sơ cá nhân</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2"></i>Đơn hàng của tôi</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                                </ul>
                                 </div>
                                  <?php else: ?>
                                    <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary px-4 fw-bold">Đăng nhập</a>
                                     <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-primary me-2 fw-bold">Đăng ký</a>
                                 <?php endif; ?>
                         </div>



                </div>
            </div>
        </nav>
    </header>
    <main class="py-4">
        <div class="container">