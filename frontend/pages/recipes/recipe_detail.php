<?php
require_once '../../includes/bootstrap.php';

$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$recipe = find_recipe_by_id($recipeId);

if (!$recipe) {
    redirect_to('/smart-recipes/frontend/pages/home.php');
}

// Lấy user_id của recipe từ database
$recipeOwnerId = null;
if ($conn) {
    $owner_stmt = $conn->prepare("SELECT user_id FROM recipes WHERE id = ?");
    $owner_stmt->bind_param("i", $recipeId);
    $owner_stmt->execute();
    $owner_result = $owner_stmt->get_result()->fetch_assoc();
    $recipeOwnerId = $owner_result ? (int)$owner_result['user_id'] : null;
}

// Kiểm tra xem user hiện tại có phải chủ bài viết không
$isRecipeOwner = false;
if (is_logged_in() && $recipeOwnerId !== null) {
    $currentUserId = (int)current_user()['id'];
    $isRecipeOwner = ($currentUserId === $recipeOwnerId);
}

// Lấy extra images từ database
$extraImages = [];
if ($conn) {
    $img_stmt = $conn->prepare("SELECT image_url, caption FROM recipe_images WHERE recipe_id = ? ORDER BY id ASC");
    $img_stmt->bind_param("i", $recipeId);
    $img_stmt->execute();
    $img_result = $img_stmt->get_result();
    while ($img_row = $img_result->fetch_assoc()) {
        $extraImages[] = [
            'url' => $img_row['image_url'],
            'caption' => $img_row['caption']
        ];
    }
}

// Xóa code lấy ảnh từ steps để gallery chỉ hiển thị ảnh chính và ảnh extra thực sự

$pageTitle = $recipe['title'] . ' - Food.';
$additionalStyles  = ['/smart-recipes/frontend/assets/css/pages/recipe.css'];
$additionalScripts = [
    '/smart-recipes/frontend/assets/js/pages/recipe_detail.js',
    '/smart-recipes/frontend/assets/js/pages/bookmarks.js',
];

include '../../includes/head.php';
include '../../includes/navbar.php';

// Navigation between recipes
$allRecipes = get_all_recipes();
$currentIndex = null;
foreach ($allRecipes as $idx => $r) {
    if ((int)$r['id'] === $recipeId) {
        $currentIndex = $idx;
        break;
    }
}
$prevRecipe = ($currentIndex !== null && $currentIndex > 0) ? $allRecipes[$currentIndex - 1] : null;
$nextRecipe = ($currentIndex !== null && $currentIndex < count($allRecipes) - 1) ? $allRecipes[$currentIndex + 1] : null;


// Comments (Fetch from DB)
$comments = [];
$user_liked_comments = [];

if ($conn) {
    // Fetch all approved comments for this recipe
    $stmt = $conn->prepare("SELECT c.*, COALESCE(u.display_name, u.username) as user_name, u.profile_image as avatar 
                            FROM comments c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.recipe_id = ? AND c.is_approved = 1 
                            ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $recipeId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        // Fetch like count
        $like_stmt = $conn->query("SELECT COUNT(id) as total FROM comment_likes WHERE comment_id = " . $row['id']);
        $like_count = $like_stmt->fetch_assoc()['total'];
        
        $row['likes'] = (int)$like_count;
        $row['avatar'] = $row['avatar'] ?: 'https://ui-avatars.com/api/?name='.urlencode($row['user_name']).'&background=3B82F6&color=fff&size=80';
        $comments[] = $row;
    }
    
    // Get user likes
    if (is_logged_in()) {
        $uid = current_user()['id'];
        $ul_stmt = $conn->query("SELECT comment_id FROM comment_likes WHERE user_id = $uid");
        while ($ul = $ul_stmt->fetch_assoc()) {
            $user_liked_comments[] = (int)$ul['comment_id'];
        }
    }
}

// Organize into parent-child structure
$parent_comments = [];
$replies = [];
foreach ($comments as $c) {
    if (empty($c['parent_id'])) {
        $parent_comments[] = $c;
    } else {
        $replies[$c['parent_id']][] = $c;
    }
}

// Rating stars helper
function renderStars(float $rating): string {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<span class="star ' . ($i <= round($rating) ? 'filled' : '') . '" data-value="'.$i.'">★</span>';
    }
    return $html;
}

// Tổng số ảnh (ảnh chính + extra images)
$totalImages = 1 + count($extraImages);
?>

