window.deleteUser = function(userId) {
    // Hiện bảng hỏi lại cho chắc cốp, lỡ bấm nhầm
    if (confirm("Cảnh báo: Chàng gái có chắc chắn muốn XÓA VĨNH VIỄN tài khoản này không? Mọi dữ liệu của họ sẽ bay màu!")) {
        
        const formData = new FormData();
        formData.append('user_id', userId);

        // Gửi lệnh xử bắn về Backend
        fetch('/smart-recipes/backend/api/delete_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Xóa thành công thì tải lại trang để làm mới danh sách
                location.reload(); 
            } else {
                alert("Lỗi rồi: " + data.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert("Mạng lỗi, chưa xóa được!");
        });
    }
};

// 1. Mở modal và điền sẵn thông tin
window.openEditModal = function(id, name, role) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_display_name').value = name;
    document.getElementById('edit_role').value = role;
    document.getElementById('editUserModal').style.display = 'flex';
};

// 2. Đóng modal
window.closeEditModal = function() {
    document.getElementById('editUserModal').style.display = 'none';
};

// 3. Xử lý khi nhấn nút Lưu (Submit form)
document.getElementById('editUserForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('edit_user_id').value;
    const newName = document.getElementById('edit_display_name').value;
    const newRole = document.getElementById('edit_role').value;

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('display_name', newName);
    formData.append('role', newRole);

    fetch('/smart-recipes/backend/api/update_user_role.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(() => alert("Lỗi mạng!"));
});

// Tính năng: Gõ tới đâu, lọc danh sách tới đó
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase(); // Lấy chữ con gõ vào và in thường hết
    
    // Lấy tất cả các hàng (tr) trong phần thân bảng (tbody)
    const tableRows = document.querySelectorAll('table tbody tr');

    tableRows.forEach(row => {
        // Gom toàn bộ chữ trong cái hàng đó lại (Tên, Email, Role...)
        const rowText = row.textContent.toLowerCase();
        
        // Nếu chữ con gõ CÓ nằm trong hàng này -> Hiện. Nếu KHÔNG -> Ẩn
        if (rowText.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});