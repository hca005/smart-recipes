<?php
require_once '../../includes/bootstrap.php';
// Redirect collection param to dedicated collection page
if (!empty($_GET['collection'])) {
    header('Location: /smart-recipes/frontend/pages/recipes/collection.php?id=' . urlencode($_GET['collection']));
    exit;
}

$pageTitle = 'All Recipes - Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/all_recipes.css'];
$additionalScripts = ['/smart-recipes/frontend/assets/js/pages/all_recipes.js'];

$recipes = get_all_recipes();

$sortBy       = $_GET['sort']       ?? 'recommended';
$collectionId = $_GET['collection'] ?? null;
$category     = $_GET['category']   ?? null;
$tag          = $_GET['tag']        ?? null;
$searchQuery  = trim($_GET['search'] ?? '');

// Search filter
if ($searchQuery) {
    $searchLower = strtolower($searchQuery);
    $searchWords = preg_split('/\s+/', $searchLower);
    
    $recipes = array_filter($recipes, function($r) use ($searchLower, $searchWords) {
        $title = strtolower($r['title'] ?? '');
        $description = strtolower($r['description'] ?? '');
        $category = strtolower($r['category'] ?? '');
        $tags = implode(' ', array_map('strtolower', $r['tags'] ?? []));
        $searchText = "$title $description $category $tags";
        
        // Check if any search word matches
        foreach ($searchWords as $word) {
            if (strlen($word) >= 2 && strpos($searchText, $word) !== false) {
                return true;
            }
        }
        return false;
    });
    
    $pageTitle = 'Search: ' . htmlspecialchars($searchQuery) . ' - Food.';
}

if ($category) {
    $recipes = array_filter($recipes, function($r) use ($category) {
        return strtolower($r['category']) === strtolower($category);
    });
}

if ($tag) {
    $recipes = array_filter($recipes, function($r) use ($tag) {
        return in_array(strtolower($tag), array_map('strtolower', $r['tags'] ?? []));
    });
}
if ($collectionId) {
    $collection = find_collection_by_id($collectionId);

    if ($collection) {
        $allowedIds = $collection['recipe_ids'] ?? [];
        $recipes = array_filter($recipes, function($r) use ($allowedIds) {
            return in_array((int)$r['id'], $allowedIds, true);
        });
    } else {
        $recipes = [];
    }
}

$recipes = array_values($recipes);

switch($sortBy) {
    case 'popular':
        usort($recipes, function($a, $b) {
            return ($b['views'] ?? 0) - ($a['views'] ?? 0);
        });
        break;
    case 'highest-rated':
        usort($recipes, function($a, $b) {
            return ($b['rating'] ?? 0) - ($a['rating'] ?? 0);
        });
        break;
    case 'newest':
        usort($recipes, function($a, $b) {
            return ($b['created_at'] ?? 0) - ($a['created_at'] ?? 0);
        });
        break;
    default:
        break;
}

include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<div class="all-recipes-page">
    <div class="recipes-header">
        <div class="container">
            <?php if ($searchQuery): ?>
                <h1 class="recipes-title">SEARCH RESULTS</h1>
                <p style="color: #6B7280; margin-top: 0.5rem;">
                    Showing results for "<strong style="color: #F59E0B;"><?= htmlspecialchars($searchQuery) ?></strong>"
                </p>
            <?php else: ?>
                <h1 class="recipes-title">ALL RECIPES</h1>
            <?php endif; ?>
        </div>
    </div>

    <div class="recipes-toolbar">
        <div class="container">
            <div class="toolbar-left">
                <span class="results-count"><?php echo count($recipes); ?> recipes found</span>
            </div>
            <div class="toolbar-right">
                <select class="sort-dropdown" id="sort-select" onchange="handleSortChange(this.value)">
                    <option value="recommended" <?php echo $sortBy === 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    <option value="popular" <?php echo $sortBy === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                    <option value="highest-rated" <?php echo $sortBy === 'highest-rated' ? 'selected' : ''; ?>>Highest Rated</option>
                    <option value="newest" <?php echo $sortBy === 'newest' ? 'selected' : ''; ?>>Newest</option>
                </select>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="recipes-grid">
            <?php foreach ($recipes as $recipe): ?>
                <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                    <div class="recipe-card-image">
                        <img src="<?php echo htmlspecialchars($recipe['image']); ?>"
                             alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    </div>
                    <div class="recipe-card-body">
                        <h3 class="recipe-card-title"><?php echo strtoupper(htmlspecialchars($recipe['title'])); ?></h3>
                        <p class="recipe-card-author">by <?php echo htmlspecialchars($recipe['author']); ?></p>

                        <div class="recipe-card-meta">
                            <div class="recipe-rating">
                                <?php $rating = $recipe['rating'] ?? 4; ?>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>

                            <div class="recipe-time">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span><?php echo htmlspecialchars($recipe['ready_in']); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="recipe-card-border"></div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (count($recipes) === 0): ?>
            <div class="no-results">
                <svg width="64" height="64" fill="none" stroke="#ccc" stroke-width="2">
                    <circle cx="32" cy="32" r="30"></circle>
                    <path d="M32 20v12M32 40h.01"></path>
                </svg>
                <h3>No recipes found</h3>
                <p>Try adjusting your filters or search criteria</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/newsletter.php'; ?>
<?php include '../../includes/footer.php'; ?>