<?php
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF'] . "?loggedout=1");
    exit;
}
session_start();
include 'header.php';
$users = [
    'Cravio' => ['password' => 'Cravio2025', 'role' => 'admin'],
    'prem' => ['password' => 'prem1924', 'role' => 'employee'],
    'prasad' => ['password' => 'prasad@4', 'role' => 'employee'],
    'jay' => ['password' => 'jay1234', 'role' => 'employee'],
    'atharv' => ['password' => 'atharv1140', 'role' => 'employee']
];

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

// Check if we're in edit mode
$edit_mode = false;
$edit_data = null;
$edit_id = null;

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $sql = "SELECT * FROM food_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
        $edit_mode = true;
    }
}

if (!isset($_SESSION['is_admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
        $username_input = $_POST['username'];
        $password_input = $_POST['password'];
        if (isset($users[$username_input]) && $users[$username_input]['password'] === $password_input) {
            $_SESSION['username'] = $username_input;
            $_SESSION['role'] = $users[$username_input]['role'];
            $_SESSION['is_admin'] = ($users[$username_input]['role'] === 'admin');
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Incorrect username or password!";
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    
        <title>Admin Login</title>
        
      
        <style>
:root {
    --primary-color: #27ae60;
    --primary-color-dark: #229954;
    --border-color: #e0e0e0;
    --card-bg: #ffffff;
    --body-bg: #f7f8fa;
    --danger-color: #e74c3c;
    --font-family: 'Inter', sans-serif;
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --border-radius: 8px;
}
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: var(--font-family);
}
body {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--body-bg);
}
.login-box {
    width: 100%;
    max-width: 400px;
    background: var(--card-bg);
    padding: 40px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    text-align: center;
}
.login-box h2 {
    color: #333;
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 24px;
}
.login-box input[type="text"],
.login-box input[type="password"] {
    width: 100%;
    background: #fff;
    color: #333;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 12px;
    margin-bottom: 16px;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.login-box input[type="text"]:focus,
.login-box input[type="password"]:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
}
.login-box button {
    width: 100%;
    background: var(--primary-color);
    color: #fff;
    padding: 12px 0;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.2s;
    margin-top: 8px;
}
.login-box button:hover {
    background: var(--primary-color-dark);
}
.error, .success {
    padding: 10px;
    border-radius: var(--border-radius);
    margin-bottom: 16px;
    width: 100%;
    box-sizing: border-box;
    font-size: 0.9rem;
}
.error {
    color: #c0392b;
    background: #fbeae5;
    border: 1px solid #e74c3c;
}
.success {
    color: #229954;
    background: #eafaf1;
    border: 1px solid #a3e9c3;
}
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Admin Login</h2>
            <?php 
            if (isset($_GET['loggedout'])) echo "<div class='success'>You have been logged out.</div>";
            if (isset($error)) echo "<div class='error'>$error</div>"; 
            ?>
            <form method="post" style="width:100%;">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Item Management</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
:root {
    --primary-color: #27ae60; 
    --primary-color-dark: #229954;
    --text-color: #333;
    --text-color-light: #555;
    --border-color: #e0e0e0;
    --card-bg: #ffffff;
    --body-bg: #f7f8fa;
    --danger-color: #e74c3c;
    --danger-color-dark: #c0392b;
    --font-family: 'Inter', sans-serif;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --border-radius: 8px;
}

body {
    background: var(--body-bg);
    font-family: var(--font-family);
    color: var(--text-color);
    margin: 0;
    padding-top: 64px;
}

.topbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 64px;
    background: var(--card-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    box-shadow: var(--shadow-sm);
    border-bottom: 1px solid var(--border-color);
    z-index: 1000;
}
.topbar .app-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-color);
}
.topbar .user-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 0.9rem;
    color: var(--text-color-light);
}
.topbar .user-actions span {
    font-weight: 500;
}
.topbar .view-btn, .topbar .logout-btn {
    padding: 8px 16px;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}
