<?php
/**
 * pages/search/by_ingredients.php
 * Trang kết quả tìm kiếm theo nguyên liệu (tính năng SMART).
 */
require_once '../../includes/bootstrap.php';

// ── Auth guard ────────────────────────────────────────────────────────────────
if (!is_logged_in()) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("Location: /smart-recipes/frontend/pages/auth/sign_in.php?redirect={$redirect}");
    exit;
}

// ── Input ─────────────────────────────────────────────────────────────────────
$rawQuery   = trim($_GET['ingredients'] ?? '');
$allRecipes = get_all_recipes();

// ── Page meta ─────────────────────────────────────────────────────────────────
$pageTitle        = $rawQuery
    ? htmlspecialchars($rawQuery) . ' – Smart Search | Food.'
    : 'Smart Search | Food.';
$additionalScripts = [
    '/smart-recipes/frontend/assets/js/pages/search_ingredients.js',
];
$additionalStyles  = [
    '/smart-recipes/frontend/assets/css/pages/search.css',
];

include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<!-- ── Header tìm kiếm tối ── -->
<div class="si-header">
    <div class="container">
        <p class="si-header-lead">
        LET'S MAKE
        <strong class="si-brand">FOOD<span class="si-brand-dot">.</span></strong>
        WITH YOUR OWN INGREDIENTS
</p>
        <form class="si-search-bar" id="ingredient-search-form" autocomplete="off">
            <input
                type="text"
                class="si-search-input"
                id="ingredient-search-input"
                placeholder="Meat, noodle"
                autocomplete="off"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
            >
            <button type="submit" class="si-search-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Search
            </button>
        </form>
    </div>
</div>

<!-- ── Strip số kết quả ── -->
<div class="si-results-strip">
    <div class="container">
        <h1 class="si-result-count-title">
            <span id="result-count">0</span> RESULTS
        </h1>
        <div class="si-sort-row">
            <label for="sort-select">Sort by:</label>
            <select id="sort-select" class="si-sort-select">
                <option value="relevance">Relevance</option>
                <option value="rating">Highest rated</option>
                <option value="time">Quickest</option>
                <option value="newest">Newest</option>
            </select>
        </div>
    </div>
</div>

<!-- ── Grid kết quả ── -->
<div class="si-grid-wrapper">
    <div class="si-grid" id="search-results-grid"></div>

    <div class="si-no-results" id="no-results">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <h3>No recipes found</h3>
        <p>Try different ingredients or fewer keywords, e.g. <em>chicken, tomato</em></p>
    </div>
</div>

<!-- ── Data injection ── -->
<script>
window.searchPageData = {
    recipes:    <?= json_encode(array_values($allRecipes), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
    query:      <?= json_encode($rawQuery) ?>,
    isLoggedIn: true
};
</script>

<?php include '../../includes/newsletter.php'; ?>
<?php include '../../includes/footer.php'; ?>