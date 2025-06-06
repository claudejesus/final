<?php
session_start();
require '../db.php';
header('Content-Type: application/json');

// Optional: Auth check
if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$username = $_GET['username'] ?? '';
if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Missing username']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'farmer'");
$stmt->bind_param("s", $username);

echo json_encode([
    'success' => $stmt->execute(),
    'message' => $stmt->execute() ? 'Farmer deleted' : 'Delete failed'
]);
