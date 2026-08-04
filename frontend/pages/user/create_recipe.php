<?php
require_once '../../includes/bootstrap.php';
require_login();

// Lấy categories từ DB cho dropdown
$categories_result = $conn->query("SELECT id, name FROM categories ORDER BY display_order ASC, name ASC");
$categories = [];
if ($categories_result) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$pageTitle        = 'Create a Recipe – Food.';
$additionalStyles = ['/smart-recipes/frontend/assets/css/pages/profile.css'];
include '../../includes/head.php';
include '../../includes/navbar.php';
?>

<style>
/* ═══════════════════════════════════
   Create Recipe Page
   ═══════════════════════════════════ */
.cr-page {
    background: #f5f5f3;
    min-height: calc(100vh - 150px);
    padding: 3rem 1rem 5rem;
}

.cr-container {
    max-width: 780px;
    margin: 0 auto;
}

/* Header */
.cr-header {
    margin-bottom: 2.5rem;
}

.cr-header h1 {
    font-size: 1.75rem;
    font-weight: 900;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #111;
    margin: 0 0 0.4rem;
}

.cr-header p {
    font-size: 0.9rem;
    color: #888;
    margin: 0;
}

.cr-header p strong {
    color: #111;
}

.cr-header p .dot { color: #FCD34D; }

/* Card section */
.cr-section {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8e8e8;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
}

.cr-section-title {
    font-size: 1rem;
    font-weight: 800;
    color: #111;
    margin: 0 0 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #FCD34D;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Form grid */
.cr-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

.cr-grid-3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1.25rem;
}

.cr-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.cr-field label {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #555;
}

.cr-input,
.cr-select,
.cr-textarea {
    border: 1.5px solid #e2e2e2;
    border-radius: 8px;
    padding: 0.65rem 0.9rem;
    font-size: 0.9rem;
    color: #111;
    font-family: inherit;
    background: #fff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    width: 100%;
    box-sizing: border-box;
}

.cr-input:focus,
.cr-select:focus,
.cr-textarea:focus {
    border-color: #FCD34D;
    box-shadow: 0 0 0 3px rgba(252,211,77,0.18);
}

.cr-input::placeholder,
.cr-textarea::placeholder { color: #ccc; }

.cr-textarea {
    resize: vertical;
    min-height: 90px;
    line-height: 1.6;
}

.cr-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.85rem center;
    padding-right: 2.2rem;
    cursor: pointer;
}

/* Upload box */
.cr-upload-box {
    border: 2px dashed #d1d5db;
    border-radius: 10px;
    background: #fafafa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
    padding: 1.5rem;
    text-align: center;
    min-height: 120px;
}

.cr-upload-box:hover {
    border-color: #FCD34D;
    background: #fffbeb;
}

.cr-upload-box svg { color: #aaa; }

.cr-upload-box p {
    font-size: 0.8rem;
    color: #aaa;
    margin: 0;
    line-height: 1.5;
}

.cr-upload-preview {
    width: 100%;
    max-height: 180px;
    object-fit: cover;
    border-radius: 8px;
    display: none;
    margin-top: 0.5rem;
}

/* ── Ingredients Table ── */
.cr-ingredients-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.cr-ingredients-table th {
    text-align: left;
    padding: 0 0 0.5rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #888;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid #e8e8e8;
}

.cr-ingredients-table td {
    padding: 0.5rem 0.4rem 0.5rem 0;
    vertical-align: middle;
}

.cr-ingredients-table td:last-child {
    width: 38px;
    text-align: right;
}

.cr-ing-name { width: 45%; }
.cr-ing-qty  { width: 20%; }
.cr-ing-unit { width: 22%; }

.cr-ing-name input,
.cr-ing-qty input { width: 100%; }

.cr-btn-del {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #FCD34D;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #000;
    transition: background 0.15s;
    flex-shrink: 0;
}

.cr-btn-del:hover { background: #F59E0B; }

.cr-btn-add-row {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: none;
    border: none;
    color: #F59E0B;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    font-family: inherit;
    transition: color 0.15s;
}

.cr-btn-add-row:hover { color: #d97706; }

/* ── Steps ── */
.cr-steps-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 0.75rem;
}

.cr-step-item {
    border: 1px solid #ebebeb;
    border-radius: 10px;
    padding: 1.1rem 1.25rem;
    position: relative;
    background: #fafafa;
}

.cr-step-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.cr-step-label {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.cr-step-img-label {
    font-size: 0.8rem;
    color: #999;
    margin: 0.65rem 0 0.35rem;
}

.cr-step-img-upload {
    border: 1.5px dashed #d1d5db;
    border-radius: 8px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    cursor: pointer;
    font-size: 0.8rem;
    color: #aaa;
    transition: border-color 0.15s;
    min-height: 60px;
}

.cr-step-img-upload:hover { border-color: #FCD34D; }

.cr-step-preview {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 8px;
    display: none;
    margin-top: 0.5rem;
}

/* ── Tags ── */
.cr-tags-selected {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.cr-tag-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: #FCD34D;
    color: #000;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
    cursor: pointer;
    border: none;
    font-family: inherit;
    transition: background 0.15s;
}

.cr-tag-pill:hover { background: #F59E0B; }

.cr-tag-pill .cr-tag-x { font-size: 1rem; line-height: 1; }

.cr-add-tag-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    margin-top: 0.5rem;
}

.cr-add-tag-row select {
    flex: 1;
}

.cr-btn-add-tag {
    background: #111;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.1rem;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    white-space: nowrap;
    transition: background 0.15s;
}

.cr-btn-add-tag:hover { background: #333; }

/* ── Actions ── */
.cr-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.cr-btn-cancel {
    background: none;
    border: 1.5px solid #d1d5db;
    color: #555;
    border-radius: 9999px;
    padding: 0.7rem 2rem;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.15s;
}

.cr-btn-cancel:hover { border-color: #aaa; color: #111; }

.cr-btn-submit {
    background: #FCD34D;
    color: #000;
    border: none;
    border-radius: 9999px;
    padding: 0.7rem 2.5rem;
    font-size: 0.9rem;
    font-weight: 800;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.15s, transform 0.1s;
    letter-spacing: 0.02em;
}

.cr-btn-submit:hover { background: #F59E0B; transform: translateY(-1px); }

@media (max-width: 640px) {
    .cr-grid-2, .cr-grid-3 { grid-template-columns: 1fr; }
    .cr-section { padding: 1.25rem 1.1rem; }
}
</style>

<div class="cr-page">
<div class="cr-container">

    <!-- Header -->
    <div class="cr-header">
        <h1>Share Your Culinary Masterpiece</h1>
        <p>Fill out the details below to add your unique recipe to
            <strong>Food</strong><span class="dot">.</span>
            Inspire others with your cooking!
        </p>
    </div>

    <form id="recipeForm" enctype="multipart/form-data">

        <!-- ── Basic Information ── -->
        <div class="cr-section">
            <p class="cr-section-title">Basic Information</p>

            <div class="cr-grid-2" style="margin-bottom:1.25rem; align-items:start;">

                <!-- Cover Image -->
                <div class="cr-field">
                    <label>Cover Image (Main)</label>
                    <div class="cr-upload-box" id="coverUploadBox" onclick="window.clickFileInput(this)" style="min-height: 80px; padding: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5">
                            <polyline points="16 16 12 12 8 16"/>
                            <line x1="12" y1="12" x2="12" y2="21"/>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                        </svg>
                        <p style="font-size:0.75rem;">Upload Cover</p>
                        <img id="coverPreview" class="cr-upload-preview" src="" alt="Cover preview" style="max-height: 120px;"> 
                        <input type="file" id="coverImageInput" name="cover_image" accept="image/*" style="display:none;" onchange="window.previewImage(this)">
                    </div>

                    <label style="margin-top: 1rem;">Additional Photos (Gallery)</label>
                    <div class="cr-upload-box" id="galleryUploadBox" onclick="window.clickFileInput(this)" style="min-height: 80px; padding: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <p style="font-size:0.75rem;">Upload Multiple (Optional)</p>
                        <input type="file" id="galleryImageInput" name="gallery_images[]" accept="image/*" multiple style="display:none;" onchange="window.previewGalleryImages(this)">
                    </div>
                    <div id="galleryPreview" style="display:flex; gap:8px; flex-wrap:wrap; margin-top:8px;"></div>
                </div>

                <!-- Right column: Title + Description -->
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    <div class="cr-field">
                        <label for="recipeTitle">Recipe Title</label>
                        <input type="text" id="recipeTitle" name="title"
                               class="cr-input" placeholder="e.g. Delicious Vegan lentil Soup">
                    </div>
                    <div class="cr-field">
                        <label for="recipeDesc">Description</label>
                        <textarea id="recipeDesc" name="description" class="cr-textarea"
                                  placeholder="A brief and appealing description of your recipe…"
                                  rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Recipe Details ── -->
        <div class="cr-section">
            <p class="cr-section-title">Recipe Details</p>

            <!-- Category row -->
            <div class="cr-field" style="margin-bottom:1.25rem;">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="cr-select cr-input">
                    <?php if (empty($categories)): ?>
                        <option value="1">General</option>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="cr-grid-3">
                <div class="cr-field">
                    <label for="difficulty">Difficulty</label>
                    <select id="difficulty" name="difficulty" class="cr-select cr-input">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div class="cr-field">
                    <label for="prepTime">Prep Time (minutes)</label>
                    <input type="number" id="prepTime" name="prep_time"
                           class="cr-input" placeholder="e.g. 15" min="1">
                </div>
                <div class="cr-field">
                    <label for="cookTime">Cook Time (minutes)</label>
                    <input type="number" id="cookTime" name="cook_time"
                           class="cr-input" placeholder="e.g. 40" min="1">
                </div>
            </div>
        </div>

        <!-- ── Ingredients ── -->
        <div class="cr-section">
            <p class="cr-section-title">Ingredients</p>

            <table class="cr-ingredients-table">
                <thead>
                    <tr>
                        <th class="cr-ing-name">Name</th>
                        <th class="cr-ing-qty">Quantity</th>
                        <th class="cr-ing-unit">Unit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="ingredientsBody">
                    <!-- seed rows -->
                    <tr>
                        <td><input type="text" class="cr-input" name="ingredients[0][name]" value="Chicken Breast" placeholder="Ingredient"></td>
                        <td><input type="number" class="cr-input" name="ingredients[0][quantity]" value="2" min="0" step="0.01"></td>
                        <td>
                                <select class="cr-input cr-select" name="ingredients[0][unit]">
                                    <option>unit</option>
                                    <option>cup</option>
                                    <option>tbsp</option>
                                    <option>tsp</option>
                                    <option>g</option>
                                    <option>kg</option>
                                    <option>ml</option>
                                    <option>l</option>
                                </select>
                            </td>
                        <td><button type="button" class="cr-btn-del" onclick="removeRow(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="cr-input" name="ingredients[1][name]" value="Garlic Cloves" placeholder="Ingredient"></td>
                        <td><input type="number" class="cr-input" name="ingredients[1][quantity]" value="3" min="0" step="0.01"></td>
                        <td>
                            <select class="cr-input cr-select" name="ingredients[1][unit]">
                                <option>unit</option><option>cup</option><option>tbsp</option>
                                <option>tsp</option><option>g</option><option>kg</option>
                                <option>ml</option><option>l</option>
                            </select>
                        </td>
                        <td><button type="button" class="cr-btn-del" onclick="removeRow(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="cr-input" name="ingredients[2][name]" value="Soy Sauce" placeholder="Ingredient"></td>
                        <td><input type="number" class="cr-input" name="ingredients[2][quantity]" value="0.25" min="0" step="0.01"></td>
                        <td>
                            <select class="cr-input cr-select" name="ingredients[2][unit]">
                                <option>unit</option><option selected>cup</option><option>tbsp</option>
                                <option>tsp</option><option>g</option><option>kg</option>
                                <option>ml</option><option>l</option>
                            </select>
                        </td>
                        <td><button type="button" class="cr-btn-del" onclick="removeRow(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button></td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="cr-btn-add-row" onclick="addIngredientRow()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add ingredient
            </button>
        </div>

        <!-- ── Instructions ── -->
        <div class="cr-section">
            <p class="cr-section-title">Instructions</p>

            <div id="steps-wrapper" class="cr-steps-list">
                <!-- Step 1 -->
                <div class="cr-step-item" data-step="1">
                    <div class="cr-step-header">
                        <span class="cr-step-label">Step 1 Description</span>
                        <button type="button" class="cr-btn-del" onclick="removeStep(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <textarea class="cr-textarea" name="steps[]" rows="2"
                              placeholder="Describe this step…">Chop the chicken breast into bite-sized pieces and mince the garlic.</textarea>

                    <p class="cr-step-img-label">Step 1 Image (Optional)</p>
                    <div class="cr-step-img-upload" onclick="window.clickFileInput(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        Click to upload image
                        <input type="file" name="step_images[]" accept="image/*" style="display:none;" onchange="window.previewStepImage(this)">
                    </div>
                    <img class="cr-step-preview" src="" alt="">
                </div>

                <!-- Step 2 -->
                <div class="cr-step-item" data-step="2">
                    <div class="cr-step-header">
                        <span class="cr-step-label">Step 2 Description</span>
                        <button type="button" class="cr-btn-del" onclick="window.removeStep(this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    
                    <textarea class="cr-textarea" name="steps[]" rows="2" placeholder="Describe this step…">In a large pan, heat olive oil over medium heat. Add chicken and cook until browned.</textarea>
                
                    <p class="cr-step-img-label">Step 2 Image (Optional)</p>
                    
                    <div class="cr-step-img-upload" onclick="window.clickFileInput(this)" style="cursor: pointer; border: 1.5px dashed #ddd; padding: 15px; text-align: center; border-radius: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 5px;">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <br>Click to upload image
                    </div>
                
                    <input type="file" name="step_images[]" accept="image/*" style="display:none;" onchange="window.previewStepImage(this)">
                    
                    <img class="cr-step-preview" src="" alt="Step Preview" style="display:none; width:100%; margin-top:10px; border-radius:8px; object-fit: cover; max-height: 200px;">
                </div>
            </div>

            <button type="button" class="cr-btn-add-row" onclick="window.addStep()">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add Step
            </button>
        </div>

        <!-- ── Tags ── -->
        <div class="cr-section">
            <p class="cr-section-title">Tags</p>

            <div class="cr-tags-selected" id="tagsSelected">
                <!-- Tags will go here -->
            </div>
            
            <div id="hiddenTagsContainer"></div>

            <p style="font-size:0.8125rem;font-weight:600;color:#555;margin:0 0 0.4rem;">Add Tags</p>
            <div class="cr-add-tag-row">
                <select id="tagSelect" class="cr-input cr-select">
                    <option value="">Select or type a tag</option>
                    <option>Breakfast</option><option>Lunch</option><option>Dinner</option>
                    <option>Dessert</option><option>Snack</option><option>Vegan</option>
                    <option>Vegetarian</option><option>Gluten-Free</option><option>Quick &amp; Easy</option>
                    <option>Spicy</option><option>Italian</option><option>Asian</option>
                    <option>Mexican</option><option>Grilled</option><option>Baked</option>
                </select>
                <button type="button" class="cr-btn-add-tag" onclick="addTag()">Add selected Tag</button>
            </div>
        </div>

        <!-- ── Actions ── -->
        <div class="cr-actions">
            <button type="button" class="cr-btn-cancel"
                    onclick="window.location.href='/smart-recipes/frontend/pages/user/profile.php'">
                Cancel
            </button>
            <button type="submit" class="cr-btn-submit">Submit Recipe</button>
        </div>

    </form>
</div>
</div>

<?php
// XÓA SẠCH SCRIPT DEMO CŨ VÀ NHÚNG FILE JS CHUẨN
$additionalScripts = ['../../assets/js/pages/create_recipe.js'];
include '../../includes/footer.php';
?>