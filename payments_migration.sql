-- ============================================
-- Payment Gateway Simulation - Database Migration
-- ============================================

-- Payments table
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('fpx','duitnow') NOT NULL,
  `payment_ref` varchar(50) NOT NULL COMMENT 'Unique payment reference (e.g., BZ-FPX-20260221-ABCD1234)',
  `bank_code` varchar(10) DEFAULT NULL COMMENT 'FPX bank code (e.g., MBB, CIMB)',
  `status` enum('pending','processing','success','failed','expired') NOT NULL DEFAULT 'pending',
  `gateway_response` text DEFAULT NULL COMMENT 'Response from payment gateway (simulated)',
  `expires_at` datetime NOT NULL COMMENT 'Payment session expiry',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_payment_ref` (`payment_ref`),
  KEY `idx_payment_order` (`order_id`),
  KEY `idx_payment_user` (`user_id`),
  KEY `idx_payment_status` (`status`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add payment_method and payment_ref columns to orders table for quick reference
ALTER TABLE `orders`
  ADD COLUMN `payment_method` varchar(20) DEFAULT NULL AFTER `status`,
  ADD COLUMN `payment_ref` varchar(50) DEFAULT NULL AFTER `payment_method`;
