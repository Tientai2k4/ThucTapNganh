<?php require_once ROOT_PATH . '/app/Views/client/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center text-primary mb-4 fw-bold text-uppercase">Đăng Ký Thành Viên</h3>
                    
                    <?php if(isset($data['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <i class="fa-solid fa-triangle-exclamation"></i> <?= $data['error'] ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>auth/processRegister" method="POST" id="registerForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@gmail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" 
                                   maxlength="10" placeholder="0912345678" required>
                            
                            <small id="phone-error" class="text-danger fw-bold" style="display: none;"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                            
                            <div class="form-text text-muted small mb-1">
                                * Mật khẩu cần ít nhất 8 ký tự, bao gồm chữ in hoa và số.
                            </div>
                            <small id="password-error" class="text-danger fw-bold" style="display: none;"></small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhập lại mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            <small id="confirm-error" class="text-danger fw-bold" style="display: none;"></small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2" id="btn-submit">Đăng Ký</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="mb-1">Đã có tài khoản?</p>
                        <a href="<?= BASE_URL ?>auth/login" class="text-decoration-none fw-bold">Đăng nhập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/Views/client/layouts/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const phoneInput = document.getElementById('phone');
    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('btn-submit');

    // 1. Kiểm tra Số điện thoại
    phoneInput.addEventListener('input', function() {
        // Chỉ cho nhập số, xóa chữ ngay lập tức
        this.value = this.value.replace(/[^0-9]/g, '');
        
        const errorSpan = document.getElementById('phone-error');
        const regexPhone = /^0[0-9]{9}$/; // Bắt đầu bằng 0, tổng 10 số

        if (this.value.length > 0 && !regexPhone.test(this.value)) {
            if (this.value.length !== 10) {
                errorSpan.innerText = "SĐT phải đủ 10 chữ số.";
            } else if (!this.value.startsWith('0')) {
                errorSpan.innerText = "SĐT phải bắt đầu bằng số 0.";
            }
            errorSpan.style.display = 'block';
            phoneInput.classList.add('is-invalid');
        } else {
            errorSpan.style.display = 'none';
            phoneInput.classList.remove('is-invalid');
            phoneInput.classList.add('is-valid');
        }
    });

    // 2. Kiểm tra Mật khẩu mạnh
    passInput.addEventListener('input', function() {
        const errorSpan = document.getElementById('password-error');
        const val = this.value;
        let msg = "";

        if (val.length < 8) {
            msg = "Mật khẩu quá ngắn (tối thiểu 8 ký tự).";
        } else if (!/[A-Z]/.test(val)) {
            msg = "Thiếu chữ in hoa (Ví dụ: A, B, C...).";
        } else if (!/[0-9]/.test(val)) {
            msg = "Thiếu số (Ví dụ: 1, 2, 3...).";
        }

        if (msg !== "") {
            errorSpan.innerText = msg;
            errorSpan.style.display = 'block';
            passInput.classList.add('is-invalid');
        } else {
            errorSpan.style.display = 'none';
            passInput.classList.remove('is-invalid');
            passInput.classList.add('is-valid');
        }
        
        // Kích hoạt check lại ô nhập lại mật khẩu nếu đang nhập
        if (confirmInput.value.length > 0) confirmInput.dispatchEvent(new Event('input'));
    });

    // 3. Kiểm tra Nhập lại mật khẩu
    confirmInput.addEventListener('input', function() {
        const errorSpan = document.getElementById('confirm-error');
        if (this.value !== passInput.value) {
            errorSpan.innerText = "Mật khẩu nhập lại không khớp.";
            errorSpan.style.display = 'block';
            confirmInput.classList.add('is-invalid');
        } else {
            errorSpan.style.display = 'none';
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        }
    });

    // 4. Chặn Submit nếu còn lỗi
    form.addEventListener('submit', function(e) {
        // Kiểm tra lại lần cuối
        const isPhoneInvalid = phoneInput.classList.contains('is-invalid');
        const isPassInvalid = passInput.classList.contains('is-invalid');
        const isConfirmInvalid = confirmInput.classList.contains('is-invalid');

        if (isPhoneInvalid || isPassInvalid || isConfirmInvalid) {
            e.preventDefault(); // Chặn gửi form
            alert("Vui lòng sửa các lỗi màu đỏ trước khi đăng ký!");
        }
    });
});
</script>