<?php
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

// Pagination setup
$items_per_page = 16;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Fetch data from MySQL with LIMIT
$sql = "SELECT id, name, type, price, image_url, city, description, dietary_preference FROM food_items LIMIT $offset, $items_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Indian Food Explorer</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Items</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 16px;
        }
        h1 {
            text-align: center;
            color: #c0392b;
            margin-bottom: 36px;
            font-size: 2.5em;
            letter-spacing: 1px;
        }
        .food-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 28px;
            margin: 0 auto;
            width: 100%;
        }
        .food-card {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(44,62,80,0.10);
            padding: 0 0 18px 0;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.3s, transform 0.3s;
            position: relative;
        }
        .food-card:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 32px rgba(192,57,43,0.18);
            z-index: 2;
        }
        .img-container {
            position: relative;
            overflow: hidden;
            border-radius: 1.2rem 1.2rem 0 0;
            height: 160px;
            background: #fbeee0;
        }
        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s cubic-bezier(.4,1.5,.5,1);
            display: block;
        }
        .food-card:hover .img-container img {
            transform: scale(1.08);
        }
        .type-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: #f1c40f;
            color: #fff;
            font-size: 0.85em;
            font-weight: bold;
            padding: 6px 16px;
            border-radius: 999px;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            z-index: 2;
            letter-spacing: 1px;
        }
        .food-title {
            font-size: 1.3em;
            font-weight: 700;
            color: #b71c1c;
            margin: 18px 0 0.2em 0;
            padding: 0 18px;
        }
        .food-desc {
            color: #555;
            font-size: 1em;
            margin-bottom: 0.5em;
            padding: 0 18px;
        }
        .food-meta {
            color: #888;
            font-size: 0.97em;
            margin-bottom: 0.5em;
            padding: 0 18px;
        }
        .food-meta span {
            margin-right: 10px;
        }
        .rating {
            color: #f39c12;
            margin-bottom: 0.7em;
            padding: 0 18px;
        }
        .view-details {
            display: block;
            width: calc(100% - 36px);
            margin: 0 18px;
            background: #c0392b;
            color: #fff;
            padding: 0.7em 0;
            border-radius: 0.7em;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background 0.3s;
            box-shadow: 0 2px 8px rgba(192, 57, 43, 0.08);
        }
        .view-details:hover {
            background: #a93226;
        }
        @media (max-width: 900px) {
            .food-list {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .food-list {
                grid-template-columns: 1fr;
            }
            .img-container {
                height: 120px;
            }
            .food-title, .food-desc, .food-meta, .rating, .view-details {
                padding-left: 10px;
                padding-right: 10px;
                margin-left: 0;
                margin-right: 0;
            }
        }
    </style>
  <style>
    @keyframes scrollHorizontal {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .scrolling-horizontal {
      display: flex;
      animation: scrollHorizontal 20s linear infinite;
    }
    .scroll-wrapper {
      overflow-x: auto;
      white-space: nowrap;
      -webkit-overflow-scrolling: touch;
    }
    .scroll-wrapper::-webkit-scrollbar {
      display: none;
    }
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-rose-100 via-yellow-100 to-pink-200 font-sans">

   <!-- Header Start -->
<header style="
  background: linear-gradient(135deg, #ff9a9e 0%, #ffb347 50%, #ffb347 51%, #ffd1ff 100%);
  color: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(255, 105, 180, 0.5);
  border: 4px solid;
  border-image-slice: 1;
  border-width: 4px;
  border-image-source: linear-gradient(90deg, #1e90ff, #ff1493, #ff00ff, #4b0082, #8a2be2);
  border-radius: 12px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
">

  <div class="company-name" style="font-size: 1.8rem; font-weight: 900; font-family: 'Comic Sans MS', cursive, sans-serif; letter-spacing: 2px;">
    FlavourFind
  </div>

  <div class="header-controls" style="display: flex; align-items: center;">
    <!-- Menu Dropdown -->
    <div class="menu-dropdown" style="position: relative; display: inline-block; margin-left: 1rem;">
      <button id="menuBtn" style="
        background: rgba(255, 255, 255, 0.25);
        border: none;
        border-radius: 8px;
        color: black;
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
        transition: background 0.3s;
        font-weight: 600;
        box-shadow: 0 0 8px rgba(255, 105, 180, 0.7);
      ">
        ☰ Menu
      </button>
      <div id="menuContent" style="
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(255, 105, 180, 0.4);
        border-radius: 12px;
        overflow: hidden;
        z-index: 2000;
        color: black;
        font-weight: 600;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      ">
        <a href="#" style="display: block; padding: 12px 18px; text-decoration: none; color: black; border-bottom: 1px solid #f0f0f0;">Home</a>
        <a href="#" style="display: block; padding: 12px 18px; text-decoration: none; color: black; border-bottom: 1px solid #f0f0f0;">About</a>
        <a href="#" style="display: block; padding: 12px 18px; text-decoration: none; color: black; border-bottom: 1px solid #f0f0f0;">Products</a>
        <a href="#" style="display: block; padding: 12px 18px; text-decoration: none; color: black; border-bottom: 1px solid #f0f0f0;">Contact Us</a>
      </div>
    </div>
  </div>
</header>

<style>
  /* Optional hover effect for menu items */
  #menuContent a:hover {
    background-color: #f0f0f0;
  }
</style>

<script>
  // JavaScript for toggling menu visibility on button click
  document.getElementById('menuBtn').addEventListener('click', function() {
    const menu = document.getElementById('menuContent');
    if (menu.style.display === 'block') {
      menu.style.display = 'none';
    } else {
      menu.style.display = 'block';
    }
  });

  // Optional: Close menu if click outside
  document.addEventListener('click', function(event) {
    const menu = document.getElementById('menuContent');
    const button = document.getElementById('menuBtn');
    if (!button.contains(event.target) && !menu.contains(event.target)) {
      menu.style.display = 'none';
    }
  });
</script>
   
   <!-- Header with Filters -->
<header class="sticky top-0 bg-white shadow z-50 p-4">
  <div class="flex flex-col md:flex-row justify-between items-center gap-4">

    <!-- Search Bar with Results -->
    <div class="w-full md:w-1/2 relative">
    <input
      type="text"
        placeholder="🔍 Search for dishes..."
      id="searchInput"
        class="w-full px-4 py-2 border rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-pink-400 text-gray-700 font-medium bg-gradient-to-r from-pink-100 via-purple-100 to-blue-100 placeholder-gray-500"
      />
      <!-- Search Results Dropdown -->
      <div id="searchResults" class="absolute w-full mt-2 bg-white rounded-lg shadow-lg hidden z-50">
        <div id="resultsList" class="max-h-96 overflow-y-auto">
          <!-- Results will be inserted here -->
        </div>
      </div>
    </div>

    <!-- New Filters -->
    <div id="filters" class="hidden md:flex flex-wrap gap-4 mt-2 md:mt-0 items-center transition-all duration-300">
      <select id="category" class="px-3 py-2 border rounded-lg shadow-sm bg-white text-gray-700">
        <option value="">Category</option>
        <option value="Street Food">Street Food</option>
        <option value="Main Course">Main Course</option>
        <option value="Desserts">Desserts</option>
        <option value="Seafood">Seafood</option>
        <option value="Beverages">Beverages</option>
      </select>

      <select id="type" class="px-3 py-2 border rounded-lg shadow-sm bg-white text-gray-700">
        <option value="">Type</option>
        <option value="Veg">Vegetarian</option>
        <option value="Non-Veg">Non-Vegetarian</option>
      </select>

      <select id="price" class="px-3 py-2 border rounded-lg shadow-sm bg-white text-gray-700">
        <option value="">Price Range</option>
        <option value="₹">Budget (₹)</option>
        <option value="₹₹">Moderate (₹₹)</option>
        <option value="₹₹₹">Premium (₹₹₹)</option>
      </select>

      <select id="rating" class="px-3 py-2 border rounded-lg shadow-sm bg-white text-gray-700">
        <option value="">Rating</option>
        <option value="5">5 Stars</option>
        <option value="4">4+ Stars</option>
        <option value="3">3+ Stars</option>
      </select>

      <select id="dietary_preference" name="dietary_preference">
          <option value="">All Dietary Options</option>
          <!-- Options will be filled by JS -->
      </select>
    </div>
  </div>
</header>

<script>
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');
  const resultsList = document.getElementById('resultsList');
  const filters = document.getElementById('filters');

  // Show filters on input click
  searchInput.addEventListener('click', () => {
    filters.classList.remove('hidden');
  });

  // Function to create search result item
  function createSearchResultItem(dish) {
    return `
      <a href="dish-details.html?dish=${encodeURIComponent(dish.name)}" 
         class="block p-3 hover:bg-gray-100 border-b last:border-b-0 transition-colors duration-200">
        <div class="flex items-center gap-3">
          <img src="${dish.image}" alt="${dish.name}" class="w-12 h-12 object-cover rounded-lg">
          <div>
            <h4 class="font-semibold text-gray-800">${dish.name}</h4>
            <p class="text-sm text-gray-600">${dish.description.substring(0, 50)}...</p>
          </div>
        </div>
      </a>
    `;
  }

  // Function to search dishes
  function searchDishes() {
    const searchQuery = searchInput.value.toLowerCase();
    const category = document.getElementById('category').value;
    const type = document.getElementById('type').value;
    const price = document.getElementById('price').value;
    const rating = document.getElementById('rating').value;
    
    // Get all dishes
    let filteredDishes = window.dishUtils.getAllDishes();
    
    // Apply search filter
    if (searchQuery.length >= 2) {
      filteredDishes = filteredDishes.filter(dish => 
        dish.name.toLowerCase().includes(searchQuery) ||
        dish.description.toLowerCase().includes(searchQuery) ||
        dish.ingredients.some(ing => ing.toLowerCase().includes(searchQuery))
      );
    }

    // Apply category filter
    if (category) {
      filteredDishes = filteredDishes.filter(dish => dish.category === category);
    }

    // Apply type filter
    if (type) {
      filteredDishes = filteredDishes.filter(dish => dish.type === type);
    }

    // Apply price filter
    if (price) {
      filteredDishes = filteredDishes.filter(dish => dish.price === price);
    }

    // Apply rating filter
    if (rating) {
      filteredDishes = filteredDishes.filter(dish => dish.rating >= parseInt(rating));
    }

    // Show results
    if (filteredDishes.length > 0) {
      resultsList.innerHTML = filteredDishes.map(createSearchResultItem).join('');
      searchResults.classList.remove('hidden');
    } else {
      resultsList.innerHTML = `
        <div class="p-3 text-gray-600">
          No dishes found matching your criteria
        </div>
      `;
      searchResults.classList.remove('hidden');
    }
  }

  // Function to show dish details
  function showDishDetails(dishName) {
    const dish = window.dishUtils.getDishByName(dishName);
    if (!dish) return;

    // Create modal HTML
    const modalHTML = `
      <div id="dishModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/2">
              <img src="${dish.image}" alt="${dish.name}" class="w-full h-96 object-cover rounded-2xl shadow-lg">
            </div>
            <div class="md:w-1/2">
              <h1 class="text-4xl font-bold text-red-700 mb-4">${dish.name}</h1>
              <p class="text-gray-600 text-lg mb-4">${dish.description}</p>
              <div class="flex items-center gap-4 mb-4">
                <span class="text-yellow-400 text-2xl">${'★'.repeat(dish.rating)}${'☆'.repeat(5-dish.rating)}</span>
                <span class="text-gray-600">(${dish.rating}/5)</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Price Range</p>
                  <p class="font-semibold">${dish.price}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Type</p>
                  <p class="font-semibold">${dish.type}</p>
                </div>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Ingredients</h2>
                <ul class="list-disc list-inside text-gray-600">
                  ${dish.ingredients.map(ing => `<li>${ing}</li>`).join('')}
                </ul>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Preparation</h2>
                <p class="text-gray-600">${dish.preparation}</p>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Origin</p>
                  <p class="font-semibold">${dish.origin}</p>
                </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Calories</p>
                  <p class="font-semibold">${dish.calories}</p>
      </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Best Time</p>
                  <p class="font-semibold">${dish.bestTime}</p>
        </div>
                <div class="bg-gray-100 p-3 rounded-lg">
                  <p class="text-gray-600">Popular Places</p>
                  <p class="font-semibold">${dish.popularPlaces.join(', ')}</p>
      </div>
        </div>
      </div>
        </div>
          <div class="mt-8 text-center">
            <button onclick="closeDishModal()" class="bg-red-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-700 transition duration-300">Close</button>
      </div>
        </div>
      </div>
    `;

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
  }

  // Function to close modal
  function closeDishModal() {
    const modal = document.getElementById('dishModal');
    if (modal) {
      modal.remove();
    }
  }

  // Add event listeners
  searchInput.addEventListener('input', searchDishes);
  
  // Add event listeners for filters
  document.getElementById('category').addEventListener('change', searchDishes);
  document.getElementById('type').addEventListener('change', searchDishes);
  document.getElementById('price').addEventListener('change', searchDishes);
  document.getElementById('rating').addEventListener('change', searchDishes);
  
  // Hide search results when clicking outside
  document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.classList.add('hidden');
    }
  });

  // Close modal when clicking outside
  document.addEventListener('click', function(event) {
    const modal = document.getElementById('dishModal');
    if (modal && event.target === modal) {
      closeDishModal();
    }
  });

  fetch('get_dietary_preferences.php')
    .then(response => response.json())
    .then(options => {
      const select = document.getElementById('dietary_preference');
      options.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt;
        option.textContent = opt;
        select.appendChild(option);
      });
    });
