<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); 
    exit;
}

$id = $_POST['recipe_id'] ?? null;
$status = $_POST['status'] ?? null; // 'approve' or 'reject'

if (empty($id) || !in_array($status, ['approve', 'reject'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$is_published = ($status === 'approve') ? 1 : 0;

$stmt = $conn->prepare("UPDATE recipes SET is_published=? WHERE id=?");
$stmt->bind_param("ii", $is_published, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Recipe status updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $conn->error]);
}
?>