<!-- ===== Recipe Nav Bar ===== -->
<div class="recipe-nav-bar">
    <div class="recipe-nav-container">
        <?php if ($prevRecipe): ?>
            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?php echo $prevRecipe['id']; ?>"
               class="recipe-nav-link">&#8249; PREVIOUS RECIPE</a>
        <?php else: ?>
            <span class="recipe-nav-link disabled">&#8249; PREVIOUS RECIPE</span>
        <?php endif; ?>

        <?php if ($nextRecipe): ?>
            <a href="/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=<?php echo $nextRecipe['id']; ?>"
               class="recipe-nav-link">NEXT RECIPE &#8250;</a>
        <?php else: ?>
            <span class="recipe-nav-link disabled">NEXT RECIPE &#8250;</span>
        <?php endif; ?>
    </div>
</div>

<!-- ===== Main Container ===== -->
<div class="recipe-detail-container">

    <div class="recipe-header">
        <h1 class="recipe-title"><?php echo strtoupper(htmlspecialchars($recipe['title'])); ?></h1>
        <div class="recipe-rating" id="interactive-rating" data-recipe-id="<?php echo $recipeId; ?>" data-logged-in="<?php echo is_logged_in() ? 'true' : 'false'; ?>">
            <div class="stars" style="cursor: pointer;" title="Click to rate!">
                <?php echo renderStars($recipe['rating'] ?? 4.5); ?>
            </div>
            <span class="rating-count" id="rating-count-display">(<?php echo $recipe['rating_count'] ?? 0; ?>)</span>
            <span class="rating-msg" id="rating-msg" style="font-size: 13px; color: #10B981; margin-left: 10px; opacity: 0; transition: opacity 0.3s;">Thanks for rating!</span>
        </div>
    </div>

    <!-- User Submission Section -->
    <div class="recipe-user-section">
        <div class="user-post-header">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($recipe['author']); ?>&background=FCD34D&color=000&size=100"
                 class="user-avatar"
                 alt="<?php echo htmlspecialchars($recipe['author']); ?>">
            <div class="user-info">
                <p class="user-submitted">Submitted by <strong><?php echo htmlspecialchars($recipe['author']); ?></strong></p>
                <p class="user-quote">"<?php echo htmlspecialchars($recipe['description']); ?>"</p>
            </div>
        </div>

        <div class="user-actions">
            <!-- Bookmark -->
            <button class="btn-action" title="Bookmark" id="btn-bookmark">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                </svg>
            </button>

            <!-- Share -->
            <button class="btn-action" title="Share" onclick="shareRecipe()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"/>
                    <circle cx="6" cy="12" r="3"/>
                    <circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
            </button>

            <!-- Copy Link -->
            <button class="btn-action" title="Copy Link" onclick="copyLink()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                </svg>
            </button>

            <!-- Report -->
            <?php if (is_logged_in() && !$isRecipeOwner): ?>
            <button class="btn-action" title="Report" onclick="openReportModal('recipe', <?= $recipeId ?>)" style="color:#EF4444;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </button>
            <?php endif; ?>

            <!-- I MADE THIS - Chỉ hiển thị cho chủ bài viết -->
            <?php if ($isRecipeOwner): ?>
            <button class="btn-made-this" id="i-made-this-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                I MADE THIS
            </button>
            <?php endif; ?>
        </div>
    </div>


    <!-- Recipe Images -->
    <div class="recipe-images">
        <!-- Main Image - Click để mở gallery từ ảnh đầu tiên -->
        <img src="<?php echo $recipe['image']; ?>"
             alt="<?php echo htmlspecialchars($recipe['title']); ?>"
             class="recipe-main-image"
             id="main-recipe-img"
             onclick="openGalleryModal(0)"
             style="cursor: pointer;">

        <!-- Thumbnails - Hiển thị ảnh chính + các ảnh extra -->
        <div class="recipe-thumbnails">
            <?php 
            // Tạo mảng tất cả ảnh để hiển thị thumbnails
            $allImages = [];
            $allImages[] = ['url' => $recipe['image'], 'caption' => 'Main photo'];
            foreach ($extraImages as $img) {
                $allImages[] = $img;
            }
            
            $totalImages = count($allImages);
            
            if ($totalImages > 1): 
                // Hiển thị tối đa 3 thumbnails (bắt đầu từ ảnh thứ 2 vì ảnh 1 đã hiển thị ở main)
                $maxThumbs = min(3, $totalImages - 1);
                
                for ($i = 0; $i < $maxThumbs; $i++):
                    $imageIndex = $i + 1; // Bắt đầu từ ảnh thứ 2 (index 1)
                    $isLastThumb = ($i === $maxThumbs - 1);
            ?>
                    <div class="recipe-thumb-wrapper" 
                         onclick="openGalleryModal(<?php echo $imageIndex; ?>)">
                        <img src="<?php echo htmlspecialchars($allImages[$imageIndex]['url']); ?>"
                             alt="<?php echo htmlspecialchars($recipe['title']); ?> - <?php echo htmlspecialchars($allImages[$imageIndex]['caption'] ?? 'Photo ' . ($imageIndex + 1)); ?>"
                             class="recipe-thumb">
                        <?php if ($isLastThumb): ?>
                            <!-- Overlay VIEW ALL cho thumbnail cuối -->
                            <div class="recipe-thumb-overlay">
                                <span>VIEW ALL (<?php echo $totalImages; ?>)</span>
                            </div>
                        <?php endif; ?>
                    </div>
            <?php 
                endfor;
            elseif ($isRecipeOwner): 
                // Chỉ có 1 ảnh (ảnh chính) - hiển thị nút Add Photo cho chủ bài viết
            ?>
                <div class="recipe-thumb-wrapper recipe-thumb-add" onclick="document.getElementById('i-made-this-btn')?.click()">
                    <div class="add-photo-placeholder">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span>Add Photo</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div> 

    <!-- Direction + Ingredients Grid -->
    <div class="recipe-content-grid">
        <!-- Directions -->
        <div class="recipe-section">
            <h2 class="section-title">DIRECTION</h2>
            <?php /* Debug: var_dump($recipe['steps']); */ ?>
            <ol class="direction-list">
                <?php if (!empty($recipe['steps'])): ?>
                    <?php foreach ($recipe['steps'] as $step): ?>
                        <?php 
                        // Hỗ trợ cả format cũ (string) và format mới (array với instruction và image)
                        if (is_array($step)) {
                            $instruction = $step['instruction'] ?? '';
                            $stepImage = $step['image'] ?? null;
                        } else {
                            $instruction = $step;
                            $stepImage = null;
                        }
                        ?>
                        <li>
                            <div class="step-content">
                                <p class="step-instruction"><?php echo htmlspecialchars($instruction); ?></p>
                                <?php if ($stepImage): ?>
                                    <img src="<?php echo htmlspecialchars($stepImage); ?>" 
                                         alt="Step image" 
                                         class="step-image"
                                         onclick="openGalleryModalWithImage('<?php echo htmlspecialchars($stepImage); ?>')">
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>
                        <div class="step-content">
                            <p class="step-instruction">No steps provided.</p>
                        </div>
                    </li>
                <?php endif; ?>
            </ol>
        </div>

        <!-- Ingredients -->
        <div class="recipe-section">
            <h2 class="section-title">INGREDIENTS</h2>
            <p class="ingredients-note">For <?php echo (int)($recipe['servings'] ?? 4); ?> servings</p>
            <ul class="ingredient-list">
                <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                    <li>
                        <input type="checkbox" class="ingredient-checkbox" id="ing-<?php echo md5($ingredient); ?>">
                        <label for="ing-<?php echo md5($ingredient); ?>"><?php echo htmlspecialchars($ingredient); ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Question & Reply -->
    <div class="questions-section">
        <h2 class="section-title">QUESTION &amp; REPLY</h2>
        
        <?php if (is_logged_in()): ?>
            <div class="comment-form-container" style="margin-bottom: 2rem;">
                <textarea id="new-comment-text" placeholder="What are your thoughts or questions?" rows="3" style="width: 100%; border-radius: 0.5rem; border: 1px solid #D1D5DB; padding: 0.75rem; font-family: inherit; font-size: 1rem; resize: vertical; margin-bottom: 0.5rem;"></textarea>
                <button class="btn-block" onclick="submitComment(0)" style="max-width: 200px;">POST COMMENT</button>
            </div>
        <?php else: ?>
            <div style="margin-bottom: 2rem; color: #6B7280; font-style: italic;">
                Please <a href="/smart-recipes/frontend/pages/auth/sign_in.php" style="color: #F59E0B; font-weight: bold;">sign in</a> to leave a comment.
            </div>
        <?php endif; ?>

        <div class="comments-list" id="comments-wrapper">
            <?php foreach ($parent_comments as $comment): ?>
                <div class="comment-item" data-comment-id="<?php echo $comment['id']; ?>">
                    <img src="<?php echo htmlspecialchars($comment['avatar']); ?>"
                         alt="<?php echo htmlspecialchars($comment['user_name']); ?>"
                         class="comment-avatar">
                    <div class="comment-content">
                        <div class="comment-header">
                            <strong><?php echo htmlspecialchars($comment['user_name']); ?></strong>
                            <span class="comment-time"><?php echo date('d/m/y', strtotime($comment['created_at'])); ?></span>
                        </div>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        <div class="comment-actions">
                            <button class="btn-reply" onclick="toggleReplyForm(<?php echo $comment['id']; ?>)">REPLY</button>
                            <button class="btn-like" onclick="toggleLike(<?php echo $comment['id']; ?>)" title="Like">
                                <?php $isLiked = in_array($comment['id'], $user_liked_comments); ?>
                                <svg viewBox="0 0 24 24" fill="<?php echo $isLiked ? '#EF4444' : 'none'; ?>" stroke="<?php echo $isLiked ? '#EF4444' : 'currentColor'; ?>" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" class="heart-icon" data-liked="<?php echo $isLiked ? 'true' : 'false'; ?>">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <span class="like-count" style="font-size: 0.875rem; margin-left: 4px; color: #6B7280; font-weight: 500; vertical-align: middle; line-height: 20px;"><?php echo $comment['likes'] > 0 ? $comment['likes'] : ''; ?></span>
                            </button>
                            <?php if (is_logged_in() && current_user()['id'] != $comment['user_id']): ?>
                            <button class="btn-reply" onclick="openReportModal('comment', <?php echo $comment['id']; ?>)" style="color:#EF4444;" title="Report">REPORT</button>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Reply Form (Hidden by default) -->
                        <div class="reply-form-container" id="reply-form-<?php echo $comment['id']; ?>" style="display: none; margin-top: 1rem;">
                            <textarea id="reply-text-<?php echo $comment['id']; ?>" placeholder="Write a reply..." rows="2" style="width: 100%; border-radius: 0.5rem; border: 1px solid #D1D5DB; padding: 0.5rem; font-family: inherit; font-size: 0.875rem; resize: vertical; margin-bottom: 0.5rem;"></textarea>
                            <button onclick="submitComment(<?php echo $comment['id']; ?>)" style="background: #111; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.75rem; font-weight: bold; cursor: pointer;">POST REPLY</button>
                        </div>
                        
                        <!-- Replies -->
                        <?php if (isset($replies[$comment['id']])): ?>
                            <div class="replies-list" style="margin-top: 1rem; padding-left: 1rem; border-left: 2px solid #E5E7EB;">
                                <?php foreach ($replies[$comment['id']] as $reply): ?>
                                    <div class="comment-item" data-comment-id="<?php echo $reply['id']; ?>" style="margin-bottom: 1rem;">
                                        <img src="<?php echo htmlspecialchars($reply['avatar']); ?>"
                                             alt="<?php echo htmlspecialchars($reply['user_name']); ?>"
                                             class="comment-avatar" style="width: 32px; height: 32px;">
                                        <div class="comment-content">
                                            <div class="comment-header">
                                                <strong><?php echo htmlspecialchars($reply['user_name']); ?></strong>
                                                <span class="comment-time"><?php echo date('d/m/y', strtotime($reply['created_at'])); ?></span>
                                            </div>
                                            <p style="font-size: 0.9rem;"><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></p>
                                            <div class="comment-actions">
                                                <button class="btn-like" onclick="toggleLike(<?php echo $reply['id']; ?>)" title="Like">
                                                    <?php $isReplyLiked = in_array($reply['id'], $user_liked_comments); ?>
                                                    <svg viewBox="0 0 24 24" fill="<?php echo $isReplyLiked ? '#EF4444' : 'none'; ?>" stroke="<?php echo $isReplyLiked ? '#EF4444' : 'currentColor'; ?>" stroke-width="2"
                                                         stroke-linecap="round" stroke-linejoin="round" class="heart-icon" data-liked="<?php echo $isReplyLiked ? 'true' : 'false'; ?>" style="width: 14px; height: 14px;">
                                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                                    </svg>
                                                    <span class="like-count" style="font-size: 0.75rem; margin-left: 4px; color: #6B7280; font-weight: 500; vertical-align: middle; line-height: 20px;"><?php echo $reply['likes'] > 0 ? $reply['likes'] : ''; ?></span>
                                                </button>
                                                <?php if (is_logged_in() && current_user()['id'] != $reply['user_id']): ?>
                                                <button class="btn-reply" onclick="openReportModal('comment', <?php echo $reply['id']; ?>)" style="color:#EF4444; margin-left: 0.5rem;" title="Report">REPORT</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($parent_comments)): ?>
            <p style="text-align: center; color: #6B7280; margin-top: 2rem;">No comments yet. Be the first!</p>
        <?php endif; ?>
    </div>

