<?php
ob_start();
// Header phải gọi trước mọi output
require_once __DIR__ . '/../../frontend/includes/bootstrap.php';
ob_clean();
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn chưa đăng nhập!']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Phương thức không hợp lệ!']);
    exit;
}

function create_slug($string) {
    $search  = ['á','à','ả','ã','ạ','ă','ắ','ằ','ẳ','ẵ','ặ','â','ấ','ầ','ẩ','ẫ','ậ',
                'é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ',
                'í','ì','ỉ','ĩ','ị',
                'ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ',
                'ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự',
                'ý','ỳ','ỷ','ỹ','ỵ','đ'];
    $replace = ['a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
                'e','e','e','e','e','e','e','e','e','e','e',
                'i','i','i','i','i',
                'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
                'u','u','u','u','u','u','u','u','u','u','u',
                'y','y','y','y','y','d'];
    $string = str_replace($search, $replace, mb_strtolower($string));
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $string));
}

$conn->begin_transaction();
try {
    $user_id     = (int)$_SESSION['user']['id'];
    $title       = trim($_POST['title'] ?? '');
    if (empty($title)) throw new Exception("Tiêu đề không được để trống");

    $slug        = create_slug($title) . '-' . time();
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 1);
    $prep_time   = (int)($_POST['prep_time']   ?? 0);
    $cook_time   = (int)($_POST['cook_time']   ?? 0);
    $servings    = (int)($_POST['servings']    ?? 4);

    // Validate difficulty
    $allowed_diff = ['Easy', 'Medium', 'Hard'];
    $difficulty   = in_array($_POST['difficulty'] ?? '', $allowed_diff)
                    ? $_POST['difficulty']
                    : 'Medium';

    // 1. Ảnh bìa
    $main_image = 'default_recipe.jpg';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $ext      = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed  = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) throw new Exception("Định dạng ảnh không hợp lệ!");
        $filename = 'recipe_' . time() . '.' . $ext;
        $dest     = __DIR__ . '/../../frontend/assets/images/recipes/' . $filename;
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $dest)) {
            throw new Exception("Không thể lưu ảnh bìa!");
        }
        $main_image = $filename;
    }

    // 2. Lưu Recipe chính
    $sql  = "INSERT INTO recipes
             (user_id, category_id, title, slug, description, prep_time, cook_time, servings, difficulty, main_image, is_published)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssiiiss",
        $user_id, $category_id, $title, $slug, $description,
        $prep_time, $cook_time, $servings, $difficulty, $main_image
    );
    $stmt->execute();
    $recipe_id = $conn->insert_id;

    // 3. Lưu Nguyên liệu — dùng prepared statements, không raw query
    if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
        $stmt_find_ing = $conn->prepare("SELECT id FROM ingredients WHERE slug = ?");
        $stmt_ins_ing  = $conn->prepare("INSERT INTO ingredients (name, slug) VALUES (?, ?)");
        $stmt_ri       = $conn->prepare(
            "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unit, display_order)
             VALUES (?, ?, ?, ?, ?)"
        );

        foreach ($_POST['ingredients'] as $index => $ing) {
            $ing_name = trim($ing['name'] ?? '');
            if (empty($ing_name)) continue;

            $ing_slug = create_slug($ing_name);

            // Tìm hoặc tạo ingredient
            $stmt_find_ing->bind_param("s", $ing_slug);
            $stmt_find_ing->execute();
            $res    = $stmt_find_ing->get_result();
            $ing_id = ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;

            if ($ing_id === 0) {
                $stmt_ins_ing->bind_param("ss", $ing_name, $ing_slug);
                $stmt_ins_ing->execute();
                $ing_id = (int)$conn->insert_id;
            }

            $qty_str = (string)($ing['quantity'] ?? '');
            $unit    = trim($ing['unit'] ?? '');
            $order   = (int)$index;
            $stmt_ri->bind_param("iissi", $recipe_id, $ing_id, $qty_str, $unit, $order);
            $stmt_ri->execute();
        }
    }

    // 4. Lưu Các bước & Ảnh từng bước
    if (isset($_POST['steps']) && is_array($_POST['steps'])) {
        $stmt_step = $conn->prepare(
            "INSERT INTO recipe_directions (recipe_id, step_number, instruction, image_url)
             VALUES (?, ?, ?, ?)"
        );

        foreach ($_POST['steps'] as $index => $instr) {
            $instr = trim($instr);
            if (empty($instr)) continue;

            $img_url  = null;
            $step_num = $index + 1;

            if (
                isset($_FILES['step_images']['name'][$index]) &&
                $_FILES['step_images']['error'][$index] === UPLOAD_ERR_OK
            ) {
                $ext_s   = strtolower(pathinfo($_FILES['step_images']['name'][$index], PATHINFO_EXTENSION));
                $img_url = 'step_' . $step_num . '_' . $recipe_id . '_' . $index . '.' . $ext_s;
                $dest_s  = __DIR__ . '/../../frontend/assets/images/recipes/' . $img_url;
                if (!move_uploaded_file($_FILES['step_images']['tmp_name'][$index], $dest_s)) {
                    $img_url = null; // Không crash nếu upload thất bại
                }
            }

            $stmt_step->bind_param("iiss", $recipe_id, $step_num, $instr, $img_url);
            $stmt_step->execute();
        }
    }

    // 5. Lưu Tags
    if (isset($_POST['tags']) && is_array($_POST['tags'])) {
        $stmt_find_tag = $conn->prepare("SELECT id FROM tags WHERE slug = ?");
        $stmt_ins_tag  = $conn->prepare("INSERT INTO tags (name, slug) VALUES (?, ?)");
        $stmt_rt       = $conn->prepare("INSERT INTO recipe_tags (recipe_id, tag_id) VALUES (?, ?)");

        foreach ($_POST['tags'] as $tag_name) {
            $tag_name = trim($tag_name);
            if (empty($tag_name)) continue;

            $tag_slug = create_slug($tag_name);

            // Tìm hoặc tạo tag
            $stmt_find_tag->bind_param("s", $tag_slug);
            $stmt_find_tag->execute();
            $res = $stmt_find_tag->get_result();
            $tag_id = ($row = $res->fetch_assoc()) ? (int)$row['id'] : 0;

            if ($tag_id === 0) {
                $stmt_ins_tag->bind_param("ss", $tag_name, $tag_slug);
                $stmt_ins_tag->execute();
                $tag_id = (int)$conn->insert_id;
            }

            // Liên kết tag với recipe
            $stmt_rt->bind_param("ii", $recipe_id, $tag_id);
            $stmt_rt->execute();
        }
    }

    // 6. Lưu Additional Images (Gallery)
    if (isset($_FILES['gallery_images'])) {
        $stmt_gal = $conn->prepare("INSERT INTO recipe_images (recipe_id, image_url, caption) VALUES (?, ?, ?)");
        $count = count($_FILES['gallery_images']['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                $ext_g = strtolower(pathinfo($_FILES['gallery_images']['name'][$i], PATHINFO_EXTENSION));
                $allowed_g = ['jpg','jpeg','png','gif','webp'];
                if (in_array($ext_g, $allowed_g)) {
                    $img_name = 'gallery_' . $recipe_id . '_' . time() . '_' . $i . '.' . $ext_g;
                    $dest_g = __DIR__ . '/../../frontend/assets/images/recipes/' . $img_name;
                    if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$i], $dest_g)) {
                        $caption = '';
                        $stmt_gal->bind_param("iss", $recipe_id, $img_name, $caption);
                        $stmt_gal->execute();
                    }
                }
            }
        }
    }

    // 7. Insert notification for Admins
    $notif_title = "Công thức mới";
    $notif_msg = $_SESSION['user']['display_name'] . " đã đăng một công thức mới: " . $title;
    $notif_type = "recipe";
    $notif_link = "/smart-recipes/frontend/pages/recipes/recipe_detail.php?id=" . $recipe_id;
    
    // Get all admin users
    $admin_res = $conn->query("SELECT id FROM users WHERE role = 'admin'");
    if ($admin_res) {
        $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        while ($admin = $admin_res->fetch_assoc()) {
            if ($admin['id'] != $user_id) { // Don't notify the admin who created it
                $stmt_notif->bind_param("issss", $admin['id'], $notif_type, $notif_title, $notif_msg, $notif_link);
                $stmt_notif->execute();
            }
        }
    }

    $conn->commit();
    echo json_encode(['status' => 'success', 'recipe_id' => $recipe_id]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
