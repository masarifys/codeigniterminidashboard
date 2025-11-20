-- ============================================
-- Client Dashboard Database Setup
-- CodeIgniter 4 Application
-- ============================================

-- Drop tables if they exist (in reverse order to handle foreign keys)
DROP TABLE IF EXISTS `service_cancellations`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `invoice_items`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `users`;

-- ============================================
-- Table: users
-- Description: User authentication and profiles
-- ============================================
CREATE TABLE `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'client') NOT NULL DEFAULT 'client',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `reset_token` VARCHAR(255) DEFAULT NULL,
    `reset_expires` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`),
    KEY `idx_role` (`role`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: services
-- Description: Client services/products management
-- ============================================
CREATE TABLE `services` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `domain` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `billing_cycle` ENUM('monthly', 'quarterly', 'semi-annually', 'annually') NOT NULL DEFAULT 'monthly',
    `registration_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `status` ENUM('active', 'suspended', 'cancelled', 'pending') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_due_date` (`due_date`),
    CONSTRAINT `fk_services_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: invoices
-- Description: Invoice management and billing
-- ============================================
CREATE TABLE `invoices` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `invoice_number` VARCHAR(50) NOT NULL,
    `service_id` INT(11) UNSIGNED DEFAULT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `due_date` DATE NOT NULL,
    `paid_date` DATE DEFAULT NULL,
    `status` ENUM('unpaid', 'paid', 'past_due', 'cancelled') NOT NULL DEFAULT 'unpaid',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_invoice_number` (`invoice_number`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_service_id` (`service_id`),
    KEY `idx_status` (`status`),
    KEY `idx_due_date` (`due_date`),
    CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_invoices_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: tickets
-- Description: Support ticket system
-- ============================================
CREATE TABLE `tickets` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    `status` ENUM('open', 'answered', 'customer_reply', 'closed') NOT NULL DEFAULT 'open',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_priority` (`priority`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_tickets_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: invoice_items
-- Description: Invoice line items for detailed billing
-- ============================================
CREATE TABLE `invoice_items` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` INT(11) UNSIGNED NOT NULL,
    `description` TEXT NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_invoice_id` (`invoice_id`),
    CONSTRAINT `fk_invoice_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: transactions
-- Description: Payment transaction records
-- ============================================
CREATE TABLE `transactions` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `transaction_id` VARCHAR(255) NOT NULL,
    `gateway` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(15,2) NOT NULL,
    `transaction_date` DATETIME NOT NULL,
    `status` ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_invoice_id` (`invoice_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_transaction_id` (`transaction_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_transactions_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: service_cancellations
-- Description: Service cancellation requests
-- ============================================
CREATE TABLE `service_cancellations` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `reason` TEXT DEFAULT NULL,
    `cancellation_type` ENUM('immediate', 'end_of_billing_period') NOT NULL DEFAULT 'end_of_billing_period',
    `status` ENUM('pending', 'approved', 'cancelled') NOT NULL DEFAULT 'pending',
    `requested_at` DATETIME DEFAULT NULL,
    `processed_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_service_id` (`service_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    CONSTRAINT `fk_service_cancellations_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_service_cancellations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data (Test Data)
-- ============================================

-- Insert test client user
-- Password: password123 (hashed with bcrypt)
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`) 
VALUES 
('testclient', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Client User', 'client', 1, NOW(), NOW());

-- Get the last inserted user ID for foreign key references
SET @user_id = LAST_INSERT_ID();

-- Insert sample services
INSERT INTO `services` (`user_id`, `product_name`, `domain`, `price`, `billing_cycle`, `registration_date`, `due_date`, `ip_address`, `status`, `created_at`, `updated_at`) 
VALUES 
(@user_id, 'Unlimited L', 'example.com', 1680000.00, 'annually', '2025-11-10', '2026-01-09', '192.168.1.100', 'active', NOW(), NOW()),
(@user_id, 'VPS Standard', 'subdomain.example.com', 250000.00, 'monthly', '2025-10-15', '2025-12-15', '192.168.1.101', 'active', NOW(), NOW()),
(@user_id, 'Shared Hosting', 'test.example.com', 150000.00, 'quarterly', '2025-09-01', '2025-12-01', NULL, 'pending', NOW(), NOW());

-- Insert sample invoices
INSERT INTO `invoices` (`user_id`, `invoice_number`, `service_id`, `amount`, `due_date`, `paid_date`, `status`, `created_at`, `updated_at`) 
VALUES 
(@user_id, 'INV-2025-0001', 1, 1680000.00, '2025-11-20', NULL, 'unpaid', NOW(), NOW()),
(@user_id, 'INV-2025-0002', 2, 250000.00, '2025-11-15', NULL, 'past_due', NOW(), NOW()),
(@user_id, 'INV-2025-0003', 3, 150000.00, '2025-10-15', '2025-10-14', 'paid', NOW(), NOW());

-- Insert sample tickets
INSERT INTO `tickets` (`user_id`, `subject`, `department`, `priority`, `status`, `created_at`, `updated_at`) 
VALUES 
(@user_id, 'Server not responding', 'Technical Support', 'high', 'open', NOW(), NOW()),
(@user_id, 'Question about billing', 'Billing', 'medium', 'answered', NOW(), NOW()),
(@user_id, 'Need help with setup', 'Technical Support', 'low', 'closed', NOW(), NOW());

-- Insert sample invoice items
INSERT INTO `invoice_items` (`invoice_id`, `description`, `amount`, `created_at`, `updated_at`) 
VALUES 
(1, 'Unlimited L Hosting - Annual Subscription', 1680000.00, NOW(), NOW()),
(2, 'VPS Standard - Monthly Fee', 250000.00, NOW(), NOW()),
(3, 'Shared Hosting - Quarterly Payment', 150000.00, NOW(), NOW());

-- Insert sample transaction (for paid invoice)
INSERT INTO `transactions` (`invoice_id`, `user_id`, `transaction_id`, `gateway`, `amount`, `transaction_date`, `status`, `created_at`, `updated_at`) 
VALUES 
(3, @user_id, 'TRX-2025-000003', 'BCA Virtual Account', 150000.00, '2025-10-14 10:30:00', 'success', NOW(), NOW());

-- ============================================
-- Verification Queries
-- ============================================

-- Check table creation
SELECT 'Tables created successfully!' AS status;

-- Show table counts
SELECT 
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM services) AS total_services,
    (SELECT COUNT(*) FROM invoices) AS total_invoices,
    (SELECT COUNT(*) FROM tickets) AS total_tickets,
    (SELECT COUNT(*) FROM invoice_items) AS total_invoice_items,
    (SELECT COUNT(*) FROM transactions) AS total_transactions,
    (SELECT COUNT(*) FROM service_cancellations) AS total_service_cancellations;

-- ============================================
-- Additional Indexes for Performance
-- ============================================

-- Add composite indexes for common queries
ALTER TABLE `services` ADD INDEX `idx_user_status` (`user_id`, `status`);
ALTER TABLE `invoices` ADD INDEX `idx_user_status` (`user_id`, `status`);
ALTER TABLE `tickets` ADD INDEX `idx_user_status` (`user_id`, `status`);

-- ============================================
-- Test Login Credentials
-- ============================================
-- Username: testclient
-- Password: password123
-- ============================================
