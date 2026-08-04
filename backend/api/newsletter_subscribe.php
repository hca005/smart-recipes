<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method.']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

// Check if already subscribed
$stmt = $conn->prepare("SELECT id, is_active FROM newsletter_subscriptions WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    if ($row['is_active']) {
        echo json_encode(['status' => 'info', 'message' => 'You are already subscribed!']);
    } else {
        // Re-activate
        $upd = $conn->prepare("UPDATE newsletter_subscriptions SET is_active = 1, unsubscribed_at = NULL WHERE email = ?");
        $upd->bind_param("s", $email);
        $upd->execute();
        echo json_encode(['status' => 'success', 'message' => 'Welcome back! You have been re-subscribed.']);
    }
    exit;
}

$ins = $conn->prepare("INSERT INTO newsletter_subscriptions (email) VALUES (?)");
$ins->bind_param("s", $email);

if ($ins->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Thank you for subscribing!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
}
?>
