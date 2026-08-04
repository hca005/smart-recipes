<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

// 1. Bảo vệ cổng: Chỉ Admin mới được làm điều này
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền Admin!']); 
    exit;
}

// 2. Nhận các thông tin (Tên, Slug, Mô tả, Icon, Màu sắc) từ giao diện gửi về
$name = trim($_POST['name'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$description = trim($_POST['description'] ?? '');
$icon = trim($_POST['icon'] ?? 'fa fa-utensils');
$color = trim($_POST['color'] ?? '#FCD34D');

// Kiểm tra xem Admin có nhập tên không
if (empty($name)) {
    echo json_encode(['status' => 'error', 'message' => 'Tên danh mục không được để trống!']);
    exit;
}

// Mẹo nhỏ: Nếu Admin lười không gõ Slug, hệ thống tự động tạo tạm 1 cái Slug từ tên
if (empty($slug)) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
}

// 3. Nhét Danh Mục mới vào Database
$stmt = $conn->prepare("INSERT INTO categories (name, slug, description, icon, color) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $slug, $description, $icon, $color);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Thêm danh mục thành công rực rỡ!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error]);
}
?>