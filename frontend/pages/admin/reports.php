<?php
require_once '../../includes/bootstrap.php';
require_admin();

// Lấy reports thật từ DB
$query = "SELECT r.id, r.reason, r.status, r.created_at,
                 u.username AS reporter_name,
                 r.reported_type, r.reported_id
          FROM reports r
          JOIN users u ON r.reporter_id = u.id
          ORDER BY r.created_at DESC";
$result = $conn->query($query);
$reports = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reports – Food. Admin</title>
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
        <div class="adm-page-header">
            <h1 class="adm-page-title">Reports</h1>
            <span style="font-size:0.8125rem;color:#9CA3AF;">Review and act on user-submitted reports</span>
        </div>

        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>REPORTED BY</th>
                        <th>TARGET</th>
                        <th>REASON</th>
                        <th>STATUS</th>
                        <th>TIME</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($reports)): ?>
                <tr><td colspan="6" style="text-align:center;padding:30px;color:#9CA3AF;">Chưa có báo cáo nào.</td></tr>
                <?php else: ?>
                <?php foreach ($reports as $r): ?>
                <tr>
                    <td style="font-weight:600;color:#111827;"><?= htmlspecialchars($r['reporter_name']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($r['reported_type']) . ' #' . $r['reported_id']) ?></td>
                    <td><?= htmlspecialchars($r['reason']) ?></td>
                    <td>
                        <?php if ($r['status'] === 'resolved'): ?>
                            <span class="adm-badge adm-badge-ok">Resolved</span>
                        <?php elseif ($r['status'] === 'dismissed'): ?>
                            <span class="adm-badge" style="background:#e5e7eb;color:#374151;">Dismissed</span>
                        <?php else: ?>
                            <span class="adm-badge adm-badge-warn">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:#9CA3AF;font-size:0.78rem;"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                    <td>
                        <div style="display:flex;gap:0.4rem;">
                            <?php if ($r['status'] !== 'resolved' && $r['status'] !== 'dismissed'): ?>
                                <button class="adm-btn adm-btn-outline" onclick="dismissReport(<?= (int)$r['id'] ?>)">Dismiss</button>
                                <button class="adm-btn adm-btn-danger" onclick="resolveReport(<?= (int)$r['id'] ?>)">Resolve</button>
                            <?php endif; ?>
                        </div>
                    </td>
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
function updateReportStatus(id, status) {
    const fd = new FormData();
    fd.append('report_id', id);
    fd.append('status', status);
    fetch('/smart-recipes/backend/api/update_report.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') location.reload();
            else alert('Lỗi: ' + d.message);
        })
        .catch(() => alert('Lỗi kết nối!'));
}
function dismissReport(id) {
    if (confirm('Dismiss this report?')) updateReportStatus(id, 'dismissed');
}
function resolveReport(id) {
    if (confirm('Mark this report as resolved?')) updateReportStatus(id, 'resolved');
}
</script>
</body></html>