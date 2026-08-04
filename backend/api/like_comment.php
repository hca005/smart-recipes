<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    exit;
}

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thả tim.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$comment_id = (int)($input['comment_id'] ?? 0);
$user_id = (int)current_user()['id'];

if ($comment_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Comment ID không hợp lệ.']);
    exit;
}

// Check if already liked
$check = $conn->prepare("SELECT id FROM comment_likes WHERE comment_id = ? AND user_id = ?");
$check->bind_param("ii", $comment_id, $user_id);
$check->execute();
$res = $check->get_result();

$liked = false;

if ($res->num_rows > 0) {
    // Unlike
    $del = $conn->prepare("DELETE FROM comment_likes WHERE comment_id = ? AND user_id = ?");
    $del->bind_param("ii", $comment_id, $user_id);
    $del->execute();
    $liked = false;
} else {
    // Like
    $ins = $conn->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
    $ins->bind_param("ii", $comment_id, $user_id);
    $ins->execute();
    $liked = true;

    // Send notification to the comment owner
    $stmt_comment = $conn->prepare("SELECT c.user_id, c.recipe_id, c.comment_text, r.title FROM comments c JOIN recipes r ON c.recipe_id = r.id WHERE c.id = ?");
    $stmt_comment->bind_param("i", $comment_id);
    $stmt_comment->execute();
    $comment_data = $stmt_comment->get_result()->fetch_assoc();

    if ($comment_data && $comment_data['user_id'] != $user_id) {
        $liker_name = current_user()['display_name'] ?? current_user()['username'];
        $notif_title = "Lượt thích mới";
        $notif_msg = $liker_name . " đã thích bình luận của bạn trong " . $comment_data['title'];
        $notif_type = "like";
        $notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $comment_data['recipe_id'] . "#comment-" . $comment_id;
        
        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        $stmt_notif->bind_param("issss", $comment_data['user_id'], $notif_type, $notif_title, $notif_msg, $notif_link);
        $stmt_notif->execute();
    }
}

// Get new like count
$count_stmt = $conn->prepare("SELECT COUNT(id) as total FROM comment_likes WHERE comment_id = ?");
$count_stmt->bind_param("i", $comment_id);
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    'status' => 'success',
    'liked' => $liked,
    'total_likes' => $total
]);
?>
