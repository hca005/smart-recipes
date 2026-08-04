<?php
require_once '../../includes/bootstrap.php';
require_admin();

$_adm_page = 'categories_tags';

// Dùng ống hút lôi toàn bộ Danh mục từ Database lên
$result_cats = null;
if ($conn) {
    try {
        $query_cats = "SELECT c.*, (SELECT COUNT(id) FROM recipes r WHERE r.category_id = c.id) as recipe_count FROM categories c ORDER BY c.id DESC";
        $result_cats = $conn->query($query_cats);
    } catch (Throwable $e) {
        $result_cats = null;
    }
}

$demoCategories = [];
if ($result_cats && $result_cats->num_rows > 0) {
    while($row = $result_cats->fetch_assoc()) {
        // Gom dữ liệu từ DB vào mảng cũ của Linh. 
        // Dùng dấu ?? để đề phòng Database chưa có cột icon/color thì nó lấy mặc định, không làm vỡ giao diện!
        $demoCategories[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'slug' => $row['slug'] ?? 'slug',
            'description' => $row['description'] ?? 'Chưa có mô tả',
            'icon' => $row['icon'] ?? 'fa fa-utensils',
            'color' => $row['color'] ?? '#93C5FD',
            'recipe_count' => $row['recipe_count'] ?? 0
        ];
    }
}
if (empty($demoCategories)) {
    $demoCategories = [
        ['id' => 1, 'name' => 'Breakfast', 'slug' => 'breakfast', 'description' => 'Morning meals', 'icon' => 'fa fa-coffee', 'color' => '#FCD34D', 'recipe_count' => 5],
        ['id' => 2, 'name' => 'Lunch', 'slug' => 'lunch', 'description' => 'Quick lunches', 'icon' => 'fa fa-utensils', 'color' => '#6EE7B7', 'recipe_count' => 8],
        ['id' => 3, 'name' => 'Dinner', 'slug' => 'dinner', 'description' => 'Hearty dinners', 'icon' => 'fa fa-drumstick-bite', 'color' => '#93C5FD', 'recipe_count' => 10],
    ];
}

$result_tags = null;
if ($conn) {
    try {
        $query_tags = "SELECT t.*, (SELECT COUNT(recipe_id) FROM recipe_tags rt WHERE rt.tag_id = t.id) as tag_count FROM tags t ORDER BY t.id DESC";
        $result_tags = $conn->query($query_tags);
    } catch (Throwable $e) {
        $result_tags = null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Categories &amp; Tags – Food. Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Rammetto+One&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/variables.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/base/reset.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/pages/admin.css">
<link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/footer.css">
<style>
/* ── Categories & Tags extra styles ──────────────────── */
.ct-create-card {
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.75rem;
}
.ct-create-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 1.25rem;
}
.ct-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}
.ct-field { display: flex; flex-direction: column; gap: 0.3rem; }
.ct-field label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #374151;
}
.ct-field input[type="text"],
.ct-field input[type="email"],
.ct-field textarea,
.ct-field select {
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    padding: 0.6rem 0.85rem;
    font-size: 0.875rem;
    font-family: inherit;
    color: #111827;
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
    width: 100%;
}
.ct-field input:focus,
.ct-field textarea:focus {
    border-color: #FCD34D;
    box-shadow: 0 0 0 3px rgba(252,211,77,0.18);
}
.ct-field textarea { resize: vertical; min-height: 72px; }
.ct-color-row { display: flex; align-items: center; gap: 0.6rem; }
.ct-color-swatch {
    width: 100%;
    height: 40px;
    border-radius: 6px;
    border: 1px solid #E5E7EB;
    background: #FCD34D;
    cursor: pointer;
}
.ct-btn-create {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #FCD34D;
    color: #000;
    border: none;
    border-radius: 6px;
    padding: 0.6rem 1.25rem;
    font-size: 0.875rem;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: background 0.15s;
}
.ct-btn-create:hover { background: #F59E0B; }

/* Existing categories section */
.ct-existing-header {
    display: flex-wrap;
    align-items: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}
.ct-existing-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}
.ct-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #FCD34D;
    color: #000;
    font-size: 0.72rem;
    font-weight: 800;
}
.ct-cat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
@media (max-width: 900px) { .ct-cat-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 580px) { .ct-cat-grid { grid-template-columns: 1fr; } }

