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
                $mainImage = $row['main_image'];
                if (strpos($mainImage, 'http') === 0) {
                    $imageUrl = $mainImage;
                } else {
                    $imageUrl = BASE_URL . '/assets/images/recipes/' . $mainImage;
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
                        } else {
                            $stepImage = BASE_URL . '/assets/images/recipes/' . $s['image_url'];
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
            } else {
                $imageUrl = BASE_URL . '/assets/images/recipes/' . $mainImage;
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

    return null;
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
        $result = $conn->query("SELECT * FROM categories ORDER BY display_order ASC, name ASC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
    }
    return $categories;
}

function find_category_by_slug($slug)
{
    global $conn;
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            return $row;
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