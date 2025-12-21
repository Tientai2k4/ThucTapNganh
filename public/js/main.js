//Khởi tạo Nút Kiểm tra Vận đơn
function initializeTrackingButton() {
    const btn = document.getElementById('check-tracking-btn');
    const resultsDiv = document.getElementById('tracking-log-results');

    if (!btn || !resultsDiv) {
        return; // Chỉ chạy nếu nút và div tồn tại (tức là ở trang Chi tiết Đơn hàng)
    }

    btn.addEventListener('click', function() {
        const orderCode = this.dataset.orderCode; // Lấy mã từ data-order-code
        const apiUrl = this.dataset.url;
        resultsDiv.innerHTML = '<p>Đang tải lịch sử vận đơn...</p>';
        btn.disabled = true; // Vô hiệu hóa nút

        // 1. Gọi AJAX đến TrackingController (đã tạo ở Bước 2)
       fetch(`${apiUrl}?order_code=${orderCode}`)
            .then(response => response.json())
            .then(data => {
                btn.disabled = false; // Bật lại nút

                if (data.success) {
                    // 2. Thành công: Hiển thị lịch sử (log)
                    let html = `<p><strong>Trạng thái hiện tại:</strong> ${data.status}</p>`;
                    html += '<ul style="padding-left: 20px; font-size: 0.9em;">';
                    
                    // (Tài liệu GHN nói 'log' là một mảng)
                    if (data.log && data.log.length > 0) {
                        data.log.forEach(logEntry => {
                            // Định dạng lại ngày (vd: "2020-05-29T14:40:46.934Z")
                            const date = new Date(logEntry.updated_date);
                            const formattedDate = date.toLocaleString('vi-VN', { 
                                day: '2-digit', 
                                month: '2-digit', 
                                year: 'numeric', 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                            
                            // (Tùy vào API GHN, status có thể là text hoặc mã)
                            let statusText = logEntry.status;
                            // (Bạn có thể thêm 1 switch case ở đây để dịch status)
                            
                            html += `<li>${formattedDate}: <strong>${statusText}</strong></li>`;
                        });
                    }
                    html += '</ul>';
                    resultsDiv.innerHTML = html;
                    resultsDiv.style.display = 'block';
                    
                } else {
                    resultsDiv.innerHTML = `<p class="text-danger">GHN: ${data.message}</p>`;
                    resultsDiv.style.display = 'block';
                }
            })
            .catch(error => {
                btn.disabled = false;
                console.error('Lỗi:', error);
                resultsDiv.innerHTML = '<p class="text-danger">Lỗi kết nối Server.</p>';
                resultsDiv.style.display = 'block';
            });
    });
}

document.addEventListener("DOMContentLoaded", function() { 
    initializeTrackingButton();
});