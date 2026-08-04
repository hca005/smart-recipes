<?php
require_once '../../includes/bootstrap.php';
require_admin();

$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM settings");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

$site_name = $settings['site_name'] ?? 'Smart Recipe';
$admin_email = $settings['admin_email'] ?? 'admin@smartrecipe.com';
$smtp_server = $settings['smtp_server'] ?? 'smtp.example.com';
$smtp_port = $settings['smtp_port'] ?? '587';
$user_registration = ($settings['user_registration'] ?? 'true') === 'true';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>System Settings – Food. Admin</title>
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
            <div>
                <h1 class="adm-page-title">System Settings</h1>
                <p class="adm-page-sub">Configure system preferences and application behavior</p>
            </div>
            <button class="adm-btn adm-btn-yellow" style="padding:0.55rem 1.25rem;font-size:0.875rem;" onclick="saveSettings()">
                💾 Save All Settings
            </button>
        </div>

        <!-- System Health -->
        <div class="adm-health-banner">
            <div class="adm-health-banner-title">❤️ System Health</div>
            <div class="adm-health-metrics">
                <div class="adm-health-item">
                    <span class="adm-health-val">0.5%</span>
                    <span class="adm-health-lbl">Memory Usage</span>
                </div>
                <div class="adm-health-item">
                    <span class="adm-health-val">0d</span>
                    <span class="adm-health-lbl">Uptime</span>
                </div>
                <div class="adm-health-item">
                    <span class="adm-health-val">0</span>
                    <span class="adm-health-lbl">Active Users</span>
                </div>
            </div>
        </div>

        <div class="adm-settings-grid">
            <!-- General Settings -->
            <div class="adm-settings-card">
                <p class="adm-settings-card-title">⚙️ General Settings</p>
                <p class="adm-settings-card-sub">Configure basic website information and behavior</p>
                <div class="adm-field"><label>Site Name</label><input type="text" id="set_site_name" value="<?= htmlspecialchars($site_name) ?>"></div>
                <div class="adm-field"><label>Site Tagline</label><input type="text" value="Share and Discover Amazing Recipes"></div>
                <div class="adm-field"><label>Admin Email</label><input type="email" id="set_admin_email" value="<?= htmlspecialchars($admin_email) ?>"></div>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>User Registration</p><span>Allow new users to register accounts</span></div>
                    <label class="adm-toggle"><input type="checkbox" id="set_user_registration" <?= $user_registration ? 'checked' : '' ?>><span class="adm-toggle-slider"></span></label>
                </div>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>Public Access</p><span>Allow non-logged in users to view recipes</span></div>
                    <label class="adm-toggle"><input type="checkbox" checked><span class="adm-toggle-slider"></span></label>
                </div>
            </div>

            <!-- Appearance -->
            <div class="adm-settings-card">
                <p class="adm-settings-card-title">🎨 Appearance</p>
                <p class="adm-settings-card-sub">Customize the look and feel of your website</p>
                <div class="adm-field">
                    <label>Theme Color</label>
                    <div class="adm-color-row">
                        <div class="adm-color-swatch" style="background:#e392fe;" onclick="this.nextElementSibling.click()"></div>
                        <input type="color" value="#e392fe" style="opacity:0;width:0;position:absolute;">
                        <input type="text" value="#e392fe" style="border:1px solid #E5E7EB;border-radius:6px;padding:0.45rem 0.7rem;font-size:0.875rem;font-family:inherit;outline:none;width:100px;">
                    </div>
                </div>
                <div class="adm-field">
                    <label>Logo Upload</label>
                    <div style="display:flex;gap:0.5rem;align-items:center;">
                        <button class="adm-btn adm-btn-outline" onclick="document.getElementById('logoFile').click()">Choose File</button>
                        <input type="file" id="logoFile" style="display:none;">
                        <span style="font-size:0.8rem;color:#9CA3AF;">No file chosen</span>
                    </div>
                </div>
                <div class="adm-field">
                    <label>Theme Mode</label>
                    <div class="adm-radio-row">
                        <label><input type="radio" name="theme" value="light"> Light</label>
                        <label><input type="radio" name="theme" value="dark"> Dark</label>
                        <label><input type="radio" name="theme" value="auto" checked> Auto</label>
                    </div>
                </div>
                <div class="adm-field"><label>Font Family</label><input type="text" value="Inter"></div>
            </div>

            <!-- Content Settings -->
            <div class="adm-settings-card">
                <p class="adm-settings-card-title">📄 Content Settings</p>
                <p class="adm-settings-card-sub">Configure how content is displayed and moderated</p>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>Auto-approve Recipes</p><span>Automatically approve new recipes without moderation</span></div>
                    <label class="adm-toggle"><input type="checkbox"><span class="adm-toggle-slider"></span></label>
                </div>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>Allow Comments</p><span>Allow users to comment on recipes</span></div>
                    <label class="adm-toggle"><input type="checkbox"><span class="adm-toggle-slider"></span></label>
                </div>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>Allow Ratings</p><span>Allow users to rate recipes</span></div>
                    <label class="adm-toggle"><input type="checkbox"><span class="adm-toggle-slider"></span></label>
                </div>
                <div class="adm-field"><label>Recipes Per Page</label><input type="number" value="12" min="1" max="100"></div>
            </div>

            <!-- Email & Notifications -->
            <div class="adm-settings-card">
                <p class="adm-settings-card-title">📧 Email &amp; Notifications</p>
                <p class="adm-settings-card-sub">Configure email server and notification preferences</p>
                <div class="adm-toggle-row">
                    <div class="adm-toggle-label"><p>Email Notifications</p><span>Send email notifications to users</span></div>
                    <label class="adm-toggle"><input type="checkbox"><span class="adm-toggle-slider"></span></label>
                </div>
                <div class="adm-field"><label>SMTP Server</label><input type="text" id="set_smtp_server" value="<?= htmlspecialchars($smtp_server) ?>"></div>
                <div class="adm-field"><label>SMTP Port</label><input type="number" id="set_smtp_port" value="<?= htmlspecialchars($smtp_port) ?>"></div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
</div>
</div>
<script>
function saveSettings() {
    var fd = new FormData();
    fd.append('site_name', document.getElementById('set_site_name').value);
    fd.append('admin_email', document.getElementById('set_admin_email').value);
    fd.append('user_registration', document.getElementById('set_user_registration').checked ? 'true' : 'false');
    fd.append('smtp_server', document.getElementById('set_smtp_server').value);
    fd.append('smtp_port', document.getElementById('set_smtp_port').value);
    
    fetch('/smart-recipes/backend/api/save_settings.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
        if (d.status === 'success') {
            alert('Settings saved successfully!');
            location.reload();
        } else {
            alert('Error: ' + d.message);
        }
    })
    .catch(() => alert('Network error'));
}
</script>
</body></html>