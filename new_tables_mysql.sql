-- ============================================
-- SQL Schema for New Tables (MySQL/MariaDB)
-- Created: 2025-11-21
-- ============================================

-- Table: clients
-- Description: Store client business information and project status
CREATE TABLE `clients` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `business_name` VARCHAR(255) NOT NULL,
    `contact_person` VARCHAR(255) DEFAULT NULL,
    `contact_email` VARCHAR(255) DEFAULT NULL,
    `contact_phone` VARCHAR(50) DEFAULT NULL,
    `domain` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('progress', 'revision', 'completed', 'cancelled') NOT NULL DEFAULT 'progress',
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: service_packages
-- Description: Store service package templates for upgrades
CREATE TABLE `service_packages` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `storage` VARCHAR(100) DEFAULT NULL,
    `bandwidth` VARCHAR(100) DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `billing_cycle` ENUM('monthly', 'quarterly', 'semi-annually', 'annually') NOT NULL DEFAULT 'monthly',
    `features` TEXT DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: reminders
-- Description: Store automated reminders for renewals and expiry dates
CREATE TABLE `reminders` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `service_id` INT(11) UNSIGNED DEFAULT NULL,
    `type` ENUM('domain_renewal', 'hosting_renewal', 'ssl_expiry', 'invoice_due', 'maintenance_due') NOT NULL DEFAULT 'domain_renewal',
    `reminder_date` DATE NOT NULL,
    `message` TEXT NOT NULL,
    `is_sent` TINYINT(1) NOT NULL DEFAULT 0,
    `sent_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- ALTER TABLE: services
-- Description: Add domain, hosting, SSL, and monitoring fields
-- ============================================

ALTER TABLE `services` 
    ADD COLUMN `registrar` VARCHAR(255) DEFAULT NULL AFTER `panel_url`,
    ADD COLUMN `domain_expiry_date` DATE DEFAULT NULL AFTER `registrar`,
    ADD COLUMN `hosting_provider` VARCHAR(255) DEFAULT NULL AFTER `domain_expiry_date`,
    ADD COLUMN `hosting_renewal_date` DATE DEFAULT NULL AFTER `hosting_provider`,
    ADD COLUMN `ssl_status` ENUM('active', 'inactive', 'expiring_soon') NOT NULL DEFAULT 'inactive' AFTER `hosting_renewal_date`,
    ADD COLUMN `ssl_expiry_date` DATE DEFAULT NULL AFTER `ssl_status`,
    ADD COLUMN `uptime_monitor_url` VARCHAR(500) DEFAULT NULL AFTER `ssl_expiry_date`,
    ADD COLUMN `uptime_status` ENUM('up', 'down', 'unknown') NOT NULL DEFAULT 'unknown' AFTER `uptime_monitor_url`,
    ADD COLUMN `last_uptime_check` DATETIME DEFAULT NULL AFTER `uptime_status`;

-- ============================================
-- Sample Data (Optional)
-- ============================================

-- Sample service package
INSERT INTO `service_packages` (`name`, `description`, `storage`, `bandwidth`, `price`, `billing_cycle`, `features`, `notes`, `is_active`, `created_at`, `updated_at`) 
VALUES 
    ('Shared Hosting Basic', 'Perfect for small websites and blogs', '10 GB SSD', '100 GB', 150000.00, 'monthly', '1 Website\n5 Email Accounts\nFree SSL Certificate\ncPanel Access\n24/7 Support', 'Entry-level hosting package', 1, NOW(), NOW()),
    ('VPS Standard', 'Full control with dedicated resources', '100 GB SSD', '1000 GB', 500000.00, 'monthly', '4 CPU Cores\n8 GB RAM\n100 GB SSD\nRoot Access\nUnlimited Websites\nFree SSL', 'For advanced users', 1, NOW(), NOW());
