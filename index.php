<?php
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
$prefix = (strpos($request_uri, '/smart-recipes') === 0) ? '/smart-recipes' : '';
header('Location: ' . $prefix . '/frontend/pages/home.php');
exit;