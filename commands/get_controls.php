<?php
require '../auth.php';
require '../db.php';

if ($_SESSION['user']['role'] !== 'farmer') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$userId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT device_name, status FROM controls WHERE farmer_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$controls = [];
while ($row = $result->fetch_assoc()) {
    $controls[$row['device_name']] = (int)$row['status'];
}

echo json_encode($controls);