</div><!-- end .recipe-detail-container -->

<!-- Newsletter -->
<?php include '../../includes/newsletter.php'; ?>

<!-- Inject bookmark state for bookmarks.js -->
<?php
$isBookmarked = false;
if (is_logged_in()) {
    $bm_check = $conn->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND recipe_id = ?");
    $bm_uid   = current_user()['id'];
    $bm_check->bind_param("ii", $bm_uid, $recipeId);
    $bm_check->execute();
    $isBookmarked = $bm_check->get_result()->num_rows > 0;
}

// Chuẩn bị dữ liệu gallery cho JavaScript
$galleryImages = [['url' => $recipe['image'], 'caption' => 'Main photo']];
foreach ($extraImages as $img) {
    $galleryImages[] = $img;
}
?>
<script>
window.bookmarkState = {
    recipeId:     <?= (int)$recipeId ?>,
    isBookmarked: <?= $isBookmarked ? 'true' : 'false' ?>,
    isLoggedIn:   <?= is_logged_in() ? 'true' : 'false' ?>
};
window.galleryImages = <?= json_encode($galleryImages, JSON_UNESCAPED_SLASHES) ?>;
</script>

<!-- Footer -->
<?php include '../../includes/footer.php'; ?>

<!-- ===== Gallery Modal ===== -->
<div id="gallery-modal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999;">
    <div class="gallery-modal-content">
        <button class="modal-close" onclick="closeGalleryModal()" aria-label="Close">&times;</button>
        
        <div class="gallery-main">
            <button class="gallery-nav gallery-prev" onclick="galleryPrev()">&#8249;</button>
            <div class="gallery-main-img-wrapper">
                <img id="gallery-main-img" src="" alt="Gallery image">
            </div>
            <button class="gallery-nav gallery-next" onclick="galleryNext()">&#8250;</button>
        </div>
        
        <p id="gallery-caption" class="gallery-caption"></p>
        <p id="gallery-counter" class="gallery-counter"></p>
        
        <div class="gallery-thumbs" id="gallery-thumbs"></div>
    </div>
