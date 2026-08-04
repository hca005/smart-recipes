<?php
session_start();
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']); 
    exit;
}

// Retrieve settings from POST
$settings = $_POST;

$success = true;
foreach ($settings as $key => $value) {
    // Basic validation to prevent SQL errors, key should be safe
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) continue;

    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->bind_param("ss", $key, $value);
    if (!$stmt->execute()) {
        $success = false;
    }
}

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Settings saved successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error saving some settings']);
}
?>
