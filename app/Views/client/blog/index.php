<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
            <li class="breadcrumb-item active">Blog - Tin tức</li>
        </ol>
    </nav>

    <h2 class="fw-bold text-primary mb-4 text-center text-uppercase">Kinh nghiệm bơi lội</h2>

    <?php if(empty($data['posts'])): ?>
        <p class="text-center">Chưa có bài viết nào.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach($data['posts'] as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                    <a href="<?= BASE_URL ?>blog/detail/<?= $p['slug'] ? $p['slug'] : $p['id'] ?>">
                        <img src="<?= BASE_URL ?>public/uploads/posts/<?= !empty($p['thumbnail']) ? $p['thumbnail'] : 'default_blog.jpg' ?>" 
                             class="card-img-top" 
                             style="height: 200px; object-fit: cover;" 
                             alt="<?= htmlspecialchars($p['title']) ?>"
                             onerror="this.src='<?= BASE_URL ?>public/assets/images/default_blog.jpg'"> </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">
                            <a href="<?= BASE_URL ?>blog/detail/<?= $p['slug'] ? $p['slug'] : $p['id'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($p['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?= htmlspecialchars(mb_substr($p['excerpt'], 0, 100)) ?>...
                        </p>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($p['created_at'])) ?></small>
                            <a href="<?= BASE_URL ?>blog/detail/<?= $p['slug'] ? $p['slug'] : $p['id'] ?>" class="btn btn-sm btn-outline-primary">Xem thêm</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>