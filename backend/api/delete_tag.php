<?php
session_start();
header('Content-Type: application/json');

// Gọi file kết nối Database của con vào (kiểm tra lại đường dẫn cho đúng nhé)
require_once '../config/database.php';

// 1. Kiểm tra xem có phải Admin đang thao tác không (Bảo mật)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền thực hiện thao tác này!']); 
    exit;
}

// 2. Nhận ID của Tag cần xóa từ giao diện gửi qua
$tag_id = $_POST['tag_id'] ?? 0;

if ($tag_id > 0) {
    // 3. Gọi lệnh SQL chém bay màu Tag khỏi Database
    $stmt = $conn->prepare("DELETE FROM tags WHERE id = ?");
    $stmt->bind_param("i", $tag_id);
    
    if ($stmt->execute()) {
        // Nếu xóa thành công, báo về cho giao diện biết để F5 lại trang
        echo json_encode(['status' => 'success', 'message' => 'Đã xóa Tag thành công!']);
    } else {
        // Nếu Database bị lỗi
        echo json_encode(['status' => 'error', 'message' => 'Lỗi DB: ' . $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID Tag không hợp lệ!']);
}
?>