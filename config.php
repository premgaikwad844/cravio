<?php
// Database configuration
$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";

// Environment configuration
$is_local = true; // Set to false for production

// Image URL configuration
function getImageUrl($image_path) {
    global $is_local;
    
    if (empty($image_path)) {
        return 'https://images.pexels.com/photos/461382/pexels-photo-461382.jpeg?auto=compress&w=120&q=80';
    }
    
    // If it's already a full URL, return as is
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    
    // For local development, handle relative paths
    if ($is_local) {
        // For local file paths, make them relative to the current directory
        if (strpos($image_path, 'uploads/') === 0) {
            return $image_path; // Already relative
        }
        
        // If it's a relative path, ensure it starts with uploads/
        if (!strpos($image_path, 'uploads/')) {
            return 'uploads/' . $image_path;
        }
    }
    
    return $image_path;
}

// Create connection
function getConnection() {
    global $servername, $username, $password, $dbname;
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}
?>