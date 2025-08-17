<?php include 'header.php'; ?>
<?php
// Function to get proper image URL for local development
function getImageUrl($image_path) {
    if (empty($image_path)) {
        return '';
    }
    
    // If it's already a full URL, return as is
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    
    // For local file paths, make them relative to the current directory
    if (strpos($image_path, 'uploads/') === 0) {
        return $image_path; // Already relative
    }
    
    // If it's a relative path, ensure it starts with uploads/
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

$sql = "SELECT id, food_name, cuisine_type, price_range, image_path, city, cooking_style, dietary_preference, state, meal_type, spice_level, popularity FROM food_items";
$result = $conn->query($sql);

$food_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $food_items[] = $row;
    }
}

// Fetch unique states
$states_sql = "SELECT DISTINCT state FROM food_items WHERE state IS NOT NULL AND state != '' ORDER BY state";
$states_result = $conn->query($states_sql);
$states = [];
if ($states_result && $states_result->num_rows > 0) {
    while($row = $states_result->fetch_assoc()) {
        $states[] = $row['state'];
    }
}

// Fetch unique cities
$cities_sql = "SELECT DISTINCT city FROM food_items WHERE city IS NOT NULL AND city != '' ORDER BY city";
$cities_result = $conn->query($cities_sql);
$cities = [];
if ($cities_result && $cities_result->num_rows > 0) {
    while($row = $cities_result->fetch_assoc()) {
        $cities[] = $row['city'];
    }
}

// Create states and cities mapping
$states_and_cities = [];
foreach ($states as $state) {
    $state_cities_sql = "SELECT DISTINCT city FROM food_items WHERE state = ? AND city IS NOT NULL AND city != '' ORDER BY city";
    $stmt = $conn->prepare($state_cities_sql);
    $stmt->bind_param("s", $state);
    $stmt->execute();
    $state_cities_result = $stmt->get_result();
    $state_cities = [];
    while($row = $state_cities_result->fetch_assoc()) {
        $state_cities[] = $row['city'];
    }
    $states_and_cities[$state] = $state_cities;
}

