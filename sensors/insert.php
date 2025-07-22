<?php
header("Content-Type: application/json");

// Read raw input
$raw = file_get_contents("php://input");

// Decode JSON
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(["error" => "Invalid JSON", "raw" => $raw]);
    exit;
}

// Extract values
$temp = floatval($data["temperature"]);
$humid = floatval($data["humidity"]);
$cooling = intval($data["cooling_status"]);
$heating = intval($data["heating_status"]);

// DB credentials
$host = "localhost";
$user = "root";
$pass = "";
$db = "maize_weevil_new";

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Insert query
$stmt = $conn->prepare("INSERT INTO sensor_data (temperature, humidity, cooling_status, heating_status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("diii", $temp, $humid, $cooling, $heating);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Data inserted"]);
} else {
    echo json_encode(["error" => "Insert failed"]);
}

$stmt->close();
$conn->close();
?>
