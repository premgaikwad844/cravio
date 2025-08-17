<?php
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
?>
<?php
session_start();
require_once 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // Check if username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        header("Location: register.php?error=username_exists");
        exit();
    }

    // Check if email already exists
    $check_email_sql = "SELECT id FROM users WHERE email = ?";
    $check_email_stmt = mysqli_prepare($conn, $check_email_sql);
    mysqli_stmt_bind_param($check_email_stmt, "s", $email);
    mysqli_stmt_execute($check_email_stmt);
    mysqli_stmt_store_result($check_email_stmt);

    if (mysqli_stmt_num_rows($check_email_stmt) > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }

    // Hash password and insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "sss", $username, $email, $hashed_password);

    if (mysqli_stmt_execute($insert_stmt)) {
        header("Location: index.php?success=registered");
        exit();
    } else {
        header("Location: register.php?error=registration_failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
        font-family: 'Roboto', Arial, sans-serif;
        background: url('https://img.freepik.com/premium-photo/food-table-top-view-free-space-your-text_187166-25767.jpg') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .login-container {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 400px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: box-shadow 0.2s;
    }
    .login-container:hover {
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.18);
    }
    .logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .logo img {
        width: 90px;
        height: auto;
        margin-bottom: 0.5rem;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .logo p {
        color: #6366f1;
        margin: 0;
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    h2 {
        font-size: 1.7rem;
        font-weight: 700;
        color: #22223b;
        margin-bottom: 1.2rem;
        text-align: center;
    }
    .form-group {
        margin-bottom: 1.1rem;
        width: 100%;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        color: #4b5563;
        font-size: 1rem;
        font-weight: 500;
    }
    .form-group input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 6px;
        font-size: 1rem;
        background: #f9fafb;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 2px #6366f133;
        background: #fff;
    }
    button {
        width: 100%;
        padding: 0.9rem;
        background: #d93025 !important;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: background 0.2s, box-shadow 0.2s;
    }
    button:hover {
        background: #d93025 !important;
        box-shadow: 0 4px 16px rgba(99,102,241,0.13);
    }
    .error {
        color: #d93025;
        text-align: center;
        margin-bottom: 1rem;
        padding: 0.6rem 0.8rem;
        background-color: #fce8e6;
        border-radius: 4px;
        font-size: 1rem;
    }
    .success {
        color: #0f9d58;
        text-align: center;
        margin-bottom: 1rem;
        padding: 0.6rem 0.8rem;
        background-color: #e6f4ea;
        border-radius: 4px;
        font-size: 1rem;
    }
    .register-link {
        text-align: center;
        margin-top: 1.2rem;
        color: #6b7280;
        font-size: 1rem;
    }
    .register-link a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    .register-link a:hover {
        color: #d93025;
        text-decoration: underline;
    }
    @media (max-width: 900px) {
        .login-container {
            max-width: 80vw;
            padding: 1.5rem;
        }
        .logo img {
            width: 80px;
        }
        h2 {
            font-size: 1.4rem;
        }
    }
    @media (max-width: 700px) {
        .login-container {
            max-width: 95vw;
            padding: 1.2rem;
        }
        .logo img {
            width: 65px;
        }
        h2 {
            font-size: 1.1rem;
        }
        .form-group input, button {
            font-size: 0.98rem;
            padding: 0.7rem;
        }
    }
    @media (max-width: 500px) {
        .login-container {
            padding: 0.7rem;
            max-width: 100vw;
            margin: 8px;
        }
        .logo img {
            width: 50px;
        }
        h2 {
            font-size: 1rem;
        }
        .form-group input, button {
            font-size: 0.95rem;
            padding: 0.6rem;
        }
        button {
            margin-top: 0.4rem;
            margin-bottom: 0.4rem;
        }
    }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="f2-removebg-preview.png" alt="Logo">
            <p><b>CRAVIO</b></p>
        </div>
        <form action="register.php" method="POST" class="login-form">
            <h2>Create Account</h2>
            <?php
            if(isset($_GET['error'])) {
                if($_GET['error'] == 'password_mismatch') {
                    echo '<p class="error">Passwords do not match</p>';
                } elseif($_GET['error'] == 'username_exists') {
                    echo '<p class="error">Username already exists</p>';
                } elseif($_GET['error'] == 'email_exists') {
                    echo '<p class="error">Email already exists</p>';
                } elseif($_GET['error'] == 'registration_failed') {
                    echo '<p class="error">Registration failed. Please try again.</p>';
                }
            }
            ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Register</button>
            <p class="register-link">Already have an account? <a href="index.php">Login here</a></p>
        </form>
    </div>
</body>
</html> 