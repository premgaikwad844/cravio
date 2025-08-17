<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CRAVIO</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        background-image: url('https://as1.ftcdn.net/v2/jpg/02/52/12/40/1000_F_252124067_aCtp9ZD934RboKmjJzkXiwYDL7XkNjpn.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
    }
    .login-container {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        padding: 2.2rem 2rem 2.5rem 2rem;
        border-radius: 18px;
        box-shadow: 0 6px 32px rgba(0,0,0,0.13);
        width: 100%;
        max-width: 370px;
        margin: 1.5rem;
        transition: box-shadow 0.2s;
        box-sizing: border-box;
    }
    .logo {
        text-align: center;
        margin-bottom: 2.2rem;
    }
    .logo img {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 0.7rem;
        box-shadow: 0 2px 12px rgba(151,201,51,0.13);
    }
    .logo p {
        color: #97c933;
        margin: 0;
        font-size: 1.2rem;
        font-weight: bold;
        letter-spacing: 2px;
    }
    .login-form h2 {
        text-align: center;
        margin-bottom: 1.2rem;
        color: #23272b;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .form-group {
        margin-bottom: 1.1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        color: #23272b;
        font-size: 1rem;
        font-weight: 600;
    }
    .form-group input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid #e0e0e0;
        border-radius: 6px;
        font-size: 1.05rem;
        background: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: #97c933;
        box-shadow: 0 0 0 2px rgba(151,201,51,0.13);
    }
    button {
        width: 100%;
        padding: 0.9rem;
        background: #d93025; /* solid red */
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 0.5rem;
        box-shadow: 0 2px 8px rgba(151,201,51,0.08);
        transition: background 0.2s, box-shadow 0.2s;
    }
    button:hover {
        background: #b71c1c; /* darker red on hover */
        box-shadow: 0 4px 16px rgba(151,201,51,0.13);
    }
    .error {
        color: #d93025;
        text-align: center;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background-color: #fce8e6;
        border-radius: 4px;
        font-size: 1rem;
    }
    .success {
        color: #0f9d58;
        text-align: center;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background-color: #e6f4ea;
        border-radius: 4px;
        font-size: 1rem;
    }
    .register-link {
        text-align: center;
        margin-top: 1.2rem;
        color: #666;
        font-size: 1rem;
    }
    .register-link a {
        color: #d93025;
        text-decoration: none;
        font-weight: 600;
    }
    .register-link a:hover {
        text-decoration: underline;
    }
    @media (max-width: 600px) {
        .login-container {
            padding: 1.1rem 0.5rem 1.5rem 0.5rem;
            max-width: 98vw;
            border-radius: 10px;
            margin: 0.5rem;
        }
        .logo img {
            width: 70px;
            height: 70px;
        }
        .logo p {
            font-size: 1rem;
        }
        .login-form h2 {
            font-size: 1.1rem;
        }
        .form-group input, button {
            font-size: 0.98rem;
            padding: 0.7rem;
        }
        .register-link {
            font-size: 0.95rem;
        }
    }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="f2-removebg-preview.png" alt="Logo">
            <p>CRAVIO</p>
        </div>
        <form action="login.php" method="POST" class="login-form" autocomplete="off">
            <h2>Login</h2>
            <?php
            if(isset($_GET['error'])) {
                echo '<p class="error">Invalid username or password</p>';
            }
            if(isset($_GET['success']) && $_GET['success'] == 'registered') {
                echo '<p class="success">Registration successful! Please login.</p>';
            }
            ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit">Login</button>
            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>
</body>
</html> 