<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');
ini_set('display_errors', '0');
error_reporting(E_ALL);

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = (int)current_user()['id'];
$notifications = [];
$total_unread = 0;

if ($conn) {
    try {
        $stmt = $conn->prepare("SELECT id, title, message, type, link, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();

        $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = 0");
        $count_stmt->bind_param("i", $user_id);
        $count_stmt->execute();
        $count_res = $count_stmt->get_result()->fetch_assoc();
        $total_unread = (int)($count_res['total'] ?? 0);
        $count_stmt->close();
    } catch (Throwable $e) {
        $notifications = [];
        $total_unread = 0;
    }
}

echo json_encode([
    'status' => 'success',
    'notifications' => $notifications,
    'unread_count' => $total_unread
]);
