<?php
require '../auth.php';
require '../db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="farmers_list.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['id', 'Username', 'Registered At']);

$result = $conn->query("SELECT id, username, created_at FROM users WHERE role = 'farmer'");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['username'], $row['created_at']]);
}

fclose($output);
exit;