</div>

<!-- ===== I Made This Modal ===== -->
<div id="made-this-modal" class="modal-overlay" style="display:none;">
    <div class="made-this-modal">
        <button class="modal-close" onclick="closeMadeThisModal()" aria-label="Close">&times;</button>
        <h3>Share Your Creation</h3>

        <?php if (!is_logged_in()): ?>
            <div class="guest-notice">
                You need to <a href="/smart-recipes/frontend/pages/auth/sign_in.php">sign in</a> to share your creation.
            </div>
        <?php else: ?>
            <div class="upload-area" id="upload-area">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#9CA3AF" stroke-width="2">
                    <rect x="3" y="3" width="42" height="42" rx="4"/>
                    <circle cx="24" cy="20" r="5"/>
                    <path d="M3 36l11-11 8 8 11-11 9 9"/>
                </svg>
                <p><span>Click to upload</span> or drag and drop</p>
                <input type="file" id="image-upload" accept="image/*" style="display:none;">
                <img id="preview-image" style="display:none; max-width:100%; margin-top:1rem; border-radius:0.5rem; max-height:200px; object-fit:cover;" alt="Preview">
            </div>

            <textarea id="post-caption" placeholder="Write a caption (optional)..." rows="3"></textarea>
            <button class="btn-block" onclick="submitPost()">POST</button>
        <?php endif; ?>
    </div>
