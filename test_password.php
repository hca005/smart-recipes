<?php
// Test file để kiểm tra password hash
// Truy cập: http://localhost/smart-recipes/test_password.php

include("backend/config/database.php");

echo "<h2>Test Password Hash</h2>";

// Tạo hash mới cho password "123456"
$password = "123456";
$newHash = password_hash($password, PASSWORD_DEFAULT);
echo "<p><strong>Password:</strong> $password</p>";
echo "<p><strong>New Hash:</strong> $newHash</p>";

// Kiểm tra hash trong database
$result = $conn->query("SELECT id, username, email, password_hash, role FROM users LIMIT 10");

if ($result && $result->num_rows > 0) {
    echo "<h3>Users trong database:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Password Hash</th><th>Test 123456</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        $testResult = password_verify($password, $row['password_hash']) ? "✅ OK" : "❌ FAIL";
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['role']}</td>";
        echo "<td style='font-size:10px;word-break:break-all;max-width:300px;'>{$row['password_hash']}</td>";
        echo "<td>$testResult</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>Không có user nào trong database! Hãy import file SQL.</p>";
}

echo "<hr>";
echo "<h3>Hướng dẫn:</h3>";
echo "<ol>";
echo "<li>Mở phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
echo "<li>Tạo database mới tên: <strong>food_recipe_db</strong></li>";
echo "<li>Import file: <strong>database/food_recipe_db (1).sql</strong></li>";
echo "<li>Đăng nhập với: <strong>admin@food.com</strong> / <strong>123456</strong></li>";
echo "</ol>";

// Nếu không có user, tự động thêm admin
if ($result && $result->num_rows == 0) {
    echo "<h3>Tự động tạo user admin...</h3>";
    $adminHash = password_hash("123456", PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password_hash, display_name, role, is_active) 
            VALUES ('admin', 'admin@food.com', '$adminHash', 'Admin Chef', 'admin', 1)";
    if ($conn->query($sql)) {
        echo "<p style='color:green;'>✅ Đã tạo user admin thành công!</p>";
        echo "<p>Email: admin@food.com | Password: 123456</p>";
    } else {
        echo "<p style='color:red;'>❌ Lỗi: " . $conn->error . "</p>";
    }
}
?>
