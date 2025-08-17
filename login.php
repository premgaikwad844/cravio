<?php
session_start();

// Database credentials
$servername = "sql109.infinityfree.com";
$dbusername = "if0_39329540";
$dbpassword = "Prem28831924";
$dbname = "if0_39329540_login_db12";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        header('Location: index3.php?error=1');
        exit();
    }

    // Connect to DB
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute query
    $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        // Use password_verify to check hashed password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header('Location: main.php');
            exit();
        }
    }
    // Invalid login
    header('Location: index3.php?error=1');
    exit();
} else {
    header('Location: index3.php');
    exit();
} 