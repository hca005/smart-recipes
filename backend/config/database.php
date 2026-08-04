<?php
// Environment variables for Cloud Production (12-Factor App) with XAMPP Local Fallbacks
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$db   = getenv('DB_NAME') ?: "food_recipe_db";
$port = (int)(getenv('DB_PORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("DB Connection Error: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");