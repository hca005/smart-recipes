<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_id = (int)current_user()['id'];
$type = $_POST['type'] ?? '';
$prefs = [];

if ($type === 'notifications') {
    $keys = ['email_notifications', 'comment_notifications', 'like_notifications', 'newsletter'];
    foreach ($keys as $k) {
        $prefs[$k] = isset($_POST[$k]) && ($_POST[$k] === 'on' || $_POST[$k] === 'true' || $_POST[$k] === '1');
    }
} elseif ($type === 'privacy') {
    $keys = ['public_profile', 'show_activity', 'show_bookmarks', 'search_indexing'];
    foreach ($keys as $k) {
        $prefs[$k] = isset($_POST[$k]) && ($_POST[$k] === 'on' || $_POST[$k] === 'true' || $_POST[$k] === '1');
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid preferences type.']);
    exit;
}

$json_prefs = json_encode($prefs);
$column = $type === 'notifications' ? 'notification_prefs' : 'privacy_prefs';

$stmt = $conn->prepare("UPDATE users SET {$column} = ? WHERE id = ?");
$stmt->bind_param("si", $json_prefs, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => ucfirst($type) . ' preferences saved successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
$stmt->close();
?>
