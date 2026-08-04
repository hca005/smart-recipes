<?php
session_start();
header("Content-Type: application/json");

// 1. Kết nối cơ sở dữ liệu
include("../config/database.php");

// 2. Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "Hết phiên làm việc, vui lòng đăng nhập lại!"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user']['id'];
    
    // Nhận dữ liệu text từ form (phải khớp với JS bên profile.php)
    $display_name = trim($_POST['display_name'] ?? '');
    $bio          = trim($_POST['bio'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');

    // Lấy lại ảnh cũ từ Session đề phòng người dùng không chọn ảnh mới
    $profile_image = $_SESSION['user']['avatar'] ?? ''; 

    // 3. Xử lý Upload Ảnh (Nếu người dùng có chọn file)
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $targetDir = "../../frontend/assets/images/users/";
        
        // Tự động tạo thư mục nếu chưa có
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Đổi tên file để không bị trùng
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $fileName = "user_" . $user_id . "_" . time() . "." . $ext;
        $targetFile = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
            $profile_image = "/smart-recipes/frontend/assets/images/users/" . $fileName;
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi: Không thể lưu file ảnh vào thư mục!"]);
            exit;
        }
    }

    // 4. Cập nhật các trường đúng với schema DB
    if (!empty($date_of_birth)) {
        $sql = "UPDATE users SET display_name = ?, bio = ?, profile_image = ?, date_of_birth = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $display_name, $bio, $profile_image, $date_of_birth, $user_id);
    } else {
        $sql = "UPDATE users SET display_name = ?, bio = ?, profile_image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $display_name, $bio, $profile_image, $user_id);
    }

    if ($stmt->execute()) {
        // 5. CẬP NHẬT LẠI SESSION
        $_SESSION['user']['display_name'] = $display_name;
        $_SESSION['user']['bio']          = $bio;
        $_SESSION['user']['avatar']       = $profile_image;
        
        echo json_encode(["status" => "success", "message" => "Hồ sơ đã được cập nhật!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi Database: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ!"]);
}
?>