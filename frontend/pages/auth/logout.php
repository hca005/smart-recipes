<?php
require_once '../../includes/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_unset();
session_destroy();

$home_url = defined('BASE_URL') ? BASE_URL . '/pages/home.php' : '/frontend/pages/home.php';
header("Location: " . $home_url);
exit;