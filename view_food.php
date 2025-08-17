<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['is_admin'])) {
    header("Location: p2.php");
    exit;
}

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

// Database configuration
$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";

// Create connection
$conn = new mysqli('sql109.infinityfree.com', 'if0_39329540', 'Prem28831924', 'if0_39329540_login_db12');



// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$filter_state = isset($_GET['state']) ? $conn->real_escape_string($_GET['state']) : '';
$filter_city = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : '';
$where_clauses = [];
if (!empty($search)) {
    $where_clauses[] = "(food_name LIKE '%$search%' OR cuisine_type LIKE '%$search%' OR city LIKE '%$search%' OR added_by LIKE '%$search%')";
}
if (!empty($filter_state)) {
    $where_clauses[] = "state = '$filter_state'";
}
if (!empty($filter_city)) {
    $where_clauses[] = "city = '$filter_city'";
}
$where_clause = '';
if (count($where_clauses) > 0) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Get total count
$count_sql = "SELECT COUNT(*) as total FROM food_items $where_clause";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$records_per_page = 10;
// Pagination

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get food items
$sql = "SELECT *, COALESCE(added_by, 'Unknown') as added_by FROM food_items $where_clause ORDER BY created_at DESC LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

$total_pages = ceil($total_records / $records_per_page);

