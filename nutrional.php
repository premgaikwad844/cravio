<?php
// nutrional.php
$dishes = include 'nutrional_data.php';
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritional Dishes</title>
    <link rel="stylesheet" href="main.css">
    <style>
        body { background: #f7f8fa; font-family: 'Nunito', Arial, sans-serif; }
        .fastfood-header { text-align: center; margin: 40px 0 24px 0; font-size: 2.5rem; color: #23272b; font-weight: 800; }
        .fastfood-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 28px; max-width: 1300px; margin: 0 auto 60px auto; padding: 0 16px; }
        .fastfood-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.07); overflow: hidden; display: flex; flex-direction: column; align-items: center; transition: box-shadow 0.2s, transform 0.22s cubic-bezier(.4,1.4,.6,1); }
        .fastfood-card:hover { box-shadow: 0 8px 32px rgba(151,201,51,0.13); transform: scale(1.06); z-index: 2; }
        .fastfood-img { width: 100%; height: 160px; object-fit: cover; }
        .fastfood-info { padding: 18px 14px 20px 14px; text-align: center; }
        .fastfood-title { font-size: 1.18rem; font-weight: 700; color: #23272b; margin-bottom: 8px; }
        .fastfood-desc { font-size: 0.98rem; color: #444; }
        @media (max-width: 600px) {
            .fastfood-header { font-size: 2rem; }
            .fastfood-img { height: 120px; }
        }
        .back-to-top-btn {
            position: fixed;
            bottom: 32px;
            right: 32px;
            z-index: 99;
            background: #fff;
            border: 2px solid #97c933;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            box-shadow: 0 2px 8px rgba(151,201,51,0.13);
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .back-to-top-btn.show { display: flex; }
        .back-to-top-btn:hover { background: #97c933; }
    </style>
</head>
<body>
    <nav class="navbar" style="background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 18px 0;">
        <div class="navbar-logo-group" style="padding-left: 48px; display: flex; align-items: center;">
            <img src="f2-removebg-preview.png" alt="fitmeal logo" class="navbar-logo-icon" style="height: 40px; margin-right: 12px;">
            <div class="navbar-logo-text">
                <span class="navbar-logo-title" style="font-size: 1.4rem; font-weight: 700; color: #97c933; letter-spacing: 1px;">Cravio</span>
            </div>
        </div>
        <ul class="navbar-links" style="display: flex; list-style: none; margin: 0; padding: 0; align-items: center;">
            <li><a href="main.php" class="active" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Home <span class="arrow">&#8250;</span></a></li>
            <li><a href="about.php" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">About us <span class="arrow">&#8250;</span></a></li>
            <li><a href="search.php" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Products <span class="arrow">&#8250;</span></a></li>
            <li><a href="#" style="color: #23272b; text-decoration: none; font-weight: 600; margin-right: 28px; font-size: 1.05rem;">Contact Us <span class="arrow">&#8250;</span></a></li>
        </ul>
        <a href="#get-menu" class="navbar-btn" style="background: #97c933; color: #fff; padding: 8px 22px; border-radius: 6px; text-decoration: none; font-weight: 700; margin-left: 24px;">Get Menu</a>
    </nav>
    <h1 class="fastfood-header">Top Nutritional & Healthy Dishes</h1>
    <div class="fastfood-grid">
        <?php foreach ($dishes as $dish): ?>
            <div class="fastfood-card" style="display:grid;grid-template-rows:160px 1fr auto auto;min-height:370px;">
                <img class="fastfood-img" src="<?php echo htmlspecialchars($dish['image']); ?>" alt="<?php echo htmlspecialchars($dish['name']); ?>">
                <div class="fastfood-title" style="font-size:1.18rem;font-weight:700;color:#23272b;margin:16px 0 8px 0;">
                    <?php echo htmlspecialchars($dish['name']); ?>
                </div>
                <div class="fastfood-desc" style="font-size:0.98rem;color:#444;margin-bottom:24px;">
                    <?php echo htmlspecialchars($dish['description']); ?>
                </div>
                <a href="dishdetails2.php?dish=<?php echo urlencode($dish['name']); ?>" class="fastfood-view-btn" style="margin-top:auto;margin-bottom:0;align-self:center;display:inline-block;padding:7px 18px;background:#97c933;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;transition:background 0.2s;">View</a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="main.php" style="display:block;text-align:center;margin:40px 0 20px 0;color:#97c933;font-weight:700;text-decoration:none;font-size:1.1rem;">&larr; Back to Home</a>
    <button id="backToTopBtn" title="Go to top" class="back-to-top-btn" aria-label="Back to top">
        <img src="https://cdn-icons-png.flaticon.com/128/664/664866.png" alt="Up Arrow" style="width: 60%; height: 60%; display: block; margin: auto; transform: rotate(-90deg);" />
    </button>
    <script>
const backToTopBtn = document.getElementById('backToTopBtn');
window.addEventListener('scroll', function() {
    if (window.scrollY > 200) {
        backToTopBtn.classList.add('show');
    } else {
        backToTopBtn.classList.remove('show');
    }
});
backToTopBtn.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
    </script>
</body>
</html> 