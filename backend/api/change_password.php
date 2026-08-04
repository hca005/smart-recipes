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

$user_id      = $_SESSION['user']['id'];
$current_pw   = $_POST['current_password'] ?? '';
$new_pw       = $_POST['new_password']     ?? '';
$confirm_pw   = $_POST['confirm_password'] ?? '';

if (empty($current_pw) || empty($new_pw) || empty($confirm_pw)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    exit;
}

if ($new_pw !== $confirm_pw) {
    echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới không khớp!']);
    exit;
}

if (strlen($new_pw) < 6) {
    echo json_encode(['status' => 'error', 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự!']);
    exit;
}

// Lấy hash hiện tại từ DB
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || !password_verify($current_pw, $row['password_hash'])) {
    echo json_encode(['status' => 'error', 'message' => 'Mật khẩu hiện tại không đúng!']);
    exit;
}

// Cập nhật mật khẩu mới
$new_hash = password_hash($new_pw, PASSWORD_DEFAULT);
$stmt2 = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
$stmt2->bind_param("si", $new_hash, $user_id);

if ($stmt2->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Đổi mật khẩu thành công!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi Database: ' . $conn->error]);
}
?>
