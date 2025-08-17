<?php
// Test file to verify search and filter functionality
include 'config.php';

$conn = getConnection();

// Test basic query
$sql = "SELECT COUNT(*) as total FROM food_items";
$result = $conn->query($sql);
$total_items = $result->fetch_assoc()['total'];

echo "<h2>Database Test Results:</h2>";
echo "<p>Total items in database: $total_items</p>";

// Test search functionality
$search_term = "pre";
$search_sql = "SELECT food_name, cuisine_type, city, state FROM food_items WHERE food_name LIKE ? LIMIT 5";
$stmt = $conn->prepare($search_sql);
$search_param = "%$search_term%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$search_result = $stmt->get_result();

echo "<h3>Search Results for '$search_term':</h3>";
if ($search_result->num_rows > 0) {
    while ($row = $search_result->fetch_assoc()) {
        echo "<p>- " . $row['food_name'] . " (" . $row['cuisine_type'] . ") - " . $row['city'] . ", " . $row['state'] . "</p>";
    }
} else {
    echo "<p>No results found for '$search_term'</p>";
}

// Test filter functionality
$filter_sql = "SELECT DISTINCT state FROM food_items WHERE state IS NOT NULL AND state != '' ORDER BY state LIMIT 10";
$filter_result = $conn->query($filter_sql);

echo "<h3>Available States (first 10):</h3>";
if ($filter_result->num_rows > 0) {
    while ($row = $filter_result->fetch_assoc()) {
        echo "<p>- " . $row['state'] . "</p>";
    }
}

$conn->close();
echo "<p><strong>Test completed successfully!</strong></p>";
echo "<p><a href='search.php'>Go to Search Page</a></p>";
?> 