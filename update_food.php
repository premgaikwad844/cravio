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
        // Check if the logged-in user is the one who added this item
        if (isset($_SESSION['username']) && $edit_data['added_by'] === $_SESSION['username']) {
            $edit_mode = true;
        } else {
            // Not allowed to edit
            $edit_mode = false;
            $edit_data = null;
            $edit_id = null;
            $edit_error = "You are not allowed to edit this item.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    
    // Validate required fields
    $required_fields = ['name', 'description', 'state', 'city', 'cuisine_type', 'meal_type', 'dietary_preference', 'spice_level', 'price_range', 'popularity', 'cooking_style'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        header("Location: p2.php?edit=$edit_id&error=missing_fields&fields=" . implode(',', $missing_fields));
        exit;
    }
    
    // Sanitize input data
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
    
    // Get current image path
    $current_image_sql = "SELECT image_path FROM food_items WHERE id = ?";
    $current_image_stmt = $conn->prepare($current_image_sql);
    $current_image_stmt->bind_param("i", $edit_id);
    $current_image_stmt->execute();
    $current_image_result = $current_image_stmt->get_result();
    $current_image_data = $current_image_result->fetch_assoc();
    $current_image_path = $current_image_data['image_path'];
    
    $image_path = $current_image_path; // Default to current image

    // Handle file upload
    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . '_' . basename($_FILES["image_upload"]["name"]);
        if (move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    } elseif (isset($_POST['image_path']) && !empty(trim($_POST['image_path']))) {
        // Accept any string (URL, relative path, filename)
        $image_path = $conn->real_escape_string(trim($_POST['image_path']));
    }
    // If neither, keep the current image

    // Update the database
    $update_sql = "UPDATE food_items SET 
                   food_name = ?, 
                   description = ?,
                   state = ?, 
                   city = ?, 
                   cuisine_type = ?, 
                   meal_type = ?, 
                   dietary_preference = ?, 
                   spice_level = ?, 
                   price_range = ?, 
                   popularity = ?, 
                   cooking_style = ?, 
                   image_path = ?
                   WHERE id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssssssssi", 
        $food_name, $description, $state, $city, $cuisine_type, $meal_type, 
        $dietary_preference, $spice_level, $price_range, $popularity, 
        $cooking_style, $image_path, $edit_id);
    
    if ($update_stmt->execute()) {
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
        header("Location: view_food.php?page=$page&updated=1");
        exit;
    } else {
        header("Location: p2.php?edit=$edit_id&error=update_failed");
        exit;
    }
} else {
    header("Location: view_food.php?error=invalid_request");
    exit;
}

// Fetch cities from database
$cities = [];
$result = $conn->query("SELECT DISTINCT city FROM food_items");
while($row = $result->fetch_assoc()) {
    $cities[] = $row['city'];
}
?>
<label for="city">City:</label>
<select name="city" id="city" required>
    <option value="">Select City</option>
    <?php foreach($cities as $city): ?>
        <option value="<?php echo htmlspecialchars($city); ?>"
            <?php if(isset($edit_data['city']) && $edit_data['city'] == $city) echo 'selected'; ?>>
            <?php echo htmlspecialchars($city); ?>a
        </option>
    <?php endforeach; ?>
</select>

<?php if (isset($edit_error)): ?>
    <div class="error" style="color:#e53935;text-align:center;margin-bottom:20px;">
        <?php echo htmlspecialchars($edit_error); ?>
    </div>
<?php endif; ?>

<?php if (isset($edit_data['created_at'])): ?>
    <p>Created at: <?php echo htmlspecialchars($edit_data['created_at']); ?></p>
<?php endif; ?>
<?php if (isset($edit_data['updated_at'])): ?>
    <p>Last updated: <?php echo htmlspecialchars($edit_data['updated_at']); ?></p>
<?php endif; ?>

$conn->close();
?> 