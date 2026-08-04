window.openEditModal = function() {
    console.log("Đang mở modal..."); // Dòng này để debug xem nút đã ăn chưa
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.add('show');
    } else {
        console.error("Không tìm thấy element có ID editProfileModal");
    }
};

window.closeEditModal = function() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.remove('show');
    }
};

// Hàm Upload Avatar
window.uploadAvatar = function(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('avatar', input.files[0]);
        fetch('/smart-recipes/backend/api/update_avatar.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('profileDisplay').src = data.new_url;
                location.reload();
            } else {
                alert('Upload error: ' + data.message);
            }
        })
        .catch(() => alert('Cannot connect to server!'));
    }
};

// ── 2. CÁC LỆNH CHẠY KHI TRANG LOAD XONG ──
document.addEventListener('DOMContentLoaded', function () {
    // Logic Follow (Sử dụng SVG cũ của Linh)
    var followBtn = document.getElementById('followBtn');
    if (followBtn) {
        var following = false;
        followBtn.addEventListener('click', function () {
            following = !following;
            this.innerHTML = following ? 
                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> FOLLOWING' : 
                '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> FOLLOW ME';
            this.classList.toggle('is-following', following);
        });
    }

    // Logic lưu Form Edit Profile
    const editForm = document.getElementById('editProfileForm');
    if (editForm) {
        editForm.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('/smart-recipes/backend/api/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Profile updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => alert('Cannot connect to API!'));
        };
    }
});

// ── CHỨC NĂNG TƯƠNG TÁC BÀI ĐĂNG (ACTIVITY FEED) ──

// 1. Thả tym
window.toggleLike = function(btn) {
    const icon = btn.querySelector('.heart-icon');
    const text = btn.querySelector('.like-text');
    
    if (icon.classList.contains('far')) {
        icon.classList.remove('far'); // Bỏ tim rỗng
        icon.classList.add('fas');    // Thêm tim đặc
        btn.style.color = '#ef4444';  // Đổi màu đỏ
        text.innerText = "Liked";
        icon.style.transform = "scale(1.2)";
        setTimeout(() => icon.style.transform = "scale(1)", 200);
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        btn.style.color = '#64748b'; // Về màu xám
        text.innerText = "Like";
    }
};

// 2. Bật/tắt khung bình luận (Đã nâng cấp định vị)
window.toggleCommentBox = function(btn) {
    // Tìm cái khung to nhất chứa toàn bộ bài đăng này
    const activityItem = btn.closest('.activity-item');
    
    // Quét bên trong bài đăng để tìm đích danh cái ô nhập chữ, bất chấp nó nằm ở đâu
    const commentBox = activityItem.querySelector('.comment-box');
    
    if (commentBox.style.display === 'none') {
        commentBox.style.display = 'flex'; // Hiện ra
        commentBox.querySelector('input').focus(); // Nháy chuột sẵn vào ô nhập
    } else {
        commentBox.style.display = 'none'; // Ẩn đi
    }
};

// 3. Đăng bình luận
// 3. Đăng bình luận và hiện ra màn hình
// 3. Đăng bình luận (Đã có Fetch gửi về Backend)
window.postComment = function(btn) {
    const commentBox = btn.closest('.comment-box');
    const input = btn.previousElementSibling;
    const commentText = input.value.trim();
    
    // Lấy ID món ăn từ cái "Định vị" con vừa gắn ở Bước 2
    const recipeId = commentBox.getAttribute('data-recipe-id');

    if (commentText === '') {
        alert("Please enter a comment!");
        return;
    }

    // Đóng gói dữ liệu để gửi đi
    const formData = new FormData();
    formData.append('recipe_id', recipeId);
    formData.append('content', commentText);

    // BẮT ĐẦU GỬI VỀ BACKEND
    fetch('/smart-recipes/backend/api/add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Lấy tên hiển thị từ data attribute trên body (được PHP inject)
            const myName = document.body.dataset.displayName || 'You';
            const myAvatar = commentBox.querySelector('img').src;
            const newCommentDiv = document.createElement('div');
            newCommentDiv.style.cssText = "display: flex; gap: 10px; margin-top: 15px; padding: 10px 15px; background: #f8fafc; border-radius: 15px; border-left: 4px solid #FCD34D;";
            newCommentDiv.innerHTML = `
                <img src="${myAvatar}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                <div>
                    <p style="margin: 0; font-weight: 700; font-size: 0.9rem; color: #111;">${myName}</p>
                    <p style="margin: 3px 0 0 0; font-size: 0.85rem; color: #444; line-height: 1.4;">${commentText}</p>
                </div>
            `;
            commentBox.parentNode.insertBefore(newCommentDiv, commentBox);
            input.value = '';
        } else {
            alert("Oops, an error occurred: " + data.message);
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        alert('Server is busy, cannot send comment!');
    });
};