</div>

<!-- ===== Report Modal ===== -->
<div id="report-modal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div class="report-modal-content" style="background:#fff; width:90%; max-width:400px; padding:2rem; border-radius:12px; position:relative;">
        <button class="modal-close" onclick="closeReportModal()" aria-label="Close" style="position:absolute; top:15px; right:15px; background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
        <h3 style="margin-top:0; font-family:'Rammetto One', sans-serif; color:#EF4444; margin-bottom:1rem;">Report Content</h3>
        <p style="color:#6B7280; font-size:0.875rem; margin-bottom:1rem;">Please provide a reason for reporting this <span id="report-type-label">content</span>.</p>
        <input type="hidden" id="report-type" value="">
        <input type="hidden" id="report-id" value="">
        <textarea id="report-reason" placeholder="Reason for reporting (e.g., spam, inappropriate)..." rows="4" style="width:100%; padding:0.75rem; border:1px solid #D1D5DB; border-radius:6px; resize:vertical; margin-bottom:1.5rem; font-family:inherit; box-sizing:border-box;"></textarea>
        <button class="btn-block" onclick="submitReport()" style="background:#EF4444;">SUBMIT REPORT</button>
    </div>
</div>

<!-- ===== Inline Scripts ===== -->
<script>
// ---- Gallery Modal ----
let currentGalleryIndex = 0;

