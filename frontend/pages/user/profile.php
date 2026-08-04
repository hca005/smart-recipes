<?php
require_once '../../includes/bootstrap.php';
require_login();

$sessionUser = current_user();

// Ensure avatar key always exists in session user
if (empty($sessionUser['avatar'])) {
    $sessionUser['avatar'] = '/smart-recipes/frontend/assets/images/default-avatar.png';
}
if (empty($sessionUser['display_name'])) {
    $sessionUser['display_name'] = $sessionUser['username'] ?? 'User';
}

// 1. Lấy thông tin user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $sessionUser['id']);
$stmt->execute();
$fullUser = $stmt->get_result()->fetch_assoc() ?? $sessionUser;

$tab = $_GET['tab'] ?? 'my_recipes';

// 2. Lấy món ăn của user từ Database
$my_user_id = $sessionUser['id']; 
$query = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
$stmt_rec = $conn->prepare($query);
$stmt_rec->bind_param("i", $my_user_id);
$stmt_rec->execute();
$my_recipes_result = $stmt_rec->get_result();

$my_recipes = [];
while ($row = $my_recipes_result->fetch_assoc()) {
    $my_recipes[] = $row;
}

// 3. Lấy Comments cho tab Q&A
$qna_comments = [];
if ($tab === 'qna') {
    $stmt_ca = $conn->prepare("SELECT c.id, c.comment_text, c.created_at, c.recipe_id, r.title, r.main_image 
                               FROM comments c
                               JOIN recipes r ON c.recipe_id = r.id
                               WHERE c.user_id = ?
                               ORDER BY c.created_at DESC");
    $stmt_ca->bind_param("i", $my_user_id);
    $stmt_ca->execute();
    $ca_res = $stmt_ca->get_result();

    while ($row = $ca_res->fetch_assoc()) {
        $qna_comments[] = [
            'id'          => $row['id'],
            'recipe_name' => $row['title'],
            'recipe_id'   => $row['recipe_id'],
            'comment_text'=> $row['comment_text'],
            'time_ago'    => date('d/m/Y H:i', strtotime($row['created_at']))
        ];
    }
}

// 4. Lấy Bookmarks thật từ Database
$stmt_bm = $conn->prepare("SELECT recipe_id FROM bookmarks WHERE user_id = ? ORDER BY created_at DESC");
$stmt_bm->bind_param("i", $my_user_id);
$stmt_bm->execute();
$bm_result = $stmt_bm->get_result();

$myBookmarks = [];
while ($row = $bm_result->fetch_assoc()) {
    $recipe = find_recipe_by_id($row['recipe_id']);
    if ($recipe) {
        $myBookmarks[] = $recipe;
    }
}

$pageTitle        = htmlspecialchars($sessionUser['display_name']) . ' – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/profile.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>
<script>document.body.dataset.displayName = <?= json_encode($sessionUser['display_name']) ?>;</script>

<div class="profile-banner"></div>

<div class="profile-header-bg">
    <div class="profile-header-inner">
        <div class="ph-left">
            <div class="ph-avatar-wrap" onclick="document.getElementById('avatarInput').click()" style="cursor: pointer; position: relative;">
                <img src="<?= htmlspecialchars($fullUser['profile_image'] ?? $sessionUser['avatar']) ?>" 
                     alt="<?= htmlspecialchars($sessionUser['display_name']) ?>"
                     class="ph-avatar" id="profileDisplay">
                <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
                <input type="file" id="avatarInput" style="display: none;" accept="image/*" onchange="window.uploadAvatar(this)">
            </div>

            <?php if ($sessionUser['id'] === $fullUser['id']): ?>
                <button class="ph-follow-btn" onclick="window.openEditModal()" 
                        style="background-color: #FCD34D !important; color: #000 !important; border: none; padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer; position: relative; z-index: 10;">
                    <i class="fas fa-user-edit" style="margin-right: 5px;"></i> EDIT PROFILE
                </button>
            <?php endif; ?>
            
            <div class="ph-stats">
                <div class="ph-stat">
                    <span class="ph-stat-label">Recipes</span>
                    <span class="ph-stat-val"><?= count($my_recipes) ?></span>
                </div>
                <div class="ph-stat-sep"></div>
                <div class="ph-stat">
                    <span class="ph-stat-label">Bookmarks</span>
                    <span class="ph-stat-val"><?= count($myBookmarks) ?></span>
                </div>
            </div>
        </div>

        <div class="ph-right">
            <h1 class="ph-username"><?= htmlspecialchars($fullUser['display_name'] ?? $sessionUser['display_name']) ?></h1>
            
            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                <p class="ph-bio"><i class="fas fa-quote-left" style="color: #FCD34D; margin-right: 8px;"></i>
                    <?= htmlspecialchars($fullUser['bio'] ?? 'Write something about your culinary passion...') ?>
                </p>
        
                <?php if (!empty($fullUser['date_of_birth'])): ?>
                    <p style="font-size: 0.9rem; color: #6B7280;"><i class="fas fa-birthday-cake" style="width: 20px; color: #EC4899;"></i> 
                        Born: <?= date('d/m/Y', strtotime($fullUser['date_of_birth'])) ?>
                    </p>
                <?php endif; ?>
        
                <p style="font-size: 0.9rem; color: #6B7280;"><i class="fas fa-calendar-alt" style="width: 20px; color: #3B82F6;"></i> 
                    Joined: <?= !empty($fullUser['created_at']) ? date('F Y', strtotime($fullUser['created_at'])) : 'Recently' ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="profile-body-wrap">
    <div class="profile-body-inner">

        <aside class="profile-sidebar">
            <p class="ps-filter-label">Filter</p>
            <nav class="ps-nav">
                <a href="?tab=my_recipes" class="ps-nav-item <?= ($tab === 'my_recipes') ? 'active' : '' ?>">My Recipes</a>
                <a href="?tab=qna" class="ps-nav-item <?= ($tab === 'qna') ? 'active' : '' ?>">Question & Reply</a>
                <a href="?tab=bookmarks" class="ps-nav-item <?= ($tab === 'bookmarks') ? 'active' : '' ?>">My Bookmarks</a>
                <a href="/smart-recipes/frontend/pages/user/create_recipe.php" class="ps-nav-item">Create a recipe</a>
                <a href="/smart-recipes/frontend/pages/user/account_settings.php" class="ps-nav-item">
                    <i class="fas fa-cog" style="margin-right: 5px;"></i>Account Settings
                </a>
            </nav>
        </aside>

        <main class="profile-main">

            <?php if ($tab === 'qna'): ?>
                <h2 class="pm-section-title">QUESTION & REPLY</h2>
                
                <?php if (empty($qna_comments)): ?>
                    <p class="pm-empty">No comments yet. Join the discussion with everyone!</p>
                <?php else: ?>
                    <div class="qna-list">
                        <?php foreach ($qna_comments as $item): ?>
                            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$item['recipe_id'] ?>" style="display: block; text-decoration: none; color: inherit;">
                                <div class="qna-item" style="background: #fff; border: 1px solid #eee; padding: 15px 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'">
                                    <div style="display: flex; gap: 15px;">
                                        <img src="<?= htmlspecialchars($sessionUser['avatar']) ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        <div style="flex: 1;">
                                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                                                <p style="margin: 0; font-weight: 700; color: #111; font-size: 0.95rem;">
                                                    <?= htmlspecialchars($sessionUser['display_name']) ?>
                                                    <span style="font-weight: 400; color: #6B7280; font-size: 0.85rem; margin-left: 5px;">commented on</span>
                                                    <span style="font-weight: 600; color: #3B82F6; font-size: 0.9rem; margin-left: 5px;"><?= htmlspecialchars($item['recipe_name']) ?></span>
                                                </p>
                                                <small style="color: #9CA3AF; font-size: 0.75rem; white-space: nowrap;"><?= htmlspecialchars($item['time_ago']) ?></small>
                                            </div>
                                            <p style="margin: 0; font-size: 0.95rem; color: #374151; line-height: 1.5; background: #f8fafc; padding: 10px 15px; border-radius: 8px; border-left: 3px solid #FCD34D;">
                                                <?= nl2br(htmlspecialchars($item['comment_text'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php elseif ($tab === 'my_recipes'): ?>
                <h2 class="pm-section-title">MY RECIPES</h2>
                <div class="recipe-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
                    <?php if (!empty($my_recipes)): ?>
                        <?php foreach ($my_recipes as $recipe): 
                            // Xử lý ảnh
                            $mainImage = $recipe['main_image'];
                            if (strpos($mainImage, 'http') === 0) {
                                $imageUrl = $mainImage;
                            } else {
                                $imageUrl = '/smart-recipes/frontend/assets/images/recipes/' . $mainImage;
                            }
                        ?>
                            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$recipe['id'] ?>" style="text-decoration: none; display: block; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'" onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                                <div class="recipe-card" style="background:#fff; border-radius:12px; border:1px solid #eee; overflow:hidden; height: 100%;">
                                    <div style="height:150px;">
                                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                             style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <div style="padding:12px;">
                                        <h3 style="font-size:1rem; color:#111; margin-bottom:5px; transition: color 0.2s;" onmouseover="this.style.color='#F59E0B'" onmouseout="this.style.color='#111'"><?= htmlspecialchars($recipe['title']) ?></h3>
                                        <p style="font-size:0.8rem; color:#666; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;"><?= htmlspecialchars($recipe['description']) ?></p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="pm-empty">You haven't posted any recipes yet! 🥘</p>
                    <?php endif; ?>
                </div>

            <?php elseif ($tab === 'bookmarks'): ?>
                <h2 class="pm-section-title">MY BOOKMARKS</h2>
                <?php if (empty($myBookmarks)): ?>
                    <div style="text-align:center;padding:3rem 0;color:#9CA3AF;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                        </svg>
                        <p>You haven't saved any recipes yet.</p>
                        <a href="/smart-recipes/frontend/pages/recipes/all_recipes.php" class="adm-btn" style="background:#FCD34D;color:#000;border:none;margin-top:10px;text-decoration:none;display:inline-block;padding:8px 16px;border-radius:20px;font-weight:bold;">Explore now</a>
                    </div>
                <?php else: ?>
                    <div class="bm-recipe-grid">
                        <?php foreach ($myBookmarks as $recipe): ?>
                        <?php $rating = (int)($recipe['rating'] ?? 4); ?>
                        <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?= (int)$recipe['id'] ?>" class="bm-recipe-card">
                            <div class="bm-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                                </svg>
                            </div>
                            <div class="bm-recipe-img-wrap">
                                <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="bm-recipe-img">
                            </div>
                            <div class="bm-recipe-body">
                                <h3 class="bm-recipe-title"><?= htmlspecialchars($recipe['title']) ?></h3>
                                <p class="bm-recipe-author">By <span><?= htmlspecialchars($recipe['author'] ?? 'Admin') ?></span></p>
                                <div class="bm-recipe-meta">
                                    <div class="bm-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?= $i <= $rating ? '#FCD34D' : 'none' ?>" stroke="#FCD34D" stroke-width="1.5">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <?php if (!empty($recipe['ready_in'])): ?>
                                    <div class="bm-recipe-time">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                        <?= htmlspecialchars($recipe['ready_in']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="bm-recipe-bar"></div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>
</div>

<div id="editProfileModal" class="modal-overlay">
    <div class="modal-content-box">
        <h3 style="margin-bottom: 25px; color: #111; font-weight: 800;">
            <i class="fas fa-user-circle" style="color: #FCD34D; margin-right: 10px;"></i>Edit Profile
        </h3>
        <form id="editProfileForm">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;"><i class="fas fa-id-card"></i> Display Name</label>
                <input type="text" name="display_name" value="<?= htmlspecialchars($fullUser['display_name'] ?? $sessionUser['display_name']) ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;"><i class="fas fa-pen-nib"></i> Bio</label>
                <textarea name="bio" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; resize: none;"><?= htmlspecialchars($fullUser['bio'] ?? '') ?></textarea>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;"><i class="fas fa-birthday-cake"></i> Date of Birth</label>
                <input type="date" name="date_of_birth" value="<?= $fullUser['date_of_birth'] ?? '' ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="window.closeEditModal()" style="padding: 10px 20px; border-radius: 10px; border: 1px solid #ddd; background: #fff; cursor: pointer; font-weight: 600;">Cancel</button>
                <button type="submit" style="padding: 10px 25px; border-radius: 10px; background: #FCD34D; border: none; font-weight: 700; cursor: pointer;">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
$additionalScripts = ['../../assets/js/pages/profile.js'];
include '../../includes/footer.php';
?>