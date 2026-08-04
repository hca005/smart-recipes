<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = (int)current_user()['id'];

// Get the latest 10 notifications
$stmt = $conn->prepare("SELECT id, title, message, type, link, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
$unread_count = 0;

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
    if ($row['is_read'] == 0) {
        $unread_count++;
    }
}
$stmt->close();

// We could also do a separate query for total unread if we wanted, 
// but checking the last 10 is usually enough for the badge UI to show e.g. "5".
// If we want exact total unread:
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = 0");
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_res = $count_stmt->get_result()->fetch_assoc();
$total_unread = (int)$count_res['total'];
$count_stmt->close();

echo json_encode([
    'status' => 'success',
    'notifications' => $notifications,
    'unread_count' => $total_unread
]);
?>
