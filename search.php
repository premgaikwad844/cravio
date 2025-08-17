<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Function to get proper image URL for local development
function getImageUrl($image_path) {
    if (empty($image_path)) {
        return '';
    }
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    if (strpos($image_path, 'uploads/') === 0) {
        return $image_path;
    }
    if (!strpos($image_path, 'uploads/')) {
        return 'uploads/' . $image_path;
    }
    return $image_path;
}

$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination settings
$items_per_page = 20; // Show 20 dishes per page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $items_per_page;

// Check if user wants to show all or search
$show_all = isset($_GET['show_all']) && $_GET['show_all'] == '1';

// Build dynamic WHERE clause based on filters
$where = [];
$params = [];
$types = '';

// Only apply filters if not showing all and there are search parameters
if (!$show_all) {
    if (!empty($_GET['q'])) {
        $where[] = "(food_name LIKE ? OR description LIKE ? OR cuisine_type LIKE ? OR city LIKE ? OR state LIKE ?)";
        $params[] = '%' . $_GET['q'] . '%';
        $params[] = '%' . $_GET['q'] . '%';
        $params[] = '%' . $_GET['q'] . '%';
        $params[] = '%' . $_GET['q'] . '%';
        $params[] = '%' . $_GET['q'] . '%';
        $types .= 'sssss';
    }
    if (!empty($_GET['state'])) {
        $where[] = "(state = ? OR state IS NULL OR state = '')";
        $params[] = $_GET['state'];
        $types .= 's';
    }
    if (!empty($_GET['city'])) {
        $where[] = "(city = ? OR city IS NULL OR city = '')";
        $params[] = $_GET['city'];
        $types .= 's';
    }
    if (!empty($_GET['cuisine_type'])) {
        $where[] = "(cuisine_type = ? OR cuisine_type IS NULL OR cuisine_type = '')";
        $params[] = $_GET['cuisine_type'];
        $types .= 's';
    }
    if (!empty($_GET['meal_type'])) {
        $where[] = "(meal_type = ? OR meal_type IS NULL OR meal_type = '')";
        $params[] = $_GET['meal_type'];
        $types .= 's';
    }
    if (!empty($_GET['dietary_preference'])) {
        $where[] = "(dietary_preference = ? OR dietary_preference IS NULL OR dietary_preference = '')";
        $params[] = $_GET['dietary_preference'];
        $types .= 's';
    }
    if (!empty($_GET['spice_level'])) {
        $where[] = "(spice_level = ? OR spice_level IS NULL OR spice_level = '')";
        $params[] = $_GET['spice_level'];
        $types .= 's';
    }
    if (!empty($_GET['price_range'])) {
        $where[] = "(price_range = ? OR price_range IS NULL OR price_range = '')";
        $params[] = $_GET['price_range'];
        $types .= 's';
    }
    if (!empty($_GET['popularity'])) {
        $where[] = "(popularity = ? OR popularity IS NULL OR popularity = '')";
        $params[] = $_GET['popularity'];
        $types .= 's';
    }
    if (!empty($_GET['cooking_style'])) {
        $where[] = "(cooking_style = ? OR cooking_style IS NULL OR cooking_style = '')";
        $params[] = $_GET['cooking_style'];
        $types .= 's';
    }
}

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM food_items";
if ($where) {
    $count_sql .= " WHERE " . implode(" AND ", $where);
}
$count_stmt = $conn->prepare($count_sql);
if ($params) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_items = $count_result->fetch_assoc()['total'];

// Calculate pagination
$total_pages = ceil($total_items / $items_per_page);
$page = min($page, $total_pages);
$offset = ($page - 1) * $items_per_page;

// Get food items with pagination
$sql = "SELECT id, food_name, cuisine_type, price_range, image_path, city, cooking_style, dietary_preference, state, meal_type, spice_level, popularity FROM food_items";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY food_name ASC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
file_put_contents('debug.log', print_r([
    'sql' => $sql,
    'types' => $types,
    'params' => $params,
    'items_per_page' => $items_per_page,
    'offset' => $offset
], true), FILE_APPEND);
$bind_types = $types . 'ii';
$bind_params = array_merge($params, [$items_per_page, $offset]);
$stmt->bind_param($bind_types, ...$bind_params);
$stmt->execute();
$result = $stmt->get_result();

$food_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $food_items[] = $row;
    }
}

