<?php
require '../db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$temperature = $data['temperature'] ?? null;
$humidity = $data['humidity'] ?? null;
$cooling_status = $data['cooling_status'] ?? 0;
$heating_status = $data['heating_status'] ?? 0;

if ($temperature === null || $humidity === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing data']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO sensor_data (temperature, humidity, cooling_status, heating_status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ddii", $temperature, $humidity, $cooling_status, $heating_status);
$stmt->execute();

echo json_encode(['success' => true]);
?>
