<?php
require '../auth.php';
require '../db.php';
header('Content-Type: application/json');

if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$username = $_GET['username'] ?? '';
if (!$username) {
    echo json_encode(['success' => false, 'message' => 'Username required']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'farmer'");
$stmt->bind_param("s", $username);
$stmt->execute();

echo json_encode([
    'success' => $stmt->affected_rows > 0,
    'message' => $stmt->affected_rows > 0 ? 'Farmer deleted' : 'Farmer not found'
]);
?>
