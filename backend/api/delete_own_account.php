<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

$user_id  = $_SESSION['user']['id'];
$password = $_POST['password'] ?? '';

if (empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mật khẩu để xác nhận!']);
    exit;
}

// Verify password
$stmt = $conn->prepare("SELECT password_hash, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy tài khoản!']);
    exit;
}

if (!password_verify($password, $row['password_hash'])) {
    echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không đúng!']);
    exit;
}

// Prevent admin from deleting their own account (optional safety)
if ($row['role'] === 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Admin không thể tự xóa tài khoản!']);
    exit;
}

// Delete user (cascading will handle related data due to foreign keys)
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Destroy session
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Tài khoản đã được xóa!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi Database: ' . $conn->error]);
}
?>
