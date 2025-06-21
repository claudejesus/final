<?php 
require '../auth.php';
require '../db.php';
header('Content-Type: application/json');

if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id = $_POST['id'] ?? '';
$username = $_POST['username'] ?? '';

if (!$id || !$username) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ? AND role = 'farmer'");
$stmt->bind_param("si", $username, $id);

$result = $stmt->execute();

echo json_encode([
    'success' => $result,
    'message' => $result ? 'Farmer updated successfully' : 'Update failed'
]);
