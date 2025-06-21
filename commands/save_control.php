<?php
require '../auth.php';
require '../db.php';

if ($_SESSION['user']['role'] !== 'farmer') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$userId = $_SESSION['user']['id'];

$data = json_decode(file_get_contents('php://input'), true);
$device = $data['device'] ?? '';
$status = isset($data['status']) ? (int)$data['status'] : null;

if (!$device || !in_array($status, [0,1], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM controls WHERE farmer_id = ? AND device_name = ?");
$stmt->bind_param("is", $userId, $device);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing
    $row = $result->fetch_assoc();
    $controlId = $row['id'];
    $stmt = $conn->prepare("UPDATE controls SET status = ? WHERE id = ?");
    $stmt->bind_param("ii", $status, $controlId);
    $stmt->execute();
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO controls (farmer_id, device_name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $userId, $device, $status);
    $stmt->execute();
}

echo json_encode(['success' => true, 'device' => $device, 'status' => $status]);
