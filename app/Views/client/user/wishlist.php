<h3><i class="fas fa-heart text-danger"></i> Danh sách yêu thích</h3>

<?php if (empty($data['products'])): ?>
    <div class="alert alert-info text-center mt-4">
        Bạn chưa thêm sản phẩm nào vào danh sách yêu thích.
    </div>
    <a href="<?= BASE_URL ?>product" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
<?php else: ?>
    <div class="row mt-4">
        <?php foreach ($data['products'] as $product): ?>
            <div class="col-6 col-md-3 mb-4">
                <div class="card card-product h-100">
                    <img src="<?= BASE_URL ?>uploads/<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="card-body text-center d-flex flex-column">
                        <a href="<?= BASE_URL ?>product/detail/<?= $product['id'] ?>" class="product-title text-truncate">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                        <div class="mb-2 mt-auto">
                            <span class="product-price"><?= number_format($product['price']) ?>đ</span>
                        </div>
                        <button class="btn btn-outline-danger btn-sm mt-2" 
                                onclick="toggleWishlist(<?= $product['id'] ?>, true)">
                            <i class="fas fa-trash"></i> Xóa khỏi DS
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
// Script này giúp xóa sản phẩm khỏi Wishlist mà không cần tải lại trang (nếu muốn)
function toggleWishlist(productId, isDelete = false) {
    fetch('<?= BASE_URL ?>wishlist/toggle', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            if (data.action === 'removed') {
                alert('Đã xóa sản phẩm khỏi danh sách yêu thích.');
                // Tải lại trang sau khi xóa
                if (isDelete) {
                    window.location.reload(); 
                }
            } else {
                alert('Đã thêm vào danh sách yêu thích.');
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>