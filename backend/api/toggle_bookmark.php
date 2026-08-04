<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$user_id   = (int)$_SESSION['user']['id'];
$recipe_id = (int)($_POST['recipe_id'] ?? 0);

if ($recipe_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Recipe ID không hợp lệ!']);
    exit;
}

// Kiểm tra đã bookmark chưa
$stmt = $conn->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND recipe_id = ?");
$stmt->bind_param("ii", $user_id, $recipe_id);
$stmt->execute();
$exists = $stmt->get_result()->num_rows > 0;

if ($exists) {
    // Xóa bookmark
    $del = $conn->prepare("DELETE FROM bookmarks WHERE user_id = ? AND recipe_id = ?");
    $del->bind_param("ii", $user_id, $recipe_id);
    $del->execute();
    echo json_encode(['status' => 'success', 'bookmarked' => false]);
} else {
    // Thêm bookmark
    $ins = $conn->prepare("INSERT INTO bookmarks (user_id, recipe_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $recipe_id);
    $ins->execute();

    // Send notification to the recipe owner
    $stmt_recipe = $conn->prepare("SELECT user_id, title FROM recipes WHERE id = ?");
    $stmt_recipe->bind_param("i", $recipe_id);
    $stmt_recipe->execute();
    $recipe_data = $stmt_recipe->get_result()->fetch_assoc();

    if ($recipe_data && $recipe_data['user_id'] != $user_id) {
        $bookmarker_name = $_SESSION['user']['display_name'] ?? $_SESSION['user']['username'];
        $notif_title = "Lượt lưu mới";
        $notif_msg = $bookmarker_name . " đã lưu công thức " . $recipe_data['title'] . " của bạn";
        $notif_type = "bookmark";
        $notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $recipe_id;
        
        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        $stmt_notif->bind_param("issss", $recipe_data['user_id'], $notif_type, $notif_title, $notif_msg, $notif_link);
        $stmt_notif->execute();
    }

    echo json_encode(['status' => 'success', 'bookmarked' => true]);
}
?>
