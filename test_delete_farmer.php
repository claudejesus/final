<?php
require 'db.php';

// Test delete functionality
$username = 'regis';

echo "Testing delete functionality for username: $username\n";

// Check if farmer exists before deletion
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND role = 'farmer'");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Farmer exists, attempting to delete...\n";
    
    // Delete the farmer
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ? AND role = 'farmer'");
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    
    if ($result && $stmt->affected_rows > 0) {
        echo "Farmer deleted successfully! Affected rows: " . $stmt->affected_rows . "\n";
    } else {
        echo "Failed to delete farmer. Affected rows: " . $stmt->affected_rows . "\n";
        echo "Error: " . $stmt->error . "\n";
    }
} else {
    echo "Farmer does not exist\n";
}

// Show remaining farmers
$result = $conn->query("SELECT id, username, role, created_at FROM users WHERE role = 'farmer'");
echo "\nRemaining farmers in database:\n";
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']}, Username: {$row['username']}, Role: {$row['role']}, Created: {$row['created_at']}\n";
}
?> 