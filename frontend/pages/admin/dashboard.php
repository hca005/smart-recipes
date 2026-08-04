<?php
require_once '../../includes/bootstrap.php';
require_admin();
$pageTitle = 'Dashboard – Food. Admin';

// Dynamic Metrics
$total_users = $conn->query("SELECT COUNT(id) FROM users")->fetch_row()[0] ?? 0;
$active_recipes = $conn->query("SELECT COUNT(id) FROM recipes WHERE is_published=1")->fetch_row()[0] ?? 0;
$pending_recipes = $conn->query("SELECT COUNT(id) FROM recipes WHERE is_published=0")->fetch_row()[0] ?? 0;
$reports_res = $conn->query("SELECT COUNT(id) FROM reports");
$total_reports = $reports_res ? $reports_res->fetch_row()[0] : 0;

// Dynamic Categories Data
$cat_chart_res = $conn->query("SELECT c.name, COUNT(r.id) as rc FROM categories c LEFT JOIN recipes r ON c.id=r.category_id GROUP BY c.id LIMIT 6");
$cat_labels = [];
$cat_data = [];
if($cat_chart_res) {
    while($row = $cat_chart_res->fetch_assoc()) {
        $cat_labels[] = $row['name'];
        $cat_data[] = $row['rc'];
    }
}

// Donut Chart Data
$total_recipes = $active_recipes + $pending_recipes;
$approved_pct = $total_recipes > 0 ? round(($active_recipes / $total_recipes) * 100, 1) : 0;
$pending_pct = $total_recipes > 0 ? round(($pending_recipes / $total_recipes) * 100, 1) : 0;

// Notifications Data
$notifs = [];
$admin_id = (int)current_user()['id'];
$notif_res = $conn->query("SELECT * FROM notifications WHERE user_id = $admin_id OR user_id IS NULL ORDER BY created_at DESC LIMIT 5");
if($notif_res) {
    while($row = $notif_res->fetch_assoc()) {
        $notifs[] = $row;
    }
}

// Line chart - Last 7 months users & recipes
$months_labels = [];
$users_data = [];
$recipes_data = [];

