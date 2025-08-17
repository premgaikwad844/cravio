<?php
$servername = "sql109.infinityfree.com";
$username = "if0_39329540";
$password = "Prem28831924";
$dbname = "if0_39329540_login_db12";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cravio</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:700,600,400&display=swap" rel="stylesheet">
</head>
<body>
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
         
        <a href="logout.php" class="navbar-btn">Logout</a>
        <div class="navbar-mobile-menu">
            
            <ul>
                <li><a href="index.php" style="background: #e53935; color: #fff; border-radius: 20px; padding: 8px 20px; display: inline-block; font-weight: bold;">Logout</a></li>
                <li><a href="main.php" class="active">Home</a></li>
                <li><a href="about.php">About us</a></li>
                <li><a href="search.php">Products</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="navbar-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="navbar-btn">Login</a>
            <?php endif; ?>
        </div>
        <button class="navbar-hamburger" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
    <section class="carousel">
        <div class="carousel-track">
            <div class="carousel-slide active" style="background-image: url('https://www.womensbyte.com/wp-content/uploads/2019/08/High-calorie-fruits-for-weight-loss.jpg');">
                <img src="https://www.womensbyte.com/wp-content/uploads/2019/08/High-calorie-fruits-for-weight-loss.jpg" alt="Search All Products" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Find Your Favorite Food</h1>
                    <p class="carousel-desc">Search our entire menu and discover something new every day!</p>
                    <div class="carousel-buttons">
                        <a href="search.php" class="carousel-btn carousel-btn-explore"><span>Search All Products</span></a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('https://png.pngtree.com/thumb_back/fh260/background/20240204/pngtree-food-composition-for-ramadan-with-space-on-right-image_15589018.png');">
                <img src="https://png.pngtree.com/thumb_back/fh260/background/20240204/pngtree-food-composition-for-ramadan-with-space-on-right-image_15589018.png" alt="Fast Food" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Fast Food</h1>
                    <p class="carousel-desc">Burgers, fries, pizza and more—delicious fast food for every craving.</p>
                    <div class="carousel-buttons">
                        <a href="fastfood.php" class="carousel-btn carousel-btn-explore"><span>Explore Fast Food</span></a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('https://img.freepik.com/premium-photo/vibrant-amazonian-ingredients-food-photography_753066-3265.jpg');">
                <img src="https://img.freepik.com/premium-photo/vibrant-amazonian-ingredients-food-photography_753066-3265.jpg" alt="Snacks" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Snacks</h1>
                    <p class="carousel-desc">Tasty snacks for every moment—chips, nuts, and more.</p>
                    <div class="carousel-buttons">
                        <a href="snacks.php" class="carousel-btn carousel-btn-explore"><span>See Snacks</span></a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('https://wallpaperaccess.com/full/8500654.jpg');">
                <img src="https://wallpaperaccess.com/full/8500654.jpg" alt="Desserts" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Desserts</h1>
                    <p class="carousel-desc">Indulge in sweet treats—cakes, pastries, and more.</p>
                    <div class="carousel-buttons">
                        <a href="desserts.php" class="carousel-btn carousel-btn-explore"><span>See Desserts</span></a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('https://pachamamafoodsng.com/wp-content/uploads/2023/07/3-1.jpg');">
                <img src="https://pachamamafoodsng.com/wp-content/uploads/2023/07/3-1.jpg" alt="Nutritional Food" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Nutritional Food</h1>
                    <p class="carousel-desc">Healthy and nutritious meals for your well-being.</p>
                    <div class="carousel-buttons">
                        <a href="nutrional.php" class="carousel-btn carousel-btn-explore"><span>See Nutritional Food</span></a>
                    </div>
                </div>
            </div>
            <div class="carousel-slide" style="background-image: url('https://img.freepik.com/free-photo/close-up-appetizing-ramadan-meal_23-2151182534.jpg');">
                <img src="https://img.freepik.com/free-photo/close-up-appetizing-ramadan-meal_23-2151182534.jpg" alt="Drinks" class="carousel-img-mobile" />
                <div class="carousel-mobile-overlay"></div>
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <h1 class="carousel-title">Drinks</h1>
                    <p class="carousel-desc">Quench your thirst with our selection of drinks and beverages.</p>
                    <div class="carousel-buttons">
                        <a href="drinks.php" class="carousel-btn carousel-btn-explore"><span>See Drinks</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="carousel-nav-wrapper">
            <button class="carousel-nav carousel-nav-left" aria-label="Previous slide">
                <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Left Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(180deg); filter: brightness(0) invert(1);" />
            </button>
            <button class="carousel-nav carousel-nav-right" aria-label="Next slide">
                <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Right Arrow" style="width: 60%; height: 60%; display: block; margin: auto; filter: brightness(0) invert(1);" />
            </button>
        </div>
    </section>
    <section class="menu-carousel">
      <button class="menu-carousel-arrow left" aria-label="Scroll left">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Left Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(180deg); filter: brightness(0) invert(1);" />
      </button>
      <div class="menu-carousel-track">
        <div class="menu-card">
          <img src="https://img.freepik.com/free-photo/variety-healthy-food-wooden-table_23-2147789177.jpg" alt="Fast Food" class="menu-card-img">
          <div class="menu-card-content">
            <h3>Fast Food</h3>
            <p class="menu-card-kcal">Explore delicious fast food options</p>
            <div class="menu-card-actions">
              <a href="fastfood.php" class="menu-card-btn">View</a>
            </div>
          </div>
        </div>
        <div class="menu-card">
          <img src="https://img.freepik.com/free-photo/assortment-vegetarian-dishes_23-2148742226.jpg" alt="Nutritional Info" class="menu-card-img">
          <div class="menu-card-content">
            <h3>Nutritional Info</h3>
            <p class="menu-card-kcal">Find healthy and nutritious meals</p>
            <div class="menu-card-actions">
              <a href="nutrional.php" class="menu-card-btn">View</a>
            </div>
          </div>
        </div>
        <div class="menu-card">
          <img src="https://img.freepik.com/free-photo/close-up-healthy-food-selection_23-2148739122.jpg" alt="Desserts" class="menu-card-img">
          <div class="menu-card-content">
            <h3>Desserts</h3>
            <p class="menu-card-kcal">Indulge in sweet dessert treats</p>
            <div class="menu-card-actions">
              <a href="desserts.php" class="menu-card-btn">View</a>
            </div>
          </div>
        </div>
        <div class="menu-card">
          <img src="https://img.freepik.com/free-photo/refreshing-cold-drink-glass_23-2147772217.jpg" alt="Drinks" class="menu-card-img">
          <div class="menu-card-content">
            <h3>Drinks</h3>
            <p class="menu-card-kcal">Quench your thirst with our drink selection</p>
            <div class="menu-card-actions">
              <a href="drinks.php" class="menu-card-btn">View</a>
            </div>
          </div>
        </div>
        <div class="menu-card">
          <img src="https://img.freepik.com/free-photo/top-view-snacks-arrangement_23-2148825123.jpg" alt="Snacks" class="menu-card-img">
          <div class="menu-card-content">
            <h3>Snacks</h3>
            <p class="menu-card-kcal">Tasty snacks for every craving</p>
            <div class="menu-card-actions">
              <a href="snacks.php" class="menu-card-btn">View</a>
            </div>
          </div>
        </div>
      </div>
      <button class="menu-carousel-arrow right" aria-label="Scroll right">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Right Arrow" style="width: 60%; height: 60%; display: block; margin: auto; filter: brightness(0) invert(1);" />
      </button>
    </section>
    <section id="explore-section" style="min-height: 100vh; background: #fafafa; display: flex; align-items: center; justify-content: center;">
      <div class="explore-info-container" style="display: flex; align-items: center; justify-content: center; width: 100%; max-width: 1300px; gap: 40px;">
        <div class="explore-info-text" style="flex: 1;">
          <h1 style="font-size: 3rem; font-weight: 700; color: #23272b; margin-bottom: 0.5em;">
            Your Personalized Food Recommendation System
          </h1>
          <p style="font-size: 1.2rem; color: #23272b; margin-bottom: 2em;">
            Discover meals and restaurants tailored just for you! Our intelligent system analyzes your preferences, dietary needs, and location to recommend the best dishes. Whether you're looking for healthy options, local favorites, or something new, we've got you covered.
          </p>
          <ul style="list-style: none; padding: 0; margin-bottom: 2em;">
            <li style="font-size: 1.1rem; margin-bottom: 0.7em; color: #23272b;">
              <span style="color: #97c933; font-size: 1.5em; vertical-align: middle;">&#10003;</span>
              Personalized meal suggestions
            </li>
            <li style="font-size: 1.1rem; margin-bottom: 0.7em; color: #23272b;">
              <span style="color: #97c933; font-size: 1.5em; vertical-align: middle;">&#10003;</span>
              Smart dietary and allergy filters
            </li>
            <li style="font-size: 1.1rem; margin-bottom: 0.7em; color: #23272b;">
              <span style="color: #97c933; font-size: 1.5em; vertical-align: middle;">&#10003;</span>
              Location-based recommendations
            </li>
            <li style="font-size: 1.1rem; margin-bottom: 0.7em; color: #23272b;">
              <span style="color: #97c933; font-size: 1.5em; vertical-align: middle;">&#10003;</span>
              User reviews and ratings
            </li>
            <li style="font-size: 1.1rem; color: #23272b;">
              <span style="color: #97c933; font-size: 1.5em; vertical-align: middle;">&#10003;</span>
              Easy to use and always up to date
            </li>
          </ul>
        </div>
        <div class="explore-info-image" style="flex: 1; display: flex; align-items: center; justify-content: center;">
          <img src="https://blogs.biomedcentral.com/on-medicine/wp-content/uploads/sites/6/2019/09/iStock-1131794876.t5d482e40.m800.xtDADj9SvTVFjzuNeGuNUUGY4tm5d6UGU5tkKM0s3iPk.jpg" alt="Food Recommendation System" style="max-width: 100%; height: auto; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        </div>
      </div>
    </section>
    <!-- Why Choose Us Section -->
    <section class="why-choose-us" style="background: #fcfefa; padding: 60px 0;">
      <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
        <div style="color: #97c933; font-size: 2.2rem; font-family: 'Nunito', cursive; margin-bottom: 0.2em;">Our benefits</div>
        <h2 style="font-size: 3rem; font-weight: 700; color: #23272b; margin-bottom: 0.5em;">Why users choose us</h2>
        <p style="max-width: 900px; margin: 0 auto 2.5em; font-size: 1.2rem; color: #23272b;">Discover why thousands trust our food recommendation system to find the best meals for their lifestyle. We combine smart technology, real reviews, and a passion for healthy eating to help you make the right choice every time.</p>
        <div class="why-choose-cards">
          <div class="why-choose-card">
            <div class="why-choose-icon">
              <img src="https://cdn-icons-png.flaticon.com/128/3075/3075977.png" alt="Healthy Food">
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #23272b; margin-bottom: 0.3em; text-align: center;">Trusted Healthy Choices</h3>
            <p style="color: #23272b; font-size: 0.95rem; text-align: center; margin: 0;">We recommend only the best, healthiest meals from verified sources and restaurants.</p>
          </div>
          <div class="why-choose-card">
            <div class="why-choose-icon">
              <img src="https://cdn-icons-png.flaticon.com/128/1046/1046857.png" alt="Personalized">
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #23272b; margin-bottom: 0.3em; text-align: center;">Personalized For You</h3>
            <p style="color: #23272b; font-size: 0.95rem; text-align: center; margin: 0;">Get meal suggestions tailored to your tastes, dietary needs, and health goals.</p>
          </div>
          <div class="why-choose-card">
            <div class="why-choose-icon">
              <img src="https://cdn-icons-png.flaticon.com/128/1828/1828919.png" alt="Easy Fast">
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #23272b; margin-bottom: 0.3em; text-align: center;">Easy & Fast</h3>
            <p style="color: #23272b; font-size: 0.95rem; text-align: center; margin: 0;">Find the perfect meal in seconds with our simple, user-friendly interface.</p>
          </div>
          <div class="why-choose-card">
            <div class="why-choose-icon">
              <img src="https://cdn-icons-png.flaticon.com/128/1828/1828961.png" alt="Community">
            </div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #23272b; margin-bottom: 0.3em; text-align: center;">Community Powered</h3>
            <p style="color: #23272b; font-size: 0.95rem; text-align: center; margin: 0;">See real reviews and ratings from our community to help you choose with confidence.</p>
          </div>
        </div>
        <hr style="border: none; border-top: 2px solid #e0e0e0; width: 90%; margin: 30px auto 0 auto;" />
      </div>
    </section>
    
    <!-- Search Button Section (Enhanced) -->
    <section class="search-cta-section">
      <div class="search-cta-content" style="position: relative; z-index: 2; max-width: 700px; margin: 0 auto; padding: 60px 24px 48px 24px; background: #97c933; border-radius: 24px; box-shadow: 0 8px 32px rgba(151,201,51,0.13); color: #fff;">
        <h2 style="margin-bottom: 1.2rem; color: #fff;">Find Your Next Favorite Meal</h2>
        <p style="font-size: 1.18rem; color: #fff; margin-bottom: 2.2rem; line-height: 1.7;">Discover a world of delicious possibilities! Our food search helps you quickly find meals that match your cravings, dietary needs, and lifestyle. Whether you want something healthy, indulgent, or new, we've got you covered.</p>
        <div style="margin-top: 2.5rem;">
          <div style="font-size: 1.13rem; color: #fff; margin-bottom: 1.2rem;">Ready to explore? Start your food journey now:</div>
          <div class="carousel-buttons">
            <a href="search.php" class="carousel-btn carousel-btn-explore"><span>Search for Food</span></a>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Chatbot Widget Integration Start -->
    <?php include 'Chatbot.php'; ?>
    <!-- Chatbot Widget Integration End -->
    <button id="backToTopBtn" title="Go to top" class="back-to-top-btn align-right" aria-label="Back to top" style="z-index: 1000; position: fixed; bottom: 32px; right: 32px;">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Up Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(-90deg);" />
    </button>
    <footer class="footer">
      <svg class="footer-wave-svg" viewBox="0 0 1440 80" width="100%" height="80" preserveAspectRatio="none" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 80px; z-index: 1;">
        <path id="footerWavePath1" fill="#97c933" fill-opacity="0.7">
          <animate attributeName="d" dur="10s" repeatCount="indefinite"
            values="M0 80C360 0 1080 160 1440 80V0H0V80Z;
                    M0 80C300 40 1140 0 1440 80V0H0V80Z;
                    M0 80C400 60 1040 120 1440 80V0H0V80Z;
                    M0 80C360 0 1080 160 1440 80V0H0V80Z" />
        </path>
      </svg>
      <svg class="footer-wave-svg" viewBox="0 0 1440 80" width="100%" height="80" preserveAspectRatio="none" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 80px; z-index: 2;">
        <path id="footerWavePath2" fill="#97c933" fill-opacity="1">
          <animate attributeName="d" dur="7s" repeatCount="indefinite"
            values="M0 80C400 40 1040 120 1440 80V0H0V80Z;
                    M0 80C320 60 1120 0 1440 80V0H0V80Z;
                    M0 80C360 0 1080 160 1440 80V0H0V80Z;
                    M0 80C400 40 1040 120 1440 80V0H0V80Z" />
        </path>
      </svg>
      <div class="footer-content">
        <div class="footer-col footer-brand">
          <div class="footer-logo-row">
            <img src="f2-removebg-preview.png" alt="fitmeal logo" class="footer-logo-icon">
            <div class="footer-logo-text">
              <span class="footer-logo-title">Cravio</span>
            </div>
          </div>
          <p class="footer-desc" style="font-size: 0.98rem; color: #fff; margin-bottom: 1.2em; max-width: 340px;">Cravio is your trusted companion for discovering, comparing, and enjoying the best food and drinks. We bring you curated recommendations, local favorites, and a seamless experience to satisfy every craving.</p>
          <div class="footer-socials" style="display: flex; gap: 18px; margin-bottom: 1.2em;">
            <img src="https://cdn-icons-png.flaticon.com/128/733/733547.png" alt="Facebook" style="width: 32px; height: 32px; display: inline-block;">
            <img src="https://cdn-icons-png.flaticon.com/128/733/733558.png" alt="Instagram" style="width: 32px; height: 32px; display: inline-block;">
          </div>
        </div>
        <div class="footer-col footer-links">
          <h3>Explore</h3>
          <ul>
            <li><a href="main.php">Home</a></li>
            <li><a href="#">Products</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="privacy_and_policy.php">Privacy & Policy</a></li>
            <li><a href="terms_and_conditions.php">Terms & Conditions</a></li>
          </ul>
        </div>
        <div class="footer-col footer-contact">
          <h3>Contact info</h3>
          <div class="footer-contact-item"><span class="footer-contact-icon">📍</span> <span><b>Our location:</b><br>Sangli, Maharashtra, India</span></div>
          <div class="footer-contact-item"><span class="footer-contact-icon">✉️</span> <span><b>Email:</b><br>cravioweb@gmail.com</span></div>
        </div>
      </div>
    </footer>
    <script src="main.js"></script>
    <script>
    // Ripple effect for .search-cta-btn
    document.addEventListener('DOMContentLoaded', function() {
      var btn = document.querySelector('.search-cta-btn');
      if (btn) {
        btn.addEventListener('click', function(e) {
          var rect = btn.getBoundingClientRect();
          var ripple = document.createElement('span');
          ripple.className = 'ripple';
          var size = Math.max(rect.width, rect.height);
          ripple.style.width = ripple.style.height = size + 'px';
          ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
          ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
          btn.appendChild(ripple);
          ripple.addEventListener('animationend', function() {
            ripple.remove();
          });
        });
      }
    });
    </script>
</body>
</html>
