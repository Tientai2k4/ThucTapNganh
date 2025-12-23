<div class="container my-5">
    <div class="row shadow-sm border rounded overflow-hidden">
        <div class="col-md-5 bg-primary text-white p-5">
            <h3 class="fw-bold mb-4">Thông tin liên hệ</h3>
            <p class="mb-4">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại tin nhắn cho chúng tôi.</p>
            
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-map-marker-alt fa-lg me-3"></i>
                <span>Nha Trang ,Khanh Hoa</span>
            </div>
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-phone-alt fa-lg me-3"></i>
                <span>090.123.4567</span>
            </div>
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-envelope fa-lg me-3"></i>
                <span>cskh@swimmingstore.com</span>
            </div>
            
            <div class="mt-5">
                <h5 class="fw-bold">Giờ làm việc</h5>
                <p>Thứ 2 - Thứ 7: 8:00 - 21:00<br>Chủ Nhật: 9:00 - 18:00</p>
            </div>
        </div>

        <div class="col-md-7 bg-white p-5">
            <h3 class="text-primary fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>
            <form action="<?= BASE_URL ?>contact/submit" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập họ tên" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control" placeholder="Nhập SĐT" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nội dung liên hệ *</label>
                    <textarea name="message" class="form-control" rows="5" placeholder="Bạn cần hỗ trợ vấn đề gì?" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">GỬI LIÊN HỆ</button>
            </form>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-12">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.863980699309!2d105.79461931540245!3d21.03812779283528!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab354920c233%3A0x5d0313a3bfdc4f37!2zQ8OGdSBHaeG6pXksIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1626084000000!5m2!1svi!2s" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</div>