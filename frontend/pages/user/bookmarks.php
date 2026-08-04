<?php
require_once '../../includes/bootstrap.php';
require_login();

$user_id = current_user()['id'];

// Lấy bookmarks thật từ DB
$stmt = $conn->prepare("SELECT recipe_id FROM bookmarks WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result   = $stmt->get_result();

$bookmarks = [];
while ($row = $result->fetch_assoc()) {
    $recipe = find_recipe_by_id($row['recipe_id']);
    if ($recipe) {
        $bookmarks[] = $recipe;
    }
}

$pageTitle        = 'My Bookmarks – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/profile.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<div style="max-width:1100px;margin:3rem auto;padding:0 1.5rem;">
    <h1 style="font-size:1.5rem;font-weight:900;text-transform:uppercase;margin-bottom:1.5rem;">
        My Bookmarks
    </h1>

    <?php if (empty($bookmarks)): ?>
        <div style="text-align:center;padding:4rem 0;color:#9CA3AF;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
            <p style="font-size:1rem;">You haven't saved any recipes yet.</p>
            <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php"
               style="display:inline-block;margin-top:1rem;background:#FCD34D;color:#000;padding:0.6rem 1.5rem;border-radius:9999px;font-weight:700;text-decoration:none;">
                Discover recipes
            </a>
        </div>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem;">
            <?php foreach ($bookmarks as $r):
                $time = ($r['ready_in'] ?? '30 min');
                $img  = !empty($r['image'])
                        ? htmlspecialchars($r['image'])
                        : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop';
            ?>
            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$r['id'] ?>"
               style="text-decoration:none;color:inherit;background:#fff;border-radius:12px;border:1px solid #eee;overflow:hidden;display:flex;flex-direction:column;">
                <div style="height:150px;overflow:hidden;">
                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($r['title']) ?>"
                         style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div style="padding:12px;flex:1;display:flex;flex-direction:column;gap:6px;">
                    <h3 style="font-size:0.95rem;font-weight:700;color:#111;margin:0;"><?= htmlspecialchars($r['title']) ?></h3>
                    <p style="font-size:0.8rem;color:#6B7280;margin:0;">By <?= htmlspecialchars($r['author'] ?? 'Unknown') ?></p>
                    <div style="display:flex;align-items:center;gap:6px;font-size:0.78rem;color:#9CA3AF;margin-top:auto;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <?= htmlspecialchars($time) ?>
                        &nbsp;·&nbsp;<?= htmlspecialchars($r['difficulty'] ?? 'Medium') ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
