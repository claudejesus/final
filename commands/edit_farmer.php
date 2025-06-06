<?php 
require '../db.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? '';
$username = $_POST['username'] ?? '';

if (!$id || !$username) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ? AND role = 'farmer'");
$stmt->bind_param("si", $username, $id);

echo json_encode([
    'success' => $stmt->execute(),
    'message' => $stmt->execute() ? 'Farmer updated successfully' : 'Update failed'
]);