.topbar .view-btn {
    background: var(--primary-color);
    color: #fff;
}
.topbar .view-btn:hover {
    background: var(--primary-color-dark);
}
.topbar .logout-btn {
    background: var(--danger-color);
    color: #fff;
}
.topbar .logout-btn:hover {
    background: var(--danger-color-dark);
}

.main-content {
    padding: 32px 24px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.wide-card {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    width: 100%;
    max-width: 800px;
    padding: 32px;
    border: 1px solid var(--border-color);
}
.wide-card h1 {
    font-size: 1.75rem;
    color: var(--text-color);
    margin-top: 0;
    margin-bottom: 24px;
    text-align: center;
    font-weight: 600;
}
.added-by {
    color: var(--text-color-light);
    font-size: 0.9rem;
    text-align: center;
    margin-bottom: 16px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 28px;
}
.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.form-group label {
    font-weight: 600;
    color: var(--text-color);
    font-size: 0.95rem;
    padding-left: 4px;
}
.required {
    color: var(--danger-color);
}
input[type="text"], input[type="url"], textarea, select, .select2-container .select2-selection--single {
    width: 100%;
    padding: 18px 20px;
    border: 1px solid transparent;
    border-radius: var(--border-radius);
    background: var(--body-bg);
    color: var(--text-color);
    font-size: 1.1rem;
    transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    box-sizing: border-box;
}
textarea {
    min-height: 80px;
    height: auto;
}
input:focus, textarea:focus, select:focus, .select2-container--open .select2-selection--single {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.2);
    background-color: #fff;
}
.select2-container .select2-selection--single {
    height: 62px; 
    background-color: var(--body-bg);
    border: 1px solid transparent;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 60px;
    padding-left: 20px;
    color: var(--text-color);
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 60px;
}
.select2-container--open .select2-selection--single {
    background-color: #fff;
}

.submit-btn-container {
    display: flex;
    justify-content: flex-end;
    margin-top: 32px;
    width: 100%;
}
.submit-btn {
    background: var(--primary-color);
    color: #fff;
    padding: 12px 24px;
    border: none;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    transition: background-color 0.2s;
}
.submit-btn:hover {
    background: var(--primary-color-dark);
}

.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 4px;
    height: 1em;
}

