<?php 
// Lấy prefix là 'staff' từ controller truyền sang
$prefix = $data['role_prefix'] ?? 'staff'; 
?>

<style>
    .text-limit-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        word-break: break-word;
        max-width: 100%;
        line-height: 1.5;
        color: #555;
    }
    .table-hover tbody tr:hover td {
        background-color: #f1f3f5;
        transition: 0.2s;
    }
    .cursor-pointer { cursor: pointer; }
</style>

<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-headset me-2"></i>Hỗ trợ Khách hàng (Staff)</h1>
            <p class="text-muted small mb-0">
                Bạn có <strong class="text-danger"><?= $data['unread'] ?? 0 ?></strong> tin nhắn mới chưa đọc.
            </p>
        </div>
    </div>

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i><?= $_SESSION['alert']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alert']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4 border-0">
        <div class="card-body py-3 bg-light rounded">
            <form action="" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 ps-0" 
                               placeholder="Tìm tên, email hoặc SĐT..." 
                               value="<?= htmlspecialchars($data['filters']['keyword'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <select name="status" class="form-select cursor-pointer">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="0" <?= (isset($data['filters']['status']) && $data['filters']['status'] === '0') ? 'selected' : '' ?>>🔴 Chưa xem</option>
                        <option value="1" <?= (isset($data['filters']['status']) && $data['filters']['status'] === '1') ? 'selected' : '' ?>>🟡 Đã xem</option>
                        <option value="2" <?= (isset($data['filters']['status']) && $data['filters']['status'] === '2') ? 'selected' : '' ?>>🟢 Đã trả lời</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter"></i> Lọc</button>
                </div>
                
                <div class="col-md-2">
                    <a href="<?= BASE_URL ?>staff/contact" class="btn btn-outline-secondary w-100"><i class="fas fa-undo"></i> Làm mới</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Hòm thư đến</h6>
            <span class="badge bg-secondary">Tổng: <?= count($data['contacts']) ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase text-muted small">
                        <tr>
                            <th class="ps-4 py-3" width="25%">Khách hàng</th>
                            <th width="35%">Nội dung</th>
                            <th width="15%">Thời gian</th>
                            <th width="10%" class="text-center">Trạng thái</th>
                            <th width="15%" class="text-center pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['contacts'])): ?>
                            <?php foreach($data['contacts'] as $c): ?>
                            <tr class="<?= $c['status'] == 0 ? 'fw-bold bg-white' : '' ?>">
                                <td class="ps-4">
                                    <div class="text-dark mb-1"><?= htmlspecialchars($c['full_name']) ?></div>
                                    <div class="small text-muted d-flex align-items-center mb-1">
                                        <i class="far fa-envelope me-2 text-primary"></i><?= htmlspecialchars($c['email']) ?>
                                    </div>
                                    <div class="small text-muted d-flex align-items-center">
                                        <i class="fas fa-phone-alt me-2 text-success"></i><?= htmlspecialchars($c['phone']) ?>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="text-limit-2 mb-1" id="short_msg_<?= $c['id'] ?>">
                                        <?= nl2br(htmlspecialchars($c['message'])) ?>
                                    </div>
                                    <a href="javascript:void(0)" 
                                       class="small text-primary text-decoration-none fw-bold fst-italic" 
                                       onclick="viewDetail('<?= $c['id'] ?>', '<?= htmlspecialchars($c['full_name']) ?>', '<?= htmlspecialchars($c['email']) ?>')">
                                       <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </a>
                                    <textarea id="full_msg_<?= $c['id'] ?>" class="d-none"><?= htmlspecialchars($c['message']) ?></textarea>
                                </td>

                                <td class="small text-muted">
                                    <div><i class="far fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($c['created_at'])) ?></div>
                                    <div class="mt-1"><i class="far fa-clock me-1"></i><?= date('H:i', strtotime($c['created_at'])) ?></div>
                                </td>

                                <td class="text-center">
                                    <?php if($c['status'] == 0): ?>
                                        <span class="badge bg-danger rounded-pill shadow-sm">Mới</span>
                                    <?php elseif($c['status'] == 1): ?>
                                        <span class="badge bg-warning text-dark rounded-pill shadow-sm">Đã xem</span>
                                    <?php else: ?>
                                        <span class="badge bg-success rounded-pill shadow-sm">Đã trả lời</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center pe-4">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="Trả lời ngay"
                                                onclick="setReplyData('<?= $c['id'] ?>', '<?= htmlspecialchars($c['full_name']) ?>', '<?= htmlspecialchars($c['email']) ?>')">
                                            <i class="fas fa-reply"></i>
                                        </button>

                                        <?php if($c['status'] == 0): ?>
                                            <a href="<?= BASE_URL . $prefix ?>/contact/mark/<?= $c['id'] ?>" 
                                               class="btn btn-sm btn-outline-success" 
                                               data-bs-toggle="tooltip" title="Đánh dấu đã xem">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= BASE_URL . $prefix ?>/contact/delete/<?= $c['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa?')" 
                                           data-bs-toggle="tooltip" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-gray-300"></i><br>
                                        <span class="h6">Không tìm thấy tin nhắn nào!</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-primary"><i class="fas fa-comment-dots me-2"></i>Chi tiết tin nhắn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small text-muted fw-bold mb-1">Người gửi:</label>
                    <div id="viewSenderName" class="fw-bold text-dark"></div>
                    <div id="viewSenderEmail" class="small text-primary"></div>
                </div>
                <hr class="my-2">
                <div class="mb-2">
                    <label class="small text-muted fw-bold mb-1">Nội dung:</label>
                    <div class="p-3 bg-light rounded border border-light text-dark" 
                         style="white-space: pre-line; line-height: 1.6;" 
                         id="viewMsgContent">
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="switchToReply()">
                    <i class="fas fa-reply me-1"></i> Trả lời ngay
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-paper-plane me-2"></i>Phản hồi khách hàng (Email)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL . $prefix ?>/contact/reply" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="replyId">
                    <input type="hidden" name="email" id="replyEmailHidden">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Người nhận:</label>
                            <input type="text" class="form-control bg-light" id="replyInfo" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Email nhận:</label>
                            <input type="text" class="form-control bg-light fw-bold text-dark" id="replyEmailDisplay" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề Email <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" value="Phản hồi từ SwimmingStore về liên hệ của bạn" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung phản hồi (HTML) <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="8" placeholder="Nhập nội dung..." required>Chào quý khách,

