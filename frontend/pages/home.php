<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$allRecipes = get_all_recipes();
$allCategories = get_all_categories();

$trendingRecipes = array_values(array_filter($allRecipes, function ($recipe) {
    return !empty($recipe['trending']);
}));

$fanFavorites = array_values(array_filter($allRecipes, function ($recipe) {
    return !empty($recipe['favorite']);
}));

// Map categories to look like collections for the frontend
$mappedCategories = array_map(function($cat) {
    return [
        'id' => $cat['slug'],
        'title' => $cat['name'],
        'banner_title' => strtoupper($cat['name']),
        'subtitle' => $cat['description'],
        'image' => $cat['image'] ?? 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=800&h=500&fit=crop', // Default fallback image
        'description' => $cat['description'],
        'category' => 'CATEGORY'
    ];
}, $allCategories);

$cravingCollections = array_slice($mappedCategories, 0, 3);
$dontMissCollections = array_slice($mappedCategories, 3, 5);
$featuredCollection = count($mappedCategories) > 8 ? $mappedCategories[8] : ($mappedCategories[0] ?? null);
$pageTitle = 'Food. - Discover What to Cook Today';
$additionalScripts = ['/smart-recipes/frontend/assets/js/pages/home_v2.js'];
include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero" style="background-image: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1480&h=600&fit=crop&q=80');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Discover What to Cook Today</h1>
        <p class="hero-subtitle">Discover thousands of delicious recipes, connect with home cooks, and share your culinary creations.</p>
        
        <div class="hero-search">
            <form class="search-form" id="hero-search-form" autocomplete="off">
        <input 
        type="text" 
        class="search-input hero-search-input" 
        placeholder="I WANT TO MAKE" 
        id="hero-search-input"
        data-logged-in="<?= is_logged_in() ? 'true' : 'false' ?>"
        autocomplete="off"
        autocorrect="off"
        autocapitalize="off"
        spellcheck="false"
    >
    <button type="submit" class="search-button">Search</button>
</form>
        </div>
    </div>
</section>

<!-- Trending Now Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">TRENDING NOW</h2>
            <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?trending=true" class="section-link">View all</a>
        </div>
        <div class="recipe-grid" id="trending-recipes"></div>
    </div>
</section>

<!-- What We're Craving Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">WHAT WE'RE CRAVING</h2>
            <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=categories" class="section-link">View all</a>
        </div>

        <div class="collection-grid-wrapper">
            <div class="collection-grid" id="craving-collections"></div>
        </div>
    </div>
</section>

<!-- Don't Miss Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">DON'T MISS</h2>
            <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?view=dont-miss" class="section-link">View all</a>
        </div>
        <div class="dont-miss-grid-wrapper" style="display: flex; justify-content: center; width: 100%;">
            <div class="dont-miss-grid" id="dont-miss-collections" style="display: flex; justify-content: center; gap: 2.5rem; flex-wrap: wrap; width: 100%; max-width: 1200px;"></div>
        </div>
    </div>
</section>

<!-- Large Collection Section -->
<section class="section">
    <div class="container">
        <?php if ($featuredCollection): ?>
        <a href="/smart-recipes/frontend/pages/recipes/collection.php?id=<?= htmlspecialchars($featuredCollection['id']) ?>"
           style="text-decoration:none;color:inherit;display:block;">
            <div class="large-collection">
                <div class="large-collection-image">
                    <img src="<?= htmlspecialchars($featuredCollection['image']) ?>"
                         alt="<?= htmlspecialchars($featuredCollection['title']) ?>">
                </div>
                <div class="large-collection-content">
                    <p class="large-collection-category">CATEGORY</p>
                    <h2 class="large-collection-title"><?= htmlspecialchars(strtoupper($featuredCollection['title'])) ?></h2>
                    <p class="large-collection-description"><?= htmlspecialchars($featuredCollection['description']) ?></p>
                </div>
            </div>
        </a>
        <?php endif; ?>
    </div>
</section>

<!-- Fan Favourites Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">FAN FAVOURITES</h2>
            <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php?favorites=true" class="section-link">View all</a>
        </div>
        <div class="recipe-grid" id="fan-favorites"></div>
    </div>
</section>

<script>
window.homepageData = {
    trendingRecipes: <?php echo json_encode($trendingRecipes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    fanFavorites: <?php echo json_encode($fanFavorites, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    cravingCollections: <?php echo json_encode($cravingCollections, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    dontMissCollections: <?php echo json_encode($dontMissCollections, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    allRecipes: <?php echo json_encode(array_values($allRecipes), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
};
</script>

<!-- Newsletter Section -->
<?php include __DIR__ . '/../includes/newsletter.php'; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>