// Fetch unique states and cities for filters
$states_sql = "SELECT DISTINCT state FROM food_items WHERE state IS NOT NULL AND state != '' ORDER BY state";
$states_result = $conn->query($states_sql);
$states = [];
if ($states_result && $states_result->num_rows > 0) {
    while($row = $states_result->fetch_assoc()) {
        $states[] = $row['state'];
    }
}
$cities_sql = "SELECT DISTINCT city, state FROM food_items WHERE city IS NOT NULL AND city != '' ORDER BY city";
$cities_result = $conn->query($cities_sql);
$cities = [];
$state_city_map = [];
if ($cities_result && $cities_result->num_rows > 0) {
    while($row = $cities_result->fetch_assoc()) {
        $cities[] = $row['city'];
        $state = $row['state'];
        $city = $row['city'];
        if (!isset($state_city_map[$state])) $state_city_map[$state] = [];
        if (!in_array($city, $state_city_map[$state])) $state_city_map[$state][] = $city;
    }
}
$conn->close();
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products | Fitmeal</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:700,600,400&display=swap" rel="stylesheet">
    <style>
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
            color: #97c933;
            font-size: 1.2rem;
            font-weight: 700;
        }
        .loading::after {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #97c933;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            background: white;
        }
        .pagination a:hover {
            background: #97c933;
            color: white;
        }
        .pagination .current {
            background: #97c933;
            color: white;
        }
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        .view-toggle a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .view-toggle .active {
            background: #97c933;
            color: white;
        }
        .view-toggle .inactive {
            background: #f0f0f0;
            color: #666;
        }
        .view-toggle .inactive:hover {
            background: #e0e0e0;
        }

        /* Enhanced Search Bar Styles */
        .modern-search-bar {
            position: relative;
            width: 100%;
            margin-bottom: 1.5rem;
            box-sizing: border-box;
        }
        .modern-search-bar input[type="text"] {
            width: 100%;
            padding: 14px 44px 14px 44px; /* left and right for icon/button */
            border-radius: 32px;
            border: 2px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(151,201,51,0.07);
            font-size: 1.08rem;
            background: #fff;
            color: #23272b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            margin: 0;
        }
        .modern-search-bar input[type="text"]:focus {
            border-color: #97c933;
            box-shadow: 0 2px 12px rgba(151,201,51,0.13);
        }
        .modern-search-bar .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #97c933;
            font-size: 1.3em;
            pointer-events: none;
        }
        .modern-search-bar button[type="submit"] {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: #97c933;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
            padding: 0;
        }
        .modern-search-bar button[type="submit"]:hover {
            background: #7ba026;
        }
        /* Ensure sidebar uses border-box for all children */
        .sidebar *, .sidebar *:before, .sidebar *:after {
            box-sizing: border-box;
        }
    </style>
    <style>
    /* Add this at the end of your <style> block or in main.css for mobile search bar styling */
    @media (max-width: 900px) {
        .mobile-search-bar {
            width: 100%;
            font-size: 1.1rem;
            padding: 14px 44px 14px 44px;
            border-radius: 32px;
            border: 2px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(151,201,51,0.07);
            background: #fff;
            color: #23272b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
            margin: 0;
        }
        .mobile-search-bar:focus {
            border-color: #97c933;
            box-shadow: 0 2px 12px rgba(151,201,51,0.13);
        }
    }
    </style>
    <style>
@media (min-width: 769px) {
  .view-toggle a:nth-child(2) { display: none !important; }
}
@media (max-width: 768px) {
  .view-toggle a:nth-child(2) { display: inline-block; }
}
</style>
</head>
<body>
<!-- Mobile-Only Menubar Start -->
<style>
@media (max-width: 768px) {
  .cravio-mobile-menubar, .cravio-mobile-hamburger {
    display: block;
  }
  .cravio-mobile-menubar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: #fff;
    z-index: 2000;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    padding: 24px 0 12px 0;
    display: none;
  }
  .cravio-mobile-menubar.active {
    display: block;
  }
  .cravio-mobile-menubar ul {
    list-style: none;
    padding: 0;
    margin: 0 0 16px 0;
    text-align: center;
  }
  .cravio-mobile-menubar ul li {
    margin: 12px 0;
  }
  .cravio-mobile-menubar a {
    text-decoration: none;
    color: #23272b;
    font-size: 1.1rem;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 20px;
    display: inline-block;
    transition: background 0.2s;
  }
  .cravio-mobile-menubar a.active,
  .cravio-mobile-menubar a:hover {
    background: #97c933;
    color: #fff;
  }
  .cravio-mobile-hamburger {
    position: fixed;
    top: 16px;
    right: 24px;
    width: 40px;
    height: 40px;
    background: none;
    border: none;
    z-index: 2100;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 6px;
  }
  .cravio-mobile-hamburger span {
    display: block;
    height: 4px;
    width: 100%;
    background: #23272b;
    border-radius: 2px;
    transition: 0.3s;
  }
}
@media (min-width: 769px) {
  .cravio-mobile-menubar, .cravio-mobile-hamburger {
    display: none !important;
  }
}
</style>
<button class="cravio-mobile-hamburger" aria-label="Open menu" onclick="cravioToggleMobileMenu()">
  <span></span>
  <span></span>
  <span></span>
