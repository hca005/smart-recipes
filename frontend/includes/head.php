<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Discover thousands of delicious recipes, connect with home cooks, and share your culinary creations.">
    <meta name="keywords" content="recipes, cooking, food, meals, ingredients">
    <meta name="author" content="Food.">
    
    <title><?php echo $pageTitle ?? 'Food. - Discover What to Cook Today'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/smart-recipes/frontend/assets/images/logo/favicon.png">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="/smart-recipes/frontend/assets/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/smart-recipes/frontend/assets/css/components/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    
    <!-- Additional Page-Specific Styles -->
    <?php if (isset($additionalStyles)): ?>
        <?php foreach ($additionalStyles as $style): ?>
            <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
