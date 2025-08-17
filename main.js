// Carousel with slideshow (horizontal sliding) effect

document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const slides = document.querySelectorAll('.carousel-slide');
    const leftBtn = document.querySelector('.carousel-nav-left');
    const rightBtn = document.querySelector('.carousel-nav-right');
    let current = 0;
    let autoSlideInterval;

    function showSlide(idx) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === idx);
        });
        track.style.transform = `translateX(-${idx * 100}vw)`;
    }

    function nextSlide() {
        current = (current + 1) % slides.length;
        showSlide(current);
    }

    function prevSlide() {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
    }

    leftBtn.addEventListener('click', function() {
        prevSlide();
        resetAutoSlide();
    });
    rightBtn.addEventListener('click', function() {
        nextSlide();
        resetAutoSlide();
    });

    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Initialize
    showSlide(current);
    startAutoSlide();

    // Mobile sidebar logic
    const showSidebarBtn = document.getElementById('showSidebarBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

    function handleSidebarDisplay() {
        if (window.innerWidth <= 900) {
            showSidebarBtn.style.display = 'flex';
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            sidebarCloseBtn.style.display = 'block';
        } else {
            showSidebarBtn.style.display = 'none';
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            sidebarCloseBtn.style.display = 'none';
        }
    }

    showSidebarBtn && showSidebarBtn.addEventListener('click', function() {
        sidebar.classList.add('open');
        sidebarOverlay.classList.add('active');
    });
    sidebarCloseBtn && sidebarCloseBtn.addEventListener('click', function() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
    });
    sidebarOverlay && sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('open');
        sidebarOverlay.classList.remove('active');
    });
    window.addEventListener('resize', handleSidebarDisplay);
    handleSidebarDisplay();

    const filtersBtn = document.getElementById('filtersToggleBtn');
    const filtersSidebar = document.getElementById('filtersSidebar');
    const filtersOverlay = document.getElementById('filtersOverlay');
    const filtersCloseBtn = document.getElementById('filtersCloseBtn');

    function openSidebar() {
        filtersSidebar.classList.add('open');
        filtersOverlay.classList.add('active');
        filtersSidebar.setAttribute('aria-hidden', 'false');
        filtersBtn.setAttribute('aria-expanded', 'true');
        filtersCloseBtn.focus();
    }
    function closeSidebar() {
        filtersSidebar.classList.remove('open');
        filtersOverlay.classList.remove('active');
        filtersSidebar.setAttribute('aria-hidden', 'true');
        filtersBtn.setAttribute('aria-expanded', 'false');
        filtersBtn.focus();
    }

    if (filtersBtn && filtersSidebar && filtersOverlay && filtersCloseBtn) {
        filtersBtn.addEventListener('click', openSidebar);
        filtersCloseBtn.addEventListener('click', closeSidebar);
        filtersOverlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && filtersSidebar.classList.contains('open')) {
                closeSidebar();
            }
        });
    }
});

// Mobile menu toggle
const hamburger = document.querySelector('.navbar-hamburger');
const mobileMenu = document.querySelector('.navbar-mobile-menu');
if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function() {
        mobileMenu.classList.toggle('active');
        hamburger.classList.toggle('open');
    });
}

// Back to Top button functionality
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

// Menu carousel horizontal scroll with seamless infinite looping
const menuTrack = document.querySelector('.menu-carousel-track');
const menuLeft = document.querySelector('.menu-carousel-arrow.left');
const menuRight = document.querySelector('.menu-carousel-arrow.right');
let menuCards = Array.from(document.querySelectorAll('.menu-card'));
let menuCurrent = 0;
let isTransitioning = false;

function isMobileMenuCarousel() {
    return window.innerWidth <= 900;
}

function scrollToMenuCard(idx, instant = false) {
    if (!menuTrack || !menuCards[idx]) return;
    menuCards[idx].scrollIntoView({ behavior: instant ? 'auto' : 'smooth', inline: 'center', block: 'nearest' });
}

