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

echo "<h2>Setting up 'added_by' column for food_items table</h2>";

// Check if the column already exists
$check_column = "SELECT COLUMN_NAME 
                 FROM INFORMATION_SCHEMA.COLUMNS 
                 WHERE TABLE_SCHEMA = '$dbname' 
                 AND TABLE_NAME = 'food_items' 
                 AND COLUMN_NAME = 'added_by'";

$result = $conn->query($check_column);

if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✅ Column 'added_by' already exists in the table.</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Column 'added_by' does not exist. Adding it now...</p>";
    
    // Add the added_by column
    $add_column = "ALTER TABLE food_items 
                   ADD COLUMN added_by VARCHAR(100) DEFAULT 'Unknown' 
                   AFTER created_at";
    
    if ($conn->query($add_column) === TRUE) {
        echo "<p style='color: green;'>✅ Successfully added 'added_by' column to food_items table.</p>";
        
        // Update existing records to have a default value
        $update_records = "UPDATE food_items 
                          SET added_by = 'Unknown' 
                          WHERE added_by IS NULL OR added_by = ''";
        
        if ($conn->query($update_records) === TRUE) {
            echo "<p style='color: green;'>✅ Updated existing records with default 'Unknown' value.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error updating existing records: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Error adding column: " . $conn->error . "</p>";
    }
}

// Show table structure
echo "<h3>Current table structure:</h3>";
$describe = "DESCRIBE food_items";
$result = $conn->query($describe);

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Show sample data
echo "<h3>Sample data with added_by column:</h3>";
$sample_data = "SELECT id, food_name, added_by, created_at 
                FROM food_items 
                ORDER BY created_at DESC 
                LIMIT 5";

$result = $conn->query($sample_data);

if ($result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Food Name</th><th>Added By</th><th>Created At</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['food_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['added_by']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No data found in the table.</p>";
}

$conn->close();

echo "<br><p><strong>Setup complete!</strong> You can now use the 'Added By' feature in your application.</p>";
echo "<p><a href='view_food.php'>View Food Items</a> | <a href='p2.php'>Add New Food</a></p>";
?> 