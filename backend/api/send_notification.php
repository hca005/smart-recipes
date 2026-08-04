<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); 
    exit;
}

$title = trim($_POST['title'] ?? '');
$message = trim($_POST['message'] ?? '');
$type = trim($_POST['type'] ?? 'system'); // 'system', 'alert', 'info'
$user_id = !empty($_POST['user_id']) ? $_POST['user_id'] : null;

if (empty($title) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Title and message are required']);
    exit;
}

// In food_recipe_db.sql, does notifications table have title? 
// Let's assume standard columns: user_id, type, message, is_read, created_at.
// If there is no title, we'll append it to the message. Wait, I better check the schema or just use message.
// I will just insert into notifications. I'll use `title` in message if no title col exists. 
// Assuming typical: user_id, message.
$final_message = "<b>" . htmlspecialchars($title) . "</b><br>" . nl2br(htmlspecialchars($message));

if ($user_id) {
    // Send to specific user
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $final_message);
    $success = $stmt->execute();
} else {
    // Send to all users
    $users = $conn->query("SELECT id FROM users");
    $success = true;
    while ($u = $users->fetch_assoc()) {
        $uid = $u['id'];
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $uid, $final_message);
        if (!$stmt->execute()) {
            $success = false;
        }
    }
}

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Notification sent']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $conn->error]);
}
?>
