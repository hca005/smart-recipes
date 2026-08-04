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

$user_id   = $_SESSION['user']['id'];
$new_email = trim($_POST['new_email'] ?? '');
$password  = $_POST['password'] ?? '';

// Validate inputs
if (empty($new_email)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập email mới!']);
    exit;
}

if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ!']);
    exit;
}

if (empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mật khẩu để xác nhận!']);
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->bind_param("si", $new_email, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email này đã được sử dụng!']);
    exit;
}

// Verify password
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || !password_verify($password, $row['password_hash'])) {
    echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không đúng!']);
    exit;
}

// Update email
$stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
$stmt->bind_param("si", $new_email, $user_id);

if ($stmt->execute()) {
    // Update session
    $_SESSION['user']['email'] = $new_email;
    echo json_encode(['status' => 'success', 'message' => 'Đổi email thành công!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi Database: ' . $conn->error]);
}
?>
