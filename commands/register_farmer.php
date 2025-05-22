<?php
require '../db.php';
header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
    exit;
}

// Check if username exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new farmer
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'farmer')");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Farmer registered successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $conn->error]);
}
?>