</button>
<div class="cravio-mobile-menubar" id="cravioMobileMenu">
  <ul>
    <li><a href="main.php">Home</a></li>
    <li><a href="about.php">About us</a></li>
    <li><a href="search.php" class="active">Products</a></li>
    <li><a href="contact.php">Contact Us</a></li>
  </ul>
  <?php if (isset($_SESSION['user_id'])): ?>
      <a href="logout.php" class="navbar-btn" style="background: #e53935; color: #fff; border-radius: 20px; padding: 8px 20px; display: inline-block; font-weight: bold;">Logout</a>
  <?php endif; ?>
</div>
<script>
function cravioToggleMobileMenu() {
  var menu = document.getElementById('cravioMobileMenu');
  menu.classList.toggle('active');
}
document.addEventListener('click', function(event) {
  var menu = document.getElementById('cravioMobileMenu');
  var hamburger = document.querySelector('.cravio-mobile-hamburger');
  if (!menu.contains(event.target) && !hamburger.contains(event.target)) {
    menu.classList.remove('active');
  }
});
document.querySelectorAll('.cravio-mobile-menubar a').forEach(function(link) {
  link.addEventListener('click', function() {
    document.getElementById('cravioMobileMenu').classList.remove('active');
  });
});
</script>
<!-- Mobile-Only Menubar End -->
    <!-- Navbar/Header -->
    <nav class="navbar">
        <div class="navbar-logo-group">
            <img src="f2-removebg-preview.png" alt="fitmeal logo" class="navbar-logo-icon">
            <div class="navbar-logo-text">
                <span class="navbar-logo-title">Cravio</span>
            </div>
        </div> 
        <ul class="navbar-links">
            <li><a href="main.php" class="active">Home <span class="arrow">&#8250;</span></a></li>
            <li><a href="about.php">About us <span class="arrow">&#8250;</span></a></li>
            <li><a href="search.php">Products <span class="arrow">&#8250;</span></a></li>
            <li><a href="contact.php">Contact Us <span class="arrow">&#8250;</span></a></li>
        </ul>
        
        <div class="navbar-mobile-menu">
            <ul>
                <li><a href="index.php" style="background: #e53935; color: #fff; border-radius: 20px; padding: 8px 20px; display: inline-block; font-weight: bold;">Logout</a></li>
                <li><a href="main.php" class="active">Home</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="search.php">Products</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="navbar-btn">Logout</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero/Banner Section -->
    <section class="hero-banner" style="position:relative; background:#888a8e; min-height:320px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
        <img src="https://wallpaperaccess.com/full/8433458.jpg" alt="Banner" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; opacity:0.45; z-index:1;">
        <div style="position:relative; z-index:2; text-align:center; width:100%;">
            <h1 style="font-size:3rem; color:#fff; font-weight:800; margin-bottom:0.5rem;">All Products</h1>
            <div class="breadcrumbs" style="font-size:1.1rem; color:#d0e17d; font-weight:700;">
                <a href="main.php" style="color:#23272b; text-decoration:none; font-weight:700;">Home</a>
                <span style="color:#fff; margin:0 8px;">|</span>
                <span style="color:#fff;">Shop</span>
            </div>
        </div>
    </section>

    <!-- Main Content Layout -->
    <div class="main-content" style="display:flex; gap:40px; max-width:1400px; margin:0 auto; padding:40px 20px 0 20px; align-items:flex-start;">
        <!-- Sidebar: Compact Filters (Desktop Only) -->
        <aside class="sidebar compact-filters fade-in-card" aria-label="Product Filters" style="background:#fff; border-radius:16px; box-shadow:0 2px 16px rgba(0,0,0,0.06); padding:18px 12px; min-width:220px; max-width:260px; flex:1 1 220px; display:flex; flex-direction:column; gap:16px; font-size:0.95rem;">
            <form method="GET" action="search.php" id="filterForm">
                <div class="modern-search-bar">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="q" id="searchInput" class="mobile-search-bar" placeholder="Search for dishes, ingredients, or cuisines…" aria-label="Search" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
                    <button type="submit" id="searchBtn" aria-label="Search">&#10140;</button>
                </div>
                <label for="stateFilter">State</label>
                <select name="state" id="stateFilter">
                    <option value="">All</option>
                    <?php foreach ($states as $state): ?>
                        <option value="<?= htmlspecialchars($state) ?>" <?= (isset($_GET['state']) && $_GET['state'] === $state) ? 'selected' : '' ?>><?= htmlspecialchars($state) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="cityFilter">City</label>
                <select name="city" id="cityFilter">
                    <option value="">All</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city) ?>" <?= (isset($_GET['city']) && $_GET['city'] === $city) ? 'selected' : '' ?>><?= htmlspecialchars($city) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="cuisineType">Cuisine Type</label>
                <select name="cuisine_type" id="cuisineType">
                    <option value="">All</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Indian') ? 'selected' : '' ?>>Indian</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Chinese') ? 'selected' : '' ?>>Chinese</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Italian') ? 'selected' : '' ?>>Italian</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Mexican') ? 'selected' : '' ?>>Mexican</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Thai') ? 'selected' : '' ?>>Thai</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Continental') ? 'selected' : '' ?>>Continental</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Fast Food') ? 'selected' : '' ?>>Fast Food</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Desserts') ? 'selected' : '' ?>>Desserts</option>
                    <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Street Food') ? 'selected' : '' ?>>Street Food</option>
                </select>
                <label for="mealType">Meal Type</label>
                <select name="meal_type" id="mealType">
                    <option value="">All</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Breakfast') ? 'selected' : '' ?>>Breakfast</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Lunch') ? 'selected' : '' ?>>Lunch</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Dinner') ? 'selected' : '' ?>>Dinner</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Snacks') ? 'selected' : '' ?>>Snacks</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Desserts') ? 'selected' : '' ?>>Desserts</option>
                    <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Drinks') ? 'selected' : '' ?>>Drinks</option>
                </select>
                <label for="dietaryPreference">Dietary Preference</label>
                <select name="dietary_preference" id="dietaryPreference">
                    <option value="">All</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Vegetarian') ? 'selected' : '' ?>>Vegetarian</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Vegan') ? 'selected' : '' ?>>Vegan</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Non-Vegetarian') ? 'selected' : '' ?>>Non-Vegetarian</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Gluten-Free') ? 'selected' : '' ?>>Gluten-Free</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Keto') ? 'selected' : '' ?>>Keto</option>
                    <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Paleo') ? 'selected' : '' ?>>Paleo</option>
                </select>
                <label for="spiceLevel">Spice Level</label>
                <select name="spice_level" id="spiceLevel">
                    <option value="">All</option>
                    <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Mild') ? 'selected' : '' ?>>Mild</option>
                    <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Medium') ? 'selected' : '' ?>>Medium</option>
                    <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Hot') ? 'selected' : '' ?>>Hot</option>
                    <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Very Hot') ? 'selected' : '' ?>>Very Hot</option>
                </select>
                <label for="priceRange">Price Range</label>
                <select name="price_range" id="priceRange">
                    <option value="">All</option>
                    <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Budget') ? 'selected' : '' ?>>Budget</option>
                    <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Moderate') ? 'selected' : '' ?>>Moderate</option>
                    <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Premium') ? 'selected' : '' ?>>Premium</option>
                </select>
                <label for="popularity">Popularity</label>
                <select name="popularity" id="popularity">
                    <option value="">All</option>
                    <option <?= (isset($_GET['popularity']) && $_GET['popularity'] === 'Most Popular') ? 'selected' : '' ?>>Most Popular</option>
                    <option <?= (isset($_GET['popularity']) && $_GET['popularity'] === 'Highly Rated') ? 'selected' : '' ?>>Highly Rated</option>
                </select>
                <label for="cookingStyle">Cooking Style</label>
                <select name="cooking_style" id="cookingStyle">
                    <option value="">All</option>
                    <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Grilled') ? 'selected' : '' ?>>Grilled</option>
                    <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Fried') ? 'selected' : '' ?>>Fried</option>
                    <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Baked') ? 'selected' : '' ?>>Baked</option>
                    <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Steamed') ? 'selected' : '' ?>>Steamed</option>
                    <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Raw') ? 'selected' : '' ?>>Raw</option>
                </select>
                <button type="submit" id="searchBtn" style="width:100%; display:block; margin-bottom:10px; padding:12px 0; background:#e74c3c; color:#fff; border:none; border-radius:10px; font-weight:700; font-size:1.1rem; cursor:pointer; transition:background 0.2s, color 0.2s; box-shadow:0 2px 8px rgba(231,76,60,0.08);">Search</button>
                <a href="search.php" id="resetFilters" style="width:100%; display:block; text-align:center; padding:12px 0; background:#97c933; color:#fff; border:none; border-radius:10px; font-weight:700; font-size:1.1rem; cursor:pointer; margin-bottom:8px; box-shadow:0 2px 8px rgba(151,201,51,0.08); text-decoration:none;">Reset Filters</a>
            </form>
        </aside>
        <!-- Mobile-Only Search Bar and Filters (Visible Only on Mobile) -->
        <div class="mobile-search-filters">
          <form method="GET" action="search.php" id="mobileSearchBarForm" style="width:100%; margin-bottom:18px;">
            <div class="modern-search-bar">
              <span class="search-icon">🔍</span>
              <input type="text" name="q" class="mobile-search-bar" placeholder="Search for dishes, ingredients, or cuisines…" aria-label="Search" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
              <button type="submit" aria-label="Search">&#10140;</button>
            </div>
            <label for="mobileStateFilter">State</label>
            <select name="state" id="mobileStateFilter">
              <option value="">All</option>
              <?php foreach ($states as $state): ?>
                <option value="<?= htmlspecialchars($state) ?>" <?= (isset($_GET['state']) && $_GET['state'] === $state) ? 'selected' : '' ?>><?= htmlspecialchars($state) ?></option>
              <?php endforeach; ?>
            </select>
            <label for="mobileCityFilter">City</label>
            <select name="city" id="mobileCityFilter">
              <option value="">All</option>
              <?php foreach ($cities as $city): ?>
                <option value="<?= htmlspecialchars($city) ?>" <?= (isset($_GET['city']) && $_GET['city'] === $city) ? 'selected' : '' ?>><?= htmlspecialchars($city) ?></option>
              <?php endforeach; ?>
            </select>
            <label for="mobileCuisineType">Cuisine Type</label>
            <select name="cuisine_type" id="mobileCuisineType">
              <option value="">All</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Indian') ? 'selected' : '' ?>>Indian</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Chinese') ? 'selected' : '' ?>>Chinese</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Italian') ? 'selected' : '' ?>>Italian</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Mexican') ? 'selected' : '' ?>>Mexican</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Thai') ? 'selected' : '' ?>>Thai</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Continental') ? 'selected' : '' ?>>Continental</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Fast Food') ? 'selected' : '' ?>>Fast Food</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Desserts') ? 'selected' : '' ?>>Desserts</option>
              <option <?= (isset($_GET['cuisine_type']) && $_GET['cuisine_type'] === 'Street Food') ? 'selected' : '' ?>>Street Food</option>
            </select>
            <label for="mobileMealType">Meal Type</label>
            <select name="meal_type" id="mobileMealType">
              <option value="">All</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Breakfast') ? 'selected' : '' ?>>Breakfast</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Lunch') ? 'selected' : '' ?>>Lunch</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Dinner') ? 'selected' : '' ?>>Dinner</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Snacks') ? 'selected' : '' ?>>Snacks</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Desserts') ? 'selected' : '' ?>>Desserts</option>
              <option <?= (isset($_GET['meal_type']) && $_GET['meal_type'] === 'Drinks') ? 'selected' : '' ?>>Drinks</option>
            </select>
            <label for="mobileDietaryPreference">Dietary Preference</label>
            <select name="dietary_preference" id="mobileDietaryPreference">
              <option value="">All</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Vegetarian') ? 'selected' : '' ?>>Vegetarian</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Vegan') ? 'selected' : '' ?>>Vegan</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Non-Vegetarian') ? 'selected' : '' ?>>Non-Vegetarian</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Gluten-Free') ? 'selected' : '' ?>>Gluten-Free</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Keto') ? 'selected' : '' ?>>Keto</option>
              <option <?= (isset($_GET['dietary_preference']) && $_GET['dietary_preference'] === 'Paleo') ? 'selected' : '' ?>>Paleo</option>
            </select>
            <label for="mobileSpiceLevel">Spice Level</label>
            <select name="spice_level" id="mobileSpiceLevel">
              <option value="">All</option>
              <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Mild') ? 'selected' : '' ?>>Mild</option>
              <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Medium') ? 'selected' : '' ?>>Medium</option>
              <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Hot') ? 'selected' : '' ?>>Hot</option>
              <option <?= (isset($_GET['spice_level']) && $_GET['spice_level'] === 'Very Hot') ? 'selected' : '' ?>>Very Hot</option>
            </select>
            <label for="mobilePriceRange">Price Range</label>
            <select name="price_range" id="mobilePriceRange">
              <option value="">All</option>
              <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Budget') ? 'selected' : '' ?>>Budget</option>
              <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Moderate') ? 'selected' : '' ?>>Moderate</option>
              <option <?= (isset($_GET['price_range']) && $_GET['price_range'] === 'Premium') ? 'selected' : '' ?>>Premium</option>
            </select>
            <label for="mobilePopularity">Popularity</label>
            <select name="popularity" id="mobilePopularity">
              <option value="">All</option>
              <option <?= (isset($_GET['popularity']) && $_GET['popularity'] === 'Most Popular') ? 'selected' : '' ?>>Most Popular</option>
              <option <?= (isset($_GET['popularity']) && $_GET['popularity'] === 'Highly Rated') ? 'selected' : '' ?>>Highly Rated</option>
            </select>
            <label for="mobileCookingStyle">Cooking Style</label>
            <select name="cooking_style" id="mobileCookingStyle">
              <option value="">All</option>
              <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Grilled') ? 'selected' : '' ?>>Grilled</option>
              <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Fried') ? 'selected' : '' ?>>Fried</option>
              <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Baked') ? 'selected' : '' ?>>Baked</option>
              <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Steamed') ? 'selected' : '' ?>>Steamed</option>
              <option <?= (isset($_GET['cooking_style']) && $_GET['cooking_style'] === 'Raw') ? 'selected' : '' ?>>Raw</option>
            </select>
            <button type="submit" style="width:100%; display:block; margin-bottom:10px; padding:12px 0; background:#e74c3c; color:#fff; border:none; border-radius:10px; font-weight:700; font-size:1.1rem; cursor:pointer; transition:background 0.2s, color 0.2s; box-shadow:0 2px 8px rgba(231,76,60,0.08);">Search</button>
            <a href="search.php" style="width:100%; display:block; text-align:center; padding:12px 0; background:#97c933; color:#fff; border:none; border-radius:10px; font-weight:700; font-size:1.1rem; cursor:pointer; margin-bottom:8px; box-shadow:0 2px 8px rgba(151,201,51,0.08); text-decoration:none;">Reset Filters</a>
          </form>
        </div>
        <style>
        .mobile-search-filters { display: none; }
        @media (max-width: 768px) {
          .mobile-search-filters {
            display: block;
            background: #fff;
            padding: 18px 10px 16px 10px;
            border-radius: 16px;
            margin: 16px 0 18px 0;
            box-shadow: 0 4px 18px rgba(151,201,51,0.10);
            max-width: 98vw;
          }
          .mobile-search-filters form {
            display: flex;
            flex-direction: column;
            gap: 14px;
          }
          .mobile-search-filters label {
            font-size: 1.05rem;
            color: #23272b;
            font-weight: 600;
            margin-bottom: 4px;
            margin-top: 2px;
          }
          .mobile-search-filters select,
          .mobile-search-filters input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1.5px solid #e0e0e0;
            background: #fafafa;
            font-size: 1.08rem;
            color: #23272b;
            margin-bottom: 2px;
            box-shadow: 0 1px 4px rgba(151,201,51,0.04);
            transition: border-color 0.2s, box-shadow 0.2s;
          }
          .mobile-search-filters select:focus,
          .mobile-search-filters input[type="text"]:focus {
            border-color: #97c933;
            box-shadow: 0 2px 10px rgba(151,201,51,0.13);
            outline: none;
          }
          .mobile-search-filters button[type="submit"] {
            background: #97c933;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px 0;
            font-size: 1.13rem;
            font-weight: 700;
            margin-top: 8px;
            margin-bottom: 0;
            box-shadow: 0 2px 8px rgba(151,201,51,0.10);
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
          }
          .mobile-search-filters button[type="submit"]:hover {
            background: #7ba026;
          }
          .mobile-search-filters a {
            display: block;
            text-align: center;
            background: #e74c3c;
            color: #fff;
            border-radius: 12px;
            padding: 12px 0;
            font-size: 1.08rem;
            font-weight: 700;
            margin-top: 6px;
            margin-bottom: 0;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(231,76,60,0.08);
            transition: background 0.2s, color 0.2s;
          }
          .mobile-search-filters a:hover {
            background: #c0392b;
          }
          .mobile-search-filters .modern-search-bar {
            position: relative;
            margin-bottom: 0.5rem;
          }
          .mobile-search-filters .modern-search-bar .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #97c933;
            font-size: 1.3em;
            pointer-events: none;
          }
          .mobile-search-filters .modern-search-bar input[type="text"] {
            padding-left: 40px;
            padding-right: 44px;
          }
          .mobile-search-filters .modern-search-bar button[type="submit"] {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: #97c933;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 1px 4px rgba(151,201,51,0.10);
            margin: 0;
            padding: 0;
          }
          .mobile-search-filters .modern-search-bar button[type="submit"]:hover {
            background: #7ba026;
          }
          .mobile-search-filters select:disabled {
            background: #f0f0f0;
            color: #aaa;
            cursor: not-allowed;
            border-color: #e0e0e0;
          }
        }
        @media (min-width: 769px) {
          .mobile-search-filters { display: none !important; }
        }
        </style>
        <!-- Main Content Area -->
        <div class="main-content" style="flex:3 1 0; min-width:320px; width:100%; display:flex; flex-direction:column;">
            <!-- View Toggle -->
            <div class="view-toggle">
                <a href="search.php?show_all=1" class="<?= $show_all ? 'active' : 'inactive' ?>">Show All Dishes</a>
                
            </div>
            
            <div class="product-area" style="flex:3 1 0; min-width:320px; width:100%;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                    <div id="resultsCount" style="font-size:1.1rem; color:#23272b;">
                        <?php 
                        $start_item = $offset + 1;
                        $end_item = min($offset + count($food_items), $total_items);
                        echo "Showing $start_item-$end_item of $total_items dishes";
                        if (!$show_all && (!empty($_GET['q']) || !empty($_GET['state']) || !empty($_GET['city']) || !empty($_GET['cuisine_type']) || !empty($_GET['meal_type']) || !empty($_GET['dietary_preference']) || !empty($_GET['spice_level']) || !empty($_GET['price_range']) || !empty($_GET['popularity']) || !empty($_GET['cooking_style']))) {
                            echo " (filtered results)";
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div id="loadingState" class="loading">Loading dishes...</div>
                
                <div class="product-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:32px;" id="resultsContainer" aria-live="polite">
                    <?php if (count($food_items) > 0): ?>
                        <?php foreach ($food_items as $item): ?>
                            <div class="food-item-card">
                                <img src="<?php echo htmlspecialchars(getImageUrl($item['image_path'])); ?>" alt="<?php echo htmlspecialchars($item['food_name']); ?>" style="width: 100%; height: 180px; object-fit: cover;">
                                <div style="padding: 20px;">
                                    <h4 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($item['food_name']); ?></h4>
                                    <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px;">
                                        <span style="background: #e74c3c; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;"><?php echo htmlspecialchars($item['price_range']); ?></span>
                                        <span style="background: #97c933; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize;"><?php echo htmlspecialchars($item['dietary_preference']); ?></span>
                                    </div>
                                    <p style="margin: 0; color: #666; font-size: 0.9rem; line-height: 1.4;">
                                        <?php echo htmlspecialchars($item['cuisine_type']); ?> | <?php echo htmlspecialchars($item['city']); ?>, <?php echo htmlspecialchars($item['state']); ?>
                                    </p>
                                    <a href="dish-detail.php?id=<?php echo urlencode($item['id']); ?>" style="display:inline-block;margin-top:14px;padding:8px 20px;background:#97c933;color:#fff;border:none;border-radius:6px;font-weight:600;text-decoration:none;transition:background 0.2s;">View</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div id="noResults" style="grid-column:1/-1; text-align:center; color:#97c933; font-size:1.2rem; font-weight:700;">No results found.</div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a>
                        <?php endif; ?>
                        
                        <?php
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        
                        if ($start_page > 1): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>">1</a>
                            <?php if ($start_page > 2): ?>
                                <span>...</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($end_page < $total_pages): ?>
                            <?php if ($end_page < $total_pages - 1): ?>
                                <span>...</span>
                            <?php endif; ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>"><?= $total_pages ?></a>
                        <?php endif; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer" style="background:#23272b; color:#fff; padding:60px 0 30px 0; margin-top:60px;">
        <div class="footer-content" style="max-width:1400px; margin:0 auto; display:flex; flex-wrap:wrap; gap:40px; justify-content:space-between;">
            <div style="flex:1 1 220px; min-width:220px;">
                <img src="f1-removebg-preview.png" alt="Cravio logo" style="width:120px; margin-bottom:18px;">
                <p class="footer-desc" style="font-size: 0.98rem; color: #fff; margin-bottom: 1.2em; max-width: 340px;">Cravio is your trusted companion for discovering, comparing, and enjoying the best food and drinks. We bring you curated recommendations, local favorites, and a seamless experience to satisfy every craving.</p>
                <div class="footer-socials" style="display: flex; gap: 18px; margin-bottom: 1.2em;">
                    <img src="https://cdn-icons-png.flaticon.com/128/733/733547.png" alt="Facebook" style="width: 32px; height: 32px; display: inline-block;">
                    <img src="https://cdn-icons-png.flaticon.com/128/733/733558.png" alt="Instagram" style="width: 32px; height: 32px; display: inline-block;">
                </div>
            </div>
            <div style="flex:1 1 180px; min-width:180px;">
                <h3 style="color:#c6e265; font-size:1.3rem; margin-bottom:18px;">Explore</h3>
                <ul style="list-style:none; padding:0; color:#fff;">
                    <li><a href="main.php" style="color:#fff; text-decoration:none;">Home</a></li>
                    <li><a href="search.php" style="color:#fff; text-decoration:none;">Products</a></li>
                    <li><a href="about.php" style="color:#fff; text-decoration:none;">About Us</a></li>
                    <li><a href="contact.php" style="color:#fff; text-decoration:none;">Contact Us</a></li>
                    <li><a href="privacy_and_policy.php" style="color:#fff; text-decoration:none;">Privacy & Policy</a></li>
                    <li><a href="terms_and_conditions.php" style="color:#fff; text-decoration:none;">Terms & Conditions</a></li>
                </ul>
            </div>
            <div style="flex:1 1 220px; min-width:220px;">
                <h3 style="color:#c6e265; font-size:1.3rem; margin-bottom:18px;">Contact info</h3>
                <div style="color:#fff;">
                    <div class="footer-contact-item" style="margin-bottom:10px;"><span class="footer-contact-icon">📍</span> <span><b>Our location:</b><br>Sangli, Maharashtra, India</span></div>
                    <div class="footer-contact-item"><span class="footer-contact-icon">✉️</span> <span><b>Email:</b><br>cravioweb@gmail.com</span></div>
                </div>
            </div>
        </div>
        <div style="text-align:center; color:#fff; margin-top:40px; font-size:1rem;">
            &copy; 2025 Cravio. All rights reserved.
        </div>
    </footer>
    
    <button id="backToTopBtn" title="Go to top" class="back-to-top-btn align-right" aria-label="Back to top" style="z-index: 1000; position: fixed; bottom: 32px; right: 32px;">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Up Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(-90deg);" />
    </button>
    <script>
    // Show/hide back to top button
    const backToTopBtn = document.getElementById('backToTopBtn');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 200) {
            backToTopBtn.style.display = 'block';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    </script>
    <script>
    // Show loading state when form is submitted
    document.getElementById('filterForm').addEventListener('submit', function() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('resultsContainer').style.display = 'none';
    });
    
    // Show loading state when pagination links are clicked
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' && e.target.href.includes('search.php')) {
            document.getElementById('loadingState').style.display = 'block';
            document.getElementById('resultsContainer').style.display = 'none';
        }
    });

    // --- State to City dynamic filter ---
