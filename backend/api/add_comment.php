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
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để bình luận.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$recipe_id = (int)($input['recipe_id'] ?? 0);
$content = trim($input['content'] ?? '');
$parent_id = isset($input['parent_id']) && $input['parent_id'] !== '' ? (int)$input['parent_id'] : null;
$user_id = (int)current_user()['id'];

if ($recipe_id <= 0 || empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Nội dung không hợp lệ.']);
    exit;
}

// Insert comment
if ($parent_id > 0) {
    $stmt = $conn->prepare("INSERT INTO comments (recipe_id, user_id, parent_id, comment_text, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iiis", $recipe_id, $user_id, $parent_id, $content);
} else {
    $stmt = $conn->prepare("INSERT INTO comments (recipe_id, user_id, comment_text, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iis", $recipe_id, $user_id, $content);
}

if ($stmt->execute()) {
    $new_id = $conn->insert_id;
    
    // Fetch the inserted comment to return
    $sel = $conn->prepare("SELECT c.id, c.comment_text, c.created_at, c.parent_id, 
                                  COALESCE(u.display_name, u.username) as user_name, u.profile_image as avatar 
                           FROM comments c 
                           JOIN users u ON c.user_id = u.id 
                           WHERE c.id = ?");
    $sel->bind_param("i", $new_id);
    $sel->execute();
    $comment = $sel->get_result()->fetch_assoc();
    
    // Insert notification for the recipe owner
    $stmt_owner = $conn->prepare("SELECT user_id, title FROM recipes WHERE id = ?");
    $stmt_owner->bind_param("i", $recipe_id);
    $stmt_owner->execute();
    $recipe = $stmt_owner->get_result()->fetch_assoc();

    if ($recipe && $recipe['user_id'] != $user_id) {
        $notif_title = "Bình luận mới";
        $notif_msg = $comment['user_name'] . " đã bình luận về " . $recipe['title'];
        $notif_type = "comment";
        $notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $recipe_id;
        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        $stmt_notif->bind_param("issss", $recipe['user_id'], $notif_type, $notif_title, $notif_msg, $notif_link);
        $stmt_notif->execute();
    }
    
    // Insert notification for parent comment owner (if it's a reply)
    if ($parent_id > 0) {
        $stmt_parent = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
        $stmt_parent->bind_param("i", $parent_id);
        $stmt_parent->execute();
        $parent_comment = $stmt_parent->get_result()->fetch_assoc();
        
        if ($parent_comment && $parent_comment['user_id'] != $user_id) {
            $reply_notif_title = "Có người trả lời bình luận của bạn";
            $reply_notif_msg = $comment['user_name'] . " đã trả lời bình luận của bạn trong " . $recipe['title'];
            $reply_notif_type = "reply";
            $reply_notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $recipe_id . "#comment-" . $new_id;
            $stmt_reply_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
            $stmt_reply_notif->bind_param("issss", $parent_comment['user_id'], $reply_notif_type, $reply_notif_title, $reply_notif_msg, $reply_notif_link);
            $stmt_reply_notif->execute();
        }
    }
    
    // Default avatar if none
    $avatar = $comment['avatar'] ?: 'https://ui-avatars.com/api/?name='.urlencode($comment['user_name']).'&background=3B82F6&color=fff&size=80';
    
    echo json_encode([
        'status' => 'success',
        'comment' => [
            'id' => $comment['id'],
            'user' => $comment['user_name'],
            'avatar' => $avatar,
            'content' => htmlspecialchars($comment['comment_text']),
            'time' => date('d/m/y', strtotime($comment['created_at'])),
            'likes' => 0,
            'parent_id' => $comment['parent_id']
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể gửi bình luận.']);
}
?>