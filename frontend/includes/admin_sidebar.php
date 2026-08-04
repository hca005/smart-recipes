<?php
$_adm_page = basename($_SERVER['PHP_SELF'], '.php');
$_adm_u    = current_user();
$_base     = defined('BASE_URL') ? BASE_URL : '/frontend';
?>
<aside class="adm-sidebar">
    <div class="adm-logo">
        <a href="<?= $_base ?>/pages/admin/dashboard.php">
            Food<span class="dot">.</span><br>Admin
        </a>
    </div>

    <a href="<?= $_base ?>/pages/admin/profile.php" class="adm-user-btn">
        <img src="<?= htmlspecialchars($_adm_u['avatar'] ?? 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=150&h=150&fit=crop') ?>"
             class="adm-user-avatar" alt="<?= htmlspecialchars($_adm_u['display_name'] ?? 'Admin') ?>">
        <span class="adm-user-name"><?= htmlspecialchars($_adm_u['display_name'] ?? 'Admin') ?></span>
    </a>

    <div class="adm-nav-group">
        <p class="adm-nav-label">Dashboards</p>
        <a href="<?= $_base ?>/pages/admin/dashboard.php"
           class="adm-nav-item <?= $_adm_page === 'dashboard' ? 'active' : '' ?>">
            <span class="adm-nav-dot"></span> Overview
        </a>
    </div>

    <div class="adm-nav-group">
        <p class="adm-nav-label">Pages</p>
        <a href="<?= $_base ?>/pages/admin/users.php"
           class="adm-nav-item <?= $_adm_page === 'users' ? 'active' : '' ?>">
            › Users
        </a>
        <a href="<?= $_base ?>/pages/admin/recipes.php"
           class="adm-nav-item <?= $_adm_page === 'recipes' ? 'active' : '' ?>">
            › Recipes
        </a>
        <a href="<?= $_base ?>/pages/admin/categories_tags.php"
           class="adm-nav-item <?= $_adm_page === 'categories_tags' ? 'active' : '' ?>">
            › Categories &amp; Tags
        </a>
        <a href="<?= $_base ?>/pages/admin/comments.php"
           class="adm-nav-item <?= $_adm_page === 'comments' ? 'active' : '' ?>">
            › Comments
        </a>
        <a href="<?= $_base ?>/pages/admin/reports.php"
           class="adm-nav-item <?= $_adm_page === 'reports' ? 'active' : '' ?>">
            › Reports
        </a>
        <a href="<?= $_base ?>/pages/admin/notifications.php"
           class="adm-nav-item <?= $_adm_page === 'notifications' ? 'active' : '' ?>">
            › Notifications
        </a>
        <a href="<?= $_base ?>/pages/admin/system_settings.php"
           class="adm-nav-item <?= $_adm_page === 'system_settings' ? 'active' : '' ?>">
            › System Settings
        </a>
    </div>
</aside>