-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2026 at 02:37 PM
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
-- Database: `ballz`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2a$12$C4cyI1eyuyKdv8V4nLnuBe0i09D12srq7IF.0Xs4blsJUBvwqh3ei');

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `name`, `description`) VALUES
(1, 'Savory', 'Savory bite-size balls'),
(2, 'Sweet', 'Sweet dessert balls'),
(6, 'Condiments', 'Extra Sauce'),
(7, 'Limited', 'Limited time menu items'),
(8, 'Drinks', 'Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `img_path` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `is_active`, `img_path`) VALUES
(1, 1, 'Classic Cheese Bomb', 'Crispy golden ball filled with molten mozzarella and cheddar, served with marinara sauce.', 8.90, 1, NULL),
(2, 1, 'Mac & Cheese Truffle Bites', 'Rich mac and cheese rolled into balls, panko-crusted, drizzled with truffle oil.', 10.90, 1, NULL),
(3, 1, 'Spicy Arancini', 'Risotto balls with fiery nduja sausage and provolone, served with roasted pepper aioli.', 9.90, 1, NULL),
(4, 1, 'Buffalo Chicken Poppers', 'Spicy shredded chicken and cream cheese balls, served with ranch dip.', 9.50, 1, NULL),
(5, 2, 'Nutella Delight', 'Warm brioche ball injected with rich Nutella, dusted with powdered sugar.', 7.90, 1, NULL),
(6, 2, 'Salted Caramel Crunch', 'Fried dough ball filled with salted caramel cream, topped with pretzel bits.', 8.50, 1, NULL),
(7, 2, 'Berry Bliss Bomboloni', 'Italian doughnut hole filled with fresh berry compote and mascarpone.', 8.90, 1, NULL),
(8, 2, 'Cinnamon Sugar Bites', 'Classic fluffy dough balls coated in cinnamon sugar, served with vanilla glaze.', 7.50, 1, NULL),
(10, 6, 'Hot Sauce', 'Spicy spicy sauce', 2.00, 1, NULL),
(11, 6, 'BBQ Sauce', 'Barbeque Saucesss', 2.00, 1, NULL),
(12, 7, 'Cheesy Volcano', 'Extra cheese burst with spicy filling', 11.90, 1, NULL),
(13, 7, 'Korean Fire Ball', 'Korean-style spicy chicken ball', 12.50, 1, NULL),
(14, 8, 'Iced Lemon Tea', 'Refreshing iced lemon tea', 4.50, 1, NULL),
(15, 8, 'Chocolate Milk', 'Cold chocolate milk drink', 5.00, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `outlet_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('pickup','dine_in') NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_vouchers`
--

CREATE TABLE `order_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `discount_applied` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outlets`
--

CREATE TABLE `outlets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outlets`
--

INSERT INTO `outlets` (`id`, `code`, `name`, `address`, `city`, `state`, `latitude`, `longitude`, `phone`, `is_active`, `created_at`) VALUES
(3, 'BALLZ-JB', 'Ballz Johor Bahru', 'Mid Valley Southkey, Johor Bahru', 'Johor Bahru', 'Johor', 1.4926590, 103.7413590, '07-5558899', 1, '2026-01-28 12:29:11'),
(4, 'BALLZ-PEN', 'Ballz Penang', 'Gurney Plaza, George Town', 'George Town', 'Penang', 5.4378920, 100.3098810, '04-8882233', 1, '2026-01-28 12:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `reward_items`
--

CREATE TABLE `reward_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_items_id` bigint(20) UNSIGNED DEFAULT NULL,
  `required_points` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reward_items`
--

INSERT INTO `reward_items` (`id`, `menu_items_id`, `required_points`) VALUES
(1, 12, 5000),
(2, 15, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `reward_transactions`
--

CREATE TABLE `reward_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `points` int(11) NOT NULL,
  `type` enum('earn','redeem') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `reward_points` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `reward_points`, `status`, `created_at`) VALUES
(1, 'Muhammad Dzulkarnain', 'dzulkarnain761@gmail.com', NULL, NULL, 0, '', '2026-01-15 05:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_identities`
--

CREATE TABLE `user_identities` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `provider_name` enum('google','facebook') NOT NULL,
  `provider_user_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('fixed','percentage') NOT NULL,
  `discount_value` decimal(8,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `name`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `start_date`, `end_date`, `is_active`) VALUES
(2, 'BALLZ5', 'RM5 OFF', 'Flat RM5 discount for orders above RM30', 'fixed', 5.00, 30.00, '2026-01-01', '2026-12-31', 1),
(3, 'SWEET10', 'Sweet Lovers 10%', '10% off sweet category items', 'percentage', 10.00, NULL, '2026-01-01', '2026-06-30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `voucher_rules`
--

CREATE TABLE `voucher_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `rule_type` enum('CATEGORY_QUANTITY','TIME_BASED','MIN_TOTAL') NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `required_quantity` int(11) DEFAULT NULL,
  `required_time_start` time DEFAULT NULL,
  `required_time_end` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher_rules`
--

INSERT INTO `voucher_rules` (`id`, `voucher_id`, `rule_type`, `category_id`, `required_quantity`, `required_time_start`, `required_time_end`) VALUES
(1, 3, 'CATEGORY_QUANTITY', 2, 2, NULL, NULL),
(2, 2, 'TIME_BASED', NULL, NULL, '15:00:00', '18:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_items_category` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `fk_order_outlet` (`outlet_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_item_order` (`order_id`),
  ADD KEY `fk_order_item_menu` (`menu_item_id`);

--
-- Indexes for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_voucher_order` (`order_id`),
  ADD KEY `fk_order_voucher_voucher` (`voucher_id`);

--
-- Indexes for table `outlets`
--
ALTER TABLE `outlets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `reward_items`
--
ALTER TABLE `reward_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu` (`menu_items_id`);

--
-- Indexes for table `reward_transactions`
--
ALTER TABLE `reward_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reward_order` (`order_id`),
  ADD KEY `idx_reward_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_identities`
--
ALTER TABLE `user_identities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `voucher_rules`
--
ALTER TABLE `voucher_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rule_category` (`category_id`),
  ADD KEY `idx_voucher_rules_voucher` (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlets`
--
ALTER TABLE `outlets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reward_items`
--
ALTER TABLE `reward_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reward_transactions`
--
ALTER TABLE `reward_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_identities`
--
ALTER TABLE `user_identities`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voucher_rules`
--
ALTER TABLE `voucher_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_outlet` FOREIGN KEY (`outlet_id`) REFERENCES `outlets` (`id`),
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_item_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  ADD CONSTRAINT `fk_order_voucher_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_voucher_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);

--
-- Constraints for table `reward_items`
--
ALTER TABLE `reward_items`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`menu_items_id`) REFERENCES `menu_items` (`id`);

--
-- Constraints for table `reward_transactions`
--
ALTER TABLE `reward_transactions`
  ADD CONSTRAINT `fk_reward_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reward_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_rules`
--
ALTER TABLE `voucher_rules`
  ADD CONSTRAINT `fk_rule_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`),
  ADD CONSTRAINT `fk_rule_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