.ct-cat-card {
    background: #fff;
    border: 1px solid #e8eaed;
    border-radius: 10px;
    padding: 1rem 1.1rem 0.85rem;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}
.ct-cat-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 0.35rem;
}
.ct-cat-icon-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.ct-cat-icon {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    background: #EFF6FF;
    border: 1.5px solid #BFDBFE;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ct-cat-icon.pink  { background: #FFF0F0; border-color: #FCA5A5; }
.ct-cat-icon svg   { display: block; }

.ct-cat-name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #111827;
}
.ct-cat-count {
    font-size: 0.8rem;
    color: #9CA3AF;
    font-weight: 500;
    flex-shrink: 0;
}
.ct-cat-desc {
    font-size: 0.8rem;
    color: #6B7280;
    margin: 0;
    line-height: 1.45;
}
.ct-cat-slug {
    font-size: 0.75rem;
    color: #9CA3AF;
    margin: 0.15rem 0 0.75rem;
}
.ct-cat-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
    padding-top: 0.5rem;
    border-top: 1px solid #f5f5f5;
}
.ct-icon-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.12s;
    color: #6B7280;
    text-decoration: none;
}
.ct-icon-btn:hover { background: #f5f5f5; }
.ct-icon-btn.red   { color: #EF4444; }
.ct-icon-btn.red:hover { background: #FEF2F2; }
.ct-icon-btn.blue  { color: #3B82F6; }
.ct-icon-btn.blue:hover { background: #EFF6FF; }
</style>
</head>
<body class="adm-body">
<div class="adm-layout">
<?php include '../../includes/admin_sidebar.php'; ?>

<div class="adm-main">
    <!-- Topbar -->
    <header class="adm-topbar">
        <div class="adm-topbar-left">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <span>Dashboards</span><span class="adm-topbar-sep">/</span><span class="adm-tb-active">Default</span>
        </div>
        <div class="adm-topbar-spacer"></div>
        <div class="adm-tb-search">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            Search
            <span style="margin-left:auto;font-size:0.7rem;background:#f0f0f0;padding:1px 5px;border-radius:3px;color:#aaa;">/</span>
        </div>
        <div class="adm-tb-icons">
            <button class="adm-tb-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </button>
            <button class="adm-tb-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            </button>
            <button class="adm-tb-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            <button class="adm-tb-icon">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </button>
        </div>
    </header>

    <div class="adm-content">
        <!-- Page Header -->
        <div class="adm-page-header">
            <div>
                <h1 class="adm-page-title">Categories &amp; Tags Management</h1>
                <p class="adm-page-sub">Manage recipe categories and tags</p>
            </div>
        </div>

        <!-- Create New Category -->
        <div class="ct-create-card">
            <p class="ct-create-title">Create New Category</p>

            <div class="ct-grid-2">
                <div class="ct-field">
                    <label>Category Name</label>
                    <input type="text" id="newCatName" placeholder="e.g., Desserts, Main Course">
                </div>
                <div class="ct-field">
                    <label>Slug (URL-friendly)</label>
                    <input type="text" id="newCatSlug" placeholder="e.g., desserts, main-course">
                </div>
            </div>

            <div class="ct-field" style="margin-bottom:1rem;">
                <label>Description</label>
                <textarea id="newCatDesc" placeholder="Brief description of this category"></textarea>
            </div>

            <div class="ct-grid-2" style="margin-bottom:1.25rem;">
                <div class="ct-field">
                    <label>Icon Class (FontAwesome)</label>
                    <input type="text" id="newCatIcon" value="fa fa-utensils">
                </div>
                <div class="ct-field">
                    <label>Color</label>
                    <div class="ct-color-row">
                        <div class="ct-color-swatch" id="colorSwatch"
                             onclick="document.getElementById('colorPicker').click()"
                             style="background:#FCD34D;"></div>
                        <input type="color" id="colorPicker" value="#FCD34D"
                               style="opacity:0;width:0;position:absolute;"
                               oninput="document.getElementById('colorSwatch').style.background=this.value;">
                    </div>
                </div>
            </div>

            <button class="ct-btn-create" onclick="createCategory()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Create Category
            </button>
        </div>

        <!-- Existing Categories -->
        <div class="ct-existing-header">
            <h2 class="ct-existing-title">Existing Categories</h2>
            <span class="ct-count-badge"><?= count($demoCategories) ?></span>
        </div>

        <div class="ct-cat-grid" id="categoriesGrid">
            <?php foreach ($demoCategories as $cat):
                $isPink = $cat['color'] === '#FCA5A5';
            ?>
            <div class="ct-cat-card" id="cat-<?= (int)$cat['id'] ?>">
                <div class="ct-cat-card-top">
                    <div class="ct-cat-icon-name">
                        <div class="ct-cat-icon <?= $isPink ? 'pink' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                 fill="none" stroke="<?= $isPink ? '#EF4444' : '#3B82F6' ?>" stroke-width="2">
                                <path d="M3 11l19-9-9 19-2-8-8-2z"/>
                            </svg>
                        </div>
                        <span class="ct-cat-name"><?= htmlspecialchars($cat['name']) ?></span>
                    </div>
                    <span class="ct-cat-count"><?= (int)$cat['recipe_count'] ?></span>
                </div>

                <p class="ct-cat-desc"><?= htmlspecialchars($cat['description']) ?></p>
                <p class="ct-cat-slug">Slug: <?= htmlspecialchars($cat['slug']) ?></p>

                <div class="ct-cat-actions">
                    <button class="ct-icon-btn red" onclick="deleteCategory(<?= (int)$cat['id'] ?>)" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                    </button>
                    <button class="ct-icon-btn blue" onclick="editCategory(<?= (int)$cat['id'] ?>)" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tags Section -->
        <div style="margin-top: 2rem;">
            <div class="ct-existing-header" style="margin-bottom: 1rem;">
                <h2 class="ct-existing-title">Tags Management</h2>
            </div>

            <!-- Add new tag -->
            <div class="ct-create-card">
                <p class="ct-create-title">Add New Tag</p>
                <div style="display: flex; gap: 0.75rem; align-items: center;">
                    <input type="text" id="newTagName" class="ct-field" placeholder="e.g., Vegan, Quick & Easy"
                           style="flex:1; border:1px solid #E5E7EB; border-radius:6px; padding:0.6rem 0.85rem; font-size:0.875rem; font-family:inherit; outline:none;">
                    <button class="ct-btn-create" onclick="addTag()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add Tag
                    </button>
                </div>
            </div>

            <!-- Existing tags from DB -->
            <?php
            $tags_result = $conn->query("SELECT * FROM tags ORDER BY name ASC");
            if ($tags_result && $tags_result->num_rows > 0):
            ?>
            <div style="display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 0.5rem;">
                <?php while ($tag = $tags_result->fetch_assoc()): ?>
                <div style="display:inline-flex; align-items:center; gap:0.4rem; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:9999px; padding:0.3rem 0.85rem; font-size:0.85rem; font-weight:600; color:#374151;">
                    <?= htmlspecialchars($tag['name']) ?>
                    <button onclick="deleteTag(<?= (int)$tag['id'] ?>)"
                            style="background:none; border:none; cursor:pointer; color:#EF4444; font-size:1rem; line-height:1; padding:0; margin-left:2px;"
                            title="Delete tag">×</button>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <p style="color:#9CA3AF; font-size:0.875rem;">Chưa có tag nào.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php include '../../includes/footer.php'; ?>
</div><!-- /.adm-main -->
</div><!-- /.adm-layout -->

<!-- Edit Modal -->
<div id="editCatModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:1.5rem; border-radius:10px; width:400px; max-width:90%;">
        <h3 style="margin-top:0;">Edit Category</h3>
        <input type="hidden" id="editCatId">
        <div class="ct-field" style="margin-bottom:0.8rem;">
            <label>Name</label>
            <input type="text" id="editCatName">
        </div>
        <div class="ct-field" style="margin-bottom:0.8rem;">
            <label>Slug</label>
            <input type="text" id="editCatSlug">
        </div>
        <div class="ct-field" style="margin-bottom:0.8rem;">
            <label>Description</label>
            <textarea id="editCatDesc"></textarea>
        </div>
        <div class="ct-field" style="margin-bottom:0.8rem;">
            <label>Icon</label>
            <input type="text" id="editCatIcon">
        </div>
        <div class="ct-field" style="margin-bottom:1.5rem;">
            <label>Color</label>
            <input type="color" id="editCatColor">
        </div>
        <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('editCatModal').style.display='none'" style="padding:0.5rem 1rem; border:1px solid #ccc; background:#fff; border-radius:4px; cursor:pointer;">Cancel</button>
            <button type="button" onclick="submitEditCategory()" class="ct-btn-create" style="margin:0;">Save</button>
        </div>
    </div>
</div>

<script>
var catData = <?= json_encode($demoCategories) ?>;
var nextId = <?= !empty($demoCategories) ? (max(array_column($demoCategories, 'id')) + 1) : 1 ?>;

function createCategory() {
    // 1. Lấy dữ liệu từ các ô nhập liệu của Linh
    var name = document.getElementById('newCatName').value.trim();
    var slug = document.getElementById('newCatSlug').value.trim();
    var desc = document.getElementById('newCatDesc').value.trim();
    var icon = document.getElementById('newCatIcon').value.trim();
    var color = document.getElementById('colorPicker').value;

    if (!name) { 
        alert('Vui lòng nhập tên danh mục nhé!'); 
        return; 
    }

    // 2. Đóng gói dữ liệu lại
    var formData = new FormData();
    formData.append('name', name);
    formData.append('slug', slug);
    formData.append('description', desc);
    formData.append('icon', icon);
    formData.append('color', color);

    // 3. Gửi sang file PHP con vừa viết bằng Fetch API
    fetch('/smart-recipes/backend/api/add_category.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Nếu PHP báo thành công -> Tự động F5 lại trang để hiện danh mục mới ngay lập tức
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(err => alert('Lỗi mạng hoặc sai đường dẫn!'));
}

function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;

    var formData = new FormData();
    formData.append('category_id', id);

    fetch('/smart-recipes/backend/api/delete_category.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            var el = document.getElementById('cat-' + id);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'scale(0.9)';
                el.style.transition = 'all 0.2s';
                setTimeout(function(){ el.remove(); }, 200);
            }
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(function() { alert('Lỗi kết nối!'); });
}

function editCategory(id) {
    var cat = catData.find(c => c.id == id);
    if(cat) {
        document.getElementById('editCatId').value = cat.id;
        document.getElementById('editCatName').value = cat.name;
        document.getElementById('editCatSlug').value = cat.slug;
        document.getElementById('editCatDesc').value = cat.description;
        document.getElementById('editCatIcon').value = cat.icon;
        document.getElementById('editCatColor').value = cat.color;
        document.getElementById('editCatModal').style.display = 'flex';
    }
}

function submitEditCategory() {
    var id = document.getElementById('editCatId').value;
    var name = document.getElementById('editCatName').value.trim();
    var slug = document.getElementById('editCatSlug').value.trim();
    var desc = document.getElementById('editCatDesc').value.trim();
    var icon = document.getElementById('editCatIcon').value.trim();
    var color = document.getElementById('editCatColor').value;

    if (!name) { 
        alert('Name is required!'); 
        return; 
    }

    var formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);
    formData.append('slug', slug);
    formData.append('description', desc);
    formData.append('icon', icon);
    formData.append('color', color);

    fetch('/smart-recipes/backend/api/update_category.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Network error!'));
}

function escHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Hàm thêm Tag mới
function addTag() {
    var tagName = document.getElementById('newTagName').value.trim();
    
    if (!tagName) {
        alert("Vui lòng nhập tên Tag nhé!");
        return;
    }

    var formData = new FormData();
    formData.append('tag_name', tagName);

    fetch('/smart-recipes/backend/api/add_tag.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(err => alert('Lỗi kết nối!'));
}

// Hàm xóa Tag
function deleteTag(id) {
    if (!confirm('Xóa tag này?')) return;

    var formData = new FormData();
    formData.append('tag_id', id);

    fetch('/smart-recipes/backend/api/delete_tag.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    })
    .catch(err => alert('Lỗi kết nối!'));
}
</script>
</body>
</html>