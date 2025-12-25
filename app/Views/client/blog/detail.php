<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>blog">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bài viết</li>
                </ol>
            </nav>

            <h1 class="fw-bold text-dark mb-3"><?= htmlspecialchars($data['post']['title']) ?></h1>
            
            <div class="d-flex align-items-center mb-4 text-muted">
                <span class="me-3"><i class="far fa-user"></i> <?= htmlspecialchars($data['post']['author_name'] ?? 'Admin') ?></span>
                <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y H:i', strtotime($data['post']['created_at'])) ?></span>
            </div>

           <?php if(!empty($data['post']['thumbnail'])): ?>
                <img src="<?= BASE_URL ?>public/uploads/posts/<?= $data['post']['thumbnail'] ?>" 
                    class="img-fluid rounded mb-4 w-100 shadow-sm" 
                    style="max-height: 500px; object-fit: cover;"
                    onerror="this.onerror=null; this.src='https://placehold.co/800x450?text=Image+Not+Found';">
            <?php endif; ?>

            <div class="article-content fs-5 lh-lg">
                <?= $data['post']['content'] ?>
            </div>

            <hr class="my-5">
            
            <div class="text-center">
                <a href="<?= BASE_URL ?>blog" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách tin
                </a>
            </div>
        </div>
    </div>
</div>