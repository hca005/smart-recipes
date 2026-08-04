<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();
header('Content-Type: application/json');

if (!is_admin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized!']);
    exit;
}

$report_id = (int)($_POST['report_id'] ?? 0);
$status    = $_POST['status'] ?? '';

$allowed = ['pending', 'reviewed', 'resolved', 'dismissed'];
if ($report_id <= 0 || !in_array($status, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ!']);
    exit;
}

$stmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $report_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
?>
