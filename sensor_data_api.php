<?php
require 'db.php';
header('Content-Type: application/json');

$limit = $_GET['limit'] ?? 50;
$result = $conn->query("SELECT temperature, humidity, timestamp FROM sensor_data ORDER BY timestamp DESC LIMIT $limit");
$data = $result->fetch_all(MYSQLI_ASSOC);

// Reverse to show oldest first
echo json_encode(array_reverse($data));
?>