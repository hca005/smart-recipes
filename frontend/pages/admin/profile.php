<?php
require_once '../../includes/bootstrap.php';
require_admin();
$adminUser = current_user();

// Get real stats
$recipe_count = $conn->query("SELECT COUNT(id) FROM recipes")->fetch_row()[0] ?? 0;
$pending_count = $conn->query("SELECT COUNT(id) FROM recipes WHERE is_published = 0")->fetch_row()[0] ?? 0;
$admin_id = (int)current_user()['id'];
$notif_count = $conn->query("SELECT COUNT(id) FROM notifications WHERE (user_id = $admin_id OR user_id IS NULL) AND is_read = 0")->fetch_row()[0] ?? 0;

// Load notification settings
$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM settings");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}
$notif_email = ($settings['admin_notif_email'] ?? 'true') === 'true';
$notif_push = ($settings['admin_notif_push'] ?? 'true') === 'true';
$notif_weekly = ($settings['admin_notif_weekly'] ?? 'true') === 'true';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Profile – Food. Admin</title>
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
            <h1 class="adm-page-title">Admin Profile</h1>
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/frontend'; ?>/pages/auth/logout.php" class="adm-btn adm-btn-red-outline" style="padding:0.5rem 1.25rem;">Logout</a>
        </div>

        <div class="adm-profile-grid">
            <!-- Left card -->
            <div class="adm-profile-card">
                <img src="<?= htmlspecialchars($adminUser['avatar'] ?? '/smart-recipes/frontend/assets/images/default-avatar.png') ?>"
                     class="adm-profile-avatar-img" alt="<?= htmlspecialchars($adminUser['display_name'] ?? 'Admin') ?>">
                <p class="adm-profile-name"><?= htmlspecialchars($adminUser['display_name'] ?? $adminUser['username']) ?></p>
                <p class="adm-profile-email"><?= htmlspecialchars($adminUser['email']) ?></p>
                <div class="adm-profile-stats">
                    <div>
                        <span class="adm-profile-stat-label">RECIPES</span>
                        <span class="adm-profile-stat-val"><?= $recipe_count ?></span>
                    </div>
                    <div>
                        <span class="adm-profile-stat-label">PENDING</span>
                        <span class="adm-profile-stat-val"><?= $pending_count ?></span>
                    </div>
                    <div>
                        <span class="adm-profile-stat-label">NOTIFS</span>
                        <span class="adm-profile-stat-val"><?= $notif_count ?></span>
                    </div>
                </div>
            </div>

            <!-- Right forms -->
            <div>
                <!-- Profile form -->
                <div class="adm-form-card">
                    <h3>Edit Profile</h3>
                    <form id="adminProfileForm">
                    <div class="adm-form-grid2">
                        <div class="adm-field">
                            <label>Username</label>
                            <input type="text" value="<?= htmlspecialchars($adminUser['username']) ?>" readonly style="background:#f8fafc;color:#9CA3AF;">
                        </div>
                        <div class="adm-field">
                            <label>Display Name</label>
                            <input type="text" name="display_name" value="<?= htmlspecialchars($adminUser['display_name'] ?? $adminUser['username']) ?>">
                        </div>
                    </div>
                    <div class="adm-field">
                        <label>Bio</label>
                        <textarea name="bio" style="width:100%;border:1px solid #E5E7EB;border-radius:6px;padding:0.6rem 0.8rem;font-size:0.875rem;font-family:inherit;resize:vertical;min-height:80px;outline:none;box-sizing:border-box;" onfocus="this.style.borderColor='#FCD34D'" onblur="this.style.borderColor='#E5E7EB'"><?= htmlspecialchars($adminUser['bio'] ?? '') ?></textarea>
                    </div>
                    <div class="adm-form-actions">
                        <button type="submit" class="adm-btn adm-btn-outline">Update Profile</button>
                        <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/frontend'; ?>/pages/auth/logout.php" class="adm-btn adm-btn-red-outline">Logout</a>
                    </div>
                    </form>
                </div>

                <!-- Avatar upload -->
                <div class="adm-form-card">
                    <h3>Update Avatar</h3>
                    <div class="adm-avatar-upload">
                        <button class="adm-btn adm-btn-outline" onclick="document.getElementById('avatarFile').click()">Choose File</button>
                        <input type="file" id="avatarFile" accept="image/*" style="display:none;" onchange="uploadAdminAvatar(this)">
                        <span style="font-size:0.8rem;color:#9CA3AF;" id="avatarFileName">no file selected</span>
                    </div>
                </div>

                <div class="adm-form-card" style="margin-top: 30px;">
                    <h3>System Notifications</h3>
                    <p style="color: #94a3b8; font-size: 0.8rem; margin-bottom: 20px;">Thiết lập quyền nhận thông báo của riêng Admin.</p>
                
                    <div style="display: flex; flex-direction: column; gap: 18px; margin-top: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="background: #eff6ff; color: #3b82f6; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 0.9rem; color: #1e293b;">Email Notifications</strong>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">Nhận email khi có user mới.</span>
                                </div>
                            </div>
                            <input type="checkbox" id="notif_email" class="adm-notif-switch" <?= $notif_email ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                        </div>
                    
                        <hr style="border: 0; border-top: 1px solid #f1f5f9;">
                    
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="background: #f0fdf4; color: #22c55e; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 0.9rem; color: #1e293b;">Push Notifications</strong>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">Hiện thông báo trình duyệt.</span>
                                </div>
                            </div>
                            <input type="checkbox" id="notif_push" class="adm-notif-switch" <?= $notif_push ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                        </div>
                    
                        <hr style="border: 0; border-top: 1px solid #f1f5f9;">
                    
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="background: #fff7ed; color: #f97316; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <strong style="display: block; font-size: 0.9rem; color: #1e293b;">Weekly Report</strong>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">Báo cáo thống kê hàng tuần.</span>
                                </div>
                            </div>
                            <input type="checkbox" id="notif_weekly" class="adm-notif-switch" <?= $notif_weekly ? 'checked' : '' ?> style="width: 18px; height: 18px; cursor: pointer;">
                        </div>
                    </div>
                </div>              

                <!-- Change password -->
                <div class="adm-form-card">
                    <h3>Change Password</h3>
                    <form id="changePasswordForm">
                    <div class="adm-field">
                        <label>Current Password</label>
                        <input type="password" name="current_password" id="cur_pw">
                    </div>
                    <div class="adm-form-grid2">
                        <div class="adm-field">
                            <label>New Password</label>
                            <input type="password" name="new_password" id="new_pw">
                        </div>
                        <div class="adm-field">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" id="conf_pw">
                        </div>
                    </div>
                    <div class="adm-form-actions">
                        <button type="submit" class="adm-btn adm-btn-outline">Change Password</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