function setupInfiniteMenuCarousel() {
    if (!menuTrack || menuCards.length < 2) return;
    // Remove any previous clones
    menuTrack.querySelectorAll('.menu-card.clone').forEach(el => el.remove());
    // Clone first and last
    const firstClone = menuCards[0].cloneNode(true);
    const lastClone = menuCards[menuCards.length - 1].cloneNode(true);
    firstClone.classList.add('clone');
    lastClone.classList.add('clone');
    menuTrack.appendChild(firstClone);
    menuTrack.insertBefore(lastClone, menuCards[0]);
    // Update menuCards NodeList
    menuCards = Array.from(menuTrack.querySelectorAll('.menu-card'));
    // Set initial scroll position to first real card
    menuCurrent = 1;
    scrollToMenuCard(menuCurrent, true);
}

if (menuTrack && menuLeft && menuRight && menuCards.length) {
    setupInfiniteMenuCarousel();
    function updateMenuCarousel() {
        setupInfiniteMenuCarousel();
        scrollToMenuCard(menuCurrent, true);
    }
    function handleTransitionEnd() {
        if (menuCurrent === 0) {
            // Jump instantly to last real card
            menuCurrent = menuCards.length - 2;
            scrollToMenuCard(menuCurrent, true);
        } else if (menuCurrent === menuCards.length - 1) {
            // Jump instantly to first real card
            menuCurrent = 1;
            scrollToMenuCard(menuCurrent, true);
        }
        isTransitioning = false;
    }
    menuLeft.addEventListener('click', function() {
        if (isTransitioning) return;
        isTransitioning = true;
        menuCurrent--;
        scrollToMenuCard(menuCurrent);
        setTimeout(handleTransitionEnd, 400);
    });
    menuRight.addEventListener('click', function() {
        if (isTransitioning) return;
        isTransitioning = true;
        menuCurrent++;
        scrollToMenuCard(menuCurrent);
        setTimeout(handleTransitionEnd, 400);
    });
    window.addEventListener('resize', updateMenuCarousel);
    scrollToMenuCard(menuCurrent, true);
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const searchResults = document.getElementById('searchResults');
    const resultsContainer = document.getElementById('resultsContainer');
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');
    const cuisineFilter = document.getElementById('cuisineFilter');
    const dietFilter = document.getElementById('dietFilter');
    const calorieFilter = document.getElementById('calorieFilter');
    const allergyFilter = document.getElementById('allergyFilter');
    const stateFilter = document.getElementById('stateFilter');
    const cityFilter = document.getElementById('cityFilter');
    const mobileStateFilter = document.getElementById('mobileStateFilter');
    const mobileCityFilter = document.getElementById('mobileCityFilter');

    // Sample food data (in a real app, this would come from an API)
    const foodItems = [
        {
            name: "Spaghetti Carbonara",
            cuisine: "italian",
            diet: "high-protein",
            calories: 450,
            allergies: ["eggs", "dairy"],
            image: "https://images.unsplash.com/photo-1621996346565-e3dbc353d2e5?auto=format&fit=crop&w=400&q=80",
            description: "Classic Italian pasta with eggs, cheese, and pancetta"
        },
        {
            name: "Grilled Chicken Salad",
            cuisine: "american",
            diet: "high-protein",
            calories: 320,
            allergies: [],
            image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=400&q=80",
            description: "Fresh mixed greens with grilled chicken breast"
        },
        {
            name: "Vegetable Curry",
            cuisine: "indian",
            diet: "vegetarian",
            calories: 280,
            allergies: ["nuts"],
            image: "https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?auto=format&fit=crop&w=400&q=80",
            description: "Spicy vegetable curry with aromatic spices"
        },
        {
            name: "Sushi Roll",
            cuisine: "japanese",
            diet: "high-protein",
            calories: 220,
            allergies: ["shellfish", "soy"],
            image: "https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?auto=format&fit=crop&w=400&q=80",
            description: "Fresh salmon and avocado sushi roll"
        },
        {
            name: "Caesar Salad",
            cuisine: "mediterranean",
            diet: "vegetarian",
            calories: 180,
            allergies: ["dairy", "eggs"],
            image: "https://images.unsplash.com/photo-1546793665-c74683f339c1?auto=format&fit=crop&w=400&q=80",
            description: "Crisp romaine lettuce with Caesar dressing"
        },
        {
            name: "Chocolate Cake",
            cuisine: "american",
            diet: "vegetarian",
            calories: 380,
            allergies: ["eggs", "dairy"],
            image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=400&q=80",
            description: "Rich chocolate cake with creamy frosting"
        },
        {
            name: "Pad Thai",
            cuisine: "thai",
            diet: "vegetarian",
            calories: 420,
            allergies: ["nuts", "eggs"],
            image: "https://images.unsplash.com/photo-1559314809-0d155014e29e?auto=format&fit=crop&w=400&q=80",
            description: "Stir-fried rice noodles with vegetables and tofu"
        },
        {
            name: "Beef Tacos",
            cuisine: "mexican",
            diet: "high-protein",
            calories: 350,
            allergies: ["dairy"],
            image: "https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=400&q=80",
            description: "Seasoned ground beef in corn tortillas"
        },
        // --- DUMMY DISHES FOR TESTING ---
        {
            name: "Vegan Buddha Bowl",
            cuisine: "fusion",
            diet: "vegan",
            calories: 310,
            allergies: [],
            image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80",
            description: "A nourishing bowl with quinoa, chickpeas, and fresh veggies."
        },
        {
            name: "Paneer Tikka Masala",
            cuisine: "indian",
            diet: "vegetarian",
            calories: 400,
            allergies: ["dairy"],
            image: "https://images.unsplash.com/photo-1505250469679-203ad9ced0cb?auto=format&fit=crop&w=400&q=80",
            description: "Grilled paneer cubes in a creamy tomato sauce."
        },
        {
            name: "Egg Fried Rice",
            cuisine: "chinese",
            diet: "non-vegetarian",
            calories: 370,
            allergies: ["eggs", "soy"],
            image: "https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=400&q=80",
            description: "Classic fried rice with eggs and vegetables."
        },
        {
            name: "Keto Avocado Toast",
            cuisine: "continental",
            diet: "keto",
            calories: 290,
            allergies: ["eggs"],
            image: "https://images.unsplash.com/photo-1464306076886-debca5e8a6b0?auto=format&fit=crop&w=400&q=80",
            description: "Low-carb toast topped with smashed avocado and poached egg."
        },
        {
            name: "Falafel Wrap",
            cuisine: "mediterranean",
            diet: "vegan",
            calories: 330,
            allergies: ["gluten"],
            image: "https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80",
            description: "Crispy falafel balls wrapped with veggies and tahini sauce."
        },
        {
            name: "Fish and Chips",
            cuisine: "fast food",
            diet: "non-vegetarian",
            calories: 520,
            allergies: ["fish", "gluten"],
            image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80",
            description: "Golden fried fish fillets with crispy potato fries."
        },
        {
            name: "Street Style Momos",
            cuisine: "street food",
            diet: "non-vegetarian",
            calories: 260,
            allergies: ["gluten"],
            image: "https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80",
            description: "Steamed dumplings filled with spiced meat and veggies."
        },
        {
            name: "Gluten-Free Brownie",
            cuisine: "desserts",
            diet: "gluten-free",
            calories: 340,
            allergies: ["eggs", "nuts"],
            image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=400&q=80",
            description: "Rich chocolate brownie made with almond flour."
        }
    ];

    // Add priceRange to each food item for demo
    foodItems.forEach(function(item, idx) {
      if (!item.priceRange) {
        // Demo: alternate price ranges
        item.priceRange = idx % 3 === 0 ? 'Budget' : (idx % 3 === 1 ? 'Moderate' : 'Premium');
      }
    });

    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const cuisine = cuisineFilter.value;
        const diet = dietFilter.value;
        const calories = calorieFilter.value;
        const allergy = allergyFilter.value;
        const state = (stateFilter && stateFilter.value) || (mobileStateFilter && mobileStateFilter.value) || "";
        const city = (cityFilter && cityFilter.value) || (mobileCityFilter && mobileCityFilter.value) || "";

        // Always show all dishes if no search/filter is applied
        if (!searchTerm && !cuisine && !diet && !calories && !allergy && !state && !city) {
            displayResults(foodItems);
            return;
        }

        let filteredItems = foodItems.filter(item => {
            // Search term filter
            const matchesSearch = !searchTerm || 
                item.name.toLowerCase().includes(searchTerm) ||
                item.description.toLowerCase().includes(searchTerm);

            // Cuisine filter
            const matchesCuisine = !cuisine || item.cuisine === cuisine;

            // Diet filter
            const matchesDiet = !diet || item.diet === diet;

            // Calorie filter
            let matchesCalories = true;
            if (calories === 'low') {
                matchesCalories = item.calories <= 300;
            } else if (calories === 'medium') {
                matchesCalories = item.calories > 300 && item.calories <= 600;
            } else if (calories === 'high') {
                matchesCalories = item.calories > 600;
            }

            // Allergy filter
            const matchesAllergy = !allergy || !item.allergies.includes(allergy);

            // State filter (case-insensitive, fallback to true if not present)
            const matchesState = !state || (item.state && item.state.toLowerCase() === state.toLowerCase());
            // City filter (case-insensitive, fallback to true if not present)
            const matchesCity = !city || (item.city && item.city.toLowerCase() === city.toLowerCase());

            return matchesSearch && matchesCuisine && matchesDiet && matchesCalories && matchesAllergy && matchesState && matchesCity;
        });

        displayResults(filteredItems);
    }

    function displayResults(items) {
        if (items.length === 0) {
            resultsContainer.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                    <img src="https://cdn-icons-png.flaticon.com/128/3917/3917132.png" alt="No results" style="width: 60px; height: 60px; opacity: 0.5; margin-bottom: 15px;">
                    <h4 style="margin: 0 0 10px 0; color: #23272b;">No results found</h4>
                    <p style="margin: 0; font-size: 0.9rem;">Try adjusting your search terms or filters</p>
                </div>
            `;
        } else {
            resultsContainer.innerHTML = items.map(item => `
                <div class="food-item-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                    <img src="${item.image}" alt="${item.name}" style="width: 100%; height: 180px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h4 style="margin: 0 0 10px 0; color: #23272b; font-size: 1.1rem;">${item.name}</h4>
                        <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px;">
                            <span style="background: #e74c3c; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">${item.priceRange}</span>
                            <span style="background: #97c933; color: #fff; padding: 3px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize;">${item.diet.replace(/-/g, ' ')}</span>
                        </div>
                        <p style="margin: 0 0 15px 0; color: #666; font-size: 0.9rem; line-height: 1.4;">${item.description}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="background: #97c933; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">${item.calories} cal</span>
                            <div style="display: flex; gap: 5px;">
                                <span style="background: rgba(151, 201, 51, 0.1); color: #97c933; padding: 2px 6px; border-radius: 3px; font-size: 0.7rem;">${item.cuisine}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        // Always show resultsContainer
        resultsContainer.style.display = 'block';
        // Smooth scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Event listeners
    searchBtn.addEventListener('click', performSearch);
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Filter change events
    [cuisineFilter, dietFilter, calorieFilter, allergyFilter, stateFilter, cityFilter, mobileStateFilter, mobileCityFilter].forEach(filter => {
        if (filter) filter.addEventListener('change', performSearch);
    });

    // Suggestion buttons
    suggestionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const searchTerm = this.getAttribute('data-search');
            searchInput.value = searchTerm;
            performSearch();
        });
    });

    // Add hover effects to suggestion buttons
    suggestionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.background = '#97c933';
            this.style.color = 'white';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.background = 'rgba(151, 201, 51, 0.1)';
            this.style.color = '#97c933';
        });
    });

    // Add focus effects to search input
    searchInput.addEventListener('focus', function() {
        this.style.borderColor = '#97c933';
    });

    searchInput.addEventListener('blur', function() {
        this.style.borderColor = '#e0e0e0';
    });

    // Add hover effects to search button
    searchBtn.addEventListener('mouseenter', function() {
        this.style.background = '#7ba026';
    });

    searchBtn.addEventListener('mouseleave', function() {
        this.style.background = '#97c933';
    });
}); 

