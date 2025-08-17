<?php
// Function to get proper image URL for local development
function getImageUrl($image_path) {
    if (empty($image_path)) {
        return 'https://via.placeholder.com/400x300/667eea/ffffff?text=No+Image';
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
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, food_name, cuisine_type, price_range, image_path, city, cooking_style, dietary_preference FROM food_items";
$result = $conn->query($sql);

$food_items = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Process image path for each item
        $row['image_path'] = getImageUrl($row['image_path']);
        $food_items[] = $row;
    }
}
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            position: relative;
        }

        .search-box {
            width: 100%;
            max-width: 500px;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            outline: none;
        }

        .search-box:focus {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
            background: rgba(255, 255, 255, 1);
        }

        .search-box::placeholder {
            color: #999;
        }

        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .food-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            backdrop-filter: blur(10px);
        }

        .food-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }

        .card-image {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .food-card:hover .card-image img {
            transform: scale(1.1);
        }

        .card-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .card-content {
            padding: 25px;
        }

        .food-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .food-description {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .food-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .location {
            display: flex;
            align-items: center;
            color: #3498db;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .location i {
            margin-right: 5px;
        }

        .price-tag {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .dietary-info {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
            display: inline-block;
        }

        .view-btn {
            width: 100%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .view-btn:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .no-results {
            text-align: center;
            color: white;
            font-size: 1.2rem;
            margin-top: 50px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }
            
            .food-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
            }
            
            .card-content {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .food-grid {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                padding: 12px 16px;
                font-size: 1rem;
            }
        }

        /* Loading Animation */
        .loading {
            text-align: center;
            color: white;
            font-size: 1.2rem;
            margin: 50px 0;
        }

        /* Smooth animations */
        .food-card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .food-card:nth-child(1) { animation-delay: 0.1s; }
        .food-card:nth-child(2) { animation-delay: 0.2s; }
        .food-card:nth-child(3) { animation-delay: 0.3s; }
        .food-card:nth-child(4) { animation-delay: 0.4s; }
        .food-card:nth-child(5) { animation-delay: 0.5s; }
        .food-card:nth-child(6) { animation-delay: 0.6s; }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-utensils"></i> Delicious Food Collection</h1>
        <p>Discover amazing dishes from around the world</p>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search for your favorite food...">
    </div>

    <div class="food-grid" id="foodGrid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imageUrl = getImageUrl($row["image_path"]);
                ?>
                <div class="food-card" data-name="<?php echo htmlspecialchars($row["food_name"]); ?>">
                    <div class="card-image">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($row["food_name"]); ?>" onerror="this.src='https://via.placeholder.com/400x300/667eea/ffffff?text=<?php echo urlencode($row["food_name"]); ?>';">
                        <div class="card-badge"><?php echo htmlspecialchars($row["cuisine_type"]); ?></div>
                    </div>
                    <div class="card-content">
                        <h3 class="food-title"><?php echo htmlspecialchars($row["food_name"]); ?></h3>
                        <div class="food-meta">
                            <span class="location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($row["city"]); ?>
                            </span>
                            <span class="price-tag"><?php echo htmlspecialchars($row["price_range"]); ?></span>
                        </div>
                        <div class="dietary-info">
                            <i class="fas fa-leaf"></i> <?php echo htmlspecialchars($row["dietary_preference"]); ?>
                        </div>
                        <a href="dish-details.html?dish=<?php echo urlencode($row["food_name"]); ?>" class="view-btn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="no-results"><i class="fas fa-search"></i> No food items found</div>';
        }
        ?>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.food-card');
            
            cards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                if (cardText.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.3s ease forwards';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add hover effects and smooth interactions
        document.querySelectorAll('.food-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.querySelectorAll('.food-card').forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Output PHP food items as JS array
        const foodItems = <?php echo json_encode($food_items); ?>;
        // Replace the static dishes array with foodItems
        // Update renderDishes() and filter logic to use foodItems and the correct field names (food_name, cuisine_type, price_range, image_path, city, cooking_style, dietary_preference)
        // ... (Paste and adapt JS from discover.html, replacing sample data with foodItems) ...
    </script>
</body>
</html>
