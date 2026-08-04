<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

// Get DB recipes with ingredients
$dbRecipes = [];
if ($conn) {
    $sql = "SELECT r.id, r.title, r.description, r.main_image, r.prep_time, r.cook_time,
                   COALESCE(u.display_name, u.username) AS author,
                   c.slug AS category
            FROM recipes r
            LEFT JOIN users u ON r.user_id = u.id
            LEFT JOIN categories c ON r.category_id = c.id
            WHERE r.is_published = 1
            ORDER BY r.created_at DESC
            LIMIT 100";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recipeId = (int)$row['id'];
            $total = ((int)$row['prep_time'] + (int)$row['cook_time']);
            
            // Handle image URL
            $mainImage = $row['main_image'];
            if (strpos($mainImage, 'http') === 0) {
                $imageUrl = $mainImage;
            } else {
                $imageUrl = '/smart-recipes/frontend/assets/images/recipes/' . $mainImage;
            }
            
            // Get ingredients for this recipe
            $ingredients = [];
            $ingStmt = $conn->prepare("SELECT i.name FROM recipe_ingredients ri JOIN ingredients i ON ri.ingredient_id = i.id WHERE ri.recipe_id = ?");
            $ingStmt->bind_param("i", $recipeId);
            $ingStmt->execute();
            $ingResult = $ingStmt->get_result();
            while ($ing = $ingResult->fetch_assoc()) {
                $ingredients[] = $ing['name'];
            }
            
            $dbRecipes[] = [
                'id'          => $recipeId,
                'title'       => $row['title'],
                'description' => $row['description'] ?? '',
                'image'       => $imageUrl,
                'ready_in'    => $total > 0 ? $total . ' min' : '30 min',
                'author'      => $row['author'] ?? 'Unknown',
                'category'    => $row['category'] ?? 'dinner',
                'tags'        => [],
                'ingredients' => $ingredients,
            ];
        }
    }
}

echo json_encode([
    'status' => 'success',
    'recipes' => $dbRecipes
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
