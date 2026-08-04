<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// 1. Kiểm tra an ninh: Chỉ có Admin mới được phép vào đây
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Láo nháo! Bạn không có quyền ở đây!']);
    exit;
}

$delete_id = $_POST['user_id'] ?? 0;

// 2. Chống tự hủy: Không cho phép Admin tự xóa nick của chính mình
if ($delete_id == $_SESSION['user']['id']) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể tự sát nhé Linh ơi!']);
    exit;
}

// 3. Tiến hành xóa khỏi Database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $delete_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Xóa thất bại: ' . $conn->error]);
}
?>