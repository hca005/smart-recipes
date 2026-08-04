<?php
require_once '../../includes/bootstrap.php';
require_admin();

// Lấy notifications thật từ DB cho Admin
$admin_id = (int)current_user()['id'];
$query = "SELECT n.id, n.type, n.title, n.message, n.is_read, n.created_at,
                 COALESCE(u.username, 'System') AS recipient_name
          FROM notifications n
          LEFT JOIN users u ON n.user_id = u.id
          WHERE n.user_id = $admin_id OR n.user_id IS NULL
          ORDER BY n.created_at DESC
          LIMIT 100";
$result = $conn->query($query);
$notifs = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $notifs[] = $row;
    }
}

$users = [];
$users_res = $conn->query("SELECT id, username FROM users");
if ($users_res) {
    while ($row = $users_res->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Notifications – Food. Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/variables.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/reset.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/pages/admin.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/footer.css">
</head>
<body class="adm-body">
<div class="adm-layout">
<?php include '../../includes/admin_sidebar.php'; ?>
<div class="adm-main">
    <header class="adm-topbar">
        <div class="adm-topbar-left"><span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Default</span></div>
        <div class="adm-topbar-spacer"></div>
        <div class="adm-tb-search"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Search</div>
        <div class="adm-tb-icons">
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg></button>
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></button>
        </div>
    </header>

    <div class="adm-content">
        <div class="adm-page-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1 class="adm-page-title">System Notifications</h1>
                <span style="font-size:0.8125rem;color:#9CA3AF;">Recent notifications list</span>
            </div>
            <button class="adm-btn" style="background:#FCD34D;color:#000;border:none;padding:0.6rem 1.25rem;font-weight:700;border-radius:6px;cursor:pointer;" onclick="document.getElementById('createNotifModal').style.display='flex'">Create Notification</button>
        </div>

        <!-- Create Notif Modal -->
        <div id="createNotifModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
            <div style="background:#fff; padding:1.5rem; border-radius:10px; width:400px; max-width:90%;">
                <h3 style="margin-top:0;">Send Notification</h3>
                <div class="ct-field" style="margin-bottom:0.8rem;">
                    <label style="font-size:0.8rem;font-weight:bold;color:#374151;">Title</label>
                    <input type="text" id="notifTitle" style="width:100%; border:1px solid #ccc; border-radius:4px; padding:0.5rem; box-sizing:border-box;margin-top:4px;">
                </div>
                <div class="ct-field" style="margin-bottom:0.8rem;">
                    <label style="font-size:0.8rem;font-weight:bold;color:#374151;">Message</label>
                    <textarea id="notifMessage" style="width:100%; border:1px solid #ccc; border-radius:4px; padding:0.5rem; box-sizing:border-box; min-height:80px;margin-top:4px;"></textarea>
                </div>
                <div class="ct-field" style="margin-bottom:1.5rem;">
                    <label style="font-size:0.8rem;font-weight:bold;color:#374151;">Recipient</label>
                    <select id="notifUserId" style="width:100%; border:1px solid #ccc; border-radius:4px; padding:0.5rem; box-sizing:border-box;margin-top:4px;">
                        <option value="">-- All Users --</option>
                        <?php foreach($users as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                    <button type="button" onclick="document.getElementById('createNotifModal').style.display='none'" style="padding:0.5rem 1rem; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
                    <button type="button" onclick="sendNotification()" class="adm-btn" style="margin:0; background:#FCD34D; color:#000; font-weight:bold; border:none;padding:0.5rem 1.2rem;border-radius:4px;cursor:pointer;">Send</button>
                </div>
            </div>
        </div>

        <div class="adm-notif-list">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>RECIPIENT</th>
                        <th>TYPE</th>
                        <th>TITLE</th>
                        <th>MESSAGE</th>
                        <th>TIME</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($notifs)): ?>
                <tr><td colspan="5" style="text-align:center;padding:30px;color:#9CA3AF;">Chưa có thông báo nào.</td></tr>
                <?php else: ?>
                <?php foreach ($notifs as $n): ?>
                <tr style="<?= $n['is_read'] ? '' : 'background:#fffbeb;' ?>">
                    <td style="font-weight:600;color:#111827;"><?= htmlspecialchars($n['recipient_name']) ?></td>
                    <td><span style="font-size:0.8rem;color:#6B7280;"><?= htmlspecialchars($n['type']) ?></span></td>
                    <td style="font-weight:600;color:#374151;"><?= htmlspecialchars($n['title']) ?></td>
                    <td style="font-size:0.8125rem;color:#6B7280;"><?= htmlspecialchars($n['message'] ?? '') ?></td>
                    <td style="color:#9CA3AF;font-size:0.78rem;white-space:nowrap;"><?= date('d/m/Y H:i', strtotime($n['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
</div>
</div>
<script>
function sendNotification() {
    var title = document.getElementById('notifTitle').value.trim();
    var msg = document.getElementById('notifMessage').value.trim();
    var user_id = document.getElementById('notifUserId').value;

    if (!title || !msg) {
        alert('Title and Message are required');
        return;
    }

    var fd = new FormData();
    fd.append('title', title);
    fd.append('message', msg);
    if (user_id) fd.append('user_id', user_id);

    fetch('/smart-recipes/backend/api/send_notification.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') location.reload();
        else alert('Error: ' + d.message);
    })
    .catch(() => alert('Network error'));
}
</script>
</body></html>