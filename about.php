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
    <title>About Us | fitmeal</title>
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:700,600,400&display=swap" rel="stylesheet">
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
    <li><a href="about.php" class="active">About us</a></li>
    <li><a href="search.php">Products</a></li>
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
    <nav class="navbar" style="position: relative; z-index: 10;">
        <div class="navbar-logo-group">
            <img src="f2-removebg-preview.png" alt="Cravio logo" class="navbar-logo-icon">
            <div class="navbar-logo-text">
                <span class="navbar-logo-title">Cravio</span>
             </div>
        </div>
        <ul class="navbar-links">
            <li><a href="main.php">Home <span class="arrow">&#8250;</span></a></li>
            <li><a href="about.php" class="active">About us <span class="arrow">&#8250;</span></a></li>
            <li><a href="search.php">Products <span class="arrow">&#8250;</span></a></li>
            <li><a href="#contact">Contact Us <span class="arrow">&#8250;</span></a></li>
        </ul>
        <a href="#get-menu" class="navbar-btn">Get Menu</a>
    </nav>
    <!-- Hero/Banner Section -->
    <section class="hero-banner" style="position:relative; background:#888a8e; min-height:320px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1500&q=80" alt="Banner" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; opacity:0.45; z-index:1;">
        <div style="position:relative; z-index:2; text-align:center; width:100%;">
            <h1 style="font-size:3rem; color:#fff; font-weight:800; margin-bottom:0.5rem;">About fitmeal</h1>
            <div class="breadcrumbs" style="font-size:1.1rem; color:#d0e17d; font-weight:700;">
                <a href="main.html" style="color:#d0e17d; text-decoration:none;">Home</a>
                <span style="color:#fff; margin:0 8px;">|</span>
                <span style="color:#fff;">About Us</span>
            </div>
        </div>
    </section>
    <main style="min-height: 80vh; background: #fafafa; padding: 0 0 60px 0;">
        <section style="max-width: 1000px; margin: 40px auto 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(151,201,51,0.07); padding: 48px 32px 32px 32px;">
            <h2 style="color: #97c933; font-size: 2.2rem; font-weight: 700; margin-bottom: 1.2em; text-align: center;">Our Story</h2>
            <p style="font-size: 1.15rem; color: #23272b; line-height: 1.7; max-width: 800px; margin: 0 auto 2em auto; text-align: center;">Founded by food lovers and tech enthusiasts, fitmeal was born from the desire to make eating well easy and enjoyable. We believe that everyone deserves access to meals that fit their tastes, health goals, and dietary needs. Our smart recommendation system combines technology, nutrition, and community insights to help you eat better every day.</p>
        </section>
        <!-- Mission, Vision, Values Section (Professional) -->
        <section style="max-width: 1000px; margin: 40px auto 0 auto; background: #f8f9fa; border-radius: 18px; box-shadow: 0 2px 12px rgba(151,201,51,0.04); padding: 40px 32px 32px 32px;">
            <div style="display: flex; flex-wrap: wrap; gap: 32px; justify-content: center; align-items: stretch;">
                <div style="flex: 1 1 220px; min-width: 220px; max-width: 320px; background: #fff; border-radius: 12px; padding: 32px 24px; text-align: left; box-shadow: 0 2px 8px rgba(151,201,51,0.06); border: 1px solid #e6e6e6; transition: box-shadow 0.2s;">
                    <h3 style="color: #23272b; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.7em;">Our Mission</h3>
                    <p style="color: #555; font-size: 1.05rem;">To empower individuals to make informed, healthy food choices through technology, transparency, and community support.</p>
                </div>
                <div style="flex: 1 1 220px; min-width: 220px; max-width: 320px; background: #fff; border-radius: 12px; padding: 32px 24px; text-align: left; box-shadow: 0 2px 8px rgba(151,201,51,0.06); border: 1px solid #e6e6e6; transition: box-shadow 0.2s;">
                    <h3 style="color: #23272b; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.7em;">Our Vision</h3>
                    <p style="color: #555; font-size: 1.05rem;">To be the leading platform for discovering, enjoying, and sharing healthy, delicious food experiences worldwide.</p>
                </div>
                <div style="flex: 1 1 220px; min-width: 220px; max-width: 320px; background: #fff; border-radius: 12px; padding: 32px 24px; text-align: left; box-shadow: 0 2px 8px rgba(151,201,51,0.06); border: 1px solid #e6e6e6; transition: box-shadow 0.2s;">
                    <h3 style="color: #23272b; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.7em;">Our Values</h3>
                    <ul style="color: #555; font-size: 1.05rem; padding-left: 1.2em; margin: 0; list-style: disc;">
                        <li>Integrity in every recommendation</li>
                        <li>Innovation for better health</li>
                        <li>Community-driven trust</li>
                        <li>Respect for diversity and choice</li>
                    </ul>
                </div>
            </div>
        </section>
        <!-- Divider -->
        <div style="max-width: 1000px; margin: 32px auto 0 auto; border-bottom: 1.5px solid #e6e6e6;"></div>
        <!-- Meet the Team Section (Professional) -->
        <section style="max-width: 1000px; margin: 40px auto 0 auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(151,201,51,0.07); padding: 48px 32px;">
            <h2 style="color: #97c933; font-size: 2.2rem; font-weight: 700; margin-bottom: 1.2em; text-align: center;">Meet the Team</h2>
            <div style="display: flex; flex-wrap: wrap; gap: 32px; justify-content: center;">
                <div style="flex: 1 1 180px; min-width: 180px; max-width: 220px; background: #f8f9fa; border-radius: 12px; padding: 28px 16px; text-align: center; box-shadow: 0 2px 8px rgba(151,201,51,0.04); border: 1px solid #e6e6e6; transition: box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; min-height: 340px;">
                    <img src="https://randomuser.me/api/portraits/men/11.jpg" alt="Jaydeep Gavane" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 1em; filter: grayscale(100%); border: 3px solid #97c933;">
                    <div>
                        <h4 style="color: #23272b; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3em;">Jaydeep Gavane</h4>
                        <p style="color: #97c933; font-size: 1rem; margin-bottom: 0.2em;">Project Lead & Frontend Developer</p>
                        <p style="color: #555; font-size: 0.97rem;">Jaydeep leads the team and brings ideas to life on the web, ensuring a seamless and innovative user experience.</p>
                    </div>
                    <div style="margin-top: 0.7em; display: flex; justify-content: center; align-items: center; gap: 12px;">
                        <a href="#" title="LinkedIn" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf0e1;</span></a>
                        <a href="#" title="Twitter" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf099;</span></a>
                    </div>
                </div>
                <div style="flex: 1 1 180px; min-width: 180px; max-width: 220px; background: #f8f9fa; border-radius: 12px; padding: 28px 16px; text-align: center; box-shadow: 0 2px 8px rgba(151,201,51,0.04); border: 1px solid #e6e6e6; transition: box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; min-height: 340px;">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Prasad Dhotre" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 1em; filter: grayscale(100%); border: 3px solid #97c933;">
                    <div>
                        <h4 style="color: #23272b; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3em;">Prasad Dhotre</h4>
                        <p style="color: #97c933; font-size: 1rem; margin-bottom: 0.2em;">UI/UX Designer</p>
                        <p style="color: #555; font-size: 0.97rem;">Prasad designs intuitive and visually appealing interfaces, focusing on user satisfaction and accessibility.</p>
                    </div>
                    <div style="margin-top: 0.7em; display: flex; justify-content: center; align-items: center; gap: 12px;">
                        <a href="#" title="LinkedIn" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf0e1;</span></a>
                        <a href="#" title="Twitter" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf099;</span></a>
                    </div>
                </div>
                <div style="flex: 1 1 180px; min-width: 180px; max-width: 220px; background: #f8f9fa; border-radius: 12px; padding: 28px 16px; text-align: center; box-shadow: 0 2px 8px rgba(151,201,51,0.04); border: 1px solid #e6e6e6; transition: box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; min-height: 340px;">
                    <img src="https://randomuser.me/api/portraits/men/33.jpg" alt="Prem Gaikwad" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 1em; filter: grayscale(100%); border: 3px solid #97c933;">
                    <div>
                        <h4 style="color: #23272b; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3em;">Prem Gaikwad</h4>
                        <p style="color: #97c933; font-size: 1rem; margin-bottom: 0.2em;">Backend Developer</p>
                        <p style="color: #555; font-size: 0.97rem;">Prem develops and maintains robust backend systems, ensuring smooth and secure operations for the platform.</p>
                    </div>
                    <div style="margin-top: 0.7em; display: flex; justify-content: center; align-items: center; gap: 12px;">
                        <a href="#" title="LinkedIn" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf0e1;</span></a>
                        <a href="#" title="Twitter" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf099;</span></a>
                    </div>
                </div>
                <div style="flex: 1 1 180px; min-width: 180px; max-width: 220px; background: #f8f9fa; border-radius: 12px; padding: 28px 16px; text-align: center; box-shadow: 0 2px 8px rgba(151,201,51,0.04); border: 1px solid #e6e6e6; transition: box-shadow 0.2s; display: flex; flex-direction: column; justify-content: space-between; min-height: 340px;">
                    <img src="https://randomuser.me/api/portraits/men/44.jpg" alt="Atharv Chopade" style="width: 70px; height: 70px; border-radius: 50%; margin-bottom: 1em; filter: grayscale(100%); border: 3px solid #97c933;">
                    <div>
                        <h4 style="color: #23272b; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3em;">Atharv Chopade</h4>
                        <p style="color: #97c933; font-size: 1rem; margin-bottom: 0.2em;">Resource & Research Manager</p>
                        <p style="color: #555; font-size: 0.97rem;">Atharv sources essential resources and gathers research and design materials, supporting the team’s creative and technical needs.</p>
                    </div>
                    <div style="margin-top: 0.7em; display: flex; justify-content: center; align-items: center; gap: 12px;">
                        <a href="#" title="LinkedIn" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf0e1;</span></a>
                        <a href="#" title="Twitter" style="color:#23272b; font-size:1.3em;"><span class="footer-icon">&#xf099;</span></a>
                    </div>
                </div>
            </div>
        </section>
        <div style="text-align:center; margin: 40px 0 0 0;">
            <a href="main.html" style="color: #97c933; font-weight: 700; font-size: 1.1rem; text-decoration: underline;">&larr; Back to Home</a>
        </div>
    </main>
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
                </ul>
            </div>
            <div style="flex:1 1 220px; min-width:220px;">
                <h3 style="color:#c6e265; font-size:1.3rem; margin-bottom:18px;">Contact info</h3>
                <div style="color:#fff;">
                    <div class="footer-contact-item" style="margin-bottom:10px;"><span class="footer-contact-icon">📍</span> <span><b>Our location:</b><br>Sangli, Maharashtra, India</span></div>
                    <div class="footer-contact-item"><span class="footer-contact-icon">✉️</span> <span><b>Email:</b><br>demo@gmail.com</span></div>
                </div>
            </div>
        </div>
        <div style="text-align:center; color:#fff; margin-top:40px; font-size:1rem;">
            &copy; 2024 Cravio. All rights reserved.
        </div>
    </footer>
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
</body>
</html> 