</script>

 



  

   <!-- Dish Showcase Section -->
    <section class="dish-showcase">
        <div class="dish-slider">
            <div class="dish-slide active">
                <div class="dish-content">
                    <div class="dish-image">
                        <img src="https://th.bing.com/th/id/OIP.6e7Vqkbgn6shoQxuQ1zUBQHaEK?rs=1&pid=ImgDetMain" alt="Hyderabadi Biryani">
                    </div>
                    <div class="dish-info">
                        <h2>Hyderabadi Biryani</h2>
                        <p class="description">A royal dish from the kitchens of the Nizams, featuring fragrant basmati rice, tender meat, and a blend of aromatic spices.</p>
                        <div class="dish-details">
                            <span class="spice-level">🌶️ Spice Level: Medium</span>
                            <span class="region">📍 Origin: Hyderabad</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dish-slide">
                <div class="dish-content">
                    <div class="dish-image">
                        <img src="https://wallpapercave.com/wp/wp7845825.jpg" alt="Masala Dosa">
                    </div>
                    <div class="dish-info">
                        <h2>Masala Dosa</h2>
                        <p class="description">A crispy, golden crepe filled with spiced potato filling, served with sambar and coconut chutney.</p>
                        <div class="dish-details">
                            <span class="spice-level">🌶️ Spice Level: Mild</span>
                            <span class="region">📍 Origin: South India</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dish-slide">
                <div class="dish-content">
                    <div class="dish-image">
                        <img src="https://img.freepik.com/premium-photo/veg-thali-from-indian-cuisine-food-platter-consists-variety-veggiespaneer-dish-lentils-jeera-riceroti-sweet-dish-curd-onion-etc-selective-focus_726363-844.jpg?w=2000" alt="Rajasthani Thali">
                    </div>
                    <div class="dish-info">
                        <h2>Rajasthani Thali</h2>
                        <p class="description">A royal platter featuring an array of traditional Rajasthani delicacies, from dal baati to gatte ki sabzi.</p>
                        <div class="dish-details">
                            <span class="spice-level">🌶️ Spice Level: Medium-Hot</span>
                            <span class="region">📍 Origin: Rajasthan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-controls">
            <button class="prev-btn">❮</button>
            <div class="slider-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
            <button class="next-btn">❯</button>
        </div>
    </section>

    <style>
        /* Dish Showcase Styles */
        .dish-showcase {
            position: relative;
            width: 100%;
            height: 80vh;
            overflow: hidden;
            background: #f8f9fa;
        }

        .dish-slider {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .dish-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dish-slide.active {
            opacity: 1;
        }

        .dish-content {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .dish-image {
            flex: 1;
            height: 500px;
            overflow: hidden;
        }

        .dish-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .dish-image img:hover {
            transform: scale(1.05);
        }

        .dish-info {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .dish-info h2 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .dish-info .description {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .dish-details {
            display: flex;
            gap: 20px;
            font-size: 1rem;
            color: #555;
        }

        .slider-controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .prev-btn, .next-btn {
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .prev-btn:hover, .next-btn:hover {
            background: #f0f0f0;
            transform: scale(1.1);
        }

        .slider-dots {
            display: flex;
            gap: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: #333;
            transform: scale(1.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.dish-slide');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            let currentSlide = 0;
            let slideInterval;

            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                slides[index].classList.add('active');
                dots[index].classList.add('active');
                currentSlide = index;
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            // Auto slide
            function startAutoSlide() {
                slideInterval = setInterval(nextSlide, 5000);
            }

            function stopAutoSlide() {
                clearInterval(slideInterval);
            }

            // Event listeners
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            });

            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            });

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    stopAutoSlide();
                    showSlide(index);
                    startAutoSlide();
                });
            });

            // Start auto sliding
            startAutoSlide();

            // Pause on hover
            const slider = document.querySelector('.dish-slider');
            slider.addEventListener('mouseenter', stopAutoSlide);
            slider.addEventListener('mouseleave', startAutoSlide);
        });
    </script>

    <!-- Recommended Section with Gradient, Cards, Ratings & Search -->