// Fetch all state-city pairs for JS
$state_city_map = [];
$state_city_result = $conn->query("SELECT state, city FROM food_items GROUP BY state, city ORDER BY state, city");
while ($row = $state_city_result->fetch_assoc()) {
    $state = $row['state'];
    $city = $row['city'];
    if (!isset($state_city_map[$state])) {
        $state_city_map[$state] = [];
    }
    if (!in_array($city, $state_city_map[$state])) {
        $state_city_map[$state][] = $city;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Food Items - Admin</title>
    <link rel="icon" type="image/png" href="f1-removebg-preview.png">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #fff;
            color: #222;
            margin: 0;
            padding: 20px;
        }
        .user-info-box {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto 10px auto;
            padding: 10px 24px;
            background: #e8f5e9;
            color: #388e3c;
            font-weight: bold;
            font-size: 1.1em;
            border-radius: 8px;
            box-shadow: 0 2px 8px #a5d6a733;
            max-width: 350px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #a5d6a7;
        }
        .header h1 {
            color: #388e3c;
            margin: 0;
        }
        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-box input[type="text"] {
            padding: 10px 15px;
            border: 1.5px solid #a5d6a7;
            border-radius: 8px;
            background: #f8fff8;
            color: #222;
            font-size: 1em;
            min-width: 250px;
        }
        .search-box button {
            background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
        }
        .search-box button:hover {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
        }
        .logout-btn {
            background: #e53935;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #c62828;
        }
        .add-btn {
            background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }
        .add-btn:hover {
            background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
        }
        .stats {
            background: #f8fff8;
            border: 1px solid #a5d6a7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .stats span {
            color: #388e3c;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 16px #a5d6a7aa;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #388e3c;
            color: #fff;
            font-weight: bold;
        }
        tr:hover {
            background: #f8fff8;
        }
        .food-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #a5d6a7;
        }
        .no-image {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            border: 2px solid #ddd;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #a5d6a7;
            border-radius: 4px;
            text-decoration: none;
            color: #388e3c;
        }
        .pagination a:hover {
            background: #a5d6a7;
            color: #fff;
        }
        .pagination .current {
            background: #388e3c;
            color: #fff;
        }
        .no-data {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-premium { background: #ffd700; color: #333; }
        .badge-moderate { background: #87ceeb; color: #333; }
        .badge-budget { background: #90ee90; color: #333; }
        .badge-hot { background: #ff6b6b; color: #fff; }
        .badge-medium { background: #ffa500; color: #fff; }
        .badge-mild { background: #98fb98; color: #333; }
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .edit-btn {
            background: #2196f3;
            color: #fff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .edit-btn:hover {
            background: #1976d2;
        }
        .delete-btn {
            background: #f44336;
            color: #fff;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .delete-btn:hover {
            background: #d32f2f;
        }
        .success-message {
            background: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            background: #f44336;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .added-by {
            color: #388e3c;
            font-weight: 600;
            font-size: 0.9em;
            padding: 4px 8px;
            background: #e8f5e9;
            border-radius: 4px;
            display: inline-block;
        }
        .image-container {
            position: relative;
            display: inline-block;
        }
        .image-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
            font-size: 10px;
            text-align: center;
            width: 100%;
        }
        /* Responsive table styles */
        @media (max-width: 900px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                margin-bottom: 20px;
                border: 1px solid #a5d6a7;
                border-radius: 8px;
                box-shadow: 0 2px 8px #a5d6a733;
                background: #fff;
                padding: 10px;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                min-height: 40px;
            }
            td:before {
                position: absolute;
                top: 12px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: bold;
                color: #388e3c;
            }
            td:nth-of-type(1):before { content: "Image"; }
            td:nth-of-type(2):before { content: "Food Name"; }
            td:nth-of-type(3):before { content: "Description"; }
            td:nth-of-type(4):before { content: "City"; }
            td:nth-of-type(5):before { content: "State"; }
            td:nth-of-type(6):before { content: "Cuisine"; }
            td:nth-of-type(7):before { content: "Meal Type"; }
            td:nth-of-type(8):before { content: "Dietary"; }
            td:nth-of-type(9):before { content: "Spice Level"; }
            td:nth-of-type(10):before { content: "Price Range"; }
            td:nth-of-type(11):before { content: "Popularity"; }
            td:nth-of-type(12):before { content: "Cooking Style"; }
            td:nth-of-type(13):before { content: "Added Date"; }
            td:nth-of-type(14):before { content: "Added By"; }
            td:nth-of-type(15):before { content: "Actions"; }
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background: rgba(0,0,0,0.7);
        }
        .modal-content {
            margin: 5% auto 0 auto;
            display: block;
            max-width: 90vw;
            max-height: 70vh;
            border-radius: 12px;
            box-shadow: 0 4px 32px #2228;
            animation: zoomIn 0.3s;
        }
        @keyframes zoomIn {
            from { transform: scale(0.7); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-caption {
            color: #fff;
            text-align: center;
            margin: 18px auto 0 auto;
            font-size: 1.1em;
            max-width: 90vw;
            background: rgba(0,0,0,0.4);
            padding: 10px 18px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px #2226;
        }
        /* Tooltip styles */
        .tooltip {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 220px;
            background-color: #222;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 8px 12px;
            position: absolute;
            z-index: 1000;
            bottom: 125%;
            left: 50%;
            margin-left: -110px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.95em;
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        /* Improved pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .pagination .page-info {
            padding: 8px 12px;
            color: #388e3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php if (isset($_SESSION['username'])): ?>
    <div class="user-info-box">
        👨‍💼 Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?>
    </div>
<?php endif; ?>
    <div class="header">
        <h1>Food Items Database</h1>
        <div style="display: flex; align-items: center; gap: 10px;">
            <a href="p2.php" class="add-btn">Add New Food</a>
            <a href="view_food.php" class="add-btn" style="background: #2196f3;">View Food Items</a>
            <a href="p2.php?logout=1" class="logout-btn">Logout</a>
        </div>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="success-message">Food item has been successfully deleted!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['updated'])): ?>
        <div class="success-message">Food item has been successfully updated!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="error-message">
            <?php 
            switch($_GET['error']) {
                case 'delete_failed':
                    echo 'Failed to delete the food item. Please try again.';
                    break;
                case 'not_found':
                    echo 'Food item not found.';
                    break;
                case 'invalid_id':
                    echo 'Invalid food item ID.';
                    break;
                default:
                    echo 'An error occurred.';
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="stats">
        <strong>Total Food Items: <span><?php echo $total_records; ?></span></strong>
        <?php if (!empty($search) || !empty($filter_state) || !empty($filter_city)): ?>
            | <strong>Search Results for: <span>"<?php echo htmlspecialchars($search); ?>"</span> in <span>"<?php echo htmlspecialchars($filter_state); ?>"</span> city <span>"<?php echo htmlspecialchars($filter_city); ?>"</span></strong>
        <?php endif; ?>
    </div>

    <div class="search-box">
        <form method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" placeholder="Search by food name, cuisine, city, or added by..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <select name="state" style="padding: 10px 15px; border: 1.5px solid #a5d6a7; border-radius: 8px;">
                <option value="">All States</option>
                <?php
                // Fetch unique states from the database
                $states_result = $conn->query("SELECT DISTINCT state FROM food_items ORDER BY state ASC");
                while ($state_row = $states_result->fetch_assoc()) {
                    $state_val = htmlspecialchars($state_row['state']);
                    $selected = ($filter_state === $state_row['state']) ? 'selected' : '';
                    echo "<option value=\"$state_val\" $selected>$state_val</option>";
                }
                ?>
            </select>
            <select name="city" style="padding: 10px 15px; border: 1.5px solid #a5d6a7; border-radius: 8px;">
                <option value="">All Cities</option>
            </select>
            <button type="submit">Search</button>
            <?php if (!empty($search) || !empty($filter_state) || !empty($filter_city)): ?>
                <a href="view_food.php" style="color: #388e3c; text-decoration: none;">Clear Search</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Food Name</th>
                    <th>Description</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Cuisine</th>
                    <th>Meal Type</th>
                    <th>Dietary</th>
                    <th>Spice Level</th>
                    <th>Price Range</th>
                    <th>Popularity</th>
                    <th>Cooking Style</th>
                    <th>Added Date</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php 
                            $image_url = getImageUrl($row['image_path']);
                            if (!empty($image_url)): ?>
                                <div class="image-container">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" 
                                         alt="Food Image" 
                                         class="food-image previewable-image" 
                                         style="cursor: pointer;"
                                         data-fullsrc="<?php echo htmlspecialchars($image_url); ?>"
                                         data-caption="<?php echo htmlspecialchars($row['food_name']); ?><?php if (!empty($row['description'])) echo ' - ' . htmlspecialchars(substr($row['description'], 0, 80)) . (strlen($row['description']) > 80 ? '...' : ''); ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div class="image-error" style="display:none;">No Image</div>
                                </div>
                            <?php else: ?>
                                <div class="no-image">No Image</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['food_name']); ?></strong></td>
                        <td>
                            <?php 
                            $desc = $row['description'];
                            if (!empty($desc)) {
                                $short = htmlspecialchars(substr($desc, 0, 50));
                                $full = htmlspecialchars($desc);
                                if (strlen($desc) > 50) {
                                    echo '<span class="tooltip">' . $short . '...<span class="tooltiptext">' . $full . '</span></span>';
                                } else {
                                    echo $short;
                                }
                            } else {
                                echo 'No description';
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                        <td><?php echo htmlspecialchars($row['cuisine_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['meal_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['dietary_preference']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($row['spice_level']); ?>">
                                <?php echo htmlspecialchars($row['spice_level']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo strtolower($row['price_range']); ?>">
                                <?php echo htmlspecialchars($row['price_range']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['popularity']); ?></td>
                        <td><?php echo htmlspecialchars($row['cooking_style']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <span class="added-by">
                                <?php
                                $added_by = trim($row['added_by']);
                                echo !empty($added_by) ? htmlspecialchars($added_by) : 'Unknown';
                                ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="p2.php?edit=<?php echo $row['id']; ?>&page=<?php echo $page; ?>" class="edit-btn">Edit</a>
                                <a href="delete_food.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this food item?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page-1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($filter_state) ? '&state=' . urlencode($filter_state) : ''; ?><?php echo !empty($filter_city) ? '&city=' . urlencode($filter_city) : ''; ?>">Previous</a>
                <?php endif; ?>
                <?php
                $max_links = 5; // Number of page links to show
                $start = max(1, $page - floor($max_links / 2));
                $end = min($total_pages, $start + $max_links - 1);
                if ($end - $start < $max_links - 1) {
                    $start = max(1, $end - $max_links + 1);
                }
                ?>
                <?php if ($start > 1): ?>
                    <a href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($filter_state) ? '&state=' . urlencode($filter_state) : ''; ?><?php echo !empty($filter_city) ? '&city=' . urlencode($filter_city) : ''; ?>">1</a>
                    <?php if ($start > 2): ?>
                        ...
                    <?php endif; ?>
                <?php endif; ?>
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($filter_state) ? '&state=' . urlencode($filter_state) : ''; ?><?php echo !empty($filter_city) ? '&city=' . urlencode($filter_city) : ''; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        ...
                    <?php endif; ?>
                    <a href="?page=<?php echo $total_pages; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($filter_state) ? '&state=' . urlencode($filter_state) : ''; ?><?php echo !empty($filter_city) ? '&city=' . urlencode($filter_city) : ''; ?>"><?php echo $total_pages; ?></a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page+1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($filter_state) ? '&state=' . urlencode($filter_state) : ''; ?><?php echo !empty($filter_city) ? '&city=' . urlencode($filter_city) : ''; ?>">Next</a>
                <?php endif; ?>
            </div>
            <div style="display: flex; justify-content: center; margin-top: 18px;">
                <form method="get" style="display: flex; align-items: center; gap: 10px;">
                    <?php if (!empty($search)): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <?php endif; ?>
                    <?php if (!empty($filter_state)): ?>
                        <input type="hidden" name="state" value="<?php echo htmlspecialchars($filter_state); ?>">
                    <?php endif; ?>
                    <?php if (!empty($filter_city)): ?>
                        <input type="hidden" name="city" value="<?php echo htmlspecialchars($filter_city); ?>">
                    <?php endif; ?>
                    <label for="goto-page" style="font-weight:bold; color:#388e3c; font-size:1.1em;">Go to page:</label>
                    <input id="goto-page" type="number" name="page" min="1" max="<?php echo $total_pages; ?>" value="<?php echo $page; ?>" style="width:90px; padding:10px 16px; border-radius:8px; border:1.5px solid #a5d6a7; font-size:1.1em;">
                    <button type="submit" style="padding:10px 24px; border-radius:8px; border:none; background:#388e3c; color:#fff; font-size:1.1em; font-weight:bold; cursor:pointer;">Go</button>
                </form>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="no-data">
            <h3>No food items found</h3>
            <p><?php echo !empty($search) ? 'Try adjusting your search terms.' : 'Add your first food item using the "Add New Food" button above.'; ?></p>
        </div>
    <?php endif; ?>

    <?php $conn->close(); ?>
</body>
<script>
// Modal image preview with caption and Escape key support
const modal = document.createElement('div');
modal.className = 'modal';
modal.innerHTML = `
    <span class="modal-close">&times;</span>
    <img class="modal-content" src="" alt="Food Image Preview">
    <div class="modal-caption"></div>
`;
document.body.appendChild(modal);
const modalImg = modal.querySelector('.modal-content');
const modalClose = modal.querySelector('.modal-close');
const modalCaption = modal.querySelector('.modal-caption');
modalClose.onclick = () => { modal.style.display = 'none'; };
modal.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };
document.addEventListener('keydown', function(e) {
    if (modal.style.display === 'block' && (e.key === 'Escape' || e.key === 'Esc')) {
        modal.style.display = 'none';
    }
});
document.querySelectorAll('.previewable-image').forEach(img => {
    img.addEventListener('click', function() {
        modalImg.src = this.getAttribute('data-fullsrc');
        modalCaption.textContent = this.getAttribute('data-caption') || '';
        modal.style.display = 'block';
    });
});
</script>
<script>
const stateCityMap = <?php echo json_encode($state_city_map); ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stateSelect = document.querySelector('select[name=\"state\"]');
    const citySelect = document.querySelector('select[name=\"city\"]');
    const selectedCity = "<?php echo isset($filter_city) ? htmlspecialchars($filter_city, ENT_QUOTES) : ''; ?>";

    function updateCityOptions() {
        const state = stateSelect.value;
        citySelect.innerHTML = '<option value=\"\">All Cities</option>';
        if (state && stateCityMap[state]) {
            stateCityMap[state].forEach(function(city) {
                const selected = (city === selectedCity) ? 'selected' : '';
                citySelect.innerHTML += `<option value=\"${city}\" ${selected}>${city}</option>`;
            });
        } else {
            // Show all cities if no state selected
            let allCities = new Set();
            Object.values(stateCityMap).forEach(arr => arr.forEach(city => allCities.add(city)));
            Array.from(allCities).sort().forEach(function(city) {
                const selected = (city === selectedCity) ? 'selected' : '';
                citySelect.innerHTML += `<option value=\"${city}\" ${selected}>${city}</option>`;
            });
        }
    }

    stateSelect.addEventListener('change', function() {
        // Clear city selection when state changes
        citySelect.value = '';
        updateCityOptions();
    });

    // Initial population
    updateCityOptions();
});
</script>
</html> 