<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized!']);
    exit;
}

$recipe_id = (int)($_POST['recipe_id'] ?? 0);

if ($recipe_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ!']);
    exit;
}

// CASCADE sẽ tự xóa recipe_ingredients, recipe_directions, recipe_tags, comments, ratings, bookmarks
$stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
$stmt->bind_param("i", $recipe_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error]);
}
?>