<section class="px-6 py-12 bg-gradient-to-r from-pink-200 via-orange-200 to-yellow-200 min-h-screen flex flex-col items-center">

  <h2 class="text-4xl font-extrabold text-red-700 mb-10 drop-shadow-lg text-center">Recommended for Food Lovers ❤️</h2>

   

  <!-- Grid Container -->
  <div id="recommendedGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 w-full max-w-7xl">

    <!-- Card Template -->
    <article tabindex="0" data-name="Pav Bhaji" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.fusion6.com.au/wp-content/uploads/2021/04/Indian-Dishes-You-Need-to-Try.jpg" alt="Pav Bhaji" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Popular</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Pav Bhaji</h3>
      <p class="text-gray-700 mb-3">Veg</p>
      <!-- Star rating -->
      <div class="flex items-center mb-4" aria-label="4 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Pav%20Bhaji" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 2 -->
    <article tabindex="0" data-name="Idli Sambhar" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://wallpapercave.com/wp/wp7845825.jpg" alt="Idli Sambhar" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">New</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Idli Sambhar</h3>
      <p class="text-gray-700 mb-3">Veg</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Idli%20Sambhar" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 3 -->
    <article tabindex="0" data-name="Tandoori Chicken" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://i.pinimg.com/originals/e1/da/d5/e1dad5315972c8a9db86fb01d69c7ecb.jpg" alt="Tandoori Chicken" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Tandoori Chicken</h3>
      <p class="text-gray-700 mb-3">Non veg</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Tandoori%20Chicken" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 4 -->
    <article tabindex="0" data-name="Aloo Vada" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://th.bing.com/th/id/OIP.Ixly--rXloGj7XUrGGq_PwHaGe?w=1024&h=895&rs=1&pid=ImgDetMain" alt="Aloo Vada" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Snack</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Aloo Vada</h3>
      <p class="text-gray-700 mb-3">Maharashtra's crunchy street snack</p>
      <div class="flex items-center mb-4" aria-label="4 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Aloo%20Vada" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>
    
    <!-- Card 5 -->
    <article tabindex="0" data-name="Dahi Vada" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://th.bing.com/th/id/OIP.Ixly--rXloGj7XUrGGq_PwHaGe?w=1024&h=895&rs=1&pid=ImgDetMain" alt="Dahi Vada" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Snack</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Dahi Vada</h3>
      <p class="text-gray-700 mb-3">Maharashtra's sweet street snack</p>
      <div class="flex items-center mb-4" aria-label="4 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Dahi%20Vada" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 6 -->
    <article tabindex="0" data-name="Chole Bhature" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://th.bing.com/th/id/OIP.Ixly--rXloGj7XUrGGq_PwHaGe?w=1024&h=895&rs=1&pid=ImgDetMain" alt="Chole Bhature" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Snack</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Chole Bhature</h3>
      <p class="text-gray-700 mb-3">Famous for its spicyness</p>
      <div class="flex items-center mb-4" aria-label="4 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Chole%20Bhature" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 7 -->
    <article tabindex="0" data-name="Butter Chicken" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.licious.in/blog/wp-content/uploads/2020/10/butter-chicken-.jpg" alt="Butter Chicken" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Popular</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Butter Chicken</h3>
      <p class="text-gray-700 mb-3">Non-veg</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Butter%20Chicken" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 8 -->
    <article tabindex="0" data-name="Masala Dosa" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/masala-dosa-1.jpg" alt="Masala Dosa" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Veg</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Masala Dosa</h3>
      <p class="text-gray-700 mb-3">South Indian Special</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Masala%20Dosa" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 9 -->
    <article tabindex="0" data-name="Rogan Josh" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.licious.in/blog/wp-content/uploads/2020/10/Rogan-Josh.jpg" alt="Rogan Josh" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Kashmiri</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Rogan Josh</h3>
      <p class="text-gray-700 mb-3">Non-veg</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Rogan%20Josh" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 10 -->
    <article tabindex="0" data-name="Dhokla" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/dhokla-recipe-1.jpg" alt="Dhokla" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Gujarati</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Dhokla</h3>
      <p class="text-gray-700 mb-3">Veg</p>
      <div class="flex items-center mb-4" aria-label="4 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Dhokla" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 11 -->
    <article tabindex="0" data-name="Vada Pav" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/vada-pav-recipe-1.jpg" alt="Vada Pav" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Street Food</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Vada Pav</h3>
      <p class="text-gray-700 mb-3">Mumbai's Favorite</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Vada%20Pav" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 12 -->
    <article tabindex="0" data-name="Pani Puri" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/pani-puri-recipe-1.jpg" alt="Pani Puri" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Street Food</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Pani Puri</h3>
      <p class="text-gray-700 mb-3">Tangy & Spicy</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Pani%20Puri" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 13 -->
    <article tabindex="0" data-name="Biryani" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.licious.in/blog/wp-content/uploads/2020/10/Chicken-Biryani.jpg" alt="Biryani" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Popular</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Biryani</h3>
      <p class="text-gray-700 mb-3">Hyderabadi Special</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Biryani" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 14 -->
    <article tabindex="0" data-name="Gulab Jamun" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/gulab-jamun-recipe-1.jpg" alt="Gulab Jamun" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Dessert</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Gulab Jamun</h3>
      <p class="text-gray-700 mb-3">Sweet Delight</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Gulab%20Jamun" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 15 -->
    <article tabindex="0" data-name="Samosa" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/samosa-recipe-1.jpg" alt="Samosa" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Snack</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Samosa</h3>
      <p class="text-gray-700 mb-3">Crispy & Spicy</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Samosa" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 16 -->
    <article tabindex="0" data-name="Rasgulla" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/rasgulla-recipe-1.jpg" alt="Rasgulla" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Dessert</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Rasgulla</h3>
      <p class="text-gray-700 mb-3">Bengali Sweet</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Rasgulla" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 17 -->
    <article tabindex="0" data-name="Kheer" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/kheer-recipe-1.jpg" alt="Kheer" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Dessert</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Kheer</h3>
      <p class="text-gray-700 mb-3">Rice Pudding</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Kheer" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 18 -->
    <article tabindex="0" data-name="Jalebi" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/jalebi-recipe-1.jpg" alt="Jalebi" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Dessert</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Jalebi</h3>
      <p class="text-gray-700 mb-3">Crispy Sweet</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Jalebi" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 19 -->
    <article tabindex="0" data-name="Litti Chokha" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/litti-chokha-recipe-1.jpg" alt="Litti Chokha" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Bihari</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Litti Chokha</h3>
      <p class="text-gray-700 mb-3">Bihar's Pride</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Litti%20Chokha" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

    <!-- Card 20 -->
    <article tabindex="0" data-name="Momo" class="bg-white p-5 rounded-3xl shadow-xl transform transition duration-300 hover:scale-105 hover:shadow-2xl focus:scale-105 focus:shadow-2xl focus:outline-none" style="opacity: 0; transform: translateY(30px);">
      <div class="relative overflow-hidden rounded-xl h-48 mb-4">
        <img src="https://www.vegrecipesofindia.com/wp-content/uploads/2021/04/momo-recipe-1.jpg" alt="Momo" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110" />
        <span class="absolute top-3 left-3 bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">Street Food</span>
      </div>
      <h3 class="text-2xl font-semibold text-red-700 mb-1">Momo</h3>
      <p class="text-gray-700 mb-3">Tibetan Delight</p>
      <div class="flex items-center mb-4" aria-label="5 out of 5 stars rating">
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
        <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.487 6.91l6.561-.954L10 0l2.952 5.956 6.561.954-4.757 4.635 1.122 6.545z"/></svg>
      </div>
      <a href="dish-details.html?dish=Momo" target="_blank" class="block w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-4 focus:ring-red-400 text-center">View Details</a>
    </article>

  </div>
  
