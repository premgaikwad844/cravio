-- Bihar Food Items Data
-- Only Begusarai, Bettiah, and Bhagalpur

USE `login_db12`;

INSERT INTO food_items (food_name, state, city, cuisine_type, meal_type, dietary_preference, spice_level, price_range, popularity, cooking_style, image_path) VALUES
-- Begusarai
('Litti Fish Curry', 'Bihar', 'Begusarai', 'Indian', 'Lunch', 'Non-Vegetarian', 'Hot', 'Moderate', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Litti+Fish+Curry'),
('Sattu Sharbat', 'Bihar', 'Begusarai', 'Indian', 'Breakfast', 'Vegan', 'Mild', 'Budget', 'Highly Rated', 'Raw', 'https://via.placeholder.com/150?text=Sattu+Sharbat'),
('Chicken Korma', 'Bihar', 'Begusarai', 'Indian', 'Dinner', 'Non-Vegetarian', 'Very Hot', 'Premium', 'Most Popular', 'Fried', 'https://via.placeholder.com/150?text=Chicken+Korma'),
('Aloo Paratha', 'Bihar', 'Begusarai', 'Indian', 'Breakfast', 'Vegetarian', 'Medium', 'Budget', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Aloo+Paratha'),
('Makhana Kheer', 'Bihar', 'Begusarai', 'Indian', 'Desserts', 'Vegetarian', 'Mild', 'Moderate', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Makhana+Kheer'),
('Bhindi Masala', 'Bihar', 'Begusarai', 'Indian', 'Lunch', 'Vegan', 'Medium', 'Budget', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Bhindi+Masala'),
('Chura Matar', 'Bihar', 'Begusarai', 'Indian', 'Snacks', 'Vegetarian', 'Mild', 'Budget', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Chura+Matar'),
('Paneer Bhurji', 'Bihar', 'Begusarai', 'Indian', 'Dinner', 'Vegetarian', 'Hot', 'Moderate', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Paneer+Bhurji'),
('Tilkut', 'Bihar', 'Begusarai', 'Desserts', 'Desserts', 'Vegetarian', 'Mild', 'Budget', 'Highly Rated', 'Raw', 'https://via.placeholder.com/150?text=Tilkut'),
('Chana Dal Tikki', 'Bihar', 'Begusarai', 'Indian', 'Snacks', 'Vegetarian', 'Medium', 'Budget', 'Most Popular', 'Fried', 'https://via.placeholder.com/150?text=Chana+Dal+Tikki'),

-- Bettiah
('Kathal Biryani', 'Bihar', 'Bettiah', 'Indian', 'Lunch', 'Vegan', 'Hot', 'Moderate', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Kathal+Biryani'),
('Gobi Manchurian', 'Bihar', 'Bettiah', 'Chinese', 'Snacks', 'Vegetarian', 'Hot', 'Moderate', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Gobi+Manchurian'),
('Kadhi Bari', 'Bihar', 'Bettiah', 'Indian', 'Dinner', 'Vegetarian', 'Medium', 'Budget', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Kadhi+Bari'),
('Moong Dal Chilla', 'Bihar', 'Bettiah', 'Indian', 'Breakfast', 'Vegan', 'Mild', 'Budget', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Moong+Dal+Chilla'),
('Chicken Tikka', 'Bihar', 'Bettiah', 'Indian', 'Dinner', 'Non-Vegetarian', 'Hot', 'Premium', 'Most Popular', 'Grilled', 'https://via.placeholder.com/150?text=Chicken+Tikka'),
('Aloo Matar Curry', 'Bihar', 'Bettiah', 'Indian', 'Lunch', 'Vegan', 'Medium', 'Budget', 'Highly Rated', 'Steamed', 'https://via.placeholder.com/150?text=Aloo+Matar+Curry'),
('Peda', 'Bihar', 'Bettiah', 'Desserts', 'Desserts', 'Vegetarian', 'Mild', 'Budget', 'Most Popular', 'Raw', 'https://via.placeholder.com/150?text=Peda'),
('Fish Fry', 'Bihar', 'Bettiah', 'Indian', 'Lunch', 'Non-Vegetarian', 'Hot', 'Moderate', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Fish+Fry'),
('Jalebi', 'Bihar', 'Bettiah', 'Desserts', 'Desserts', 'Vegetarian', 'Mild', 'Budget', 'Most Popular', 'Fried', 'https://via.placeholder.com/150?text=Jalebi'),
('Dhokla', 'Bihar', 'Bettiah', 'Indian', 'Snacks', 'Vegetarian', 'Mild', 'Budget', 'Highly Rated', 'Steamed', 'https://via.placeholder.com/150?text=Dhokla'),

-- Bhagalpur
('Silbatta Chutney', 'Bihar', 'Bhagalpur', 'Indian', 'Snacks', 'Vegan', 'Hot', 'Budget', 'Highly Rated', 'Raw', 'https://via.placeholder.com/150?text=Silbatta+Chutney'),
('Kharna', 'Bihar', 'Bhagalpur', 'Indian', 'Dinner', 'Non-Vegetarian', 'Medium', 'Moderate', 'Most Popular', 'Steamed', 'https://via.placeholder.com/150?text=Kharna'),
('Chilka Roti', 'Bihar', 'Bhagalpur', 'Indian', 'Breakfast', 'Vegetarian', 'Mild', 'Budget', 'Highly Rated', 'Fried', 'https://via.placeholder.com/150?text=Chilka+Roti'); 