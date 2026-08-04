<?php
header("Content-Type: application/json");
include("../config/database.php");

$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

// check email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email exists"]);
    exit;
}

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// insert
$stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hash);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}