</section>

<script>
  // Filter cards by search input
  function filterRecommended(query) {
    const cards = document.querySelectorAll('#recommendedGrid > article');
    query = query.trim().toLowerCase();
    cards.forEach(card => {
      const name = card.getAttribute('data-name').toLowerCase();
      if(name.includes(query)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  }

  // Simple scroll reveal animation for cards
  function revealOnScroll() {
    const cards = document.querySelectorAll('#recommendedGrid > article');
    const triggerBottom = window.innerHeight * 0.85;

    cards.forEach(card => {
      const cardTop = card.getBoundingClientRect().top;
      if(cardTop < triggerBottom) {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
        card.style.transition = 'all 0.6s ease-out';
      }
    });
  }
  window.addEventListener('scroll', revealOnScroll);
  window.addEventListener('load', revealOnScroll);
</script>
<style>
  .hide-scrollbar {
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .scrolling-horizontal {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    cursor: grab;
  }
  .scrolling-horizontal:active {
    cursor: grabbing;
  }
</style>

 <style>
  #cardsContainer {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    padding: 10px;
  }

  .card {
    width: 200px;
    box-sizing: border-box;
  }

  @media (max-width: 600px) {
    .card {
      width: 90%; /* Cards take most of screen on small devices */
    }
  }
</style>

<!-- Add dishes.js script -->
<script src="dishes.js"></script>


<script>
  // Function to create dish card
  function createDishCard(dish) {
    return `
      <a href="dish-details.html?dish=${encodeURIComponent(dish.name)}" class="block">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
          <img src="${dish.image}" alt="${dish.name}" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">${dish.name}</h3>
            <p class="text-gray-600 mb-4">${dish.description}</p>
            <div class="flex justify-between items-center">
              <span class="text-yellow-400">${'★'.repeat(dish.rating)}${'☆'.repeat(5-dish.rating)}</span>
              <span class="text-gray-700 font-semibold">${dish.price}</span>
            </div>
          </div>
        </div>
      </a>
    `;
  }

  // Function to display dishes on main page
  function displayMainPageDishes() {
    const dishes = window.dishUtils.getDisplayDishes();
    const grid = document.getElementById('dishesGrid');
    if (grid) {
      grid.innerHTML = dishes.map(dish => createDishCard(dish)).join('');
    }
  }

  // Add event listeners
  searchInput.addEventListener('input', searchDishes);
  
  // Add event listeners for filters
  document.getElementById('category').addEventListener('change', searchDishes);
  document.getElementById('type').addEventListener('change', searchDishes);
  document.getElementById('price').addEventListener('change', searchDishes);
  document.getElementById('rating').addEventListener('change', searchDishes);
  
  // Display dishes on page load
  displayMainPageDishes();
</script>

 

<!-- Hotels and Lodging Section -->
<section class="hotels-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Hotels & Lodging Near You</h2>
        <div class="text-center mb-4">
            <p class="text-muted">Finding the best places to stay in your area...</p>
            <div class="spinner-border text-primary" role="status" id="hotels-loading">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="row" id="hotels-container">
            <!-- Hotels will be dynamically loaded here -->
        </div>
    </div>
</section>

 

<!-- Scripts -->
<script src="dishes.js"></script>
<script src="hotels.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dishes
        displayMainPageDishes();
        
        // Initialize hotels
        const hotelsLoading = document.getElementById('hotels-loading');
        if (hotelsLoading) {
            hotelsLoading.style.display = 'none';
        }
        initializeHotels().finally(() => {
            if (hotelsLoading) {
                hotelsLoading.style.display = 'none';
            }
        });
    });
</script>
<h1>Food Items</h1>
  <div class="food-list">
  <?php
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          echo '<article tabindex="0" data-name="'.htmlspecialchars($row["name"]).'" class="food-card">';
          echo '<div class="img-container">';
          echo '<img src="'.htmlspecialchars($row["image_url"]).'" alt="'.htmlspecialchars($row["name"]).'" />';
          echo '<span class="type-badge">'.htmlspecialchars($row["type"]).'</span>';
          echo '</div>';
          echo '<h3 class="food-title">'.htmlspecialchars($row["name"]).'</h3>';
          echo '<p class="food-desc">'.htmlspecialchars($row["description"]).'</p>';
          echo '<div class="food-meta">';
          echo '<span>'.htmlspecialchars($row["city"]).'</span>';
          echo '<span>';
          if (is_numeric($row["price"])) {
              echo '₹' . number_format($row["price"], 2);
          } else {
              echo htmlspecialchars($row["price"]);
          }
          echo '</span>';
          echo '</div>';
          echo '<div class="dietary-preference">Dietary Preference: ' . htmlspecialchars($row["dietary_preference"]) . '</div>';
          echo '<a href="dish-details.html?dish='.urlencode($row["name"]).'" target="_blank" class="view-details">View Details</a>';
          echo '</article>';
      }
  } else {
      echo "<p>No records found</p>";
  }
  $conn->close();
  ?>
  </div>

 

</body>
</html>
