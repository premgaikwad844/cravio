<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['is_admin'])) {
    header("Location: p2.php");
    exit;
}

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

// Create table if not exists (add added_by column if missing)
$sql = "CREATE TABLE IF NOT EXISTS food_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(255) NOT NULL,
    description TEXT,
    state VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    cuisine_type VARCHAR(100) NOT NULL,
    meal_type VARCHAR(100) NOT NULL,
    dietary_preference VARCHAR(100) NOT NULL,
    spice_level VARCHAR(50) NOT NULL,
    price_range VARCHAR(50) NOT NULL,
    popularity VARCHAR(100) NOT NULL,
    cooking_style VARCHAR(100) NOT NULL,
    image_path VARCHAR(500),
    added_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Add description column if it doesn't exist (for existing tables)
$check_description = "SHOW COLUMNS FROM food_items LIKE 'description'";
$result = $conn->query($check_description);
if ($result->num_rows == 0) {
    $add_description = "ALTER TABLE food_items ADD COLUMN description TEXT AFTER food_name";
    $conn->query($add_description);
}

// Add added_by column if it doesn't exist
$check_added_by = "SHOW COLUMNS FROM food_items LIKE 'added_by'";
$result = $conn->query($check_added_by);
if ($result->num_rows == 0) {
    $add_added_by = "ALTER TABLE food_items ADD COLUMN added_by VARCHAR(100) AFTER image_path";
    $conn->query($add_added_by);
}

if (!$conn->query($sql)) {
    echo "Error creating table: " . $conn->error;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $food_name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $state = $conn->real_escape_string($_POST['state']);
    $city = $conn->real_escape_string($_POST['city']);
    $cuisine_type = $conn->real_escape_string($_POST['cuisine_type']);
    $meal_type = $conn->real_escape_string($_POST['meal_type']);
    $dietary_preference = $conn->real_escape_string($_POST['dietary_preference']);
    $spice_level = $conn->real_escape_string($_POST['spice_level']);
    $price_range = $conn->real_escape_string($_POST['price_range']);
    $popularity = $conn->real_escape_string($_POST['popularity']);
    $cooking_style = $conn->real_escape_string($_POST['cooking_style']);
    $added_by = isset($_SESSION['username']) ? $conn->real_escape_string($_SESSION['username']) : null;
    
    // Validate required fields
    if (empty($food_name) || empty($price_range)) {
        echo "<script>alert('Food Name and Price Range are required!'); window.location.href='p2.php';</script>";
        exit;
    }
    
    // Handle image URL
    $image_path = "";
    if (isset($_POST['image_path']) && !empty($_POST['image_path'])) {
        $image_path = $conn->real_escape_string($_POST['image_path']);
        
        // Validate URL format
        if (!filter_var($image_path, FILTER_VALIDATE_URL)) {
            echo "<script>alert('Please enter a valid image URL!'); window.location.href='p2.php';</script>";
            exit;
        }
    }
    
    // Insert data into database (now includes added_by)
    $sql = "INSERT INTO food_items (food_name, description, state, city, cuisine_type, meal_type, dietary_preference, spice_level, price_range, popularity, cooking_style, image_path, added_by) 
            VALUES ('$food_name', '$description', '$state', '$city', '$cuisine_type', '$meal_type', '$dietary_preference', '$spice_level', '$price_range', '$popularity', '$cooking_style', '$image_path', '$added_by')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Food item saved successfully!');
            window.location.href='p2.php';
        </script>";
    } else {
        echo "<script>
            alert('Error saving data: " . $conn->error . "');
            window.location.href='p2.php';
        </script>";
    }
}

$conn->close();
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Save Food Item</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #222;
            text-align: center;
            padding: 50px;
        }
        .message {
            background: #f8fff8;
            border: 1px solid #a5d6a7;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 500px;
        }
        .success {
            color: #388e3c;
        }
        .error {
            color: #e53935;
        }
    </style>
</head>
<body>
    <div class="message">
        <h2>Processing...</h2>
        <p>Please wait while we save your food item.</p>
    </div>
</body>
</html>
