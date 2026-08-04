<?php
$_adm_page = basename($_SERVER['PHP_SELF'], '.php');
$_adm_u    = current_user();
?>
<aside class="adm-sidebar">
    <div class="adm-logo">
        <a href="/smart-recipes/frontend/pages/admin/dashboard.php">
            Food<span class="dot">.</span><br>Admin
        </a>
    </div>

    <a href="/smart-recipes/frontend/pages/admin/profile.php" class="adm-user-btn">
        <img src="<?= htmlspecialchars($_adm_u['avatar'] ?? '/smart-recipes/frontend/assets/images/default-avatar.png') ?>"
             class="adm-user-avatar" alt="<?= htmlspecialchars($_adm_u['display_name'] ?? 'Admin') ?>">
        <span class="adm-user-name"><?= htmlspecialchars($_adm_u['display_name'] ?? 'Admin') ?></span>
    </a>

    <div class="adm-nav-group">
        <p class="adm-nav-label">Dashboards</p>
        <a href="/smart-recipes/frontend/pages/admin/dashboard.php"
           class="adm-nav-item <?= $_adm_page === 'dashboard' ? 'active' : '' ?>">
            <span class="adm-nav-dot"></span> Overview
        </a>
    </div>

    <div class="adm-nav-group">
        <p class="adm-nav-label">Pages</p>
        <a href="/smart-recipes/frontend/pages/admin/users.php"
           class="adm-nav-item <?= $_adm_page === 'users' ? 'active' : '' ?>">
            › Users
        </a>
        <a href="/smart-recipes/frontend/pages/admin/recipes.php"
           class="adm-nav-item <?= $_adm_page === 'recipes' ? 'active' : '' ?>">
            › Recipes
        </a>
        <a href="/smart-recipes/frontend/pages/admin/categories_tags.php"
           class="adm-nav-item <?= $_adm_page === 'categories_tags' ? 'active' : '' ?>">
            › Categories &amp; Tags
        </a>
        <a href="/smart-recipes/frontend/pages/admin/comments.php"
           class="adm-nav-item <?= $_adm_page === 'comments' ? 'active' : '' ?>">
            › Comments
        </a>
        <a href="/smart-recipes/frontend/pages/admin/reports.php"
           class="adm-nav-item <?= $_adm_page === 'reports' ? 'active' : '' ?>">
            › Reports
        </a>
        <a href="/smart-recipes/frontend/pages/admin/notifications.php"
           class="adm-nav-item <?= $_adm_page === 'notifications' ? 'active' : '' ?>">
            › Notifications
        </a>
        <a href="/smart-recipes/frontend/pages/admin/system_settings.php"
           class="adm-nav-item <?= $_adm_page === 'system_settings' ? 'active' : '' ?>">
            › System Settings
        </a>
    </div>
</aside>