-- ============================================
-- SQL Schema for New Tables (SQLite)
-- Created: 2025-11-21
-- ============================================

-- Table: clients
-- Description: Store client business information and project status
CREATE TABLE `clients` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `business_name` VARCHAR(255) NOT NULL,
    `contact_person` VARCHAR(255) DEFAULT NULL,
    `contact_email` VARCHAR(255) DEFAULT NULL,
    `contact_phone` VARCHAR(50) DEFAULT NULL,
    `domain` VARCHAR(255) DEFAULT NULL,
    `status` TEXT CHECK(`status` IN ('progress', 'revision', 'completed', 'cancelled')) NOT NULL DEFAULT 'progress',
    `notes` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL
);

CREATE INDEX `idx_clients_user_id` ON `clients` (`user_id`);

-- Table: service_packages
-- Description: Store service package templates for upgrades
CREATE TABLE `service_packages` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `storage` VARCHAR(100) DEFAULT NULL,
    `bandwidth` VARCHAR(100) DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `billing_cycle` TEXT CHECK(`billing_cycle` IN ('monthly', 'quarterly', 'semi-annually', 'annually')) NOT NULL DEFAULT 'monthly',
    `features` TEXT DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `is_active` INTEGER NOT NULL DEFAULT 1,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL
);

-- Table: reminders
-- Description: Store automated reminders for renewals and expiry dates
CREATE TABLE `reminders` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `user_id` INTEGER NOT NULL,
    `service_id` INTEGER DEFAULT NULL,
    `type` TEXT CHECK(`type` IN ('domain_renewal', 'hosting_renewal', 'ssl_expiry', 'invoice_due', 'maintenance_due')) NOT NULL DEFAULT 'domain_renewal',
    `reminder_date` DATE NOT NULL,
    `message` TEXT NOT NULL,
    `is_sent` INTEGER NOT NULL DEFAULT 0,
    `sent_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL
);

CREATE INDEX `idx_reminders_user_id` ON `reminders` (`user_id`);
CREATE INDEX `idx_reminders_service_id` ON `reminders` (`service_id`);

-- ============================================
-- ALTER TABLE: services
-- Description: Add domain, hosting, SSL, and monitoring fields
-- ============================================

ALTER TABLE `services` ADD COLUMN `registrar` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `domain_expiry_date` DATE DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `hosting_provider` VARCHAR(255) DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `hosting_renewal_date` DATE DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `ssl_status` TEXT CHECK(`ssl_status` IN ('active', 'inactive', 'expiring_soon')) NOT NULL DEFAULT 'inactive';
ALTER TABLE `services` ADD COLUMN `ssl_expiry_date` DATE DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `uptime_monitor_url` VARCHAR(500) DEFAULT NULL;
ALTER TABLE `services` ADD COLUMN `uptime_status` TEXT CHECK(`uptime_status` IN ('up', 'down', 'unknown')) NOT NULL DEFAULT 'unknown';
ALTER TABLE `services` ADD COLUMN `last_uptime_check` DATETIME DEFAULT NULL;

-- ============================================
-- Sample Data (Optional)
-- ============================================

-- Sample service package
INSERT INTO `service_packages` (`name`, `description`, `storage`, `bandwidth`, `price`, `billing_cycle`, `features`, `notes`, `is_active`, `created_at`, `updated_at`) 
VALUES 
    ('Shared Hosting Basic', 'Perfect for small websites and blogs', '10 GB SSD', '100 GB', 150000.00, 'monthly', '1 Website
5 Email Accounts
Free SSL Certificate
cPanel Access
24/7 Support', 'Entry-level hosting package', 1, datetime('now'), datetime('now')),
    ('VPS Standard', 'Full control with dedicated resources', '100 GB SSD', '1000 GB', 500000.00, 'monthly', '4 CPU Cores
8 GB RAM
100 GB SSD
Root Access
Unlimited Websites
Free SSL', 'For advanced users', 1, datetime('now'), datetime('now'));