// On page load, show only dummy dishes for testing in search.html
window.addEventListener('DOMContentLoaded', function() {
  if (typeof displayResults === 'function' && Array.isArray(foodItems)) {
    // Only show dummy dishes (the last 8 in the array)
    const dummyDishes = foodItems.slice(-8);
    displayResults(dummyDishes);
  }
});

// Mobile sidebar logic for search & filters
(function() {
  const openBtn = document.getElementById('openMobileSidebar');
  const sidebar = document.getElementById('mobileSidebar');
  const overlay = document.getElementById('mobile-menu-overlay');
  const closeBtn = document.getElementById('closeMobileSidebar');

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (openBtn && sidebar && overlay && closeBtn) {
    openBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && sidebar.classList.contains('open')) {
        closeSidebar();
      }
    });
  }
})(); 

// Contact form validation and animation
window.addEventListener('DOMContentLoaded', function() {
  // Fade-in animation for cards and form
  document.querySelectorAll('.fade-in-card').forEach(function(el, i) {
    setTimeout(function() {
      el.style.opacity = 1;
      el.style.transform = 'translateY(0)';
    }, 200 + i * 120);
    el.style.opacity = 0;
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.7s, transform 0.7s';
  });

  var form = document.querySelector('form[aria-label="Contact form"]');
  if (form) {
    var name = form.querySelector('input[name="name"]');
    var email = form.querySelector('input[name="email"]');
    var message = form.querySelector('textarea[name="message"]');
    var success = form.querySelector('#form-success');

    function validateEmail(val) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    }

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      var valid = true;
      if (!name.value.trim()) {
        name.style.borderColor = '#e74c3c';
        valid = false;
      } else {
        name.style.borderColor = '#e0e0e0';
      }
      if (!validateEmail(email.value)) {
        email.style.borderColor = '#e74c3c';
        valid = false;
      } else {
        email.style.borderColor = '#e0e0e0';
      }
      if (!message.value.trim()) {
        message.style.borderColor = '#e74c3c';
        valid = false;
      } else {
        message.style.borderColor = '#e0e0e0';
      }
      if (valid) {
        success.style.display = 'block';
        success.setAttribute('aria-live', 'polite');
        form.reset();
        setTimeout(function() { success.style.display = 'none'; }, 3500);
      }
    });
    [name, email, message].forEach(function(input) {
      input.addEventListener('input', function() {
        this.style.borderColor = '#e0e0e0';
      });
    });
  }
}); 