// Pagination setup
$items_per_page = 20;
$total_items = count($food_items);
$total_pages = $total_items > 0 ? ceil($total_items / $items_per_page) : 1;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$page = min($page, $total_pages);
$start_index = ($page - 1) * $items_per_page;
$food_items_page = array_slice($food_items, $start_index, $items_per_page);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
  <title>Discover Dishes</title>
  <link rel="icon" type="image/png" href="logo1.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f7fafc;
      margin: 0;
      color: #222;
      /* Ensure proper mobile behavior */
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      text-size-adjust: 100%;
      /* Prevent horizontal scroll */
      overflow-x: hidden;
    }
    .discover-container {
      display: flex;
      min-height: 100vh;
      height: 100vh;
      overflow: hidden;
      flex-direction: row;
      /* Ensure proper flex behavior */
      box-sizing: border-box;
    }
    .sidebar {
      width: 270px;
      background: #f8fff8;
      border-right: 1.5px solid #a5d6a7;
      border-radius: 0 18px 18px 0;
      box-shadow: 2px 0 12px 0 rgba(67,160,71,0.04);
      padding-top: 30px;
      padding-bottom: 30px;
      display: flex;
      flex-direction: column;
      position: relative;
      z-index: 1001;
      height: 100vh;
      min-height: 0;
      left: 0;
      top: 0;
      transition: none;
      transform: none !important;
      padding-left: 22px;
      padding-right: 22px;
      /* Ensure proper display */
      flex-shrink: 0;
    }
    .sidebar h2 {
      font-size: 1.3rem;
      margin-bottom: 1.2rem;
      color: #4CAF50;
      font-weight: 600;
      position: sticky;
      top: 0;
      background: #fff;
      z-index: 2;
      padding-top: 32px;
      padding-bottom: 0.5rem;
    }
    .sidebar .search-bar {
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      padding: 12px 22px;
      border: 1px solid #cbd5e1;
      border-radius: 999px;
      margin-bottom: 2.2rem;
      font-size: 1rem;
      display: block;
      background: #fff;
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
      transition: border 0.2s, box-shadow 0.2s;
      position: sticky;
      top: 3.2rem;
      z-index: 2;
    }
    .search-bar:focus {
      border: 1.5px solid #4CAF50;
      outline: none;
      box-shadow: 0 4px 16px 0 rgba(76,175,80,0.08);
    }
    .sidebar .filters-scroll {
      flex: 1 1 auto;
      overflow-y: auto;
      min-height: 0;
      padding-bottom: 1.5rem;
      scrollbar-width: none; /* Firefox */
      -ms-overflow-style: none; /* IE 10+ */
    }
    .sidebar .filters-scroll::-webkit-scrollbar {
      display: none;
    }
    .filter-group {
      margin-bottom: 18px;
    }
    .filter-group label {
      font-size: 1rem;
      font-weight: 700;
      color: #FF0000 !important;
      margin-bottom: 0.3rem;
      display: block;
    }
    .filter-group label[for="cuisineFilter"] {
      color: #388e3c;
    }
    .filter-group label[for="popularityFilter"] {
      color: #e53935;
    }
    .filter-group label[for="dietaryFilter"] {
      color: #1976d2;
    }
    .filter-group select, .filter-group input[type=checkbox] {
      width: 100%;
      padding: 7px 8px;
      border-radius: 5px;
      border: 1px solid #cbd5e1;
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }
    .main-content {
      flex: 1;
      padding: 40px 5vw 40px 5vw;
      box-sizing: border-box;
      height: 100vh;
      overflow-y: auto;
      margin-left: 0;
    }
    .dishes-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 32px;
    }
    .dish-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
      padding: 20px 18px 16px 18px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: box-shadow 0.2s;
    }
    .dish-card:hover {
      box-shadow: 0 8px 32px 0 rgba(24,28,38,0.13), 0 2px 12px 0 rgba(0,0,0,0.10);
    }
    .dish-img {
      width: 100%;
      max-width: 220px;
      height: auto;
      aspect-ratio: 1/1;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 1rem;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .dish-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.3rem;
      color: #222;
      text-align: center;
    }
    .dish-hotel {
      font-size: 0.98rem;
      color: #4CAF50;
      margin-bottom: 0.2rem;
      text-align: center;
    }
    .dish-tags {
      font-size: 0.92rem;
      color: #888;
      margin-bottom: 0.2rem;
      text-align: center;
    }
    .dish-type-price {
      font-size: 0.98rem;
      color: #444;
      margin-top: 0.2rem;
      text-align: center;
    }
    .dish-type {
      font-weight: 600;
      color: #388e3c;
      text-transform: uppercase;
    }
    .dish-price {
      font-weight: 500;
      color: #b4880b;
      text-transform: capitalize;
    }
    @media (max-width: 900px) {
      .discover-container {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
      }
      .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1.5px solid #e2e8f0;
        position: relative;
        z-index: 10;
        height: 60vh;
        max-height: 60vh;
        left: unset;
        top: unset;
        padding-left: 18px;
        padding-right: 18px;
      }
      .main-content {
        padding: 24px 4vw 24px 4vw;
        height: calc(100vh - 60vh);
        max-height: calc(100vh - 60vh);
        margin-left: 0;
      }
      .dish-img {
        max-width: 180px;
        margin-bottom: 0.7rem;
      }
    }
    @media (max-width: 600px) {
      .discover-container {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
        /* Ensure proper mobile layout */
        position: relative;
      }
      .sidebar {
        width: 100% !important;
        border-right: none;
        border-bottom: 1.5px solid #e2e8f0;
        position: fixed !important;
        top: 56px !important;
        left: 0 !important;
        right: 0 !important;
        width: 100vw !important;
        height: calc(100vh - 56px) !important;
        max-height: calc(100vh - 56px) !important;
        overflow-y: auto !important;
        display: none !important;
        z-index: 1000 !important;
        padding: 20px !important;
        background: #fff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        transform: translateY(0) !important;
        /* Ensure proper viewport handling */
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain !important;
        /* Force display when needed */
        flex-shrink: 0 !important;
        box-sizing: border-box !important;
      }
      .sidebar.open {
        display: block !important;
        display: flex !important;
        flex-direction: column !important;
      }
      .sidebar h2 {
        margin: 0 0 20px 0 !important;
        text-align: center !important;
        font-size: 1.2rem !important;
        padding: 10px 0 !important;
        border-bottom: 1px solid #e2e8f0 !important;
        /* Prevent sticky positioning issues */
        position: static !important;
        /* Ensure visibility */
        display: block !important;
        visibility: visible !important;
      }
      .sidebar .filters-scroll {
        max-height: none !important;
        padding-bottom: 20px !important;
        overflow-y: visible !important;
        margin-top: 10px !important;
        /* Ensure proper scrolling */
        -webkit-overflow-scrolling: touch !important;
        /* Ensure visibility */
        display: block !important;
        visibility: visible !important;
        flex: 1 !important;
      }
      .sidebar .search-bar {
        margin: 0 0 25px 0 !important;
        width: 100% !important;
        padding: 18px 20px !important;
        font-size: 1.1rem !important;
        background: #fff !important;
        border: 2px solid #4CAF50 !important;
        border-radius: 15px !important;
        box-shadow: 0 4px 12px rgba(76,175,80,0.1) !important;
        box-sizing: border-box !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        /* Ensure proper input behavior */
        -webkit-tap-highlight-color: transparent !important;
        -webkit-touch-callout: none !important;
        -webkit-user-select: text !important;
        user-select: text !important;
        /* Ensure visibility and focus */
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        /* Prevent zoom on focus */
        font-size: 16px !important;
      }
      .search-bar:focus {
        outline: none !important;
        border: 2px solid #4CAF50 !important;
        box-shadow: 0 6px 20px rgba(76,175,80,0.2) !important;
        background: #fff !important;
        /* Prevent zoom on focus */
        font-size: 16px !important;
        /* Ensure focus is visible */
        opacity: 1 !important;
        visibility: visible !important;
      }
      .search-bar::placeholder {
        color: #999 !important;
        font-size: 16px !important;
      }
      .filter-group {
        margin-bottom: 20px !important;
        /* Ensure visibility */
        display: block !important;
        visibility: visible !important;
      }
      .filter-group label {
        font-size: 1rem !important;
        margin-bottom: 8px !important;
        display: block !important;
        font-weight: 600 !important;
        /* Ensure visibility */
        visibility: visible !important;
      }
      .form-select {
        font-size: 1rem !important;
        padding: 12px 15px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        background: #fff !important;
        width: 100% !important;
        box-sizing: border-box !important;
        /* Ensure visibility */
        display: block !important;
        visibility: visible !important;
      }
      .form-select:focus {
        outline: none !important;
        border-color: #4CAF50 !important;
        box-shadow: 0 0 0 3px rgba(76,175,80,0.1) !important;
      }
      .sidebar .close-btn {
        display: flex !important;
        position: fixed !important;
        top: 70px !important;
        right: 20px !important;
        background: #fff !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 1.2rem !important;
        color: #666 !important;
        cursor: pointer !important;
        z-index: 1004 !important;
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        /* Ensure visibility */
        visibility: visible !important;
        opacity: 1 !important;
      }
      .main-content {
        padding: 20px 15px !important;
        margin-top: 56px !important;
        height: calc(100vh - 56px) !important;
        max-height: calc(100vh - 56px) !important;
        margin-left: 0 !important;
        overflow-y: auto !important;
        /* Ensure proper layout */
        flex: 1 !important;
        box-sizing: border-box !important;
      }
      h1 {
        font-size: 1.3rem !important;
        margin-bottom: 1rem !important;
      }
      .dishes-list {
        gap: 15px !important;
        /* Ensure visibility */
        display: grid !important;
        visibility: visible !important;
      }
      .dish-card {
        padding: 15px !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        /* Ensure visibility */
        display: flex !important;
        flex-direction: column !important;
        visibility: visible !important;
      }
      .dish-img {
        width: 100% !important;
        max-width: 200px !important;
        height: auto !important;
        aspect-ratio: 1/1 !important;
        margin-bottom: 10px !important;
        display: block !important;
        margin-left: auto !important;
        margin-right: auto !important;
        border-radius: 8px !important;
      }
      .dish-title {
        font-size: 1.1rem !important;
        margin-bottom: 8px !important;
      }
      .dish-hotel, .dish-type-price, .dish-tags {
        font-size: 0.9rem !important;
      }
      .dish-type-price {
        margin-top: 5px !important;
      }
      .show-sidebar-btn {
        padding: 10px 20px !important;
        font-size: 1rem !important;
        border-radius: 8px !important;
        background: #4CAF50 !important;
        color: white !important;
        border: none !important;
        font-weight: 600 !important;
        /* Ensure visibility */
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
      }
    }
    @media (max-width: 400px) {
      .dish-card {
        padding: 10px 4px 10px 4px;
      }
      .dish-img {
        width: 90px;
        height: 90px;
      }
    }
    .show-sidebar-btn {
      display: none;
    }
    @media (max-width: 900px) {
      .show-sidebar-btn {
        display: flex;
        background: #111 !important;
        color: #fff !important;
        border: 2px solid #111 !important;
        border-radius: 8px !important;
        padding: 12px 28px 12px 22px;
      }
      .show-sidebar-btn:hover, .show-sidebar-btn:focus {
        background: #333 !important;
        color: #fff !important;
        border: 2px solid #333 !important;
      }
    }
    .sidebar .close-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      top: 14px;
      right: 18px;
      background: #f2f2f2;
      border: none;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      font-size: 1.6rem;
      color: #666;
      cursor: pointer;
      z-index: 20;
      box-shadow: 0 2px 8px 0 rgba(0,0,0,0.07);
      transition: background 0.18s, color 0.18s;
    }
    .sidebar .close-btn:hover, .sidebar .close-btn:focus {
      background: #e0e0e0;
      color: #222;
    }
    .sidebar .close-btn svg {
      width: 1.3em;
      height: 1.3em;
      display: block;
      fill: currentColor;
    }
    .navbar {
      width: 100%;
      background: #A2FF00;
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
      padding: 0.7rem 0;
      position: sticky;
      top: 0;
      z-index: 1002;
    }
    .navbar-content {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2vw;
    }
    .navbar-logo {
      font-family: 'Poppins', sans-serif;
      font-size: 1.5rem;
      font-weight: 700;
      color: #222;
      letter-spacing: 1px;
      text-decoration: none;
      transition: color 0.2s;
    }
    .navbar-logo:hover {
      color: #222;
    }
    .navbar-link {
      font-size: 1.08rem;
      color: #222;
      text-decoration: none;
      font-weight: 500;
      margin-left: 1.5rem;
      padding: 7px 18px;
      border-radius: 999px;
      background: rgba(255,255,255,0.18);
      transition: background 0.2s, color 0.2s;
    }
    .navbar-link:hover {
      background: #fff;
      color: #A2FF00;
    }
    .show-sidebar-btn {
      display: none;
    }
    @media (max-width: 900px) {
      .show-sidebar-btn {
        display: flex;
      }
    }
    @media (max-width: 600px) {
      .show-sidebar-btn {
        display: flex;
      }
    }
    .discover-heading {
      text-align: center;
      margin-top: 1.2rem;
      margin-bottom: 2.2rem;
    }
    .discover-heading h1 {
      font-size: 2.5rem;
      font-weight: 800;
      color: #222;
      margin-bottom: 0.5rem;
      letter-spacing: 1px;
    }
    .discover-quote {
      font-size: 1.13rem;
      color: #388e3c;
      font-weight: 600;
      margin-bottom: 0.3rem;
      font-style: italic;
      letter-spacing: 0.3px;
    }
    .discover-subtext {
      font-size: 1.08rem;
      color: #555;
      font-weight: 500;
      letter-spacing: 0.3px;
    }
    @media (max-width: 600px) {
      .discover-heading h1 {
        font-size: 1.45rem;
      }
      .discover-quote {
        font-size: 0.98rem;
      }
      .discover-subtext {
        font-size: 0.92rem;
      }
    }
    .filters-btn-row {
      display: none;
    }
    @media (max-width: 900px) {
      .filters-btn-row {
        display: flex;
        justify-content: center;
        margin-bottom: 1.1rem;
      }
    }
    @media (max-width: 600px) {
      .filters-btn-row {
        margin-bottom: 0.7rem;
      }
    }
    @media (max-width: 900px) {
      .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
      }
      .main-content {
        margin-top: 56px;
      }
    }
    .sidebar-heading {
      color: #333333 !important;
      font-size: 1.18rem;
      font-weight: 800;
      margin: 1.2rem 0 1.1rem 0;
      text-align: left;
      letter-spacing: 0.5px;
      text-shadow: 0 1px 0 #fff2f2;
    }
    @media (max-width: 600px) {
      .sidebar-heading {
        text-align: center;
        font-size: 1.05rem;
      }
    }
    .dish-info-row {
      display: flex;
      justify-content: center;
      gap: 1.1em;
      margin-top: 0.5em;
    }
    .dish-type-label {
      font-weight: 700;
      font-size: 1.01em;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .dish-type-label.veg {
      color: #43a047;
    }
    .dish-type-label.nonveg {
      color: #e53935;
    }
    .dish-type-label.vegan {
      color: #1976d2;
    }
    .dish-price-label {
      font-weight: 700;
      font-size: 1.01em;
      text-transform: capitalize;
      letter-spacing: 0.5px;
    }
    .dish-price-label.premium {
      color: #b4880b;
    }
    .dish-price-label.moderate {
      color: #388e3c;
    }
    .dish-price-label.budget {
      color: #616161;
    }
    .form-select option {
      background-color: #e3f0ff !important;
      color: #222;
    }
    
    /* Better Mobile Sidebar Layout */
    #filterSearchBtn {
      width: 100%;
      padding: 14px 0;
      margin-top: 18px;
      background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
      color: #fff;
      font-size: 1.13em;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
      outline: none;
    }
    #filterSearchBtn:hover, #filterSearchBtn:focus {
      background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
      box-shadow: 0 4px 16px rgba(67,160,71,0.15);
      transform: translateY(-2px) scale(1.02);
    }
    #pagination {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin: 20px 0;
      flex-wrap: wrap;
    }
    #pagination a,
    #pagination span {
      display: inline-block;
      min-width: 36px;
      padding: 8px 14px;
      font-size: 1.08em;
      border-radius: 6px;
      text-align: center;
      text-decoration: none;
      transition: background 0.18s, color 0.18s, box-shadow 0.18s;
      margin: 0 2px;
      font-weight: 500;
      box-shadow: 0 1px 4px rgba(67,160,71,0.06);
    }
    #pagination a {
      background: #f8fff8;
      color: #388e3c;
      border: 1.5px solid #a5d6a7;
      cursor: pointer;
    }
    #pagination a:hover,
    #pagination a:focus {
      background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
      color: #fff;
      border-color: #43a047;
      box-shadow: 0 2px 8px rgba(67,160,71,0.13);
      outline: none;
    }
    #pagination .current {
      background: #43a047;
      color: #fff;
      border: 1.5px solid #388e3c;
      font-weight: 700;
      cursor: default;
      box-shadow: 0 2px 8px rgba(67,160,71,0.13);
    }
    #pagination span {
      background: transparent;
      color: #888;
      border: none;
      cursor: default;
      box-shadow: none;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="navbar-content">
      <a href="MainProject.html" class="navbar-logo">
        <img src="1bcff800-22be-4fe8-9889-d29a9dc91456.jpg" alt="Cravio Logo" style="height: 40px; width: auto; margin-right: 10px; vertical-align: middle;" />
        Cravio
      </a>
      <a href="MainProject.html" class="navbar-link">Home</a>
    </div>
  </nav>
  <div class="discover-container">
    <aside class="sidebar" id="sidebar">
      <button class="close-btn" id="closeSidebarBtn" style="display:none;">
        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.225 6.225a1 1 0 011.414 0L10 8.586l2.36-2.36a1 1 0 111.415 1.415L11.415 10l2.36 2.36a1 1 0 01-1.415 1.415L10 11.415l-2.36 2.36a1 1 0 01-1.415-1.415L8.586 10l-2.36-2.36a1 1 0 010-1.415z" fill="currentColor"/>
        </svg>
      </button>
      <h2 class="sidebar-heading">Refine Your Food Discovery</h2>
      <input type="text" id="searchInput" class="search-bar" placeholder="Search dishes..." />
      <div class="filters-scroll">
        <div class="filter-group">
          <label for="stateFilter">State</label>
          <select id="stateFilter" class="form-select">
            <option value="">All States</option>
            <?php foreach ($states as $state): ?>
              <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label for="cityFilter">City</label>
          <select id="cityFilter" class="form-select">
            <option value="">All Cities</option>
            <?php foreach ($cities as $city): ?>
              <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label for="cuisineFilter">Cuisine Type</label>
          <select id="cuisineFilter" class="form-select">
            <option value="">All Cuisines</option>
            <option value="Indian">Indian</option>
            <option value="Chinese">Chinese</option>
            <option value="Desserts">Desserts</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="mealTypeFilter">Meal Type</label>
          <select id="mealTypeFilter" class="form-select">
            <option value="">All Meal Types</option>
            <option value="Breakfast">Breakfast</option>
            <option value="Lunch">Lunch</option>
            <option value="Dinner">Dinner</option>
            <option value="Snacks">Snacks</option>
            <option value="Desserts">Desserts</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="dietaryFilter">Dietary Preferences</label>
          <select id="dietaryFilter" class="form-select">
            <option value="">All Dietary Options</option>
            <option value="Vegetarian">Vegetarian</option>
            <option value="Vegan">Vegan</option>
            <option value="Non-Vegetarian">Non-Vegetarian</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="spiceLevelFilter">Spice Level</label>
          <select id="spiceLevelFilter" class="form-select">
            <option value="">All Spice Levels</option>
            <option value="Mild">Mild</option>
            <option value="Medium">Medium</option>
            <option value="Hot">Hot</option>
            <option value="Very Hot">Very Hot</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="priceRangeFilter">Price Range</label>
          <select id="priceRangeFilter" class="form-select">
            <option value="">All Price Ranges</option>
            <option value="Budget">Budget</option>
            <option value="Moderate">Moderate</option>
            <option value="Premium">Premium</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="popularityFilter">Popularity</label>
          <select id="popularityFilter" class="form-select">
            <option value="">All Popularity</option>
            <option value="Most Popular">Most Popular</option>
            <option value="Highly Rated">Highly Rated</option>
          </select>
        </div>
        <div class="filter-group">
          <label for="cookingStyleFilter">Cooking Style</label>
          <select id="cookingStyleFilter" class="form-select">
            <option value="">All Cooking Styles</option>
            <option value="Grilled">Grilled</option>
            <option value="Fried">Fried</option>
            <option value="Baked">Baked</option>
            <option value="Steamed">Steamed</option>
            <option value="Raw">Raw</option>
          </select>
        </div>
        <button id="filterSearchBtn">Search</button>
        <button id="closeFilterBtn" style="background:#e53935;">Close</button>
      </div>
    </aside>
    <main class="main-content">
      <div class="discover-heading">
        <h1>Discover Dishes</h1>
        <div class="discover-subtext">Browse, filter, and explore the best dishes tailored to your taste and mood.</div>
      </div>
      <div class="filters-btn-row">
        <button class="show-sidebar-btn" id="showSidebarBtn">
          Filters
        </button>
      </div>
      <div class="dishes-list" id="dishesList"></div>
    </main>
  </div>
  <script>
    // Sidebar toggle for mobile
    function handleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const showBtn = document.getElementById('showSidebarBtn');
      const closeBtn = document.getElementById('closeSidebarBtn');
      const mainContent = document.querySelector('.main-content');
      const searchInput = document.getElementById('searchInput');
      
      function checkMobile() {
        if (window.innerWidth <= 600) {
          showBtn.style.display = 'block';
          showBtn.style.display = 'flex';
          closeBtn.style.display = 'flex';
          sidebar.style.display = 'none';
          sidebar.classList.remove('open');
          mainContent.style.filter = '';
        } else {
          showBtn.style.display = 'none';
          closeBtn.style.display = 'none';
          sidebar.style.display = '';
          sidebar.classList.remove('open');
          mainContent.style.filter = '';
        }
      }
      
      // Show sidebar button - improved for cross-browser compatibility
      showBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Force sidebar to be visible
        sidebar.style.display = 'block';
        sidebar.style.display = 'flex';
        sidebar.style.flexDirection = 'column';
        sidebar.classList.add('open');
        mainContent.style.filter = 'blur(2px)';
        
        // Ensure search input is visible and focusable
        searchInput.style.display = 'block';
        searchInput.style.visibility = 'visible';
        searchInput.style.opacity = '1';
        
        // Focus on search input after a delay to ensure sidebar is rendered
        setTimeout(() => {
          searchInput.focus();
          searchInput.click(); // Additional click to ensure focus on some browsers
        }, 100);
      });
      
      // Close sidebar button - improved
      closeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        sidebar.style.display = 'none';
        sidebar.classList.remove('open');
        mainContent.style.filter = '';
        searchInput.blur();
      });
      
      // Enhanced search input handling for better keyboard support
      searchInput.addEventListener('input', function(e) {
        renderDishes();
      });
      
      // Multiple event listeners for better cross-browser compatibility
      searchInput.addEventListener('focus', function(e) {
        // Ensure sidebar is open on mobile when search is focused
        if (window.innerWidth <= 600) {
          if (!sidebar.classList.contains('open')) {
            sidebar.style.display = 'block';
            sidebar.style.display = 'flex';
            sidebar.style.flexDirection = 'column';
            sidebar.classList.add('open');
            mainContent.style.filter = 'blur(2px)';
          }
        }
      });
      
      // Additional touch events for better mobile support
      searchInput.addEventListener('touchstart', function(e) {
        // Ensure the input is properly focused on touch
        setTimeout(() => {
          this.focus();
        }, 50);
      }, { passive: true });
      
      searchInput.addEventListener('touchend', function(e) {
        // Prevent any default behavior that might interfere
        e.stopPropagation();
      }, { passive: false });
      
      // Close sidebar when clicking outside
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 600 && 
            !sidebar.contains(e.target) && 
            !showBtn.contains(e.target) &&
            sidebar.classList.contains('open')) {
          sidebar.style.display = 'none';
          sidebar.classList.remove('open');
          mainContent.style.filter = '';
        }
      });
      
      // Handle window resize
      window.addEventListener('resize', checkMobile);
      
      // Handle orientation change
      window.addEventListener('orientationchange', function() {
        setTimeout(checkMobile, 100);
      });
      
      // Initialize
      checkMobile();
    }
    handleSidebar();

    // Output PHP data to JavaScript
    const foodItems = <?php echo json_encode($food_items); ?>;
    const itemsPerPage = 20;
    let currentPage = 1;
    
    // JavaScript function to get proper image URL (same logic as PHP)
    function getImageUrl(imagePath) {
        if (!imagePath || imagePath.trim() === '') {
            return '';
        }
        
        // If it's already a full URL, return as is
        try {
            new URL(imagePath);
            return imagePath; // Valid URL
        } catch (e) {
            // Not a valid URL, treat as local path
        }
        
        // For local file paths, make them relative to the current directory
        if (imagePath.startsWith('uploads/')) {
            return imagePath; // Already relative
        }
        
        // If it's a relative path, ensure it starts with uploads/
        if (!imagePath.includes('uploads/')) {
            return 'uploads/' + imagePath;
        }
        
        return imagePath;
    }
    
    // Debug: Log the data to console
    console.log('Food Items from Database:', foodItems);

    // Convert database data to match the expected format
    const dishes = foodItems.map(item => ({
      name: item.food_name || '',
      cuisine: item.cuisine_type || '',
      mealType: item.meal_type || 'Lunch', // Use actual database field
      dietary: item.dietary_preference || '',
      spiceLevel: item.spice_level || 'Medium', // Use actual database field
      priceRange: item.price_range || '',
      popularity: item.popularity || 'Most Popular', // Use actual database field
      cookingStyle: item.cooking_style || '',
      healthy: true, // Default value since not in database
      img: item.image_path ? getImageUrl(item.image_path) : 'https://images.pexels.com/photos/461382/pexels-photo-461382.jpeg?auto=compress&w=120&q=80',
      tags: [], // Default value since not in database
      foodType: item.dietary_preference === 'Vegetarian' ? 'Veg' : item.dietary_preference === 'Non-Vegetarian' ? 'Non-Veg' : 'Vegan',
      city: item.city || '',
      state: item.state || '' // Use actual database field
    }));
    
    // Debug: Log the converted dishes
    console.log('Converted Dishes:', dishes);

    // States and cities data
    const statesAndCities = <?php echo json_encode($states_and_cities); ?>;

    // Function to update city dropdown based on selected state
    function updateCityDropdown() {
      const stateSelect = document.getElementById('stateFilter');
      const citySelect = document.getElementById('cityFilter');
      const selectedState = stateSelect.value;
      
      // Clear current city options
      citySelect.innerHTML = '<option value="">All Cities</option>';
      
      // Add cities for selected state
      if (selectedState && statesAndCities[selectedState]) {
        statesAndCities[selectedState].forEach(city => {
          const option = document.createElement('option');
          option.value = city;
          option.textContent = city;
          citySelect.appendChild(option);
        });
      }
    }

    function renderDishes() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const cuisine = document.getElementById('cuisineFilter').value;
      const mealType = document.getElementById('mealTypeFilter').value;
      const dietary = document.getElementById('dietaryFilter').value;
      const spiceLevel = document.getElementById('spiceLevelFilter').value;
      const priceRange = document.getElementById('priceRangeFilter').value;
      const popularity = document.getElementById('popularityFilter').value;
      const cookingStyle = document.getElementById('cookingStyleFilter').value;
      const state = document.getElementById('stateFilter').value;
      const city = document.getElementById('cityFilter').value;
      
      let filtered = dishes.filter(dish => {
        return (
          (!search || dish.name.toLowerCase().includes(search)) &&
          (!cuisine || dish.cuisine.toLowerCase() === cuisine.toLowerCase()) &&
          (!mealType || dish.mealType === mealType) &&
          (!dietary || dish.dietary.toLowerCase() === dietary.toLowerCase()) &&
          (!spiceLevel || dish.spiceLevel === spiceLevel) &&
          (!priceRange || dish.priceRange.toLowerCase() === priceRange.toLowerCase()) &&
          (!popularity || dish.popularity === popularity) &&
          (!cookingStyle || dish.cookingStyle.toLowerCase() === cookingStyle.toLowerCase()) &&
          (!state || dish.state === state) &&
          (!city || dish.city.toLowerCase() === city.toLowerCase())
        );
      });
      
      filtered = filtered.sort((a, b) => a.name.localeCompare(b.name));

      // Show only 20 items if no search/filter, else show all
      let toShow = filtered;
      const hasActiveFilters = search || cuisine || mealType || dietary || spiceLevel || priceRange || popularity || cookingStyle || state || city;
      if (!hasActiveFilters) {
        toShow = filtered.slice(0, 20);
      }

      const list = document.getElementById('dishesList');
      list.innerHTML = toShow.length ? toShow.map(dish => `
        <div class="dish-card">
          <img class="dish-img" src="${dish.img}" alt="${dish.name}" />
          <div class="dish-title">${dish.name}</div>
          <div class="dish-info-row">
            <span class="dish-type-label ${dish.foodType === 'Veg' ? 'veg' : dish.foodType === 'Non-Veg' ? 'nonveg' : 'vegan'}">${dish.foodType}</span>
            <span class="dish-price-label ${dish.priceRange === 'Premium' ? 'premium' : dish.priceRange === 'Moderate' ? 'moderate' : 'budget'}">${dish.priceRange}</span>
          </div>
        </div>
      `).join('') : `<div style="grid-column:1/-1;text-align:center;color:#888;font-size:1.1rem;padding:40px 20px;">No dishes found</div>`;
    }
    
    // Reset to page 1 on filter/search
    document.getElementById('searchInput').addEventListener('input', function() { currentPage = 1; renderDishes(); });
    document.getElementById('cuisineFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('mealTypeFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('dietaryFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('spiceLevelFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('priceRangeFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('popularityFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('cookingStyleFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    document.getElementById('stateFilter').addEventListener('change', function() { updateCityDropdown(); currentPage = 1; renderDishes(); });
    document.getElementById('cityFilter').addEventListener('change', function() { currentPage = 1; renderDishes(); });
    // Only trigger filter on button click
    document.getElementById('filterSearchBtn').addEventListener('click', renderDishes);
    // Also update city dropdown on state change
    document.getElementById('stateFilter').addEventListener('change', function() { updateCityDropdown(); });
    // Close filter sidebar on mobile when close button is clicked
    document.getElementById('closeFilterBtn').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.querySelector('.main-content');
      if (window.innerWidth <= 600) {
        sidebar.style.display = 'none';
        sidebar.classList.remove('open');
        mainContent.style.filter = '';
      }
    });
    window.onload = renderDishes;
  </script>
  
</body>
</html> 