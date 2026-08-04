<?php
// navbar.php — requires bootstrap.php to be loaded first
$_nav_user   = current_user();
$_isLoggedIn = is_logged_in();

/*
|--------------------------------------------------------------------------
| Category URL helpers
|--------------------------------------------------------------------------
*/

if (!function_exists('category_url_by_name')) {
    function category_url_by_name($menuTitle, $fallback = '/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories') {
        static $lookup = null;

        if ($lookup === null) {
            $lookup = [];
            if (function_exists('get_all_categories')) {
                $allCategories = get_all_categories();
                foreach ($allCategories as $category) {
                    $title = $category['name'] ?? '';
                    $slug  = $category['slug'] ?? '';
                    if ($title !== '' && $slug !== '') {
                        $key = strtolower(trim($title));
                        $lookup[$key] = '/smart-recipes/frontend/pages/recipes/collection.php?id=' . urlencode($slug);
                    }
                }
            }
        }

        $menuKey = strtolower(trim($menuTitle));
        return $lookup[$menuKey] ?? $fallback;
    }
}

// Generate the dropdown menus dynamically from the categories table
$allCategories = function_exists('get_all_categories') ? get_all_categories() : [];
$recipesMenu = [];
foreach ($allCategories as $cat) {
    $recipesMenu[] = [
        'label' => $cat['name'],
        'url' => '/smart-recipes/frontend/pages/recipes/collection.php?id=' . urlencode($cat['slug'])
    ];
}

// Since we don't have enough categories to split them into Popular, Healthy, Seasonal etc.,
// we will just reuse them or show empty for now, but keeping the menu structure
$popularMenu = array_slice($recipesMenu, 0, 3);
$healthyMenu = array_slice($recipesMenu, 3, 3);
$seasonalMenu = array_slice($recipesMenu, 6, 3);
?>

