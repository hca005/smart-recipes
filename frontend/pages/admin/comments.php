<?php
require_once '../../includes/bootstrap.php';
require_admin(); // Bảo vệ Admin

// LỆNH LÔI DATA THẬT: Nối 3 bảng Comments, Users và Recipes
$query = "SELECT c.id, c.comment_text, c.created_at, u.display_name, r.title AS recipe_title 
          FROM comments c 
          JOIN users u ON c.user_id = u.id 
          JOIN recipes r ON c.recipe_id = r.id 
          ORDER BY c.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Comments – Food. Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/variables.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/reset.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/pages/admin.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/footer.css">
</head>
<body class="adm-body">
<div class="adm-layout">
    <?php include '../../includes/admin_sidebar.php'; ?>
    
    <div class="adm-main">
        <header class="adm-topbar">
            <div class="adm-topbar-left"><span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Comments</span></div>
            <div class="adm-topbar-spacer"></div>
            </header>

        <div class="adm-content">
            <div class="adm-page-header">
                <h1 class="adm-page-title">Latest Comments</h1>
                <span style="font-size:0.8125rem;color:#9CA3AF;">Follow community discussions</span>
            </div>

            <div class="adm-comment-list">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($cmt = $result->fetch_assoc()): ?>
                    <div class="adm-comment-item">
                        <div style="flex:1;">
                            <p class="adm-comment-meta">
                                <strong><?= htmlspecialchars($cmt['display_name']) ?></strong>
                                on <a href="#"><?= htmlspecialchars($cmt['recipe_title']) ?></a>
                            </p>
                            <p class="adm-comment-text"><?= htmlspecialchars($cmt['comment_text']) ?></p>
                            <p class="adm-comment-time"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></p>
                        </div>
                        <div>
                            <button class="adm-btn adm-btn-danger" onclick="deleteComment(<?= $cmt['id'] ?>)">Delete</button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="padding: 30px; text-align: center; color: #9CA3AF; background: #fff; border-radius: 12px; border: 1px solid #E5E7EB;">
                        Chưa có bình luận nào trên hệ thống.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>
</div>

<script>
function deleteComment(id) {
    if (confirm("Admin có chắc chắn muốn XÓA VĨNH VIỄN bình luận này không?")) {
        const formData = new FormData();
        formData.append('comment_id', id);

        fetch('/smart-recipes/backend/api/delete_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload(); // Xóa thành công thì tải lại trang
            } else {
                alert("Lỗi: " + data.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert("Mạng lỗi, chưa xóa được!");
        });
    }
}
</script>
</body>
</html>