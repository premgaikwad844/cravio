-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2025 at 05:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_db12`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `mobile`, `password`, `created_at`) VALUES
(5, 'prem', 'premgaikwadgg@gmail.com', '8261989219', '$2y$10$xr69zr/QLJVBm5Cp210jl.4UKV0MyrQInnXinvV24b5GbGs0ZSsgW', '2025-06-29 02:02:10'),
(6, 'admin1', 'admin1@admin.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-29 02:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `price` varchar(50) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `dietary_preference` varchar(100) DEFAULT NULL,
  `cuisine_type` varchar(100) NOT NULL,
  `meal_type` varchar(100) NOT NULL,
  `spice_level` varchar(50) NOT NULL,
  `price_range` varchar(50) NOT NULL,
  `popularity` varchar(100) NOT NULL,
  `cooking_style` varchar(100) NOT NULL,
  `image_path` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` varchar(100) DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `food_name`, `type`, `price`, `image_url`, `city`, `state`, `description`, `dietary_preference`, `cuisine_type`, `meal_type`, `spice_level`, `price_range`, `popularity`, `cooking_style`, `image_path`, `created_at`, `added_by`) VALUES
(0, 'pani puri', 'South Indian', 'Budget', 'uploads/685d49b127189_oakton-3564312-dissolved-oxygen-meter-with-probe-3564312.jpg', 'Anantnag', 'Jammu and Kashmir', NULL, 'Lacto-ovo vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'South Indian', 'Budget', 'uploads/685d4a1935eb6_environmental-express-3561325-portable-ph-meter-with-ph-and-temperature-probes-3561325.jpg', 'Chamba', 'Himachal Pradesh', NULL, 'Lacto-ovo vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pre', 'North Indian', 'Premium', 'uploads/685d4a36c9e53_oakton-3564312-dissolved-oxygen-meter-with-probe-3564312.jpg', 'Vijayawada', 'Andhra Pradesh', NULL, 'Vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Budget', 'uploads/685d4ae299915_oakton-3563430-phtestr-30-waterproof-pocket-tester-3563430.jpg', 'Chamba', 'Himachal Pradesh', NULL, '', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Premium', 'uploads/685d4ba66b92e_oakton-3563430-phtestr-30-waterproof-pocket-tester-3563430.jpg', 'Rajmahal', 'Jharkhand', NULL, 'Vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Premium', 'uploads/685d4c0d8b3d3_oakton-3563434-waterproof-multiparameter-pocket-tester-3563434.jpg', 'Rajmahal', 'Jharkhand', NULL, 'Lacto-vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Premium', 'uploads/685d4c5e19cb1_oakton-3563434-waterproof-multiparameter-pocket-tester-3563434.jpg', 'Rajmahal', 'Jharkhand', NULL, 'Lacto-ovo vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Premium', 'uploads/685d4caf8480b_oakton-3563416-waterproof-ph-pocket-tester-3563416.jpg', 'Bilaspur', 'Himachal Pradesh', NULL, 'Vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pani puri', 'North Indian', 'Premium', 'uploads/685d4ff414613_oakton-3563416-waterproof-ph-pocket-tester-3563416.jpg', 'Bilaspur', 'Himachal Pradesh', NULL, 'Vegetarian', '', '', '', '', '', '', NULL, '2025-06-29 01:53:15', 'Unknown'),
(0, 'pre', '', '', '', 'Port Blair', 'Andaman and Nicobar Islands', NULL, 'Vegetarian', 'Thai', 'Breakfast', 'Mild', 'Budget', 'Most Popular', 'Grilled', 'uploads/1751162017_0ff5dde0-0cec-45f1-a45c-1fff08a5f082.jpeg', '2025-06-29 01:53:37', 'Unknown'),
(0, 'pre', '', '', '', 'Port Blair', 'Andaman and Nicobar Islands', NULL, 'Paleo', 'Fast Food', 'Breakfast', 'Mild', 'Budget', 'Highly Rated', 'Grilled', 'uploads/1751162360_1bcff800-22be-4fe8-9889-d29a9dc91456.jpg', '2025-06-29 01:59:20', 'Unknown'),
(0, 'puri', '', '', '', 'Port Blair', 'Andaman and Nicobar Islands', NULL, 'Gluten-Free', 'Continental', 'Dinner', 'Hot', 'Budget', 'Most Popular', 'Fried', 'uploads/1751163390_0ff5dde0-0cec-45f1-a45c-1fff08a5f082.jpeg', '2025-06-29 02:16:30', 'Unknown'),
(0, 'pre', '', '', '', 'Itanagar', 'Arunachal Pradesh', NULL, 'Vegetarian', 'Continental', 'Breakfast', 'Hot', 'Budget', 'Most Popular', 'Steamed', 'uploads/1751164398_1bcff800-22be-4fe8-9889-d29a9dc91456.jpg', '2025-06-29 02:33:18', 'Unknown'),
(0, 'pre', '', '', '', 'Jharia', 'Jharkhand', NULL, 'Vegan', 'Fast Food', 'Lunch', 'Medium', 'Budget', 'Most Popular', 'Grilled', 'uploads/1751166186_0ff5dde0-0cec-45f1-a45c-1fff08a5f082.jpeg', '2025-06-29 03:03:06', 'Unknown'),
(0, 'pre', '', '', '', 'Bokaro', 'Jharkhand', NULL, 'Gluten-Free', 'Chinese', 'Snacks', 'Hot', 'Budget', 'Most Popular', 'Grilled', 'uploads/1751166583_0ff5dde0-0cec-45f1-a45c-1fff08a5f082.jpeg', '2025-06-29 03:09:43', 'Cravio'),
(0, 'tanuja gavade', '', '', '', 'Port Blair', 'Andaman and Nicobar Islands', NULL, 'Gluten-Free', 'Continental', 'Dinner', 'Mild', 'Budget', 'Most Popular', 'Steamed', 'uploads/1751166696_0ff5dde0-0cec-45f1-a45c-1fff08a5f082.jpeg', '2025-06-29 03:11:36', 'prem');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `mobile`, `password`, `created_at`) VALUES
(1, 'sakshi gaikwad', 'sakshigaikwadgg@gmail.com', '0987654321', '$2y$10$gn2UCDphjtl8eC4ViR1tiO1p2Awl6cojt.q0EP1RRU8qBoNCek5mq', '2025-06-13 08:00:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
