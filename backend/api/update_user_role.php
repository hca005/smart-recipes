<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Quyền hạn không đủ!']);
    exit;
}

$user_id = $_POST['user_id'] ?? 0;
$new_role = $_POST['role'] ?? 'user';
$new_name = $_POST['display_name'] ?? ''; // Nhận tên mới từ JS

// Cập nhật CẢ role VÀ display_name
$stmt = $conn->prepare("UPDATE users SET role = ?, display_name = ? WHERE id = ?");
// Chữ "ssi" nghĩa là: String (Role), String (Tên), Integer (ID)
$stmt->bind_param("ssi", $new_role, $new_name, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
?>