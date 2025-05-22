<?php
require '../db.php';
header('Content-Type: application/json');

// Simulate command processing
$action = json_decode(file_get_contents('php://input'), true)['action'];
$allowed_actions = ['fan_on', 'fan_off'];

if (!in_array($action, $allowed_actions)) {
    echo json_encode(['success' => false, 'error' => 'Invalid command']);
    exit;
}

// Save command to database
$stmt = $conn->prepare("INSERT INTO commands (action, status) VALUES (?, 'pending')");
$stmt->bind_param("s", $action);

if ($stmt->execute()) {
    // Simulate device response after 1 second
    sleep(1);
    $status = rand(0, 10) > 1 ? 'success' : 'failed'; // 90% success rate
    $conn->query("UPDATE commands SET status = '$status' WHERE id = {$stmt->insert_id}");
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>