<nav class="navbar">
    <div class="navbar-container">

        <!-- Logo -->
        <a href="/smart-recipes/frontend/pages/home.php" class="navbar-logo">
            Food<span class="dot">.</span>
        </a>

        <!-- Navigation Menu -->
        <ul class="navbar-menu">

            <!-- Recipes Dropdown -->
            <li class="navbar-item">
                <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="navbar-link">Recipes</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-heading">All categories</li>
                    <?php foreach ($recipesMenu as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="dropdown-see-more">See more</a>
                    </li>
                </ul>
            </li>

            <!-- Popular Dropdown -->
            <li class="navbar-item">
                <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?trending=true" class="navbar-link">Popular</a>
                <ul class="dropdown-menu">
                    <li class="dropdown-heading">Trending now</li>
                    <?php foreach ($popularMenu as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?trending=true" class="dropdown-see-more">See more</a>
                    </li>
                </ul>
            </li>

            <!-- Healthy & Diet Dropdown -->
            <li class="navbar-item">
                <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="navbar-link">Healthy &amp; Diet</a>
                <ul class="dropdown-menu">
                    <?php foreach ($healthyMenu as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li>
                        <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="dropdown-see-more">See more</a>
                    </li>
                </ul>
            </li>

            <!-- Seasonal Dropdown -->
            <li class="navbar-item">
                <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="navbar-link">Seasonal</a>
                <ul class="dropdown-menu">
                    <?php foreach ($seasonalMenu as $item): ?>
                        <li>
                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                <?= htmlspecialchars($item['label']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

        </ul>

        <!-- Right Section -->
        <div class="navbar-right">

            <!-- Search -->
            <div class="navbar-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" placeholder="Search recipes" id="navbar-search-input">
            </div>

<?php if ($_isLoggedIn && $_nav_user): ?>

    <?php if (is_admin()): ?>
    <!-- ══ ADMIN: Direct link to dashboard ══ -->
    <a href="/smart-recipes/frontend/pages/admin/dashboard.php" class="user-avatar-btn" style="text-decoration:none;">
        <img
            src="<?= htmlspecialchars($_nav_user['avatar']) ?>"
            alt="<?= htmlspecialchars($_nav_user['display_name']) ?>"
            class="user-avatar-img"
        >
    </a>

    <?php else: ?>
    <!-- ══ USER: Notifications + Avatar ══ -->
    <div style="display:flex; align-items:center; gap:1.5rem;">
        
        <!-- Notification Bell -->
        <div class="notif-menu" id="notifMenu" style="position:relative;">
            <button class="notif-btn" id="notifBtn" aria-label="Notifications" style="background:none; border:none; cursor:pointer; position:relative; color:#FFFFFF; display:flex; align-items:center; padding:0.2rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span class="notif-badge" id="notifBadge" style="display:none; position:absolute; top:-2px; right:-2px; background:#EF4444; color:#fff; font-size:0.65rem; font-weight:bold; height:16px; min-width:16px; border-radius:10px; line-height:16px; text-align:center; padding:0 4px;"></span>
            </button>
            <div class="notif-dropdown-panel" id="notifDropdown" role="menu" style="display:none; position:absolute; top:calc(100% + 10px); right:-10px; width:320px; background:#fff; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); border:1px solid #E5E7EB; z-index:1000; overflow:hidden;">
                <div style="padding:1rem; border-bottom:1px solid #E5E7EB; display:flex; justify-content:space-between; align-items:center;">
                    <h4 style="margin:0; font-size:1rem; font-weight:700;">Notifications</h4>
                </div>
                <div id="notifList" style="max-height:350px; overflow-y:auto; padding:0;">
                    <div style="padding:1.5rem; text-align:center; color:#6B7280; font-size:0.875rem;">Loading...</div>
                </div>
            </div>
        </div>

        <!-- User Avatar + Dropdown -->
        <div class="user-menu" id="userMenu">
            <button class="user-avatar-btn" id="userMenuBtn"
                    aria-expanded="false" aria-haspopup="true">
                <img
                    src="<?= htmlspecialchars($_nav_user['avatar']) ?>"
                    alt="<?= htmlspecialchars($_nav_user['display_name']) ?>"
                    class="user-avatar-img"
                >
        </button>
        <div class="user-dropdown-panel" id="userDropdown" role="menu">
            <div class="udp-header">
                <img src="<?= htmlspecialchars($_nav_user['avatar']) ?>"
                     alt="<?= htmlspecialchars($_nav_user['display_name']) ?>"
                     class="udp-avatar">
                <span class="udp-name"><?= htmlspecialchars($_nav_user['display_name']) ?></span>
            </div>
            <div class="udp-divider"></div>
            <a class="udp-item" href="/smart-recipes/frontend/pages/user/profile.php" role="menuitem">
                <span class="udp-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                <span>Edit profile</span>
                <span class="udp-chevron"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>
            <a class="udp-item" href="/smart-recipes/frontend/pages/user/account_settings.php" role="menuitem">
                <span class="udp-icon"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
                <span>Account settings and privacy</span>
                <span class="udp-chevron"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>
            <div class="udp-divider"></div>
            <a class="udp-item udp-item--logout" href="/smart-recipes/frontend/pages/auth/logout.php" role="menuitem">
                <span class="udp-icon udp-icon--red"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></span>
                <span>Log out</span>
                <span class="udp-chevron udp-chevron--red"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
            </a>
        </div>
    </div>
    </div> <!-- Close flex wrapper -->
    <?php endif; ?>

<?php else: ?>
    <div class="navbar-auth">
        <a href="/smart-recipes/frontend/pages/auth/sign_in.php" class="btn-login">Login</a>
        <a href="/smart-recipes/frontend/pages/auth/sign_up.php" class="btn-register">Register</a>
    </div>
<?php endif; ?>

        </div><!-- /.navbar-right -->
    </div><!-- /.navbar-container -->
</nav>

<script src="/smart-recipes/frontend/assets/js/components/navbar.js?v=<?= time() ?>"></script>