<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method.']);
    exit;
}

$user_id   = (int)$_SESSION['user']['id'];
$recipe_id = (int)($_POST['recipe_id'] ?? 0);
$caption   = trim($_POST['caption'] ?? '');

if ($recipe_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Recipe ID không hợp lệ!']);
    exit;
}

if (!isset($_FILES['recipe_image']) || $_FILES['recipe_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn ảnh!']);
    exit;
}

$ext     = strtolower(pathinfo($_FILES['recipe_image']['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($ext, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Định dạng ảnh không hợp lệ!']);
    exit;
}

$filename = 'made_' . $user_id . '_' . $recipe_id . '_' . time() . '.' . $ext;
$dest     = __DIR__ . '/../../frontend/assets/images/recipes/' . $filename;

if (!move_uploaded_file($_FILES['recipe_image']['tmp_name'], $dest)) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu ảnh!']);
    exit;
}

$image_url = '/smart-recipes/frontend/assets/images/recipes/' . $filename;

// Lưu vào bảng recipe_images
$stmt = $conn->prepare(
    "INSERT INTO recipe_images (recipe_id, image_url, caption) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $recipe_id, $image_url, $caption);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'image_url' => $image_url]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
}
?>
