<?php
// Debug file to test search functionality
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Debug Search Page</h1>";

// Test database connection
$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";

echo "<h2>Testing Database Connection...</h2>";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
}

// Test basic query
echo "<h2>Testing Basic Query...</h2>";
$sql = "SELECT COUNT(*) as total FROM food_items";
$result = $conn->query($sql);

if ($result) {
    $total = $result->fetch_assoc()['total'];
    echo "<p>Total items in database: <strong>$total</strong></p>";
} else {
    echo "<p style='color: red;'>❌ Error in basic query: " . $conn->error . "</p>";
}

// Test search functionality
echo "<h2>Testing Search Functionality...</h2>";
$search_term = isset($_GET['search']) ? $_GET['search'] : 'pre';

$search_sql = "SELECT food_name, cuisine_type, city, state FROM food_items WHERE food_name LIKE ? LIMIT 5";
$stmt = $conn->prepare($search_sql);

if ($stmt) {
    $search_param = "%$search_term%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $search_result = $stmt->get_result();
    
    echo "<p>Search results for '$search_term':</p>";
    if ($search_result->num_rows > 0) {
        echo "<ul>";
        while ($row = $search_result->fetch_assoc()) {
            echo "<li>" . $row['food_name'] . " (" . $row['cuisine_type'] . ") - " . $row['city'] . ", " . $row['state'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>No results found for '$search_term'</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Error preparing search statement: " . $conn->error . "</p>";
}

// Test filter functionality
echo "<h2>Testing Filter Functionality...</h2>";
$filter_sql = "SELECT DISTINCT state FROM food_items WHERE state IS NOT NULL AND state != '' ORDER BY state LIMIT 10";
$filter_result = $conn->query($filter_sql);

if ($filter_result) {
    echo "<p>Available states:</p>";
    echo "<ul>";
    while ($row = $filter_result->fetch_assoc()) {
        echo "<li>" . $row['state'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Error in filter query: " . $conn->error . "</p>";
}

// Show current URL parameters
echo "<h2>Current URL Parameters:</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

$conn->close();

echo "<h2>Test Search Form:</h2>";
echo "<form method='GET'>";
echo "<input type='text' name='search' placeholder='Enter search term' value='" . htmlspecialchars($search_term) . "'>";
echo "<button type='submit'>Test Search</button>";
echo "</form>";

echo "<p><a href='search.php'>Go to Main Search Page</a></p>";
?> 