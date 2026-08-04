<?php
// =============================================================
//  bootstrap.php  –  Smart Recipe shared helpers + session
// =============================================================
require_once __DIR__ . '/../../backend/config/database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Path constants ────────────────────────────────────────────
$_req_uri = $_SERVER['REQUEST_URI'] ?? '';
$_app_prefix = (strpos($_req_uri, '/smart-recipes') === 0) ? '/smart-recipes' : '';
define('APP_PREFIX',  $_app_prefix);
define('BASE_URL',    $_app_prefix . '/frontend');
define('USERS_JSON',  __DIR__ . '/../data/users.json');

// ── Redirect helper ───────────────────────────────────────────
function redirect_to($path)
{
    header('Location: ' . $path);
    exit;
}

// ── Session helpers ───────────────────────────────────────────
function current_user()
{
    // Kiểm tra xem 'user' có tồn tại không rồi mới lấy, không có thì trả về null
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function is_logged_in()
{
    // Chỉ kiểm tra xem session 'user' đã được tạo chưa
    return isset($_SESSION['user']);
}

function is_admin()
{
    // Kiểm tra an toàn 3 lớp để không bị lỗi "Trying to access array offset on null"
    return isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect_to(APP_PREFIX . '/frontend/pages/auth/sign_in.php');
    }
}

function require_admin()
{
    if (!is_admin()) {
        redirect_to(APP_PREFIX . '/frontend/pages/home.php');
    }
}

// ── User JSON helpers ─────────────────────────────────────────
function get_users_data(): array
{
    if (!file_exists(USERS_JSON)) return ['users' => []];
    $json = file_get_contents(USERS_JSON);
    return json_decode($json, true) ?? ['users' => []];
}