function openGalleryModal(startIndex) {
    console.log('openGalleryModal called with index:', startIndex);
    console.log('galleryImages:', window.galleryImages);
    
    if (!window.galleryImages || window.galleryImages.length === 0) {
        console.log('No gallery images found');
        // Nếu không có ảnh và có nút upload, mở modal upload
        const madeThisModal = document.getElementById('made-this-modal');
        if (madeThisModal) {
            madeThisModal.style.display = 'flex';
        }
        return;
    }
    
    // Đảm bảo startIndex hợp lệ
    startIndex = parseInt(startIndex) || 0;
    if (startIndex < 0) startIndex = 0;
    if (startIndex >= window.galleryImages.length) startIndex = 0;
    
    currentGalleryIndex = startIndex;
    updateGalleryView();
    renderGalleryThumbs();
    
    const modal = document.getElementById('gallery-modal');
    if (modal) {
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        console.log('Gallery modal opened');
    } else {
        console.error('Gallery modal element not found!');
    }
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeGalleryModal() {
    document.getElementById('gallery-modal').style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
}

function updateGalleryView() {
    if (!window.galleryImages || !window.galleryImages[currentGalleryIndex]) return;
    
    const img = window.galleryImages[currentGalleryIndex];
    const mainImg = document.getElementById('gallery-main-img');
    const caption = document.getElementById('gallery-caption');
    const counter = document.getElementById('gallery-counter');
    
    if (mainImg) mainImg.src = img.url;
    if (caption) caption.textContent = img.caption || '';
    if (counter) counter.textContent = (currentGalleryIndex + 1) + ' / ' + window.galleryImages.length;
    
    // Update active thumb
    document.querySelectorAll('#gallery-thumbs .gallery-thumb').forEach((thumb, idx) => {
        thumb.classList.toggle('active', idx === currentGalleryIndex);
    });
}

function galleryPrev() {
    if (!window.galleryImages || window.galleryImages.length === 0) return;
    currentGalleryIndex = (currentGalleryIndex - 1 + window.galleryImages.length) % window.galleryImages.length;
    updateGalleryView();
}

function galleryNext() {
    if (!window.galleryImages || window.galleryImages.length === 0) return;
    currentGalleryIndex = (currentGalleryIndex + 1) % window.galleryImages.length;
    updateGalleryView();
}

function renderGalleryThumbs() {
    const container = document.getElementById('gallery-thumbs');
    if (!container || !window.galleryImages) return;
    
    container.innerHTML = '';
    window.galleryImages.forEach((img, idx) => {
        const thumb = document.createElement('img');
        thumb.src = img.url;
        thumb.className = 'gallery-thumb' + (idx === currentGalleryIndex ? ' active' : '');
        thumb.style.cursor = 'pointer';
        thumb.onclick = function() {
            currentGalleryIndex = idx;
            updateGalleryView();
        };
        container.appendChild(thumb);
    });
}

// Initialize gallery modal events when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Close gallery on overlay click
    const galleryModal = document.getElementById('gallery-modal');
    if (galleryModal) {
        galleryModal.addEventListener('click', function(e) {
            if (e.target === this) closeGalleryModal();
        });
    }
    
    // Close made-this modal on overlay click
    const madeThisModal = document.getElementById('made-this-modal');
    if (madeThisModal) {
        madeThisModal.addEventListener('click', function(e) {
            if (e.target === this) closeMadeThisModal();
        });
    }
    
    // I Made This button
    const iMadeThisBtn = document.getElementById('i-made-this-btn');
    if (iMadeThisBtn) {
        iMadeThisBtn.addEventListener('click', function() {
            document.getElementById('made-this-modal').style.display = 'flex';
        });
    }
    
    // Upload area
    const uploadArea = document.getElementById('upload-area');
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            document.getElementById('image-upload').click();
        });
    }
    
    // Image upload preview
    const imageUpload = document.getElementById('image-upload');
    if (imageUpload) {
        imageUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const preview = document.getElementById('preview-image');
                    if (preview) {
                        preview.src = ev.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    console.log('Gallery initialized. Images:', window.galleryImages);
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const galleryModal = document.getElementById('gallery-modal');
    if (galleryModal && galleryModal.style.display === 'flex') {
        if (e.key === 'ArrowLeft') galleryPrev();
        if (e.key === 'ArrowRight') galleryNext();
        if (e.key === 'Escape') closeGalleryModal();
    }
});

