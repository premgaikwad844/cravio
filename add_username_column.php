<?php
// Database configuration
$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add added_by column to food_items table
$sql = "ALTER TABLE food_items ADD COLUMN added_by VARCHAR(100) DEFAULT 'Unknown' AFTER created_at";

if ($conn->query($sql) === TRUE) {
    echo "Successfully added 'added_by' column to food_items table<br>";
} else {
    echo "Error adding column: " . $conn->error . "<br>";
}

$conn->close();
echo "Database update completed!";
?> 