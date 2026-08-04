<?php
ob_start();
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để báo cáo.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$reported_type = $input['reported_type'] ?? '';
$reported_id = (int)($input['reported_id'] ?? 0);
$reason = trim($input['reason'] ?? '');
$reporter_id = (int)current_user()['id'];

$allowed_types = ['recipe', 'comment'];
if (!in_array($reported_type, $allowed_types) || $reported_id <= 0 || empty($reason)) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ. Vui lòng thử lại.']);
    exit;
}

// Check if already reported by this user to prevent spam
$check = $conn->prepare("SELECT id FROM reports WHERE reporter_id = ? AND reported_type = ? AND reported_id = ? AND status = 'pending'");
$check->bind_param("isi", $reporter_id, $reported_type, $reported_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn đã báo cáo nội dung này rồi và đang chờ duyệt.']);
    exit;
}

// Insert report
$stmt = $conn->prepare("INSERT INTO reports (reporter_id, reported_type, reported_id, reason, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())");
$stmt->bind_param("isis", $reporter_id, $reported_type, $reported_id, $reason);

if ($stmt->execute()) {
    $report_id = $conn->insert_id;
    // Notify all admins
    $admin_res = $conn->query("SELECT id FROM users WHERE role = 'admin'");
    if ($admin_res) {
        $reporter_name = current_user()['display_name'] ?? current_user()['username'];
        $notif_title = "Báo cáo vi phạm mới";
        $notif_msg = $reporter_name . " đã gửi một báo cáo vi phạm mới cần xử lý.";
        $notif_type = "report";
        $notif_link = "/smart-recipes/frontend/pages/admin/reports.php"; // Link to admin reports

        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        while ($admin = $admin_res->fetch_assoc()) {
            $stmt_notif->bind_param("issss", $admin['id'], $notif_type, $notif_title, $notif_msg, $notif_link);
            $stmt_notif->execute();
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Báo cáo của bạn đã được gửi. Cảm ơn bạn!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể gửi báo cáo: ' . $conn->error]);
}
?>
