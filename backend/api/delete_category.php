<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Bảo vệ cổng
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized!']); exit;
}

$id = $_POST['category_id'] ?? 0;

if ($id > 0) {
    // Chém bay màu Category khỏi Database
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error]);
    }
}
?>