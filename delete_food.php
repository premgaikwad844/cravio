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

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Get the image path before deleting
    $sql = "SELECT image_path FROM food_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['image_path'];
        
        // Delete from database
        $delete_sql = "DELETE FROM food_items WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            // Delete the image file if it exists
            if (!empty($image_path) && file_exists($image_path)) {
                unlink($image_path);
            }
            header("Location: view_food.php?deleted=1");
            exit;
        } else {
            header("Location: view_food.php?error=delete_failed");
            exit;
        }
    } else {
        header("Location: view_food.php?error=not_found");
        exit;
    }
} else {
    header("Location: view_food.php?error=invalid_id");
    exit;
}

$conn->close();
?> 