function closeMadeThisModal() {
    document.getElementById('made-this-modal').style.display = 'none';
    const preview = document.getElementById('preview-image');
    if (preview) { preview.style.display = 'none'; preview.src = ''; }
    const upload = document.getElementById('image-upload');
    if (upload) upload.value = '';
    const caption = document.getElementById('post-caption');
    if (caption) caption.value = '';
}

// ---- Submit post ----
function submitPost() {
    const imageFile = document.getElementById('image-upload')
                      ? document.getElementById('image-upload').files[0] : null;
    const caption   = document.getElementById('post-caption')
                      ? document.getElementById('post-caption').value.trim() : '';

    if (!imageFile) {
        alert('Please upload a photo of your creation!');
        return;
    }

    const recipeId = window.bookmarkState?.recipeId;
    if (!recipeId) { alert('Recipe not found.'); return; }

    const fd = new FormData();
    fd.append('recipe_image', imageFile);
    fd.append('caption', caption);
    fd.append('recipe_id', recipeId);

    fetch('/smart-recipes/backend/api/upload_made_this.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                alert('Your creation has been shared! Thank you 🎉');
                closeMadeThisModal();
                location.reload(); // Reload để thấy ảnh mới
            } else {
                alert('Error: ' + (d.message || 'Upload failed'));
            }
        })
        .catch(() => alert('Could not connect to server.'));
}

// ---- Submit Comment ----
function submitComment(parentId) {
    if (window.bookmarkState && !window.bookmarkState.isLoggedIn) {
        alert('Please sign in to comment!');
        window.location.href = '/smart-recipes/frontend/pages/auth/sign_in.php';
        return;
    }
    
    let inputEl = parentId === 0 ? document.getElementById('new-comment-text') : document.getElementById('reply-text-' + parentId);
    let content = inputEl.value.trim();
    
    if (!content) {
        alert('Please enter your comment!');
        return;
    }
    
    const recipeId = window.bookmarkState?.recipeId;
    
    fetch('/smart-recipes/backend/api/add_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ recipe_id: recipeId, parent_id: parentId, content: content })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            inputEl.value = '';
            location.reload(); // Reload trang để thấy comment mới
        } else {
            alert(data.message || 'Error submitting comment');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Cannot connect to server.');
    });
}

function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}

// ---- Toggle Like ----
function toggleLike(commentId) {
    if (window.bookmarkState && !window.bookmarkState.isLoggedIn) {
        alert('Please sign in to like!');
        return;
    }
    
    fetch('/smart-recipes/backend/api/like_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ comment_id: commentId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const commentEl = document.querySelector('[data-comment-id="' + commentId + '"]');
            if (!commentEl) return;
            const heartIcon = commentEl.querySelector('.heart-icon');
            const countSpan = commentEl.querySelector('.like-count');
            
            if (data.liked) {
                heartIcon.style.fill = '#EF4444';
                heartIcon.style.stroke = '#EF4444';
                heartIcon.setAttribute('data-liked', 'true');
            } else {
                heartIcon.style.fill = 'none';
                heartIcon.style.stroke = 'currentColor';
                heartIcon.setAttribute('data-liked', 'false');
            }
            
            if (countSpan) {
                countSpan.textContent = data.total_likes > 0 ? data.total_likes : '';
            }
        } else {
            alert(data.message || 'Error liking comment');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Cannot connect to server.');
    });
}

