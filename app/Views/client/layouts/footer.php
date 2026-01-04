
<style>
    .footer-modern {
        background-color: #4a4a4a; /* Màu xám đậm vừa phải, in ra sẽ rất đẹp */
    }
    .text-cyan {
        color: #00e5ff !important; /* Giữ tiêu đề nổi bật trên nền xám */
    }
    .footer-link:hover {
        color: #00e5ff !important;
        opacity: 0.8;
    }

    @media print {
    /* Chuyển nền footer về màu trắng hoặc xám rất nhạt để tiết kiệm mực và rõ chữ */
    .footer-modern {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    /* Chuyển toàn bộ chữ về màu đen để đạt độ tương phản cao nhất */
    .footer-modern p, 
    .footer-modern a, 
    .footer-modern h5,
    .footer-modern i {
        color: #000 !important;
    }

    /* Ẩn các icon mạng xã hội vì chúng không có giá trị trên giấy in */
    .social-icons {
        display: none;
    }
}
</style>
</div> 
</main>

<footer class="footer-modern text-white pt-5 pb-4">
    <div class="container text-center text-md-start">
        <div class="row text-center text-md-start">
            
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-cyan">Về Thế Giới Bơi Lội</h5>
                <p>
                    Hệ thống phân phối dụng cụ bơi lội chính hãng hàng đầu Việt Nam. Cam kết chất lượng, đồng hành cùng bạn chinh phục đường đua xanh.
                </p>
                <div class="social-icons mt-4">
                    <a href="#" class="btn btn-primary btn-floating m-1 rounded-circle"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-danger btn-floating m-1 rounded-circle"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="btn btn-info btn-floating m-1 rounded-circle"><i class="fab fa-twitter"></i></a> <a href="#" class="btn btn-dark btn-floating m-1 rounded-circle"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-cyan">Hỗ trợ</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= BASE_URL ?>page/buying_guide" class="text-white text-decoration-none footer-link">Hướng dẫn mua hàng</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>page/return_policy" class="text-white text-decoration-none footer-link">Chính sách đổi trả</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>page/shipping_policy" class="text-white text-decoration-none footer-link">Vận chuyển & Giao nhận</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>page/privacy_policy" class="text-white text-decoration-none footer-link">Bảo mật thông tin</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-cyan">Sản phẩm</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?= BASE_URL ?>product" class="text-white text-decoration-none footer-link">Kính bơi cận</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>product" class="text-white text-decoration-none footer-link">Đồ bơi thi đấu</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>product" class="text-white text-decoration-none footer-link">Mũ bơi Silicone</a></li>
                    <li class="mb-2"><a href="<?= BASE_URL ?>product" class="text-white text-decoration-none footer-link">Phụ kiện bơi lội</a></li>
                </ul>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 fw-bold text-cyan">Liên hệ</h5>
                <p><i class="fas fa-home me-3"></i> 123 Đường Bơi Lội, Hà Nội</p>
                <p><i class="fas fa-envelope me-3"></i> cskh@swimstore.com</p>
                <p><i class="fas fa-phone me-3"></i> 090.123.4567</p>
                <p><i class="fas fa-clock me-3"></i> 8:00 - 21:00 (Hàng ngày)</p>
            </div>
        </div>

        <hr class="mb-4 text-white-50">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p class="text-white-50">© 2025 <strong>Thế Giới Bơi Lội</strong>. All Rights Reserved.</p>
            </div>
            <div class="col-md-5 col-lg-4">
                <p class="text-center text-md-end text-white-50">
                   Designed for Swimmers
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>