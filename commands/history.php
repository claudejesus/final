<?php
require '../db.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT action, status, timestamp FROM commands ORDER BY timestamp DESC LIMIT 10");
$commands = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($commands);
?>