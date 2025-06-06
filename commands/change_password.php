<?php
require '../auth.php';
require '../db.php';
header('Content-Type: application/json');

$userId = $_SESSION['user']['id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';

if (!$current || !$new) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

// Get current password
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($hashed);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hashed)) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

// Update password
$newHash = password_hash($new, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->bind_param("si", $newHash, $userId);

if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Password update failed']);
}
