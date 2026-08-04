<?php
session_start();
header("Content-Type: application/json");

// 1. Kết nối Database
include("../config/database.php");

// 2. Kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "Vui lòng đăng nhập lại!"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $user_id = $_SESSION['user']['id'];
    $file = $_FILES['avatar'];
    
    // Tạo tên file mới dựa trên ID và thời gian để không bị trùng
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = "user_" . $user_id . "_" . time() . "." . $ext;
    
    // Đường dẫn để lưu file thực tế trên máy tính
    $uploadDir = "../../frontend/assets/images/users/";
    
    // Nếu thư mục chưa có thì tạo mới
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $uploadPath = $uploadDir . $fileName;

    // 3. Di chuyển file vào thư mục
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Đường dẫn để lưu vào database (để hiển thị trên web)
        $dbPath = "/smart-recipes/frontend/assets/images/users/" . $fileName;

        // 4. Cập nhật vào bảng users
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $dbPath, $user_id);
        
        if ($stmt->execute()) {
            // Cập nhật lại Session để các trang khác (như Navbar) cũng đổi ảnh theo
            $_SESSION['user']['avatar'] = $dbPath;
            
            echo json_encode(["status" => "success", "new_url" => $dbPath]);
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi database: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Không thể lưu file vào thư mục!"]);
    }
}