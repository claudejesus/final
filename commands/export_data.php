<?php
require '../auth.php';  // optional: only if export requires login
require '../db.php';

// Set headers to prompt download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sensor_data.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Output CSV headers
fputcsv($output, ['ID', 'Temperature (°C)', 'Humidity (%)', 'Timestamp']);

// Query sensor data
$result = $conn->query("SELECT id, temperature, humidity, timestamp FROM sensor_data ORDER BY timestamp DESC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['temperature'],
            $row['humidity'],
            $row['timestamp']
        ]);
    }
}

fclose($output);
exit;