// Modern search bar interactivity for search.html
window.addEventListener('DOMContentLoaded', function() {
  var searchInput = document.getElementById('searchInput');
  var clearBtn = document.getElementById('clearSearch');
  if (searchInput && clearBtn) {
    searchInput.addEventListener('input', function() {
      clearBtn.style.display = this.value ? 'block' : 'none';
    });
    clearBtn.addEventListener('click', function() {
      searchInput.value = '';
      clearBtn.style.display = 'none';
      searchInput.focus();
      // Optionally trigger search/filter logic here
    });
    searchInput.addEventListener('focus', function() {
      searchInput.style.borderColor = '#97c933';
    });
    searchInput.addEventListener('blur', function() {
      searchInput.style.borderColor = '#e0e0e0';
    });
  }
  // Mobile search bar (if present)
  var mobileSearchInput = document.getElementById('mobileSearchInput');
  if (mobileSearchInput) {
    mobileSearchInput.addEventListener('focus', function() {
      mobileSearchInput.style.borderColor = '#97c933';
    });
    mobileSearchInput.addEventListener('blur', function() {
      mobileSearchInput.style.borderColor = '#e0e0e0';
    });
  }
}); 

// Improved Reset Filters functionality for search.html
window.addEventListener('DOMContentLoaded', function() {
  var resetBtn = document.getElementById('resetFilters');
  var searchInput = document.getElementById('searchInput');
  var mobileSearchInput = document.getElementById('mobileSearchInput');
  var clearBtn = document.getElementById('clearSearch');
  var sidebar = document.querySelector('.sidebar.compact-filters');
  var mobileSidebar = document.getElementById('mobileSidebar');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      if (sidebar) {
        sidebar.querySelectorAll('select').forEach(function(sel) {
          sel.selectedIndex = 0;
        });
      }
      if (mobileSidebar) {
        mobileSidebar.querySelectorAll('select').forEach(function(sel) {
          sel.selectedIndex = 0;
        });
      }
      if (searchInput) searchInput.value = '';
      if (mobileSearchInput) mobileSearchInput.value = '';
      if (clearBtn) clearBtn.style.display = 'none';
      // Optionally trigger search/filter logic here
    });
  }
}); 