const stateCityMap = <?php echo json_encode($state_city_map); ?>;
const stateFilter = document.getElementById('stateFilter');
const cityFilter = document.getElementById('cityFilter');
function updateCityOptions() {
    const selectedState = stateFilter.value;
    cityFilter.innerHTML = '<option value="">All</option>';
    if (selectedState && stateCityMap[selectedState]) {
        stateCityMap[selectedState].forEach(function(city) {
            const opt = document.createElement('option');
            opt.value = city;
            opt.textContent = city;
            cityFilter.appendChild(opt);
        });
    } else {
        // If no state selected, show all cities
        <?php foreach ($cities as $city): ?>
            var opt = document.createElement('option');
            opt.value = "<?= htmlspecialchars($city) ?>";
            opt.textContent = "<?= htmlspecialchars($city) ?>";
            cityFilter.appendChild(opt);
        <?php endforeach; ?>
    }
}
stateFilter.addEventListener('change', updateCityOptions);
window.addEventListener('DOMContentLoaded', function() {
    updateCityOptions();
    // If a city is pre-selected, keep it selected
    <?php if (isset($_GET['city']) && $_GET['city']): ?>
        var selectedCity = "<?= htmlspecialchars($_GET['city']) ?>";
        for (var i = 0; i < cityFilter.options.length; i++) {
            if (cityFilter.options[i].value === selectedCity) {
                cityFilter.selectedIndex = i;
                break;
            }
        }
    <?php endif; ?>
});
</script>
    <script>
    // Show/hide mobile search bar based on screen size
    function updateMobileSearchBar() {
        var mobileSearchBarForm = document.getElementById('mobileSearchBarForm');
        if (window.innerWidth <= 900) {
            mobileSearchBarForm.style.display = 'block';
        } else {
            mobileSearchBarForm.style.display = 'none';
        }
    }
    window.addEventListener('resize', updateMobileSearchBar);
    window.addEventListener('DOMContentLoaded', updateMobileSearchBar);
    </script>
    <script>
    // --- State to City dynamic filter for mobile ---