for ($i = 6; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i month"));
    $months_labels[] = date('M', strtotime("-$i month"));
    
    // Users
    $u_count = $conn->query("SELECT COUNT(id) FROM users WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'")->fetch_row()[0] ?? 0;
    $users_data[] = (int)$u_count;
    
    // Recipes
    $r_count = $conn->query("SELECT COUNT(id) FROM recipes WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'")->fetch_row()[0] ?? 0;
    $recipes_data[] = (int)$r_count;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?></title>
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/variables.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/reset.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/pages/admin.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/footer.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
</head>
<body class="adm-body">

<div class="adm-layout">
<?php include '../../includes/admin_sidebar.php'; ?>

<div class="adm-main">
    <!-- Topbar -->
    <header class="adm-topbar">
        <div class="adm-topbar-left">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Default</span>
        </div>
        <div class="adm-topbar-spacer"></div>
        <div class="adm-tb-search">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Search
            <span style="margin-left:auto;font-size:0.7rem;background:#f0f0f0;padding:1px 5px;border-radius:3px;color:#aaa;">/</span>
        </div>
        <div class="adm-tb-icons">
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></button>
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg></button>
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button>
            <button class="adm-tb-icon"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></button>
        </div>
    </header>

    <!-- Dashboard body -->
    <div class="adm-dash-body">
        <div class="adm-dash-content">
            <h2 style="font-size:1.1rem;font-weight:700;color:#111;margin:0 0 1.1rem;">Overview</h2>

            <!-- Metrics -->
            <div class="adm-metrics">
                <div class="adm-metric-card">
                    <p class="adm-metric-label">Total users</p>
                    <div class="adm-metric-row">
                        <span class="adm-metric-val"><?= $total_users ?></span>
                        <span class="adm-metric-change">+0% ↑</span>
                    </div>
                </div>
                <div class="adm-metric-card">
                    <p class="adm-metric-label">Active recipes</p>
                    <div class="adm-metric-row">
                        <span class="adm-metric-val"><?= $active_recipes ?></span>
                        <span class="adm-metric-change down">-0% ↗</span>
                    </div>
                </div>
                <div class="adm-metric-card">
                    <p class="adm-metric-label">Pending Recipes</p>
                    <div class="adm-metric-row">
                        <span class="adm-metric-val"><?= $pending_recipes ?></span>
                        <span class="adm-metric-change">+0% ↑</span>
                    </div>
                </div>
                <div class="adm-metric-card">
                    <p class="adm-metric-label">Report Filed</p>
                    <div class="adm-metric-row">
                        <span class="adm-metric-val"><?= $total_reports ?></span>
                        <span class="adm-metric-change neutral">No changed</span>
                    </div>
                </div>
            </div>

            <!-- Line chart -->
            <div class="adm-chart-card" style="margin-bottom:1rem;">
                <div class="adm-chart-header">
                    <div class="adm-chart-tabs">
                        <span class="adm-chart-tab active">Growth (Last 7 Months)</span>
                    </div>
                    <div class="adm-chart-legend">
                        <span><span class="adm-legend-dot" style="background:#FCD34D;"></span> New Users</span>
                        <span><span class="adm-legend-dot" style="background:#d1d5db;border:1px dashed #aaa;"></span> New Recipes</span>
                    </div>
                </div>
                <canvas id="lineChart" height="100"></canvas>
            </div>

            <!-- Bar + Donut -->
            <div class="adm-chart-2col">
                <div class="adm-chart-card">
                    <div class="adm-chart-header">
                        <strong style="font-size:0.875rem;font-weight:700;color:#111;">Top Recipes by Category</strong>
                    </div>
                    <canvas id="barChart" height="160"></canvas>
                </div>
                <div class="adm-chart-card">
                    <div class="adm-chart-header">
                        <strong style="font-size:0.875rem;font-weight:700;color:#111;">Recipe Status Breakdown</strong>
                    </div>
                    <div class="adm-chart-donut-row">
                        <canvas id="donutChart" width="130" height="130" style="flex-shrink:0;"></canvas>
                        <div class="adm-donut-legend">
                            <div class="adm-donut-legend-item"><span><span class="adm-legend-dot" style="background:#FCD34D;"></span>Approved</span><strong><?= $approved_pct ?>%</strong></div>
                            <div class="adm-donut-legend-item"><span><span class="adm-legend-dot" style="background:#D1D5DB;"></span>Pending</span><strong><?= $pending_pct ?>%</strong></div>
                            <div class="adm-donut-legend-item"><span><span class="adm-legend-dot" style="background:#9CA3AF;"></span>Hidden</span><strong>0%</strong></div>
                            <div class="adm-donut-legend-item"><span><span class="adm-legend-dot" style="background:#6EE7B7;"></span>Other</span><strong>0%</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Notifications -->
        <div class="adm-notif-sidebar">
            <p class="adm-notif-title">Notifications</p>
            <?php if(empty($notifs)): ?>
                <p style="color:#888; font-size:0.85rem;">No new notifications</p>
            <?php else: ?>
                <?php foreach($notifs as $n): ?>
                <div class="adm-notif-item">
                    <div class="adm-notif-icon">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <div class="adm-notif-text"><p><?= strip_tags($n['message']) ?></p><span><?= date('M d, H:i', strtotime($n['created_at'])) ?></span></div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
</div><!-- /.adm-main -->
</div><!-- /.adm-layout -->

<script>
// Line Chart
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months_labels) ?>,
        datasets: [{
            label: 'New Users',
            data: <?= json_encode($users_data) ?>,
            borderColor: '#FCD34D',
            backgroundColor: 'rgba(252,211,77,0.08)',
            tension: 0.4, fill: true, borderWidth: 2, pointRadius: 3
        },{
            label: 'New Recipes',
            data: <?= json_encode($recipes_data) ?>,
            borderColor: '#D1D5DB',
            borderDash: [5,5],
            backgroundColor: 'transparent',
            tension: 0.4, fill: false, borderWidth: 2, pointRadius: 2
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } },
               scales: { y: { ticks: { callback: v => v >= 1000 ? (v/1000)+'K' : v }, grid: { color: '#f0f0f0' } }, x: { grid: { display: false } } } }
});

// Bar Chart
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($cat_labels) ?>,
        datasets: [{
            data: <?= json_encode($cat_data) ?>,
            backgroundColor: ['#93C5FD','#6EE7B7','#FCD34D','#C4B5FD','#86EFAC','#FCA5A5'],
            borderRadius: 4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } },
               scales: { y: { grid: { color: '#f0f0f0' } }, x: { grid: { display: false } } } }
});

// Donut Chart
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Approved','Pending','Hidden','Other'],
        datasets: [{ data: [<?= $approved_pct ?>,<?= $pending_pct ?>,0,0],
            backgroundColor: ['#FCD34D','#D1D5DB','#9CA3AF','#6EE7B7'],
            borderWidth: 0 }]
    },
    options: { responsive: false, plugins: { legend: { display: false } }, cutout: '65%' }
});
</script>
</body></html>