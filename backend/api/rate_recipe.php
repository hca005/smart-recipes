<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to rate.']);
    exit;
}

$user = current_user();
$user_id = (int)$user['id'];

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$recipe_id = isset($input['recipe_id']) ? (int)$input['recipe_id'] : 0;
$rating = isset($input['rating']) ? (int)$input['rating'] : 0;

if ($recipe_id <= 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid recipe or rating.']);
    exit;
}

// Ensure the ratings table uses unique key on recipe_id + user_id.
// If not, we should check and update.
$check = $conn->prepare("SELECT id FROM ratings WHERE recipe_id = ? AND user_id = ?");
$check->bind_param("ii", $recipe_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Update existing rating
    $stmt = $conn->prepare("UPDATE ratings SET rating = ?, updated_at = NOW() WHERE recipe_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $rating, $recipe_id, $user_id);
} else {
    // Insert new rating
    $stmt = $conn->prepare("INSERT INTO ratings (recipe_id, user_id, rating, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("iii", $recipe_id, $user_id, $rating);
}

if ($stmt->execute()) {
    // Calculate new average
    $avg_stmt = $conn->prepare("SELECT ROUND(AVG(rating), 1) as avg_rating, COUNT(id) as rating_count FROM ratings WHERE recipe_id = ?");
    $avg_stmt->bind_param("i", $recipe_id);
    $avg_stmt->execute();
    $avg_res = $avg_stmt->get_result()->fetch_assoc();
    
    // Notify the recipe owner (only for new ratings to prevent spam, or update if it's 5 star)
    $is_new = ($result->num_rows == 0);
    if ($is_new) {
        $stmt_owner = $conn->prepare("SELECT user_id, title FROM recipes WHERE id = ?");
        $stmt_owner->bind_param("i", $recipe_id);
        $stmt_owner->execute();
        $recipe = $stmt_owner->get_result()->fetch_assoc();

        if ($recipe && $recipe['user_id'] != $user_id) {
            $notif_title = "Đánh giá mới";
            $notif_msg = $user['display_name'] . " đã đánh giá " . $rating . " sao cho " . $recipe['title'];
            $notif_type = "rating";
            $notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $recipe_id;
            $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
            $stmt_notif->bind_param("issss", $recipe['user_id'], $notif_type, $notif_title, $notif_msg, $notif_link);
            $stmt_notif->execute();
        }
    }
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Rating saved successfully.',
        'new_average' => $avg_res['avg_rating'],
        'new_count' => $avg_res['rating_count']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save rating.']);
}
?>
