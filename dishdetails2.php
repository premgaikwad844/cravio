<?php
// dishdetails2.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getImageUrl($image_path) {
    if (empty($image_path)) return '';
    if (filter_var($image_path, FILTER_VALIDATE_URL)) return $image_path;
    return $image_path;
}

$dish_name = isset($_GET['dish']) ? urldecode($_GET['dish']) : '';
$dish = null;
$dishes_fastfood = include 'fastfood_data.php';
$dishes_snacks = include 'snacks_data.php';
$dishes_desserts = include 'desserts_data.php';
$dishes_drinks = include 'drinks_data.php';
$dishes_nutrional = include 'nutrional_data.php';
$all_dishes = array_merge($dishes_fastfood, $dishes_snacks, $dishes_desserts, $dishes_drinks, $dishes_nutrional);
if ($dish_name) {
    foreach ($all_dishes as $item) {
        if (strcasecmp($item['name'], $dish_name) === 0) {
            $dish = $item;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dish Details | Cravio</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:700,600,400&display=swap" rel="stylesheet">
    <style>
        body { background: #f7f8fa; margin: 0; font-family: 'Nunito', Arial, 'Helvetica Neue', Helvetica, sans-serif; }
        .navbar { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 18px 0; }
        .navbar .container { display: flex; align-items: center; justify-content: space-between; max-width: 1200px; margin: 0 auto; padding: 0 32px; }
        .navbar a { color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem; }
        .navbar a:last-child { margin-right: 0; }
        .navbar .brand { color: #97c933; font-size: 1.4rem; font-weight: 700; letter-spacing: 1px; }
        .hero-img-wrap { position:relative; width:100vw; left:50%; right:50%; margin-left:-50vw; margin-right:-50vw; max-width:100vw; overflow:hidden; }
        .hero-img { width:100%; height:340px; object-fit:cover; display:block; filter:brightness(0.85); }
        .hero-overlay { position:absolute; top:0; left:0; width:100%; height:100%; background:linear-gradient(180deg,rgba(0,0,0,0.32) 0%,rgba(0,0,0,0.12) 100%); z-index:1; }
        .hero-title { position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); color:#fff; font-size:2.8rem; font-weight:800; letter-spacing:1px; z-index:2; text-shadow:0 2px 16px rgba(0,0,0,0.18); text-align:center; width:100%; }
        .breadcrumb { max-width:1100px; margin:32px auto 0 auto; font-size:1.08rem; color:#97c933; font-weight:700; text-align:left; padding:0 48px; }
        .dish-detail-main { max-width:1100px; margin:64px auto 0 auto; display:flex; gap:80px; align-items:center; background:none; box-shadow:none; border-radius:0; padding:0 48px; }
        .dish-img-col { flex:1; min-width:320px; display:flex; justify-content:center; align-items:center; }
        .dish-img-main { width:100%; max-width:420px; border-radius:18px; object-fit:cover; }
        .dish-info-col { flex:2; min-width:320px; padding-top:0; display:flex; flex-direction:column; align-items:flex-start; }
        .dish-short-desc { font-size:1.22rem; color:#23272b; font-weight:500; margin-bottom:32px; line-height:1.6; padding-top:12px; }
        .spec-pairs { width:100%; margin-bottom:32px; }
        .spec-row { display:flex; gap:24px; margin-bottom:18px; font-size:1.13rem; }
        .spec-label { font-weight:600; min-width:160px; color:#6b6b6b; letter-spacing:0.2px; }
        .spec-value { color:#23272b; }
        .dish-detail-desc-section { max-width:900px; margin:72px auto 0 auto; background:none; border-radius:0; box-shadow:none; padding:0 48px 60px 48px; display:block; }
        .dish-detail-desc-section h2 { color:#23272b; font-size:2.1rem; margin-bottom:28px; font-weight:700; letter-spacing:0.5px; }
        .dish-detail-desc-section p { color:#444; font-size:1.22rem; line-height:2.1; margin-bottom:32px; }
        .footer-navbar { background:#23272b; color:#fff; padding:32px 0 18px 0; margin-top:0; }
        .footer-navbar .footer-container { max-width:1200px; margin:0 auto; padding:0 32px; display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; }
        .footer-navbar .footer-brand { font-size:1.2rem; font-weight:700; letter-spacing:1px; color:#97c933; }
        .footer-navbar .footer-links { display:flex; gap:32px; }
        .footer-navbar .footer-links a { color:#fff; text-decoration:none; font-size:1.05rem; font-weight:600; transition:color 0.2s; }
        .footer-navbar .footer-links a:hover { color:#97c933; }
        .footer-navbar .footer-copy { font-size:0.98rem; color:#c6e265; margin-top:12px; }
        @media (max-width: 900px) {
            .dish-detail-main { flex-direction:column; gap:40px; padding:0 12px; align-items:stretch; }
            .breadcrumb { padding:0 12px; margin-top:24px; }
            .dish-detail-desc-section { padding:0 12px 40px 12px; }
            .dish-img-col { margin-bottom:18px; }
            .footer-navbar .footer-container { flex-direction:column; gap:18px; align-items:flex-start; }
            .footer-navbar .footer-links { gap:18px; }
        }
        .navbar-logo-group { padding-left: 48px; }
    </style>
</head>
<body>
    <nav class="navbar" style="position: relative; z-index: 10;">
        <div class="navbar-logo-group">
            <img src="logo.png" alt="Cravio logo" class="navbar-logo-icon">
            <div class="navbar-logo-text">
                <span class="navbar-logo-title">Cravio</span>
                <span class="navbar-logo-subtitle">food delivery</span>
            </div>
        </div>
        <ul class="navbar-links">
            <li><a href="#" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Home <span class="arrow">&#8250;</span></a></li>
            <li><a href="#" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">About <span class="arrow">&#8250;</span></a></li>
            <li><a href="#" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Products <span class="arrow">&#8250;</span></a></li>
            <li><a href="drinks.php" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Drinks <span class="arrow">&#8250;</span></a></li>
            <li><a href="#" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Contact Us <span class="arrow">&#8250;</span></a></li>
        </ul>
        <a href="#get-menu" class="navbar-btn">Get Menu</a>
        <button class="navbar-hamburger" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
    <div class="hero-img-wrap">
        <img id="dishImg" class="hero-img" src="<?php echo $dish ? htmlspecialchars(getImageUrl($dish['image'])) : ''; ?>" alt="Dish image">
        <div class="hero-overlay"></div>
        <div class="hero-title" id="dishTitle"><?php echo $dish ? htmlspecialchars($dish['name']) : 'Dish Not Found'; ?></div>
    </div>
    <div class="breadcrumb" id="breadcrumb">
        <?php if ($dish): ?>
            <a href='main.php' style='color:#97c933;text-decoration:none;'>Home</a> <span style='color:#bbb;margin:0 6px;'>|</span> <a href='fastfood.php' style='color:#97c933;text-decoration:none;'>Fast Food</a> <span style='color:#bbb;margin:0 6px;'>|</span> <span style='color:#23272b;'><?php echo htmlspecialchars($dish['name']); ?></span>
        <?php endif; ?>
    </div>
    <div class="dish-detail-main" style="justify-content:center;">
        <div class="dish-img-col" style="display:flex;justify-content:center;align-items:center;">
            <img id="dishImgMain" class="dish-img-main" src="<?php echo $dish ? htmlspecialchars(getImageUrl($dish['image'])) : ''; ?>" alt="Dish image">
        </div>
        <div class="dish-info-col" style="align-items:center;text-align:center;">
            <div class="dish-short-desc" id="dishShortDesc" style="text-align:center;">
                <?php echo $dish ? htmlspecialchars($dish['cuisine_type']) . ' | ' . htmlspecialchars($dish['city']) . ', ' . htmlspecialchars($dish['state']) : ''; ?>
            </div>
            <div class="spec-pairs" id="specPairs" style="margin:0 auto;text-align:left;display:inline-block;">
                <?php if ($dish): ?>
                    <div class='spec-row'><span class='spec-label'>Cuisine Type</span><span class='spec-value'><?php echo isset($dish['cuisine_type']) ? htmlspecialchars($dish['cuisine_type']) : '-'; ?></span></div>
                    <?php if (isset($dish['drink_type'])): // It's a drink ?>
                        <div class='spec-row'><span class='spec-label'>Drink Type</span><span class='spec-value'><?php echo isset($dish['drink_type']) ? htmlspecialchars($dish['drink_type']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Category</span><span class='spec-value'><?php echo isset($dish['category']) ? htmlspecialchars($dish['category']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Flavor Profile</span><span class='spec-value'><?php echo isset($dish['flavor_profile']) ? htmlspecialchars($dish['flavor_profile']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Serving Temp</span><span class='spec-value'><?php echo isset($dish['serving_temp']) ? htmlspecialchars($dish['serving_temp']) : '-'; ?></span></div>
                    <?php else: // It's food ?>
                        <div class='spec-row'><span class='spec-label'>Meal Type</span><span class='spec-value'><?php echo isset($dish['meal_type']) ? htmlspecialchars($dish['meal_type']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Dietary Preference</span><span class='spec-value'><?php echo isset($dish['dietary_preference']) ? htmlspecialchars($dish['dietary_preference']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Spice Level</span><span class='spec-value'><?php echo isset($dish['spice_level']) ? htmlspecialchars($dish['spice_level']) : '-'; ?></span></div>
                        <div class='spec-row'><span class='spec-label'>Cooking Style</span><span class='spec-value'><?php echo isset($dish['cooking_style']) ? htmlspecialchars($dish['cooking_style']) : '-'; ?></span></div>
                    <?php endif; ?>
                    <div class='spec-row'><span class='spec-label'>Price Range</span><span class='spec-value'><?php echo isset($dish['price_range']) ? htmlspecialchars($dish['price_range']) : '-'; ?></span></div>
                    <div class='spec-row'><span class='spec-label'>State</span><span class='spec-value'><?php echo isset($dish['state']) ? htmlspecialchars($dish['state']) : '-'; ?></span></div>
                    <div class='spec-row'><span class='spec-label'>City</span><span class='spec-value'><?php echo isset($dish['city']) ? htmlspecialchars($dish['city']) : '-'; ?></span></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="dish-detail-desc-section" style="text-align:center;">
        <h2>Description</h2>
        <p id="dishDesc" style="text-align:center;">
            <?php echo $dish ? (isset($dish['description']) ? htmlspecialchars($dish['description']) : 'No description available.') : 'Sorry, we could not find details for this dish.'; ?>
        </p>
        <div style="display: flex; justify-content: center; gap: 16px; align-items: center;">
        <?php if ($dish): ?>
            <button 
                onclick="searchHotelsInCity('<?php echo htmlspecialchars($dish['city'], ENT_QUOTES); ?>')"
                style="display:inline-block;padding:8px 22px;background:#97c933;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;transition:background 0.2s;cursor:pointer;">
                Find Hotels on Map
            </button>
        <?php endif; ?>
        <a href="fastfood.php" class="back-btn" style="display:inline-block;padding:8px 22px;background:#23272b;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;transition:background 0.2s;">&larr; Back to Fast Food</a>
        </div>
    </div>
    <script>
function searchHotelsInCity(city) {
    if (!city) {
        alert('City not specified!');
        return;
    }
    const query = encodeURIComponent('hotels in ' + city);
    window.open(`https://www.google.com/maps/search/${query}`, '_blank');
}
    </script>
    <!-- Bottom to Top Button -->
    <button id="backToTopBtn" title="Go to top" class="back-to-top-btn" aria-label="Back to top">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Up Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(-90deg);" />
    </button>
    <script>
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
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-col footer-brand">
          <div class="footer-logo-row">
            <img src="https://cdn-icons-png.flaticon.com/128/3075/3075977.png" alt="fitmeal logo" class="footer-logo-icon">
            <div class="footer-logo-text">
              <span class="footer-logo-title">Cravio</span>
            </div>
          </div>
          <p class="footer-desc">Integer maximus accumsan nunc, sit amet tempor lectus facilisis eu. Cras vel elit felis. Vestibulum convallis ipsum id aliquam varius.</p>
          <div class="footer-socials">
            <a href="#" aria-label="Twitter"><img src="https://cdn-icons-png.flaticon.com/128/733/733579.png" alt="Twitter"></a>
            <a href="#" aria-label="Facebook"><img src="https://cdn-icons-png.flaticon.com/128/733/733547.png" alt="Facebook"></a>
            <a href="#" aria-label="Instagram"><img src="https://cdn-icons-png.flaticon.com/128/733/733558.png" alt="Instagram"></a>
            <a href="#" aria-label="YouTube"><img src="https://cdn-icons-png.flaticon.com/128/733/733646.png" alt="YouTube"></a>
          </div>
        </div>
        <div class="footer-col footer-links">
          <h3>Explore</h3>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="drinks.php">Drinks</a></li>
            <li><a href="#">Clients</a></li>
            <li><a href="#">Contact Us</a></li>
          </ul>
        </div>
        <div class="footer-col footer-contact">
          <h3>Contact info</h3>
          <div class="footer-contact-item"><span class="footer-contact-icon">📍</span> <span><b>Our location:</b><br>Goldschmidtstraße 13, 04103 Leipzig</span></div>
          <div class="footer-contact-item"><span class="footer-contact-icon">📞</span> <span><b>Phones:</b><br>+49078-039-23-11<br>+49078-028-55-60</span></div>
        </div>
      </div>
      <div class="footer-navbar">
        <div class="footer-container">
          <div class="footer-brand">Cravio</div>
          <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">Blog</a>
            <a href="#">Products</a>
            <a href="#">Clients</a>
            <a href="#">Contact Us</a>
          </div>
          <div class="footer-copy">&copy; 2024 Cravio. All rights reserved.</div>
        </div>
      </div>
    </footer>
</body>
</html> 