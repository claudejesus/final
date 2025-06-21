<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../auth.php';
require '../db.php';
header('Content-Type: application/json');

// Debug logging
error_log("=== DELETE FARMER REQUEST ===");
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Username from GET: " . ($_GET['username'] ?? 'not set'));
error_log("Session ID: " . session_id());
error_log("Session user: " . json_encode($_SESSION['user'] ?? 'not set'));

if ($_SESSION['user']['role'] !== 'admin') {
    error_log("Unauthorized access attempt - user role: " . ($_SESSION['user']['role'] ?? 'not set'));
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$username = $_GET['username'] ?? '';
if (!$username) {
    error_log("No username provided in delete request");
    echo json_encode(['success' => false, 'message' => 'Username required']);
    exit;
}

error_log("Attempting to delete farmer with username: " . $username);

$stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'farmer'");
$stmt->bind_param("s", $username);
$result = $stmt->execute();

error_log("Delete query executed. Affected rows: " . $stmt->affected_rows);
error_log("=== END DELETE FARMER REQUEST ===");

echo json_encode([
    'success' => $stmt->affected_rows > 0,
    'message' => $stmt->affected_rows > 0 ? 'Farmer deleted successfully' : 'Farmer not found'
]);
?>
