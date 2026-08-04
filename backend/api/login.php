<?php
session_start();
header("Content-Type: application/json");

// Kết nối database
include("../config/database.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 1. Tìm người dùng theo email
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

// 2. Kiểm tra mật khẩu
if ($user && password_verify($password, $user['password_hash'])) {

    // 3. QUAN TRỌNG: Lưu vào mảng $_SESSION['user'] để khớp với bootstrap.php
    $_SESSION['user'] = [
        'id'           => $user['id'],
        'username'     => $user['username'],
        'email'        => $user['email'],
        // Nếu display_name trong DB trống thì dùng tạm username
        'display_name' => $user['display_name'] ?? $user['username'], 
        // Nếu chưa có ảnh thì dùng ảnh mặc định
        'avatar'       => $user['profile_image'] ?? '/smart-recipes/frontend/assets/images/default-avatar.png',
        'role'         => $user['role'] ?? 'user'
    ];

    echo json_encode([
        "status" => "success",
        "username" => $_SESSION['user']['username'],
        "role"     => $_SESSION['user']['role']
    ]);
} else {
    // 4. Trả về lỗi nếu sai thông tin
    echo json_encode([
        "status" => "error",
        "message" => "Email hoặc mật khẩu không chính xác!"
    ]);
}