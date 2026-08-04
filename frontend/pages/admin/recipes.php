<?php
require_once '../../includes/bootstrap.php';
require_admin();

// Lấy recipes thật từ DB
$sql = "SELECT r.id, r.title, r.main_image, r.difficulty, r.is_published, r.created_at,
               COALESCE(u.display_name, u.username) AS author,
               c.name AS category
        FROM recipes r
        LEFT JOIN users u ON r.user_id = u.id
        LEFT JOIN categories c ON r.category_id = c.id
        ORDER BY r.created_at DESC
        LIMIT 100";
$result  = $conn->query($sql);
$recipes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Recipes – Food. Admin</title>
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
        <div class="adm-topbar-left"><span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Recipes</span></div>
        <div class="adm-topbar-spacer"></div>
        <div class="adm-tb-search"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Search</div>
    </header>

    <div class="adm-content">
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Recipes Moderation</h1>
                <p class="adm-page-sub">Manage recipes sent by users (<?= count($recipes) ?> total)</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="adm-filters">
            <input type="text" class="adm-filter-input" id="recipeSearchInput" placeholder="Search recipes by title..." oninput="filterRecipes()">
        </div>

        <div class="adm-table-wrap">
            <table class="adm-table" id="recipesTable">
                <thead>
                    <tr>
                        <th style="width:36px;"><input type="checkbox" class="adm-cb" id="checkAll" onchange="document.querySelectorAll('.row-cb').forEach(c=>c.checked=this.checked)"></th>
                        <th>IMAGE</th>
                        <th>TITLE</th>
                        <th>AUTHOR</th>
                        <th>CATEGORY</th>
                        <th>DIFFICULTY</th>
                        <th>DATE</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($recipes)): ?>
                <tr><td colspan="9" style="text-align:center;padding:30px;color:#9CA3AF;">Chưa có công thức nào.</td></tr>
                <?php else: ?>
                <?php foreach ($recipes as $r):
                    if (!empty($r['main_image'])) {
                        $img = (strpos($r['main_image'], 'http') === 0) 
                            ? htmlspecialchars($r['main_image']) 
                            : '/smart-recipes/frontend/assets/images/recipes/' . htmlspecialchars($r['main_image']);
                    } else {
                        $img = '';
                    }
                    $status = $r['is_published'] ? 'Approved' : 'Pending';
                    $badgeClass = $r['is_published'] ? 'adm-badge-ok' : 'adm-badge-warn';
                ?>
                <tr data-id="<?= (int)$r['id'] ?>">
                    <td><input type="checkbox" class="adm-cb row-cb" value="<?= (int)$r['id'] ?>"></td>
                    <td>
                        <?php if ($img): ?>
                        <img src="<?= $img ?>" class="adm-recipe-thumb" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        <?php else: ?>
                        <div class="adm-recipe-thumb-ph" style="width:50px;height:50px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#9CA3AF;">?</div>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight:600;color:#111827;max-width:200px;"><?= htmlspecialchars($r['title']) ?></td>
                    <td><?= htmlspecialchars($r['author'] ?? '–') ?></td>
                    <td><?= htmlspecialchars($r['category'] ?? '–') ?></td>
                    <td style="text-transform:capitalize;"><?= htmlspecialchars($r['difficulty'] ?? 'Medium') ?></td>
                    <td style="color:#9CA3AF;font-size:0.78rem;"><?= date('Y-m-d', strtotime($r['created_at'])) ?></td>
                    <td><span class="adm-badge <?= $badgeClass ?>"><?= $status ?></span></td>
                    <td>
                        <div style="display:flex;gap:0.4rem;">
                            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$r['id'] ?>" class="adm-btn adm-btn-outline" target="_blank">View</a>
                            <?php if (!$r['is_published']): ?>
                            <button class="adm-btn" style="background:#FCD34D;color:#000;border:none;" onclick="updateRecipeStatus(<?= (int)$r['id'] ?>, 'approve')">Approve</button>
                            <?php else: ?>
                            <button class="adm-btn" style="background:#d1d5db;color:#000;border:none;" onclick="updateRecipeStatus(<?= (int)$r['id'] ?>, 'reject')">Hide</button>
                            <?php endif; ?>
                            <button class="adm-btn adm-btn-danger" onclick="deleteRecipe(<?= (int)$r['id'] ?>)">Delete</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../../includes/footer.php'; ?>
</div>
</div>

<script>
function deleteRecipe(id) {
    if (!confirm('Bạn có chắc muốn xóa công thức này? Hành động không thể hoàn tác!')) return;

    const fd = new FormData();
    fd.append('recipe_id', id);

    fetch('/smart-recipes/backend/api/delete_recipe.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const row = document.querySelector('tr[data-id="' + id + '"]');
                if (row) row.remove();
            } else {
                alert('Lỗi: ' + d.message);
            }
        })
        .catch(() => alert('Lỗi kết nối!'));
}

function updateRecipeStatus(id, status) {
    if (!confirm(`Are you sure you want to ${status} this recipe?`)) return;

    const fd = new FormData();
    fd.append('recipe_id', id);
    fd.append('status', status);

    fetch('/smart-recipes/backend/api/update_recipe_status.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                location.reload();
            } else {
                alert('Lỗi: ' + d.message);
            }
        })
        .catch(() => alert('Lỗi kết nối!'));
}

function filterRecipes() {
    const q = document.getElementById('recipeSearchInput').value.toLowerCase();
    document.querySelectorAll('#recipesTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
</body></html>
