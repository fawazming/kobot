-- ============================================================
-- KoboTrack - Complete Database Schema
-- ============================================================
-- Run this SQL file to set up the entire database
-- ============================================================

CREATE DATABASE IF NOT EXISTS kobotrack DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kobotrack;

-- ============================================================
-- Businesses Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `businesses` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `public_key` VARCHAR(100) NOT NULL,
    `secret_key` VARCHAR(100) NOT NULL,
    `webhook_secret` VARCHAR(100) NOT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `business_id` (`business_id`),
    UNIQUE KEY `public_key` (`public_key`),
    UNIQUE KEY `secret_key` (`secret_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Transactions Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `transactions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_id` VARCHAR(50) NOT NULL,
    `business_id` VARCHAR(50) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `original_amount` DECIMAL(10,2) NOT NULL,
    `payable_amount` DECIMAL(10,2) NOT NULL,
    `payment_status` ENUM('pending', 'success', 'failed', 'expired') NOT NULL DEFAULT 'pending',
    `registration_id` VARCHAR(50) DEFAULT NULL,
    `webhook_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `metadata` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `transaction_id` (`transaction_id`),
    KEY `business_id` (`business_id`),
    KEY `payment_status` (`payment_status`),
    KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Registrations Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `registrations` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `registration_id` VARCHAR(50) NOT NULL,
    `transaction_id` VARCHAR(50) NOT NULL,
    `json_data` LONGTEXT NOT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `registration_id` (`registration_id`),
    KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Webhook Logs Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `webhook_logs` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_id` VARCHAR(50) DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `signature` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('received', 'verified', 'failed', 'duplicate') NOT NULL DEFAULT 'received',
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Admins Table
-- ============================================================
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Seed Data: Default Admin Account
-- Password: admin123 (bcrypt hashed)
-- ============================================================
INSERT INTO `admins` (`username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`)
VALUES ('admin', 'admin@kobotrack.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', 'active', NOW(), NOW());

-- ============================================================
-- Seed Data: Sample Business
-- ============================================================
INSERT INTO `businesses` (`business_id`, `name`, `phone`, `email`, `public_key`, `secret_key`, `webhook_secret`, `status`, `created_at`, `updated_at`)
VALUES (
    'BUS_001',
    'KoboTrack Demo Business',
    '+2348000000001',
    'demo@kobotrack.com',
    'pk_live_kobotrack_demo_public_key_001',
    'sk_live_kobotrack_demo_secret_key_001',
    'whsec_kobotrack_demo_webhook_secret_001',
    'active',
    NOW(),
    NOW()
);
