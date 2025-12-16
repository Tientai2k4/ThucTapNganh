<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Swimming Store - Chuyên đồ bơi chính hãng' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/client.css">
</head>
<body>
    <div class="bg-primary text-white py-1 small">
        <div class="container d-flex justify-content-between align-items-center">
            <span><i class="fas fa-phone-alt me-1"></i> Hotline: 090.123.4567 (8h-21h)</span>
            <div>
                <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-0">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary fs-3" href="<?= BASE_URL ?>">
                    <i class="fas fa-swimmer"></i> SWIM STORE
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a></li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kính bơi</a>
                            <div class="dropdown-menu mega-menu">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <span class="mega-title">CÔNG DỤNG</span>
                                            <a class="mega-item" href="#">Kính bơi cận</a>
                                            <a class="mega-item" href="#">Kính thi đấu</a>
                                            <a class="mega-item" href="#">Kính trẻ em</a>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="mega-title">THƯƠNG HIỆU</span>
                                            <a class="mega-item" href="#">Speedo</a>
                                            <a class="mega-item" href="#">Arena</a>
                                            <a class="mega-item" href="#">Phoenix</a>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="mega-title">PHỤ KIỆN</span>
                                            <a class="mega-item" href="#">Dây đeo kính</a>
                                            <a class="mega-item" href="#">Hộp đựng kính</a>
                                        </div>
                                        <div class="col-md-3">
                                            <img src="https://yeuboiloi.com/wp-content/uploads/2021/06/banner-kinh-boi-can.jpg" class="img-fluid rounded shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="#">Mũ bơi</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Quần áo bơi</a></li>
                    </ul>

                    <div class="d-flex align-items-center">
                        <a href="<?= BASE_URL ?>cart" class="btn position-relative text-primary me-3 border-0">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle btn-sm d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                                    <?php if(!empty($_SESSION['user_avatar'])): ?>
                                        <img src="<?= $_SESSION['user_avatar'] ?>" class="rounded-circle me-2" width="25" height="25">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/dashboard"><i class="fas fa-cog me-2"></i>Quản trị</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>user/profile"><i class="fas fa-address-book me-2"></i>Sổ địa chỉ</a></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>user/history"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>client/auth/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>client/auth/login" class="btn btn-primary btn-sm px-3 fw-bold">Đăng nhập</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="py-4">
        <div class="container">