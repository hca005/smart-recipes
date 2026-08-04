<?php
session_start();
$_SESSION['user'] = ['id' => 1];
$_POST['recipe_id'] = 1;
$_SERVER['REQUEST_METHOD'] = 'POST';

ob_start();
include 'backend/api/toggle_bookmark.php';
$out = ob_get_clean();

echo "OUTPUT:\n$out\n";
