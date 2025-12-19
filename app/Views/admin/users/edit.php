<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cập nhật người dùng: <?= htmlspecialchars($data['user']['full_name']) ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>admin/user/update" method="POST">
                        <input type="hidden" name="user_id" value="<?= $data['user']['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Email (Không thể sửa)</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['user']['email']) ?>" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phân quyền (Vai trò)</label>
                            <select name="role" class="form-select">
                                <option value="member" <?= $data['user']['role'] == 'member' ? 'selected' : '' ?>>Member (Khách hàng)</option>
                                <option value="staff" <?= $data['user']['role'] == 'staff' ? 'selected' : '' ?>>Staff (Nhân viên)</option>
                                <option value="admin" <?= $data['user']['role'] == 'admin' ? 'selected' : '' ?>>Admin (Quản trị viên)</option>
                            </select>
                            <div class="form-text text-danger">* Lưu ý: Admin có toàn quyền hệ thống.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Trạng thái tài khoản</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" <?= $data['user']['status'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusSwitch">
                                    Hoạt động / Khóa tài khoản
                                </label>
                            </div>
                            <div class="form-text">Gạt sang phải để kích hoạt, tắt để khóa tài khoản.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASE_URL ?>admin/user" class="btn btn-secondary">Quay lại</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>