const mobileStateCityMap = <?php echo json_encode($state_city_map); ?>;
const mobileStateFilter = document.getElementById('mobileStateFilter');
const mobileCityFilter = document.getElementById('mobileCityFilter');
const mobileCityLabel = document.querySelector('label[for="mobileCityFilter"]');
function updateMobileCityOptions() {
    const selectedState = mobileStateFilter.value;
    mobileCityFilter.innerHTML = '';
    if (selectedState && mobileStateCityMap[selectedState]) {
        mobileCityLabel.style.display = 'block';
        mobileCityFilter.style.display = 'block';
        mobileCityFilter.disabled = false;
        mobileCityFilter.innerHTML = '<option value="">All</option>';
        mobileStateCityMap[selectedState].forEach(function(city) {
            const opt = document.createElement('option');
            opt.value = city;
            opt.textContent = city;
            mobileCityFilter.appendChild(opt);
        });
    } else {
        // Show but disable city dropdown if no state selected
        mobileCityLabel.style.display = 'block';
        mobileCityFilter.style.display = 'block';
        mobileCityFilter.disabled = true;
        mobileCityFilter.innerHTML = '<option value="">Select state first</option>';
    }
}
if (mobileStateFilter && mobileCityFilter && mobileCityLabel) {
    mobileStateFilter.addEventListener('change', updateMobileCityOptions);
    window.addEventListener('DOMContentLoaded', function() {
        updateMobileCityOptions();
        // If a city is pre-selected, keep it selected
        <?php if (isset($_GET['city']) && $_GET['city']): ?>
            var selectedCity = "<?= htmlspecialchars($_GET['city']) ?>";
            for (var i = 0; i < mobileCityFilter.options.length; i++) {
                if (mobileCityFilter.options[i].value === selectedCity) {
                    mobileCityFilter.selectedIndex = i;
                    break;
                }
            }
        <?php endif; ?>
    });
}
</script>
    <script src="main.js"></script>
</body>
</html> 