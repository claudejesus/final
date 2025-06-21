<?php
require 'db.php';

// Add a test farmer
$username = 'test_farmer';
$password = password_hash('test123', PASSWORD_DEFAULT);
$role = 'farmer';

// Check if farmer already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Test farmer already exists\n";
} else {
    // Insert test farmer
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    
    if ($stmt->execute()) {
        echo "Test farmer added successfully\n";
    } else {
        echo "Failed to add test farmer: " . $stmt->error . "\n";
    }
}

// Show all farmers
$result = $conn->query("SELECT id, username, role, created_at FROM users WHERE role = 'farmer'");
echo "\nCurrent farmers in database:\n";
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Username: {$row['username']}, Role: {$row['role']}, Created: {$row['created_at']}\n";
}
?> 