.full-width {
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .wide-card {
        padding: 24px;
    }
    .topbar {
        padding: 0 16px;
    }
    .main-content {
        padding: 24px 16px;
    }
}
    </style>
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
<!-- Fixed Topbar -->
<div class="topbar">
    <div class="app-title">Cravio Admin Panel</div>
    <div class="user-actions">
        <?php if (isset($_SESSION['username'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <?php endif; ?>
        <a href="view_food.php" class="view-btn">View Food Items</a>
        <a href="?logout=1" class="logout-btn">Logout</a>
    </div>
</div>
<div class="main-content">
    <?php if (isset($_SESSION['username'])): ?>
    <div class="added-by">Added By: <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    <?php endif; ?>
    <div class="wide-card">
    <h1><?php echo $edit_mode ? 'Edit Food Item' : 'Add New Food Item'; ?></h1>
    <form action="<?php echo $edit_mode ? 'update_food.php' : 'save.php'; ?>" method="post">
    <?php if ($edit_mode): ?>
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? (int)$_GET['page'] : 1; ?>">
    <?php endif; ?>
    <div class="form-grid">
    <div class="form-group">
        <label for="name">Food Name: <span class="required">*</span></label>
        <input type="text" id="name" name="name" required onchange="validateFoodName()" 
               value="<?php echo $edit_mode ? htmlspecialchars($edit_data['food_name']) : ''; ?>">
        <span id="nameError" class="error-message"></span>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="3" placeholder="Enter a short description of the food item..." required><?php echo $edit_mode ? htmlspecialchars($edit_data['description']) : ''; ?></textarea>
        <span id="descriptionError" class="error-message"></span>
    </div>
    <div class="form-group">
        <label for="state">State:</label>
        <select id="state" name="state" required>
            <option value="">Select State</option>
            <?php
            $states = [
                'Andaman and Nicobar Islands','Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chandigarh','Chhattisgarh','Dadra and Nagar Haveli and Daman and Diu','Delhi','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Ladakh','Lakshadweep','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Puducherry','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'
            ];
            foreach ($states as $state) {
                $selected = ($edit_mode && $edit_data['state'] == $state) ? 'selected' : '';
                echo "<option value=\"$state\" $selected>$state</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="city">City:</label>
        <select id="city" name="city" required>
            <option value="">Select City</option>
            <?php if ($edit_mode): ?>
                <option value="<?php echo htmlspecialchars($edit_data['city']); ?>" selected><?php echo htmlspecialchars($edit_data['city']); ?></option>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="cuisine_type">Cuisine Type:</label>
        <select id="cuisine_type" name="cuisine_type" required>
            <option value="">Select Cuisine Type</option>
            <option value="Indian" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Indian') ? 'selected' : ''; ?>>Indian</option>
            <option value="Chinese" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
            <option value="Italian" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Italian') ? 'selected' : ''; ?>>Italian</option>
            <option value="Mexican" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Mexican') ? 'selected' : ''; ?>>Mexican</option>
            <option value="Thai" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Thai') ? 'selected' : ''; ?>>Thai</option>
            <option value="Continental" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Continental') ? 'selected' : ''; ?>>Continental</option>
            <option value="Fast Food" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Fast Food') ? 'selected' : ''; ?>>Fast Food</option>
            <option value="Desserts" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Desserts') ? 'selected' : ''; ?>>Desserts</option>
            <option value="Street Food" <?php echo ($edit_mode && isset($edit_data['cuisine_type']) && $edit_data['cuisine_type'] == 'Street Food') ? 'selected' : ''; ?>>Street Food</option>
        </select>
    </div>
    <div class="form-group">
        <label for="meal_type">Meal Type:</label>
        <select id="meal_type" name="meal_type" required>
            <option value="">Select Meal Type</option>
            <option value="Breakfast" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Breakfast') ? 'selected' : ''; ?>>Breakfast</option>
            <option value="Lunch" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Lunch') ? 'selected' : ''; ?>>Lunch</option>
            <option value="Dinner" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Dinner') ? 'selected' : ''; ?>>Dinner</option>
            <option value="Snacks" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Snacks') ? 'selected' : ''; ?>>Snacks</option>
            <option value="Desserts" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Desserts') ? 'selected' : ''; ?>>Desserts</option>
            <option value="Drinks" <?php echo ($edit_mode && isset($edit_data['meal_type']) && $edit_data['meal_type'] == 'Drinks') ? 'selected' : ''; ?>>Drinks</option>
        </select>
    </div>
    <div class="form-group">
        <label for="dietary_preference">Dietary Preference:</label>
        <select id="dietary_preference" name="dietary_preference" required>
            <option value="">Select Dietary Preference</option>
            <option value="Vegetarian" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Vegetarian') ? 'selected' : ''; ?>>Vegetarian</option>
            <option value="Vegan" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Vegan') ? 'selected' : ''; ?>>Vegan</option>
            <option value="Non-Vegetarian" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Non-Vegetarian') ? 'selected' : ''; ?>>Non-Vegetarian</option>
            <option value="Gluten-Free" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Gluten-Free') ? 'selected' : ''; ?>>Gluten-Free</option>
            <option value="Keto" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Keto') ? 'selected' : ''; ?>>Keto</option>
            <option value="Paleo" <?php echo ($edit_mode && isset($edit_data['dietary_preference']) && $edit_data['dietary_preference'] == 'Paleo') ? 'selected' : ''; ?>>Paleo</option>
        </select>
    </div>
    <div class="form-group">
        <label for="spice_level">Spice Level:</label>
        <select id="spice_level" name="spice_level" required>
            <option value="">Select Spice Level</option>
            <option value="Mild" <?php echo ($edit_mode && isset($edit_data['spice_level']) && $edit_data['spice_level'] == 'Mild') ? 'selected' : ''; ?>>Mild</option>
            <option value="Medium" <?php echo ($edit_mode && isset($edit_data['spice_level']) && $edit_data['spice_level'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
            <option value="Hot" <?php echo ($edit_mode && isset($edit_data['spice_level']) && $edit_data['spice_level'] == 'Hot') ? 'selected' : ''; ?>>Hot</option>
            <option value="Very Hot" <?php echo ($edit_mode && isset($edit_data['spice_level']) && $edit_data['spice_level'] == 'Very Hot') ? 'selected' : ''; ?>>Very Hot</option>
        </select>
    </div>
    <div class="form-group">
        <label for="price_range">Price Range: <span class="required">*</span></label>
        <select id="price_range" name="price_range" required onchange="validatePriceRange()">
            <option value="">Select Price Range</option>
            <option value="Budget" <?php echo ($edit_mode && isset($edit_data['price_range']) && $edit_data['price_range'] == 'Budget') ? 'selected' : ''; ?>>Budget</option>
            <option value="Moderate" <?php echo ($edit_mode && isset($edit_data['price_range']) && $edit_data['price_range'] == 'Moderate') ? 'selected' : ''; ?>>Moderate</option>
            <option value="Premium" <?php echo ($edit_mode && isset($edit_data['price_range']) && $edit_data['price_range'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
        </select>
        <span id="priceRangeError" class="error-message"></span>
    </div>
    <div class="form-group">
        <label for="popularity">Popularity:</label>
        <select id="popularity" name="popularity" required>
            <option value="">Select Popularity</option>
            <option value="Most Popular" <?php echo ($edit_mode && isset($edit_data['popularity']) && $edit_data['popularity'] == 'Most Popular') ? 'selected' : ''; ?>>Most Popular</option>
            <option value="Highly Rated" <?php echo ($edit_mode && isset($edit_data['popularity']) && $edit_data['popularity'] == 'Highly Rated') ? 'selected' : ''; ?>>Highly Rated</option>
        </select>
    </div>
    <div class="form-group">
        <label for="cooking_style">Cooking Style:</label>
        <select id="cooking_style" name="cooking_style" required>
            <option value="">Select Cooking Style</option>
            <option value="Grilled" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Grilled') ? 'selected' : ''; ?>>Grilled</option>
            <option value="Fried" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Fried') ? 'selected' : ''; ?>>Fried</option>
            <option value="Baked" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Baked') ? 'selected' : ''; ?>>Baked</option>
            <option value="Steamed" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Steamed') ? 'selected' : ''; ?>>Steamed</option>
            <option value="Raw" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Raw') ? 'selected' : ''; ?>>Raw</option>
            <option value="Boiled" <?php echo ($edit_mode && isset($edit_data['cooking_style']) && $edit_data['cooking_style'] == 'Boiled') ? 'selected' : ''; ?>>Boiled</option>
        </select>
    </div>
    <div class="form-group full-width">
        <label for="image_path">Food Image URL: <span class="required">*</span></label>
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            <input
                type="url"
                id="image_path"
                name="image_path"
                placeholder="Enter any image URL..."
                required
                value="<?php echo $edit_mode ? htmlspecialchars($edit_data['image_path']) : ''; ?>"
                style="flex: 1;"
            >
            <img 
                id="preview"
                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                alt="Image Preview"
                style="width: 220px; height: 160px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover; background: #f8f9fa;"
            >
        </div>
    </div>
    <?php if (isset($_SESSION['username'])): ?>
        <div class="form-group full-width">
            <label for="added_by">Added By:</label>
            <input type="text" id="added_by" name="added_by" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
    <?php endif; ?>
    </div>
    <div class="submit-btn-container">
        <button type="submit" class="submit-btn"><?php echo $edit_mode ? 'Update Food Item' : 'Submit'; ?></button>
    </div>
    </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image_path');
        const previewImage = document.getElementById('preview');
        const placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // transparent pixel
        const errorImg = 'https://via.placeholder.com/220x160?text=Image+Not+Found';

        function updatePreview() {
            const url = imageInput.value.trim();
            if (!url) {
                previewImage.src = placeholder;
                return;
            }
            previewImage.src = url;
        }

        // Set the initial state and error handler
        previewImage.onerror = function() {
            this.onerror = null; // Prevent infinite loops
            this.src = errorImg;
        };

        // Update on input
        imageInput.addEventListener('input', updatePreview);

        // Update on page load if a URL already exists
        if (imageInput.value) {
            updatePreview();
        }

        // State to City mapping (comprehensive)
        const stateCityMap = {
            "Andaman and Nicobar Islands": ["Port Blair"],
            "Andhra Pradesh": ["Adoni","Amaravati","Anantapur","Chandragiri","Chittoor","Dowlaiswaram","Eluru","Guntur","Kadapa","Kakinada","Kurnool","Machilipatnam","Nagarjunakonda","Rajahmundry","Srikakulam","Tirupati","Vijayawada","Visakhapatnam","Vizianagaram","Yemmiganur"],
            "Arunachal Pradesh": ["Itanagar"],
            "Assam": ["Dhuburi","Dibrugarh","Dispur","Guwahati","Jorhat","Nagaon","Sivasagar","Silchar","Tezpur","Tinsukia"],
            "Bihar": ["Ara","Barauni","Begusarai","Bettiah","Bhagalpur","Bihar Sharif","Bodh Gaya","Buxar","Chapra","Darbhanga","Dehri","Dinapur Nizamat","Gaya","Hajipur","Jamalpur","Katihar","Madhubani","Motihari","Munger","Muzaffarpur","Patna","Purnia","Pusa","Saharsa","Samastipur","Sasaram","Sitamarhi","Siwan"],
            "Chandigarh": ["Chandigarh"],
            "Chhattisgarh": ["Ambikapur","Bhilai","Bilaspur","Dhamtari","Durg","Jagdalpur","Raipur","Rajnandgaon"],
            "Dadra and Nagar Haveli and Daman and Diu": ["Daman","Diu","Silvassa"],
            "Delhi": ["Delhi","New Delhi"],
            "Goa": ["Madgaon","Panaji"],
            "Gujarat": ["Ahmadabad","Amreli","Bharuch","Bhavnagar","Bhuj","Dwarka","Gandhinagar","Godhra","Jamnagar","Junagadh","Kandla","Khambhat","Kheda","Mahesana","Morbi","Nadiad","Navsari","Okha","Palanpur","Patan","Porbandar","Rajkot","Surat","Surendranagar","Valsad","Veraval"],
            "Haryana": ["Ambala","Bhiwani","Chandigarh","Faridabad","Firozpur Jhirka","Gurugram","Hansi","Hisar","Jind","Kaithal","Karnal","Kurukshetra","Panipat","Pehowa","Rewari","Rohtak","Sirsa","Sonipat"],
            "Himachal Pradesh": ["Bilaspur","Chamba","Dalhousie","Dharmshala","Hamirpur","Kangra","Kullu","Mandi","Nahan","Shimla","Una"],
            "Jammu and Kashmir": ["Anantnag","Baramula","Doda","Gulmarg","Jammu","Kathua","Punch","Rajouri","Srinagar","Udhampur"],
            "Jharkhand": ["Bokaro","Chaibasa","Deoghar","Dhanbad","Dumka","Giridih","Hazaribag","Jamshedpur","Jharia","Rajmahal","Ranchi","Saraikela"],
            "Karnataka": ["Badami","Ballari","Bengaluru","Belagavi","Bhadravati","Bidar","Chikkamagaluru","Chitradurga","Davangere","Halebid","Hassan","Hubballi-Dharwad","Kalaburagi","Kolar","Madikeri","Mandya","Mangaluru","Mysuru","Raichur","Shivamogga","Shravanabelagola","Shrirangapattana","Tumakuru","Vijayapura"],
            "Kerala": ["Alappuzha","Vatakara","Idukki","Kannur","Kochi","Kollam","Kottayam","Kozhikode","Mattancheri","Palakkad","Thalassery","Thiruvananthapuram","Thrissur"],
            "Ladakh": ["Kargil","Leh"],
            "Lakshadweep": [],
            "Madhya Pradesh": ["Balaghat","Barwani","Betul","Bharhut","Bhind","Bhojpur","Bhopal","Burhanpur","Chhatarpur","Chhindwara","Damoh","Datia","Dewas","Dhar","Dr. Ambedkar Nagar (Mhow)","Guna","Gwalior","Hoshangabad","Indore","Itarsi","Jabalpur","Jhabua","Khajuraho","Khandwa","Khargone","Maheshwar","Mandla","Mandsaur","Morena","Murwara","Narsimhapur","Narsinghgarh","Narwar","Neemuch","Nowgong","Orchha","Panna","Raisen","Rajgarh","Ratlam","Rewa","Sagar","Sarangpur","Satna","Sehore","Seoni","Shahdol","Shajapur","Sheopur","Shivpuri","Ujjain","Vidisha"],
            "Maharashtra": ["Ahmadnagar","Akola","Amravati","Aurangabad","Bhandara","Bhusawal","Bid","Buldhana","Chandrapur","Daulatabad","Dhule","Jalgaon","Kalyan","Karli","Kolhapur","Mahabaleshwar","Malegaon","Matheran","Mumbai","Nagpur","Nanded","Nashik","Osmanabad","Pandharpur","Parbhani","Pune","Ratnagiri","Sangli","Satara","Sevagram","Solapur","Thane","Ulhasnagar","Vasai-Virar","Wardha","Yavatmal"],
            "Manipur": ["Imphal"],
            "Meghalaya": ["Cherrapunji","Shillong"],
            "Mizoram": ["Aizawl","Lunglei"],
            "Nagaland": ["Kohima","Mon","Phek","Wokha","Zunheboto"],
            "Odisha": ["Balangir","Baleshwar","Baripada","Bhubaneshwar","Brahmapur","Cuttack","Dhenkanal","Kendujhar","Konark","Koraput","Paradip","Phulabani","Puri","Sambalpur","Udayagiri"],
            "Puducherry": ["Karaikal","Mahe","Puducherry","Yanam"],
            "Punjab": ["Amritsar","Batala","Chandigarh","Faridkot","Firozpur","Gurdaspur","Hoshiarpur","Jalandhar","Kapurthala","Ludhiana","Nabha","Patiala","Rupnagar","Sangrur"],
            "Rajasthan": ["Abu","Ajmer","Alwar","Amer","Barmer","Beawar","Bharatpur","Bhilwara","Bikaner","Bundi","Chittaurgarh","Churu","Dhaulpur","Dungarpur","Ganganagar","Hanumangarh","Jaipur","Jaisalmer","Jalor","Jhalawar","Jhunjhunu","Jodhpur","Kishangarh","Kota","Merta","Nagaur","Nathdwara","Pali","Phalodi","Pushkar","Sawai Madhopur","Shahpura","Sikar","Sirohi","Tonk","Udaipur"],
            "Sikkim": ["Gangtok","Gyalshing","Lachung","Mangan"],
            "Tamil Nadu": ["Arcot","Chengalpattu","Chennai","Chidambaram","Coimbatore","Cuddalore","Dharmapuri","Dindigul","Erode","Kanchipuram","Kanniyakumari","Kodaikanal","Kumbakonam","Madurai","Mamallapuram","Nagappattinam","Nagercoil","Palayamkottai","Pudukkottai","Rajapalayam","Ramanathapuram","Salem","Thanjavur","Tiruchchirappalli","Tirunelveli","Tiruppur","Thoothukudi","Udhagamandalam","Vellore"],
            "Telangana": ["Hyderabad","Karimnagar","Khammam","Mahbubnagar","Nizamabad","Sangareddi","Warangal"],
            "Tripura": ["Agartala"],
            "Uttar Pradesh": ["Agra","Aligarh","Amroha","Ayodhya","Azamgarh","Bahraich","Ballia","Banda","Bara Banki","Bareilly","Basti","Bijnor","Bithur","Budaun","Bulandshahr","Deoria","Etah","Etawah","Faizabad","Farrukhabad-cum-Fatehgarh","Fatehpur","Fatehpur Sikri","Ghaziabad","Ghazipur","Gonda","Gorakhpur","Hamirpur","Hardoi","Hathras","Jalaun","Jaunpur","Jhansi","Kannauj","Kanpur","Lakhimpur","Lalitpur","Lucknow","Mainpuri","Mathura","Meerut","Mirzapur-Vindhyachal","Moradabad","Muzaffarnagar","Partapgarh","Pilibhit","Prayagraj","Rae Bareli","Rampur","Saharanpur","Sambhal","Shahjahanpur","Sitapur","Sultanpur","Tehri","Varanasi"],
            "Uttarakhand": ["Almora","Dehra Dun","Haridwar","Mussoorie","Nainital","Pithoragarh"],
            "West Bengal": ["Alipore","Alipur Duar","Asansol","Baharampur","Bally","Balurghat","Bankura","Baranagar","Barasat","Barrackpore","Basirhat","Bhatpara","Bishnupur","Budge Budge","Burdwan","Chandernagore","Darjeeling","Diamond Harbour","Dum Dum","Durgapur","Halisahar","Haora","Hugli","Ingraj Bazar","Jalpaiguri","Kalimpong","Kamarhati","Kanchrapara","Kharagpur","Cooch Behar","Kolkata","Krishnanagar","Malda","Midnapore","Murshidabad","Nabadwip","Palashi","Panihati","Purulia","Raiganj","Santipur","Shantiniketan","Shrirampur","Siliguri","Siuri","Tamluk","Titagarh"]
        };

        $(document).ready(function() {
            function initSelect2(selector, placeholder) {
                $(selector).select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%'
                });
            }

            initSelect2('#state', 'Select State');
            initSelect2('#city', 'Select City');
            initSelect2('#cuisine_type', 'Select Cuisine Type');
            initSelect2('#meal_type', 'Select Meal Type');
            initSelect2('#dietary_preference', 'Select Dietary Preference');
            initSelect2('#spice_level', 'Select Spice Level');
            initSelect2('#price_range', 'Select Price Range');
            initSelect2('#popularity', 'Select Popularity');
            initSelect2('#cooking_style', 'Select Cooking Style');

            // Populate city options when state changes
            $('#state').on('change', function() {
                const state = $(this).val();
                const citySelect = $('#city');
                citySelect.empty().append('<option value="">Select City</option>');
                if (stateCityMap[state]) {
                    stateCityMap[state].forEach(function(city) {
                        citySelect.append($('<option>', { value: city, text: city }));
                    });
                }
                citySelect.val('').trigger('change'); // Update Select2
            });

            // Edit mode: pre-populate city options and select the current city
            <?php if ($edit_mode): ?>
            const currentState = '<?php echo htmlspecialchars($edit_data['state']); ?>';
            const currentCity = '<?php echo htmlspecialchars($edit_data['city']); ?>';
            if (currentState && stateCityMap[currentState]) {
                const citySelect = $('#city');
                citySelect.empty().append('<option value="">Select City</option>');
                stateCityMap[currentState].forEach(function(city) {
                    citySelect.append($('<option>', { value: city, text: city }));
                });
                citySelect.val(currentCity).trigger('change');
            }
            <?php endif; ?>

        });

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const isNameValid = validateFoodName();
            const isPriceValid = validatePriceRange();
            
            if (!isNameValid || !isPriceValid) {
                e.preventDefault();
                alert('Please fill in all required fields correctly.');
            }
        });

        function filterDropdown(searchInputId, selectId) {
            const input = document.getElementById(searchInputId);
            const filter = input.value.toLowerCase();
            const select = document.getElementById(selectId);
            for (let i = 0; i < select.options.length; i++) {
                const option = select.options[i];
                if (option.text.toLowerCase().indexOf(filter) > -1 || option.value === "") {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
        }
    </script>
    
    <?php if (isset($conn)): ?>
        <?php $conn->close(); ?>
    <?php endif; ?>
</body>
</html>