// ---- Share ----
function shareRecipe() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($recipe['title']); ?>',
            url: window.location.href
        }).catch(console.error);
    } else {
        copyLink();
    }
}

// ---- Copy Link ----
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function () {
        alert('Link copied to clipboard!');
    }).catch(function () {
        const el = document.createElement('input');
        el.value = window.location.href;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Link copied!');
    });
}

// ---- Open gallery with single image (for step images) ----
function openGalleryModalWithImage(imageUrl) {
    // Tạo temporary gallery với 1 ảnh
    const tempGallery = [{ url: imageUrl, caption: 'Step image' }];
    const originalGallery = window.galleryImages;
    
    window.galleryImages = tempGallery;
    currentGalleryIndex = 0;
    updateGalleryView();
    renderGalleryThumbs();
    
    const modal = document.getElementById('gallery-modal');
    if (modal) {
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }
    document.body.style.overflow = 'hidden';
    
    // Restore original gallery when modal closes
    const originalClose = closeGalleryModal;
    closeGalleryModal = function() {
        window.galleryImages = originalGallery;
        document.getElementById('gallery-modal').style.display = 'none';
        document.body.style.overflow = '';
        closeGalleryModal = originalClose;
    };
}

// ---- Interactive Rating ----
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('interactive-rating');
    if (!ratingContainer) return;
    
    const stars = ratingContainer.querySelectorAll('.star');
    const isLoggedIn = ratingContainer.getAttribute('data-logged-in') === 'true';
    const recipeId = ratingContainer.getAttribute('data-recipe-id');
    const msg = document.getElementById('rating-msg');
    
    let currentRating = 0;
    
    // Khởi tạo rating hiện tại từ DOM
    stars.forEach(s => {
        if (s.classList.contains('filled')) {
            currentRating = Math.max(currentRating, parseInt(s.getAttribute('data-value')));
        }
    });

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            if (!isLoggedIn) return;
            const val = parseInt(this.getAttribute('data-value'));
            stars.forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                s.style.color = sVal <= val ? '#FCD34D' : '#E5E7EB';
            });
        });
        
        star.addEventListener('mouseout', function() {
            if (!isLoggedIn) return;
            stars.forEach(s => {
                const sVal = parseInt(s.getAttribute('data-value'));
                s.style.color = sVal <= currentRating ? '#F59E0B' : '#E5E7EB';
            });
        });
        
        star.addEventListener('click', function() {
            if (!isLoggedIn) {
                if (typeof window.showAuthModal === 'function') {
                    window.showAuthModal();
                } else {
                    alert('You must be logged in to rate recipes!');
                    window.location.href = '/smart-recipes/frontend/pages/auth/sign_in.php';
                }
                return;
            }
            
            const val = parseInt(this.getAttribute('data-value'));
            
            // Call API
            fetch('/smart-recipes/backend/api/rate_recipe.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ recipe_id: recipeId, rating: val })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    currentRating = Math.round(data.new_average);
                    // Update UI permanently
                    stars.forEach(s => {
                        const sVal = parseInt(s.getAttribute('data-value'));
                        s.classList.toggle('filled', sVal <= currentRating);
                        s.style.color = ''; // Remove inline styles to let CSS take over
                    });
                    
                    document.getElementById('rating-count-display').textContent = '(' + data.new_count + ')';
                    
                    msg.style.opacity = '1';
                    setTimeout(() => { msg.style.opacity = '0'; }, 3000);
                } else {
                    alert(data.message || 'Error saving rating');
                }
            })
            .catch(err => {
                console.error(err);
                alert('An error occurred. Please try again.');
            });
        });
    });
});

// ---- Report Feature ----
function openReportModal(type, id) {
    document.getElementById('report-type').value = type;
    document.getElementById('report-id').value = id;
    document.getElementById('report-type-label').textContent = type;
    document.getElementById('report-reason').value = '';
    
    const modal = document.getElementById('report-modal');
    if (modal) {
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
    }
}

function closeReportModal() {
    const modal = document.getElementById('report-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function submitReport() {
    const type = document.getElementById('report-type').value;
    const id = document.getElementById('report-id').value;
    const reason = document.getElementById('report-reason').value.trim();

    if (!reason) {
        alert('Vui lòng nhập lý do báo cáo.');
        return;
    }

    fetch('/smart-recipes/backend/api/submit_report.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ reported_type: type, reported_id: id, reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeReportModal();
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi kết nối. Vui lòng thử lại sau.');
    });
}
</script>