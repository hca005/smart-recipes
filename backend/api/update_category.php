<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); 
    exit;
}

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$icon = trim($_POST['icon'] ?? 'fa fa-utensils');
$color = trim($_POST['color'] ?? '#FCD34D');

if (empty($id) || empty($name)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing category ID or Name']);
    exit;
}

if (empty($slug)) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
}

$stmt = $conn->prepare("UPDATE categories SET name=?, slug=?, description=?, icon=?, color=? WHERE id=?");
$stmt->bind_param("sssssi", $name, $slug, $description, $icon, $color, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $conn->error]);
}
?>