function save_users_data(array $data): void
{
    file_put_contents(
        USERS_JSON,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function get_all_users(): array
{
    return get_users_data()['users'] ?? [];
}

function find_user_by_id($id): ?array
{
    foreach (get_all_users() as $user) {
        if (($user['id'] ?? '') === $id) {
            return $user;
        }
    }
    return null;
}

// ── Recipe helpers ────────────────────────────────────────────
function get_all_recipes()
{
    global $conn;

    $dbRecipes = [];
    if ($conn) {
        $sql = "SELECT r.id, r.title, r.description, r.main_image, r.prep_time, r.cook_time,
                       r.difficulty, r.servings, r.created_at, r.view_count, r.is_featured,
                       COALESCE(u.display_name, u.username) AS author,
                       c.slug AS category,
                       (SELECT ROUND(AVG(rating), 1) FROM ratings WHERE recipe_id = r.id) AS average_rating,
                       (SELECT COUNT(id) FROM ratings WHERE recipe_id = r.id) AS review_count
                FROM recipes r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN categories c ON r.category_id = c.id
                WHERE r.is_published = 1
                ORDER BY r.created_at DESC";
        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $total = ((int)$row['prep_time'] + (int)$row['cook_time']);
                
                // Xử lý ảnh: nếu là URL đầy đủ thì giữ nguyên, nếu là tên file thì thêm path
                $mainImage = $row['main_image'] ?? '';
                if (strpos($mainImage, 'http') === 0) {
                    $imageUrl = $mainImage;
                } else {
                    $foodPhotos = [
                        'https://images.unsplash.com/photo-1621996346565-e3d5d6281293?w=800&fit=crop',
                        'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&fit=crop',
                        'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&fit=crop',
                        'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&fit=crop',
                        'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800&fit=crop'
                    ];
                    $recId = (int)($row['id'] ?? 1);
                    $imageUrl = $foodPhotos[$recId % count($foodPhotos)];
                }
                
                $dbRecipes[] = [
                    'id'          => (int)$row['id'],
                    'title'       => $row['title'],
                    'description' => $row['description'] ?? '',
                    'image'       => $imageUrl,
                    'ready_in'    => $total > 0 ? $total . ' min' : '30 min',
                    'servings'    => (int)$row['servings'],
                    'author'      => $row['author'] ?? 'Unknown',
                    'rating'      => isset($row['average_rating']) && $row['average_rating'] !== null ? (float)$row['average_rating'] : 0,
                    'review_count'=> isset($row['review_count']) ? (int)$row['review_count'] : 0,
                    'views'       => (int)$row['view_count'],
                    'created_at'  => strtotime($row['created_at']),
                    'ingredients' => [],
                    'steps'       => [],
                    'tags'        => [],
                    'category'    => $row['category'] ?? 'dinner',
                    'difficulty'  => $row['difficulty'] ?? 'Medium',
                    'trending'    => (int)$row['view_count'] > 5,
                    'favorite'    => (int)$row['is_featured'] === 1,
                ];
            }
        }
    }

    if (empty($dbRecipes)) {
        // Fallback comprehensive recipe dataset for instant live cloud preview
        $dbRecipes = [
            // DINNER & PASTA
            [
                'id' => 1,
                'title' => 'Creamy Garlic Pasta',
                'description' => 'Rich and smooth pasta tossed with roasted garlic, parmesan, and fresh herbs.',
                'image' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281293?w=800&fit=crop',
                'ready_in' => '25 min',
                'servings' => 2,
                'author' => 'Chef Alex',
                'rating' => 4.8,
                'review_count' => 14,
                'views' => 1200,
                'created_at' => time(),
                'ingredients' => ['Pasta', 'Garlic', 'Heavy Cream', 'Parmesan Cheese', 'Olive Oil', 'Parsley'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Boil pasta in salted water until al dente.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Sauté garlic in olive oil, add cream and parmesan.', 'image' => null],
                    ['step_number' => 3, 'instruction' => 'Toss pasta with sauce and garnish with parsley.', 'image' => null]
                ],
                'tags' => ['Italian', 'Pasta', 'Quick'],
                'category' => 'dinner',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 2,
                'title' => 'Avocado Tuna Salad',
                'description' => 'Fresh avocado mashed with wild tuna, lime juice, and cilantro.',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&fit=crop',
                'ready_in' => '15 min',
                'servings' => 2,
                'author' => 'Nguyen Linh',
                'rating' => 4.9,
                'review_count' => 22,
                'views' => 2500,
                'created_at' => time() - 3600,
                'ingredients' => ['Avocado', 'Tuna Can', 'Lime', 'Red Onion', 'Cilantro'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Dice avocado and mix with canned tuna.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Add lime juice, diced onion, and cilantro.', 'image' => null]
                ],
                'tags' => ['Healthy', 'Low Carb', 'Quick'],
                'category' => 'healthy',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 3,
                'title' => 'Grilled Chicken Salad',
                'description' => 'Juicy grilled chicken breast over mixed greens, cherry tomatoes, and balsamic dressing.',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&fit=crop',
                'ready_in' => '20 min',
                'servings' => 1,
                'author' => 'Admin Chef',
                'rating' => 4.7,
                'review_count' => 9,
                'views' => 950,
                'created_at' => time() - 7200,
                'ingredients' => ['Chicken Breast', 'Mixed Greens', 'Cherry Tomatoes', 'Balsamic Vinegar', 'Olive Oil'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Season and grill chicken breast for 6-8 minutes per side.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Slice chicken and place over fresh mixed greens.', 'image' => null]
                ],
                'tags' => ['High Protein', 'Salad'],
                'category' => 'lunch',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            // BREAKFAST & BRUNCH
            [
                'id' => 4,
                'title' => 'Fluffy Buttermilk Pancakes',
                'description' => 'Light and airy golden pancakes served with cultured butter and warm maple syrup.',
                'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=800&fit=crop',
                'ready_in' => '20 min',
                'servings' => 4,
                'author' => 'Jamie Oliver',
                'rating' => 4.9,
                'review_count' => 35,
                'views' => 2800,
                'created_at' => time() - 10000,
                'ingredients' => ['Flour', 'Buttermilk', 'Eggs', 'Butter', 'Maple Syrup', 'Baking Powder'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Whisk dry and wet ingredients separately, then combine gently.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Pour batter onto a hot buttered griddle and cook until golden.', 'image' => null]
                ],
                'tags' => ['Breakfast', 'Sweet', 'Family'],
                'category' => 'breakfast-brunch',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 5,
                'title' => 'Classic French Toast',
                'description' => 'Thick slices of brioche soaked in cinnamon vanilla custard, toasted to perfection.',
                'image' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=800&fit=crop',
                'ready_in' => '15 min',
                'servings' => 2,
                'author' => 'Gordon Ramsay',
                'rating' => 4.8,
                'review_count' => 18,
                'views' => 2200,
                'created_at' => time() - 15000,
                'ingredients' => ['Brioche Bread', 'Eggs', 'Milk', 'Vanilla Extract', 'Cinnamon', 'Berries'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Whisk eggs, milk, vanilla and cinnamon in a shallow bowl.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Dip brioche and sear in a hot skillet with melted butter.', 'image' => null]
                ],
                'tags' => ['Breakfast', 'Brunch', 'Sweet'],
                'category' => 'breakfast-brunch',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 6,
                'title' => 'Avocado Toast with Poached Egg',
                'description' => 'Creamy mashed avocado on toasted sourdough topped with a runny poached egg.',
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800&fit=crop',
                'ready_in' => '10 min',
                'servings' => 1,
                'author' => 'Nguyen Linh',
                'rating' => 4.9,
                'review_count' => 42,
                'views' => 3100,
                'created_at' => time() - 20000,
                'ingredients' => ['Sourdough', 'Avocado', 'Egg', 'Lemon', 'Chili Flakes', 'Sea Salt'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Toast sourdough and mash avocado with lemon juice and salt.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Poach egg in simmering water for 3 minutes and place on top.', 'image' => null]
                ],
                'tags' => ['Breakfast', 'Healthy', 'Quick'],
                'category' => 'breakfast-brunch',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 7,
                'title' => 'Açaí Breakfast Bowl',
                'description' => 'Superfood açaí smoothie topped with chia seeds, banana slices, and fresh granola.',
                'image' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?w=800&fit=crop',
                'ready_in' => '10 min',
                'servings' => 1,
                'author' => 'Chef Alex',
                'rating' => 4.7,
                'review_count' => 15,
                'views' => 1900,
                'created_at' => time() - 25000,
                'ingredients' => ['Açaí Puree', 'Banana', 'Blueberries', 'Granola', 'Honey', 'Chia Seeds'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Blend frozen açaí and banana until thick and smooth.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Pour into bowl and arrange fresh fruit and granola on top.', 'image' => null]
                ],
                'tags' => ['Superfood', 'Healthy', 'Vegan'],
                'category' => 'breakfast-brunch',
                'difficulty' => 'Easy',
                'trending' => false,
                'favorite' => true
            ],
            // ASIAN FLAVORS
            [
                'id' => 8,
                'title' => 'Vietnamese Pho Bo',
                'description' => 'Aromatic beef noodle soup infused with star anise, ginger, and thin beef slices.',
                'image' => 'https://images.unsplash.com/photo-1576577445504-6af96477db52?w=800&fit=crop',
                'ready_in' => '120 min',
                'servings' => 4,
                'author' => 'Rachael Ray',
                'rating' => 4.9,
                'review_count' => 50,
                'views' => 2900,
                'created_at' => time() - 30000,
                'ingredients' => ['Beef Bones', 'Rice Noodles', 'Beef Slices', 'Star Anise', 'Ginger', 'Bean Sprouts', 'Basil'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Simmer beef bones with charred ginger and spices for 3 hours.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Assemble rice noodles, raw beef slices, and pour boiling broth over.', 'image' => null]
                ],
                'tags' => ['Vietnamese', 'Noodles', 'Soup'],
                'category' => 'asian-flavors',
                'difficulty' => 'Hard',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 9,
                'title' => 'Pad Thai Noodles',
                'description' => 'Authentic Thai stir-fried rice noodles with jumbo shrimp, tofu, and crushed peanuts.',
                'image' => 'https://images.unsplash.com/photo-1559314809-0d155014e29e?w=800&fit=crop',
                'ready_in' => '30 min',
                'servings' => 2,
                'author' => 'Gordon Ramsay',
                'rating' => 4.8,
                'review_count' => 28,
                'views' => 1850,
                'created_at' => time() - 35000,
                'ingredients' => ['Rice Noodles', 'Shrimp', 'Tofu', 'Tamarind Paste', 'Peanuts', 'Lime', 'Egg'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Stir-fry shrimp and tofu in a hot wok.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Add soaked noodles and tamarind sauce, toss until coated.', 'image' => null]
                ],
                'tags' => ['Thai', 'Noodles', 'Asian'],
                'category' => 'asian-flavors',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 10,
                'title' => 'Miso Ramen',
                'description' => 'Rich savory Japanese ramen with chashu pork belly, soft boiled egg, and nori.',
                'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800&fit=crop',
                'ready_in' => '45 min',
                'servings' => 2,
                'author' => 'Jamie Oliver',
                'rating' => 4.9,
                'review_count' => 31,
                'views' => 2600,
                'created_at' => time() - 40000,
                'ingredients' => ['Ramen Noodles', 'Miso Paste', 'Pork Belly', 'Soft Egg', 'Nori', 'Green Onions'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Whisk red miso paste into hot dashi chicken broth.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Cook ramen noodles and top with sliced pork and ramen egg.', 'image' => null]
                ],
                'tags' => ['Japanese', 'Ramen', 'Soup'],
                'category' => 'asian-flavors',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 11,
                'title' => 'Beef Teriyaki Bowl',
                'description' => 'Tender sliced beef simmered in a sweet soy teriyaki glaze served over jasmine rice.',
                'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=800&fit=crop',
                'ready_in' => '20 min',
                'servings' => 2,
                'author' => 'Admin Chef',
                'rating' => 4.7,
                'review_count' => 19,
                'views' => 1900,
                'created_at' => time() - 45000,
                'ingredients' => ['Beef Slices', 'Teriyaki Sauce', 'Jasmine Rice', 'Broccoli', 'Sesame Seeds'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Sear beef in a wok and glaze with teriyaki sauce.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Serve over steamed rice with broccoli.', 'image' => null]
                ],
                'tags' => ['Asian', 'Beef', 'Quick'],
                'category' => 'asian-flavors',
                'difficulty' => 'Easy',
                'trending' => false,
                'favorite' => true
            ],
            // DESSERTS
            [
                'id' => 12,
                'title' => 'Chocolate Lava Cake',
                'description' => 'Decadent individual chocolate cakes with a warm molten chocolate center.',
                'image' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=800&fit=crop',
                'ready_in' => '25 min',
                'servings' => 4,
                'author' => 'Gordon Ramsay',
                'rating' => 4.9,
                'review_count' => 48,
                'views' => 3500,
                'created_at' => time() - 50000,
                'ingredients' => ['Dark Chocolate', 'Butter', 'Eggs', 'Sugar', 'Flour', 'Vanilla'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Melt chocolate and butter, whisk in eggs and sugar.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Bake in ramekins for 12 minutes until edges are firm and center is soft.', 'image' => null]
                ],
                'tags' => ['Dessert', 'Chocolate', 'Baking'],
                'category' => 'desserts',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 13,
                'title' => 'Classic Tiramisu',
                'description' => 'Italian ladyfingers dipped in espresso, layered with whipped mascarpone cream.',
                'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=800&fit=crop',
                'ready_in' => '30 min',
                'servings' => 8,
                'author' => 'Jamie Oliver',
                'rating' => 4.9,
                'review_count' => 39,
                'views' => 3100,
                'created_at' => time() - 55000,
                'ingredients' => ['Ladyfingers', 'Mascarpone', 'Espresso', 'Cocoa Powder', 'Heavy Cream', 'Egg Yolks'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Dip ladyfingers in strong coffee.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Layer with sweet mascarpone cream and dust with cocoa powder.', 'image' => null]
                ],
                'tags' => ['Italian', 'Dessert', 'No-Bake'],
                'category' => 'desserts',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 14,
                'title' => 'Crème Brûlée',
                'description' => 'Silky vanilla bean custard with a crisp caramelized sugar crust on top.',
                'image' => 'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=800&fit=crop',
                'ready_in' => '60 min',
                'servings' => 4,
                'author' => 'Rachael Ray',
                'rating' => 4.8,
                'review_count' => 24,
                'views' => 1800,
                'created_at' => time() - 60000,
                'ingredients' => ['Heavy Cream', 'Egg Yolks', 'Sugar', 'Vanilla Bean'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Bake custard in water bath until set.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Sprinkle sugar on top and torch until caramelized.', 'image' => null]
                ],
                'tags' => ['French', 'Dessert', 'Gourmet'],
                'category' => 'desserts',
                'difficulty' => 'Hard',
                'trending' => false,
                'favorite' => true
            ],
            // SOUPS
            [
                'id' => 15,
                'title' => 'French Onion Soup',
                'description' => 'Rich caramelized onion soup topped with toasted baguette and melted Gruyère cheese.',
                'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&fit=crop',
                'ready_in' => '60 min',
                'servings' => 4,
                'author' => 'Chef Alex',
                'rating' => 4.8,
                'review_count' => 16,
                'views' => 1400,
                'created_at' => time() - 65000,
                'ingredients' => ['Yellow Onions', 'Beef Broth', 'Gruyere Cheese', 'Baguette', 'Butter', 'Thyme'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Slowly caramelize onions for 45 minutes until deep brown.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Add broth, top with baguette slice and Gruyère, broil until bubbling.', 'image' => null]
                ],
                'tags' => ['Soup', 'French', 'Cheese'],
                'category' => 'soups',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 16,
                'title' => 'Tom Yum Soup',
                'description' => 'Spicy and sour Thai lemongrass soup loaded with juicy shrimp and mushrooms.',
                'image' => 'https://images.unsplash.com/photo-1562802378-063ec186a863?w=800&fit=crop',
                'ready_in' => '30 min',
                'servings' => 4,
                'author' => 'Gordon Ramsay',
                'rating' => 4.9,
                'review_count' => 27,
                'views' => 1650,
                'created_at' => time() - 70000,
                'ingredients' => ['Shrimp', 'Lemongrass', 'Kaffir Lime Leaves', 'Mushrooms', 'Chili Paste', 'Lime Juice'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Boil broth with bruised lemongrass and lime leaves.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Add shrimp and mushrooms, season with fish sauce and lime.', 'image' => null]
                ],
                'tags' => ['Soup', 'Thai', 'Spicy'],
                'category' => 'soups',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 17,
                'title' => 'Creamy Pumpkin Soup',
                'description' => 'Velvety roasted pumpkin soup garnished with toasted pumpkin seeds and heavy cream.',
                'image' => 'https://images.unsplash.com/photo-1603569283847-aa295f0d016a?w=800&fit=crop',
                'ready_in' => '35 min',
                'servings' => 4,
                'author' => 'Nguyen Linh',
                'rating' => 4.7,
                'review_count' => 12,
                'views' => 1100,
                'created_at' => time() - 75000,
                'ingredients' => ['Pumpkin Puree', 'Vegetable Broth', 'Heavy Cream', 'Nutmeg', 'Garlic', 'Onion'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Sauté onion and garlic, add roasted pumpkin and broth.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Blend until silky smooth and swirl in heavy cream.', 'image' => null]
                ],
                'tags' => ['Soup', 'Comfort Food', 'Vegetarian'],
                'category' => 'soups',
                'difficulty' => 'Easy',
                'trending' => false,
                'favorite' => true
            ],
            // DRINKS & COCKTAILS
            [
                'id' => 18,
                'title' => 'Watermelon Mint Cooler',
                'description' => 'Hydrating summer drink made with fresh blended watermelon, lime, and crushed mint.',
                'image' => 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=800&fit=crop',
                'ready_in' => '10 min',
                'servings' => 4,
                'author' => 'Rachael Ray',
                'rating' => 4.8,
                'review_count' => 15,
                'views' => 940,
                'created_at' => time() - 80000,
                'ingredients' => ['Watermelon', 'Fresh Mint', 'Lime Juice', 'Sparkling Water', 'Ice'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Blend seedless watermelon cubes until smooth.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Strain into glasses with crushed mint and sparkling water.', 'image' => null]
                ],
                'tags' => ['Drink', 'Summer', 'Refreshing'],
                'category' => 'drinks-cocktails',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 19,
                'title' => 'Classic Mojito',
                'description' => 'Cuban highball cocktail featuring white rum, muddled fresh mint, lime juice, and soda.',
                'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=800&fit=crop',
                'ready_in' => '5 min',
                'servings' => 1,
                'author' => 'Admin Chef',
                'rating' => 4.9,
                'review_count' => 33,
                'views' => 2100,
                'created_at' => time() - 85000,
                'ingredients' => ['White Rum', 'Fresh Mint', 'Lime Juice', 'Simple Syrup', 'Club Soda'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Muddle mint leaves with lime juice and sugar syrup.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Add rum, fill glass with ice and top with soda water.', 'image' => null]
                ],
                'tags' => ['Cocktail', 'Drink', 'Party'],
                'category' => 'drinks-cocktails',
                'difficulty' => 'Easy',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 20,
                'title' => 'Mango Banana Smoothie',
                'description' => 'Thick creamy tropical smoothie blended with ripe mango, banana, and Greek yogurt.',
                'image' => 'https://images.unsplash.com/photo-1497534446932-c925b458314e?w=800&fit=crop',
                'ready_in' => '5 min',
                'servings' => 2,
                'author' => 'Jamie Oliver',
                'rating' => 4.7,
                'review_count' => 20,
                'views' => 1500,
                'created_at' => time() - 90000,
                'ingredients' => ['Ripe Mango', 'Banana', 'Greek Yogurt', 'Honey', 'Almond Milk'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Add all ingredients to high-speed blender.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Blend for 60 seconds until thick and frosty.', 'image' => null]
                ],
                'tags' => ['Smoothie', 'Healthy', 'Breakfast'],
                'category' => 'drinks-cocktails',
                'difficulty' => 'Easy',
                'trending' => false,
                'favorite' => true
            ],
            // APPETIZERS & COMFORT FOOD
            [
                'id' => 21,
                'title' => 'Korean Fried Chicken',
                'description' => 'Extra crispy twice-fried chicken wings tossed in a sticky sweet and spicy gochujang sauce.',
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800&fit=crop',
                'ready_in' => '45 min',
                'servings' => 4,
                'author' => 'Chef Alex',
                'rating' => 4.9,
                'review_count' => 45,
                'views' => 3400,
                'created_at' => time() - 95000,
                'ingredients' => ['Chicken Wings', 'Cornstarch', 'Gochujang Paste', 'Honey', 'Garlic', 'Sesame'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Coat chicken in cornstarch and double-fry for crispiness.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Toss fried wings in bubbling gochujang glaze.', 'image' => null]
                ],
                'tags' => ['Korean', 'Chicken', 'Comfort Food'],
                'category' => 'comfort-food',
                'difficulty' => 'Hard',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 22,
                'title' => 'Baked Macaroni and Cheese',
                'description' => 'Ultra creamy macaroni coated in cheddar cheese sauce with a crunchy breadcrumb crust.',
                'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&fit=crop',
                'ready_in' => '40 min',
                'servings' => 6,
                'author' => 'Gordon Ramsay',
                'rating' => 4.8,
                'review_count' => 38,
                'views' => 2700,
                'created_at' => time() - 100000,
                'ingredients' => ['Macaroni', 'Cheddar Cheese', 'Milk', 'Butter', 'Breadcrumbs', 'Mustard Powder'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Make cheese béchamel sauce and fold in cooked macaroni.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Top with buttered breadcrumbs and bake until golden.', 'image' => null]
                ],
                'tags' => ['Comfort Food', 'Pasta', 'Cheese'],
                'category' => 'comfort-food',
                'difficulty' => 'Medium',
                'trending' => true,
                'favorite' => true
            ],
            [
                'id' => 23,
                'title' => 'Caprese Bruschetta',
                'description' => 'Toasted sourdough bread topped with vine-ripened tomatoes, fresh mozzarella, and basil.',
                'image' => 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?w=800&fit=crop',
                'ready_in' => '15 min',
                'servings' => 4,
                'author' => 'Rachael Ray',
                'rating' => 4.7,
                'review_count' => 14,
                'views' => 920,
                'created_at' => time() - 105000,
                'ingredients' => ['Baguette', 'Tomatoes', 'Mozzarella', 'Fresh Basil', 'Garlic', 'Balsamic Glaze'],
                'steps' => [
                    ['step_number' => 1, 'instruction' => 'Toast baguette slices and rub with raw garlic clove.', 'image' => null],
                    ['step_number' => 2, 'instruction' => 'Top with tomato-basil mixture and drizzle balsamic glaze.', 'image' => null]
                ],
                'tags' => ['Appetizer', 'Italian', 'Quick'],
                'category' => 'appetizers-snacks',
                'difficulty' => 'Easy',
                'trending' => false,
                'favorite' => true
            ]
        ];
    }

    return $dbRecipes;
}

function get_next_recipe_id()
{
    $ids = array_column(get_all_recipes(), 'id');
    return empty($ids) ? 1 : (max($ids) + 1);
}

function find_recipe_by_id($id)
{
    global $conn;

    // Tìm trong DB
    if ($conn) {
        $stmt = $conn->prepare(
            "SELECT r.*, COALESCE(u.display_name, u.username) AS author, c.slug AS category_slug,
                    (SELECT ROUND(AVG(rating), 1) FROM ratings WHERE recipe_id = r.id) AS average_rating,
                    (SELECT COUNT(id) FROM ratings WHERE recipe_id = r.id) AS review_count
             FROM recipes r
             LEFT JOIN users u ON r.user_id = u.id
             LEFT JOIN categories c ON r.category_id = c.id
             WHERE r.id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            // Lấy ingredients với định lượng
            $ingredients = [];
            $ing_res = $conn->query("SELECT i.name, ri.quantity, ri.unit FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = " . (int)$id . " ORDER BY ri.display_order");
            if ($ing_res) {
                while ($ing = $ing_res->fetch_assoc()) {
                    // Format: "200g chicken breast" hoặc "2 eggs"
                    $qty = trim($ing['quantity'] ?? '');
                    $unit = trim($ing['unit'] ?? '');
                    $name = trim($ing['name']);
                    
                    if ($qty && $unit) {
                        // Nếu unit là "unit" hoặc "piece", chỉ hiện số lượng
                        if (in_array(strtolower($unit), ['unit', 'piece', 'pieces', 'whole', ''])) {
                            $ingredients[] = $qty . ' ' . $name;
                        } else {
                            // Thêm khoảng trắng giữa số lượng và đơn vị (vd: 1 cup, 200 g)
                            $ingredients[] = $qty . ' ' . $unit . ' ' . $name;
                        }
                    } elseif ($qty) {
                        $ingredients[] = $qty . ' ' . $name;
                    } else {
                        $ingredients[] = $name;
                    }
                }
            }

            // Lấy steps với ảnh
            $steps = [];
            $step_res = $conn->query("SELECT step_number, instruction, image_url FROM recipe_directions WHERE recipe_id = " . (int)$id . " ORDER BY step_number");
            if ($step_res) {
                while ($s = $step_res->fetch_assoc()) {
                    $stepImage = null;
                    if (!empty($s['image_url'])) {
                        // Nếu là URL đầy đủ thì giữ nguyên, nếu là tên file thì thêm path
                        if (strpos($s['image_url'], 'http') === 0) {
                            $stepImage = $s['image_url'];
                        } elseif (!empty($s['image_url']) && file_exists(__DIR__ . '/../assets/images/recipes/' . $s['image_url'])) {
                            $stepImage = BASE_URL . '/assets/images/recipes/' . $s['image_url'];
                        } else {
                            $stepImage = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&fit=crop';
                        }
                    }
                    $steps[] = [
                        'step_number' => (int)$s['step_number'],
                        'instruction' => $s['instruction'],
                        'image' => $stepImage
                    ];
                }
            }

            // Xử lý ảnh: nếu là URL đầy đủ thì giữ nguyên, nếu là tên file thì thêm path
            $mainImage = $row['main_image'];
            if (strpos($mainImage, 'http') === 0) {
                $imageUrl = $mainImage;
            } elseif (!empty($mainImage) && file_exists(__DIR__ . '/../assets/images/recipes/' . $mainImage)) {
                $imageUrl = BASE_URL . '/assets/images/recipes/' . $mainImage;
            } else {
                $imageUrl = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&fit=crop';
            }

            $total = ((int)$row['prep_time'] + (int)$row['cook_time']);
            return [
                'id'          => (int)$row['id'],
                'title'       => $row['title'],
                'description' => $row['description'] ?? '',
                'image'       => $imageUrl,
                'ready_in'    => $total > 0 ? $total . ' min' : '30 min',
                'servings'    => (int)$row['servings'],
                'author'      => $row['author'] ?? 'Unknown',
                'rating'      => isset($row['average_rating']) && $row['average_rating'] !== null ? (float)$row['average_rating'] : 0,
                'rating_count'=> isset($row['review_count']) ? (int)$row['review_count'] : 0,
                'views'       => (int)$row['view_count'],
                'created_at'  => strtotime($row['created_at']),
                'ingredients' => $ingredients,
                'steps'       => $steps ?: [['step_number' => 1, 'instruction' => 'No steps provided.', 'image' => null]],
                'tags'        => [],
                'category'    => $row['category_slug'] ?? 'dinner',
                'difficulty'  => $row['difficulty'] ?? 'Medium',
            ];
        }
    }

    // Fallback: search in get_all_recipes() if DB row not found
    $all = get_all_recipes();
    foreach ($all as $rec) {
        if ((int)$rec['id'] === (int)$id) {
            return $rec;
        }
    }
    return $all[0] ?? null;
}

// ── Bookmark helpers ──────────────────────────────────────────
function get_bookmark_ids()
{
    return $_SESSION['bookmarks'] ?? [];
}

function is_bookmarked($recipeId)
{
    return in_array((int)$recipeId, get_bookmark_ids(), true);
}

function toggle_bookmark($recipeId)
{
    $bookmarks = get_bookmark_ids();
    if (in_array((int)$recipeId, $bookmarks, true)) {
        $_SESSION['bookmarks'] = array_values(
            array_filter($bookmarks, fn($id) => (int)$id !== (int)$recipeId)
        );
    } else {
        $bookmarks[]           = (int)$recipeId;
        $_SESSION['bookmarks'] = array_values(array_unique($bookmarks));
    }
}

// ── Category helpers ────────────────────────────────────────
function get_all_categories()
{
    global $conn;
    $categories = [];
    if ($conn) {
        try {
            $result = $conn->query("SELECT * FROM categories ORDER BY display_order ASC, name ASC");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
        } catch (Throwable $e) {
            $categories = [];
        }
    }

    if (empty($categories)) {
        $categories = [
            ['id' => 1, 'name' => 'Breakfast & Brunch', 'slug' => 'breakfast-brunch', 'description' => 'Delicious morning recipes and brunch spreads', 'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=800&h=500&fit=crop', 'display_order' => 1],
            ['id' => 2, 'name' => 'Lunch', 'slug' => 'lunch', 'description' => 'Quick, satisfying sandwiches, salads, and bowls', 'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=800&h=500&fit=crop', 'display_order' => 2],
            ['id' => 3, 'name' => 'Dinner', 'slug' => 'dinner', 'description' => 'Hearty dinners and pasta dishes for family and friends', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3d5d6281293?w=800&h=500&fit=crop', 'display_order' => 3],
            ['id' => 4, 'name' => 'Appetizers & Snacks', 'slug' => 'appetizers-snacks', 'description' => 'Crispy finger foods, bruschetta, and party starters', 'image' => 'https://images.unsplash.com/photo-1541745537411-b8046dc6d66c?w=800&h=500&fit=crop', 'display_order' => 4],
            ['id' => 5, 'name' => 'Desserts', 'slug' => 'desserts', 'description' => 'Decadent chocolate cakes, tiramisu, and sweet treats', 'image' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62?w=800&h=500&fit=crop', 'display_order' => 5],
            ['id' => 6, 'name' => 'Drinks & Cocktails', 'slug' => 'drinks-cocktails', 'description' => 'Refreshing mocktails, fruit coolers, and classic mojitos', 'image' => 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=800&h=500&fit=crop', 'display_order' => 6],
            ['id' => 7, 'name' => 'Soups', 'slug' => 'soups', 'description' => 'Warming French onion, Tom Yum, and pumpkin soups', 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&h=500&fit=crop', 'display_order' => 7],
            ['id' => 8, 'name' => 'Asian Flavors', 'slug' => 'asian-flavors', 'description' => 'Vietnamese Pho, Pad Thai noodles, and Japanese Ramen', 'image' => 'https://images.unsplash.com/photo-1576577445504-6af96477db52?w=800&h=500&fit=crop', 'display_order' => 8],
            ['id' => 9, 'name' => 'Comfort Food', 'slug' => 'comfort-food', 'description' => 'Baked Mac & Cheese, Korean fried chicken, and rich stews', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&h=500&fit=crop', 'display_order' => 9],
            ['id' => 10, 'name' => 'Healthy', 'slug' => 'healthy', 'description' => 'Nutritious low-calorie salads, bowls, and grilled greens', 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&h=500&fit=crop', 'display_order' => 10],
        ];
    }

    return $categories;
}

function find_category_by_slug($slug)
{
    global $conn;
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT * FROM categories WHERE slug = ?");
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                return $row;
            }
        } catch (Throwable $e) {}
    }

    $all = get_all_categories();
    $targetSlug = strtolower(trim($slug));
    foreach ($all as $c) {
        $cSlug = strtolower($c['slug'] ?? '');
        $cName = strtolower($c['name'] ?? '');
        if ($cSlug === $targetSlug || $cName === $targetSlug || strpos($cSlug, $targetSlug) !== false || strpos($targetSlug, $cSlug) !== false) {
            return $c;
        }
    }
    return null;
}

function find_user_by_email($email) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}