Cảm ơn quý khách đã liên hệ. Về vấn đề quý khách thắc mắc, chúng tôi xin phản hồi như sau:

...

Trân trọng,
Đội ngũ hỗ trợ khách hàng SwimmingStore.</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fas fa-paper-plane me-2"></i> GỬI PHẢN HỒI
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentContact = { id: '', name: '', email: '' };

    function viewDetail(id, name, email) {
        currentContact = { id: id, name: name, email: email };
        var fullContent = document.getElementById('full_msg_' + id).value;
        document.getElementById('viewMsgContent').innerText = fullContent;
        document.getElementById('viewSenderName').innerText = name;
        document.getElementById('viewSenderEmail').innerText = email;
        var viewModal = new bootstrap.Modal(document.getElementById('viewDetailModal'));
        viewModal.show();
    }

    function setReplyData(id, name, email) {
        document.getElementById('replyId').value = id;
        document.getElementById('replyEmailHidden').value = email;
        document.getElementById('replyInfo').value = name;
        document.getElementById('replyEmailDisplay').value = email;
        var replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
        replyModal.show();
    }

    function switchToReply() {
        var viewModalEl = document.getElementById('viewDetailModal');
        var viewModal = bootstrap.Modal.getInstance(viewModalEl);
        viewModal.hide();
        setTimeout(function() {
            setReplyData(currentContact.id, currentContact.name, currentContact.email);
        }, 300);
    }
</script>