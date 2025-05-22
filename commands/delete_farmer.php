<?php
require '../db.php';
header('Content-Type: application/json');

$username = $_GET['username'] ?? '';

if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Username is required']);
    exit;
}

// Prevent deleting admin
if ($username === 'admin') {
    echo json_encode(['success' => false, 'message' => 'Cannot delete admin account']);
    exit;
}

// Delete farmer
$stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'farmer'");
$stmt->bind_param("s", $username);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Farmer deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Farmer not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . $conn->error]);
}
?>