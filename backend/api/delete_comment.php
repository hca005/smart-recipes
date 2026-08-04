<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// Chỉ Admin mới được quyền đi dọn rác
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Láo nháo! Bạn không có quyền ở đây!']);
    exit;
}

$comment_id = $_POST['comment_id'] ?? 0;

if ($comment_id > 0) {
    // Tiễn vong bình luận
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi Database: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ!']);
}
?>