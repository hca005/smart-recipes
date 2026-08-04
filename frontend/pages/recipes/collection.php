<?php
require_once '../../includes/bootstrap.php';

$categoryId = $_GET['id'] ?? '';
$category   = find_category_by_slug($categoryId);

if (!$category) {
    // Fallback: show first category
    $allCats    = get_all_categories();
    $category   = $allCats[0] ?? null;
}

if (!$category) {
    header('Location: /smart-recipes/frontend/pages/home.php');
    exit;
}

// Map category to look like collection for UI template
$collection = [
    'title' => $category['name'],
    'banner_title' => strtoupper($category['name']),
    'subtitle' => $category['description'],
    'image' => $category['image'] ?? 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=800&h=500&fit=crop',
    'description' => $category['description']
];

// Get recipes for this category
$collectionRecipes = [];
$allRecipes = get_all_recipes();
foreach ($allRecipes as $recipe) {
    if (($recipe['category'] ?? '') === $category['slug']) {
        $collectionRecipes[] = $recipe;
    }
}

$pageTitle        = htmlspecialchars($collection['title']) . ' – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/all_recipes.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<style>
/* ── Collection page specific ── */
.col-page { background: #fff; min-height: calc(100vh - 150px); }

/* Yellow banner header */
.col-banner-header {
    background: #FCD34D;
    padding: 2rem 0 1.75rem;
}
.col-banner-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}
.col-banner-title {
    font-size: 2rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #000;
    margin: 0 0 0.5rem;
}
.col-banner-sub {
    font-size: 0.9rem;
    color: #555;
    max-width: 500px;
    margin: 0;
    line-height: 1.55;
}

/* Hero image */
.col-hero-wrap {
    max-width: 1280px;
    margin: 2rem auto 0;
    padding: 0 2rem;
}
.col-hero-img-container {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    width: 100%;
    height: 380px;
}
.col-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.col-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.1) 60%);
    display: flex;
    align-items: flex-end;
    padding: 2rem 2.5rem;
}
.col-hero-text {
    color: #fff;
    font-size: 1.75rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1.2;
    max-width: 480px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.4);
}
.col-hero-text::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: #FCD34D;
    margin-top: 0.75rem;
    border-radius: 2px;
}

/* Recipe grid section */
.col-grid-section {
    max-width: 1280px;
    margin: 2.5rem auto 4rem;
    padding: 0 2rem;
}

/* Breadcrumb */
.col-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8125rem;
    color: #9CA3AF;
    margin-bottom: 1.5rem;
}
.col-breadcrumb a {
    color: #9CA3AF;
    text-decoration: none;
    transition: color 0.15s;
}
.col-breadcrumb a:hover { color: #374151; }
.col-breadcrumb .sep { color: #D1D5DB; }
.col-breadcrumb .current { color: #374151; font-weight: 500; }

/* Count badge */
.col-count {
    font-size: 0.8125rem;
    color: #6B7280;
    margin-bottom: 1.5rem;
    font-weight: 500;
}
.col-grid-section .recipes-grid {
    margin-bottom: 0;
}

.col-grid-section .recipe-card {
    height: 100%;
}

/* Responsive */
@media (max-width: 1024px) {
    .col-recipe-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
}

@media (max-width: 640px) {
    .col-recipe-card {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .col-recipe-img-wrap {
        width: 100%;
        height: 180px;
        border-radius: 10px;
    }

    .col-recipe-body {
        height: auto;
        padding: 0 0 0.2rem;
    }

    .col-banner-title {
        font-size: 1.4rem;
    }

    .col-hero-img-container {
        height: 220px;
    }

    .col-hero-text {
        font-size: 1.2rem;
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .col-recipe-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .col-hero-img-container { height: 300px; }
}
@media (max-width: 640px) {
    .col-recipe-grid { grid-template-columns: 1fr; gap: 1.25rem; }
    .col-banner-title { font-size: 1.4rem; }
    .col-hero-img-container { height: 220px; }
    .col-hero-text { font-size: 1.2rem; }
}
</style>

<div class="col-page">

    <!-- ── Yellow header banner ── -->
    <div class="col-banner-header">
        <div class="col-banner-inner">
            <h1 class="col-banner-title"><?= htmlspecialchars($collection['title']) ?></h1>
            <?php if (!empty($collection['subtitle'])): ?>
            <p class="col-banner-sub"><?= htmlspecialchars($collection['subtitle']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Hero image with overlay text ── -->
    <div class="col-hero-wrap">
        <div class="col-hero-img-container">
            <img
                src="<?= htmlspecialchars($collection['image']) ?>"
                alt="<?= htmlspecialchars($collection['title']) ?>"
                class="col-hero-img"
            >
            <div class="col-hero-overlay">
                <p class="col-hero-text"><?= htmlspecialchars($collection['banner_title'] ?? $collection['title']) ?></p>
            </div>
        </div>
    </div>

    <!-- ── Recipe grid ── -->
    <div class="col-grid-section">

        <!-- Breadcrumb -->
        <nav class="col-breadcrumb">
            <a href="/smart-recipes/frontend/pages/home.php">Home</a>
            <span class="sep">/</span>
            <a href="/smart-recipes/frontend/pages/home.php">Collections</a>
            <span class="sep">/</span>
            <span class="current"><?= htmlspecialchars($collection['title']) ?></span>
        </nav>

        <!-- Count -->
        <p class="col-count"><?= count($collectionRecipes) ?> recipes in this collection</p>

<!-- Grid -->
<div class="recipes-grid">
    <?php foreach ($collectionRecipes as $recipe):
        $rating = (int)($recipe['rating'] ?? 4);
    ?>
    <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$recipe['id'] ?>"
       class="recipe-card">

        <div class="recipe-card-image">
            <img
                src="<?= htmlspecialchars($recipe['image']) ?>"
                alt="<?= htmlspecialchars($recipe['title']) ?>"
                loading="lazy"
            >
        </div>

        <div class="recipe-card-body">
            <h3 class="recipe-card-title"><?= htmlspecialchars($recipe['title']) ?></h3>

            <p class="recipe-card-author">
                By <?= htmlspecialchars($recipe['author'] ?? 'Admin') ?>
            </p>

            <div class="recipe-card-meta">
                <div class="recipe-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= $i <= $rating ? 'filled' : '' ?>">★</span>
                    <?php endfor; ?>
                </div>

                <?php if (!empty($recipe['ready_in'])): ?>
                <div class="recipe-time">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span><?= htmlspecialchars($recipe['ready_in']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="recipe-card-border"></div>
    </a>
    <?php endforeach; ?>
</div>
    </div>

<?php
$additionalScripts = [];
include '../../includes/footer.php';
?>