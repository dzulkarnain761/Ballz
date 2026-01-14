-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 04:53 AM
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
-- Table structure for table `auth_sessions`
--

CREATE TABLE `auth_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `access_token` varchar(512) NOT NULL,
  `refresh_token` varchar(512) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Sweet', 'Sweet dessert balls');

-- --------------------------------------------------------

--
-- Table structure for table `menu_images`
--

CREATE TABLE `menu_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `alt_text` varchar(150) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 1, 'Classic Cheese Bomb', 'Crispy golden ball filled with molten mozzarella and cheddar, served with marinara sauce.', 8.90, 1),
(2, 1, 'Mac & Cheese Truffle Bites', 'Rich mac and cheese rolled into balls, panko-crusted, drizzled with truffle oil.', 10.90, 1),
(3, 1, 'Spicy Arancini', 'Risotto balls with fiery nduja sausage and provolone, served with roasted pepper aioli.', 9.90, 1),
(4, 1, 'Buffalo Chicken Poppers', 'Spicy shredded chicken and cream cheese balls, served with ranch dip.', 9.50, 1),
(5, 2, 'Nutella Delight', 'Warm brioche ball injected with rich Nutella, dusted with powdered sugar.', 7.90, 1),
(6, 2, 'Salted Caramel Crunch', 'Fried dough ball filled with salted caramel cream, topped with pretzel bits.', 8.50, 1),
(7, 2, 'Berry Bliss Bomboloni', 'Italian doughnut hole filled with fresh berry compote and mascarpone.', 8.90, 1),
(8, 2, 'Cinnamon Sugar Bites', 'Classic fluffy dough balls coated in cinnamon sugar, served with vanilla glaze.', 7.50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_options`
--

CREATE TABLE `menu_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_group_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `price_modifier` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_options`
--

INSERT INTO `menu_options` (`id`, `option_group_id`, `name`, `price_modifier`, `is_default`, `sort_order`) VALUES
(1, 1, 'Normal', 0.00, 1, 0),
(2, 1, 'Spicy', 0.50, 0, 0),
(3, 1, 'Extra Spicy', 1.00, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `menu_option_groups`
--

CREATE TABLE `menu_option_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `min_select` int(11) NOT NULL DEFAULT 0,
  `max_select` int(11) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_option_groups`
--

INSERT INTO `menu_option_groups` (`id`, `menu_item_id`, `name`, `is_required`, `min_select`, `max_select`, `sort_order`) VALUES
(1, 1, 'Spice Level', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_accounts`
--

CREATE TABLE `oauth_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` bigint(20) UNSIGNED NOT NULL,
  `provider_user_id` varchar(255) NOT NULL,
  `access_token` text NOT NULL,
  `refresh_token` text DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_providers`
--

CREATE TABLE `oauth_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `issuer_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `outlet_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('pickup','dine_in') NOT NULL,
  `table_number` varchar(10) DEFAULT NULL,
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
-- Table structure for table `order_item_options`
--

CREATE TABLE `order_item_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(100) NOT NULL,
  `price_modifier` decimal(8,2) NOT NULL
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

-- --------------------------------------------------------

--
-- Table structure for table `outlet_menu_items`
--

CREATE TABLE `outlet_menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `price_override` decimal(8,2) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outlet_menu_options`
--

CREATE TABLE `outlet_menu_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_id` bigint(20) UNSIGNED NOT NULL,
  `menu_option_id` bigint(20) UNSIGNED NOT NULL,
  `price_override` decimal(8,2) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outlet_vouchers`
--

CREATE TABLE `outlet_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `outlet_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `reward_points` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_session_user` (`user_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `menu_images`
--
ALTER TABLE `menu_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_image_menu` (`menu_item_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_items_category` (`category_id`);

--
-- Indexes for table `menu_options`
--
ALTER TABLE `menu_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_option_group` (`option_group_id`);

--
-- Indexes for table `menu_option_groups`
--
ALTER TABLE `menu_option_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_option_group_menu` (`menu_item_id`);

--
-- Indexes for table `oauth_accounts`
--
ALTER TABLE `oauth_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_provider_user` (`provider_id`,`provider_user_id`),
  ADD KEY `fk_oauth_user` (`user_id`);

--
-- Indexes for table `oauth_providers`
--
ALTER TABLE `oauth_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
-- Indexes for table `order_item_options`
--
ALTER TABLE `order_item_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_item_option` (`order_item_id`);

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
-- Indexes for table `outlet_menu_items`
--
ALTER TABLE `outlet_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_outlet_menu` (`outlet_id`,`menu_item_id`),
  ADD KEY `fk_outlet_menu_item` (`menu_item_id`);

--
-- Indexes for table `outlet_menu_options`
--
ALTER TABLE `outlet_menu_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_outlet_option` (`outlet_id`,`menu_option_id`),
  ADD KEY `fk_outlet_option` (`menu_option_id`);

--
-- Indexes for table `outlet_vouchers`
--
ALTER TABLE `outlet_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_outlet_voucher` (`outlet_id`,`voucher_id`),
  ADD KEY `fk_outlet_voucher_voucher` (`voucher_id`);

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
-- AUTO_INCREMENT for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `menu_images`
--
ALTER TABLE `menu_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_options`
--
ALTER TABLE `menu_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu_option_groups`
--
ALTER TABLE `menu_option_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `oauth_accounts`
--
ALTER TABLE `oauth_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_providers`
--
ALTER TABLE `oauth_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `order_item_options`
--
ALTER TABLE `order_item_options`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlet_menu_items`
--
ALTER TABLE `outlet_menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlet_menu_options`
--
ALTER TABLE `outlet_menu_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outlet_vouchers`
--
ALTER TABLE `outlet_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_transactions`
--
ALTER TABLE `reward_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_rules`
--
ALTER TABLE `voucher_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD CONSTRAINT `fk_session_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_images`
--
ALTER TABLE `menu_images`
  ADD CONSTRAINT `fk_image_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`);

--
-- Constraints for table `menu_options`
--
ALTER TABLE `menu_options`
  ADD CONSTRAINT `fk_option_group` FOREIGN KEY (`option_group_id`) REFERENCES `menu_option_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_option_groups`
--
ALTER TABLE `menu_option_groups`
  ADD CONSTRAINT `fk_option_group_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `oauth_accounts`
--
ALTER TABLE `oauth_accounts`
  ADD CONSTRAINT `fk_oauth_provider` FOREIGN KEY (`provider_id`) REFERENCES `oauth_providers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_oauth_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `order_item_options`
--
ALTER TABLE `order_item_options`
  ADD CONSTRAINT `fk_order_item_option` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_vouchers`
--
ALTER TABLE `order_vouchers`
  ADD CONSTRAINT `fk_order_voucher_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_voucher_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);

--
-- Constraints for table `outlet_menu_items`
--
ALTER TABLE `outlet_menu_items`
  ADD CONSTRAINT `fk_outlet_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_outlet_menu_outlet` FOREIGN KEY (`outlet_id`) REFERENCES `outlets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `outlet_menu_options`
--
ALTER TABLE `outlet_menu_options`
  ADD CONSTRAINT `fk_outlet_option` FOREIGN KEY (`menu_option_id`) REFERENCES `menu_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_outlet_option_outlet` FOREIGN KEY (`outlet_id`) REFERENCES `outlets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `outlet_vouchers`
--
ALTER TABLE `outlet_vouchers`
  ADD CONSTRAINT `fk_outlet_voucher_outlet` FOREIGN KEY (`outlet_id`) REFERENCES `outlets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_outlet_voucher_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;

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