</div>
</div>

<script>
// Avatar file name display
document.getElementById('avatarFile').addEventListener('change', function() {
    document.getElementById('avatarFileName').textContent = this.files[0]?.name || 'no file selected';
    uploadAdminAvatar(this);
});

// Upload avatar via API
function uploadAdminAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const fd = new FormData();
    fd.append('avatar', input.files[0]);
    fetch('/smart-recipes/backend/api/update_avatar.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                document.querySelector('.adm-profile-avatar-img').src = d.new_url;
                alert('Avatar updated!');
            } else {
                alert('Error: ' + d.message);
            }
        })
        .catch(() => alert('Network error'));
}

// Update profile form
document.getElementById('adminProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    fetch('/smart-recipes/backend/api/update_profile.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                alert('Profile updated!');
                location.reload();
            } else {
                alert('Error: ' + d.message);
            }
        })
        .catch(() => alert('Network error'));
});

// Change password form
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const newPw  = document.getElementById('new_pw').value;
    const confPw = document.getElementById('conf_pw').value;
    if (newPw !== confPw) { alert('New passwords do not match!'); return; }
    if (newPw.length < 6)  { alert('Password must be at least 6 characters.'); return; }

    const fd = new FormData(this);
    fetch('/smart-recipes/backend/api/change_password.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                alert('Password changed successfully!');
                this.reset();
            } else {
                alert('Error: ' + d.message);
            }
        })
        .catch(() => alert('Network error'));
});

// Notification toggles — save to DB
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.adm-notif-switch').forEach(sw => {
        sw.addEventListener('change', function() {
            var fd = new FormData();
            fd.append('admin_' + this.id, this.checked ? 'true' : 'false');
            fetch('/smart-recipes/backend/api/save_settings.php', { method: 'POST', body: fd });
        });
    });
});
</script>
</body></html>