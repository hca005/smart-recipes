<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php'; // Đảm bảo đường dẫn này đúng với file kết nối DB của con nhé

// 1. Bảo vệ cổng: Chỉ Admin mới được thêm Tag
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền Admin!']); 
    exit;
}

// 2. Nhận chữ mà con gõ vào ô "New Tag"
$tag_name = trim($_POST['tag_name'] ?? '');

if (empty($tag_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Tên tag không được để trống!']);
    exit;
}

// 3. Kiểm tra xem Tag này đã có trong kho chưa (tránh tạo 2 tag trùng nhau)
$stmt_check = $conn->prepare("SELECT id FROM tags WHERE name = ?");
$stmt_check->bind_param("s", $tag_name);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Tag này đã tồn tại trong hệ thống rồi!']);
    exit;
}

// 4. Tạo slug từ tên tag
$tag_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $tag_name)));

// 5. Nhét Tag mới vào Database
$stmt_insert = $conn->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
$stmt_insert->bind_param("ss", $tag_name, $tag_slug);

if ($stmt_insert->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Đã thêm Tag thành công!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi cơ sở dữ liệu: ' . $conn->error]);
}
?>