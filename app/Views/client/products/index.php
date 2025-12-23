<div class="container py-5">
    <button class="btn btn-primary d-lg-none mb-3 w-100 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterSidebar">
        <i class="fas fa-filter me-2"></i> Bộ lọc tìm kiếm
    </button>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="filterSidebar">
                <div class="offcanvas-header border-bottom bg-light">
                    <h5 class="offcanvas-title fw-bold text-primary">Bộ lọc sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#filterSidebar"></button>
                </div>
                
                <div class="offcanvas-body p-0">
                    <form id="filterForm" action="<?= BASE_URL ?>product" method="GET" class="card border-0 shadow-sm p-4 sticky-top" style="top: 80px; z-index: 99;">
                        
                        <input type="hidden" name="keyword" value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                        <input type="hidden" name="sort" id="hiddenSort" value="<?= htmlspecialchars($data['filters']['sort'] ?? 'newest') ?>">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0 text-uppercase"><i class="fas fa-filter me-2 text-primary"></i>Bộ lọc</h5>
                            <a href="<?= BASE_URL ?>product" class="text-decoration-none small text-danger fw-bold hover-opacity">
                                <i class="fas fa-sync-alt me-1"></i>Làm mới
                            </a>
                        </div>

                        <div class="filter-group mb-4">
                            <h6 class="fw-bold text-uppercase mb-3 ps-2 border-start border-4 border-primary">Danh mục</h6>
                            
                            <div class="category-list">
                                <div class="cat-item mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category_id" value="" id="cat_all" 
                                            <?= empty($data['filters']['category_id']) ? 'checked' : '' ?> 
                                            onchange="this.form.submit()"> <label class="form-check-label fw-bold cursor-pointer w-100" for="cat_all">Tất cả sản phẩm</label>
                                    </div>
                                </div>

                                <?php 
                                    // Xử lý cây danh mục (Tách cha - con)
                                    $tree = [];
                                    $currentCatId = $data['filters']['category_id'] ?? 0;
                                    
                                    foreach($data['categories'] as $cat) {
                                        if(empty($cat['parent_id'])) {
                                            $tree[$cat['id']] = $cat;
                                            $tree[$cat['id']]['children'] = [];
                                        }
                                    }
                                    foreach($data['categories'] as $cat) {
                                        if(!empty($cat['parent_id']) && isset($tree[$cat['parent_id']])) {
                                            $tree[$cat['parent_id']]['children'][] = $cat;
                                        }
                                    }
                                ?>

                                <?php foreach($tree as $parent): ?>
                                    <?php 
                                        $isParentActive = ($currentCatId == $parent['id']);
                                        $hasChildren = !empty($parent['children']);
                                        // Mở accordion nếu đang chọn cha hoặc con
                                        $isChildActive = false;
                                        if($hasChildren) {
                                            $isChildActive = in_array($currentCatId, array_column($parent['children'], 'id'));
                                        }
                                        $isOpen = $isParentActive || $isChildActive;
                                    ?>
                                    
                                    <div class="cat-group mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="form-check flex-grow-1">
                                                <input class="form-check-input" type="radio" name="category_id" 
                                                    value="<?= $parent['id'] ?>" id="cat_<?= $parent['id'] ?>"
                                                    <?= $isParentActive ? 'checked' : '' ?> 
                                                    onchange="this.form.submit()"> <label class="form-check-label cursor-pointer w-100 <?= $isParentActive ? 'text-primary fw-bold' : '' ?>" for="cat_<?= $parent['id'] ?>">
                                                    <?= htmlspecialchars($parent['name']) ?>
                                                </label>
                                            </div>

                                            <?php if($hasChildren): ?>
                                                <button class="btn btn-sm btn-link text-dark text-decoration-none p-0 px-2 toggle-cat-btn <?= $isOpen ? '' : 'collapsed' ?>" 
                                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $parent['id'] ?>">
                                                    <i class="fas fa-chevron-down transition-icon"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($hasChildren): ?>
                                            <div class="collapse <?= $isOpen ? 'show' : '' ?> ps-3 mt-1 border-start ms-3 border-2" id="collapse_<?= $parent['id'] ?>">
                                                <?php foreach($parent['children'] as $child): ?>
                                                    <?php $isThisChildActive = ($currentCatId == $child['id']); ?>
                                                    <div class="form-check py-1">
                                                        <input class="form-check-input" type="radio" name="category_id" 
                                                            value="<?= $child['id'] ?>" id="cat_<?= $child['id'] ?>"
                                                            <?= $isThisChildActive ? 'checked' : '' ?> 
                                                            onchange="this.form.submit()"> <label class="form-check-label small cursor-pointer w-100 <?= $isThisChildActive ? 'text-primary fw-bold' : 'text-muted' ?>" for="cat_<?= $child['id'] ?>">
                                                            <?= htmlspecialchars($child['name']) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="filter-group mb-4">
                            <h6 class="fw-bold text-uppercase mb-3 ps-2 border-start border-4 border-primary">Khoảng giá</h6>
                            
                            <div class="price-slider-container mb-3">
                                <div class="slider-track"></div>
                                <div class="slider-progress" id="slider-progress"></div>
                                <input type="range" class="range-input" id="rangeMin" min="0" max="5000000" step="50000" value="<?= $data['filters']['price_min'] ?? 0 ?>">
                                <input type="range" class="range-input" id="rangeMax" min="0" max="5000000" step="50000" value="<?= $data['filters']['price_max'] ?? 5000000 ?>">
                            </div>

                            <div class="price-inputs d-flex justify-content-between gap-2">
                                <div class="input-group input-group-sm">
                                    <input type="number" name="min" id="inputMin" class="form-control text-center fw-bold text-primary" value="<?= $data['filters']['price_min'] ?? 0 ?>">
                                    <span class="input-group-text bg-white small">đ</span>
                                </div>
                                <span class="align-self-center text-muted">-</span>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="max" id="inputMax" class="form-control text-center fw-bold text-primary" value="<?= $data['filters']['price_max'] ?? 5000000 ?>">
                                    <span class="input-group-text bg-white small">đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="filter-group mb-4">
                            <h6 class="fw-bold text-uppercase mb-3 ps-2 border-start border-4 border-primary">Thương hiệu</h6>
                            <div class="brand-list custom-scrollbar pe-2" style="max-height: 180px; overflow-y: auto;">
                                <?php foreach($data['brands'] as $brand): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="brand[]" value="<?= $brand['id'] ?>" id="brand_<?= $brand['id'] ?>"
                                            <?= (isset($data['filters']['brands']) && in_array($brand['id'], $data['filters']['brands'])) ? 'checked' : '' ?>>
                                        <label class="form-check-label small cursor-pointer" for="brand_<?= $brand['id'] ?>">
                                            <?= htmlspecialchars($brand['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="filter-group mb-4">
                            <h6 class="fw-bold text-uppercase mb-3 ps-2 border-start border-4 border-primary">Kích thước</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <?php $sizes = ['S', 'M', 'L', 'XL', 'XXL', 'FreeSize']; ?>
                                <?php foreach($sizes as $s): ?>
                                    <input type="checkbox" class="btn-check" name="size[]" id="size_<?= $s ?>" value="<?= $s ?>"
                                        <?= (isset($data['filters']['sizes']) && in_array($s, $data['filters']['sizes'])) ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-secondary btn-sm size-btn shadow-sm" for="size_<?= $s ?>"><?= $s ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill text-uppercase transition-btn">
                            <i class="fas fa-check-circle me-1"></i> Áp dụng bộ lọc
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border-start border-5 border-primary">
                <div class="mb-2 mb-md-0">
                    <h5 class="fw-bold text-dark m-0">
                        <?php if(!empty($data['filters']['keyword'])): ?>
                            Kết quả tìm kiếm: "<span class="text-primary"><?= htmlspecialchars($data['filters']['keyword']) ?></span>"
                        <?php else: ?>
                            Danh sách sản phẩm
                        <?php endif; ?>
                    </h5>
                    <?php if(!empty($data['products'])): ?>
                        <small class="text-muted">Hiển thị <strong><?= count($data['products']) ?></strong> trên tổng số <?= $data['pagination']['total_products'] ?> sản phẩm</small>
                    <?php endif; ?>
                </div>
                
                <?php if(!empty($data['products'])): ?>
                <div class="d-flex align-items-center">
                    <label class="me-2 text-muted small text-nowrap"><i class="fas fa-sort me-1"></i>Sắp xếp:</label>
                    <select class="form-select form-select-sm border-secondary shadow-none fw-bold text-dark" style="width: 170px;"
                        onchange="document.getElementById('hiddenSort').value = this.value; document.getElementById('filterForm').submit();">
                        <option value="newest" <?= ($data['filters']['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                        <option value="price_asc" <?= ($data['filters']['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                        <option value="price_desc" <?= ($data['filters']['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                        <option value="name_asc" <?= ($data['filters']['sort'] ?? '') == 'name_asc' ? 'selected' : '' ?>>Tên A-Z</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <?php if(empty($data['products'])): ?>
                <div class="text-center py-5 bg-white rounded shadow-sm border">
                    <div class="mb-3 text-muted opacity-50">
                        <i class="fas fa-search fa-4x"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Rất tiếc, không tìm thấy sản phẩm nào!</h4>
                    <p class="text-muted mb-4 px-3">
                        Không có sản phẩm nào khớp với lựa chọn của bạn.<br>
                        Hãy thử bỏ bớt các tiêu chí lọc hoặc tìm kiếm từ khóa khác.
                    </p>
                    <a href="<?= BASE_URL ?>product" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-sync-alt me-2"></i>Xóa toàn bộ bộ lọc
                    </a>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 g-3 g-md-4">
                    <?php foreach($data['products'] as $prod): ?>
                        <?php 
                            $stock = isset($prod['total_stock']) ? (int)$prod['total_stock'] : ((int)$prod['product_qty'] ?? 0);
                            $isOutOfStock = ($stock <= 0);
                            $hasSale = ($prod['sale_price'] > 0 && $prod['sale_price'] < $prod['price']);
                            $percent = $hasSale ? round((($prod['price'] - $prod['sale_price']) / $prod['price']) * 100) : 0;
                        ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm product-card overflow-hidden">
                                <div class="position-absolute top-0 start-0 p-2 w-100 d-flex justify-content-between pointer-events-none z-2">
                                    <?php if($hasSale && !$isOutOfStock): ?>
                                        <span class="badge bg-danger rounded-pill shadow-sm">-<?= $percent ?>%</span>
                                    <?php else: ?> <span></span> <?php endif; ?>
                                    
                                    <?php if($isOutOfStock): ?>
                                        <span class="badge bg-dark shadow-sm">Hết hàng</span>
                                    <?php endif; ?>
                                </div>

                                <div class="card-img-wrapper position-relative bg-light" style="padding-top: 100%;">
                                    <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>">
                                        <img src="<?= BASE_URL ?>public/uploads/<?= $prod['image'] ?>" 
                                             class="position-absolute top-0 start-0 w-100 h-100 object-fit-contain p-3 transition-transform" 
                                             alt="<?= htmlspecialchars($prod['name']) ?>">
                                    </a>
                                </div>

                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="text-uppercase text-muted extra-small fw-bold mb-1">
                                        <?= htmlspecialchars($prod['brand_name'] ?? 'No Brand') ?>
                                    </div>
                                    
                                    <h6 class="card-title text-dark lh-sm mb-2 text-truncate-2" style="min-height: 40px;">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="text-decoration-none text-dark hover-primary product-name-link">
                                            <?= htmlspecialchars($prod['name']) ?>
                                        </a>
                                    </h6>

                                    <div class="mt-auto">
                                        <?php if($hasSale): ?>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="text-danger fw-bold fs-5"><?= number_format($prod['sale_price'], 0, ',', '.') ?>₫</span>
                                                <small class="text-muted text-decoration-line-through"><?= number_format($prod['price'], 0, ',', '.') ?>₫</small>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-dark fw-bold fs-5"><?= number_format($prod['price'], 0, ',', '.') ?>₫</span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mt-3">
                                        <a href="<?= BASE_URL ?>product/detail/<?= $prod['id'] ?>" class="btn btn-outline-primary w-100 rounded-pill btn-view-more fw-bold">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($data['pagination']['total_pages'] > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php 
                                $cur = $data['pagination']['current_page'];
                                $total = $data['pagination']['total_pages'];
                                // Xóa tham số page cũ để tạo link mới
                                $queryParams = array_diff_key($data['filters'], ['limit'=>1, 'offset'=>1]);
                                $url = BASE_URL . "product?" . http_build_query($queryParams) . "&page=";
                            ?>
                            
                            <li class="page-item <?= ($cur <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link border-0 shadow-sm rounded-circle mx-1" href="<?= $url . ($cur - 1) ?>"><i class="fas fa-chevron-left"></i></a>
                            </li>

                            <?php for($i=1; $i<=$total; $i++): ?>
                                <li class="page-item">
                                    <a class="page-link border-0 shadow-sm rounded-circle mx-1 <?= ($i == $cur) ? 'bg-primary text-white fw-bold' : 'text-dark' ?>" 
                                       href="<?= $url . $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?= ($cur >= $total) ? 'disabled' : '' ?>">
                                <a class="page-link border-0 shadow-sm rounded-circle mx-1" href="<?= $url . ($cur + 1) ?>"><i class="fas fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Slider Giá */
    .price-slider-container { position: relative; height: 6px; margin-top: 15px; }
    .slider-track { width: 100%; height: 6px; background: #dee2e6; position: absolute; border-radius: 3px; top: 0; }
    .slider-progress { height: 6px; background: #0d6efd; position: absolute; border-radius: 3px; top: 0; }
    .range-input {
        position: absolute; width: 100%; height: 6px; top: 0; background: none; pointer-events: none;
        -webkit-appearance: none; appearance: none;
    }
    .range-input::-webkit-slider-thumb {
        height: 20px; width: 20px; border-radius: 50%; border: 3px solid #0d6efd;
        background: #fff; pointer-events: auto; -webkit-appearance: none; cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2); transition: transform 0.2s;
    }
    .range-input::-webkit-slider-thumb:hover { transform: scale(1.2); }

    /* Hiệu ứng Mũi tên Accordion */
    .toggle-cat-btn .transition-icon { transition: transform 0.3s ease; }
    .toggle-cat-btn.collapsed .transition-icon { transform: rotate(-90deg); }
    .toggle-cat-btn:not(.collapsed) .transition-icon { transform: rotate(0deg); }

    /* Product Card */
    .product-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
    .transition-transform { transition: transform 0.5s ease; }
    .card-img-wrapper:hover .transition-transform { transform: scale(1.08); }
    
    .btn-view-more { transition: all 0.3s; opacity: 0.9; }
    .product-card:hover .btn-view-more { opacity: 1; background-color: #0d6efd; color: white; }
    .transition-btn:hover { transform: translateY(-2px); }

    .cursor-pointer { cursor: pointer; }
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .size-btn { min-width: 45px; }
    .btn-check:checked + .size-btn { background-color: #0d6efd; color: white; border-color: #0d6efd; }
    .hover-opacity:hover { opacity: 0.7; }
    .product-name-link:hover { color: #0d6efd !important; }
    .extra-small { font-size: 0.75rem; }
    
    /* Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #adb5bd; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. XỬ LÝ THANH TRƯỢT GIÁ (MƯỢT MÀ)
    const rangeMin = document.getElementById('rangeMin');
    const rangeMax = document.getElementById('rangeMax');
    const inputMin = document.getElementById('inputMin');
    const inputMax = document.getElementById('inputMax');
    const progress = document.getElementById('slider-progress');
    const minGap = 100000;

    function updateProgress() {
        let minVal = parseInt(rangeMin.value);
        let maxVal = parseInt(rangeMax.value);
        const maxLimit = parseInt(rangeMax.max);

        // Ngăn kéo qua nhau
        if (maxVal - minVal < minGap) {
            if (event.target === rangeMin) {
                rangeMin.value = maxVal - minGap;
            } else {
                rangeMax.value = minVal + minGap;
            }
        } else {
            inputMin.value = minVal;
            inputMax.value = maxVal;
            progress.style.left = (minVal / maxLimit) * 100 + "%";
            progress.style.width = ((maxVal - minVal) / maxLimit) * 100 + "%";
        }
    }

    rangeMin.addEventListener('input', updateProgress);
    rangeMax.addEventListener('input', updateProgress);

    // Đồng bộ input nhập tay
    inputMin.addEventListener('change', function() {
        let val = parseInt(this.value);
        if(val < 0) val = 0;
        if(val >= parseInt(rangeMax.value)) val = parseInt(rangeMax.value) - minGap;
        this.value = val;
        rangeMin.value = val;
        updateProgress();
    });

    inputMax.addEventListener('change', function() {
        let val = parseInt(this.value);
        if(val > parseInt(rangeMax.max)) val = parseInt(rangeMax.max);
        if(val <= parseInt(rangeMin.value)) val = parseInt(rangeMin.value) + minGap;
        this.value = val;
        rangeMax.value = val;
        updateProgress();
    });

    // Chạy lần đầu
    updateProgress();
});
</script>