-- ============================================
-- Client Dashboard Database Setup - SQLite Version
-- CodeIgniter 4 Application
-- ============================================

-- Drop tables if they exist (in reverse order to handle foreign keys)
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;

-- ============================================
-- Table: users
-- Description: User authentication and profiles
-- ============================================
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role TEXT CHECK(role IN ('admin', 'client')) NOT NULL DEFAULT 'client',
    is_active INTEGER NOT NULL DEFAULT 1,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL
);

CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_is_active ON users(is_active);

-- ============================================
-- Table: services
-- Description: Client services/products management
-- ============================================
CREATE TABLE services (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    billing_cycle TEXT CHECK(billing_cycle IN ('monthly', 'quarterly', 'semi-annually', 'annually')) NOT NULL DEFAULT 'monthly',
    registration_date DATE NOT NULL,
    due_date DATE NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    status TEXT CHECK(status IN ('active', 'suspended', 'cancelled', 'pending')) NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX idx_services_user_id ON services(user_id);
CREATE INDEX idx_services_status ON services(status);
CREATE INDEX idx_services_due_date ON services(due_date);
CREATE INDEX idx_services_user_status ON services(user_id, status);

-- ============================================
-- Table: invoices
-- Description: Invoice management and billing
-- ============================================
CREATE TABLE invoices (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    service_id INTEGER DEFAULT NULL,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    paid_date DATE DEFAULT NULL,
    status TEXT CHECK(status IN ('unpaid', 'paid', 'past_due', 'cancelled')) NOT NULL DEFAULT 'unpaid',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE INDEX idx_invoices_user_id ON invoices(user_id);
CREATE INDEX idx_invoices_service_id ON invoices(service_id);
CREATE INDEX idx_invoices_status ON invoices(status);
CREATE INDEX idx_invoices_due_date ON invoices(due_date);
CREATE INDEX idx_invoices_user_status ON invoices(user_id, status);

-- ============================================
-- Table: tickets
-- Description: Support ticket system
-- ============================================
CREATE TABLE tickets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    subject VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    priority TEXT CHECK(priority IN ('low', 'medium', 'high')) NOT NULL DEFAULT 'medium',
    status TEXT CHECK(status IN ('open', 'answered', 'customer_reply', 'closed')) NOT NULL DEFAULT 'open',
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX idx_tickets_user_id ON tickets(user_id);
CREATE INDEX idx_tickets_status ON tickets(status);
CREATE INDEX idx_tickets_priority ON tickets(priority);
CREATE INDEX idx_tickets_created_at ON tickets(created_at);
CREATE INDEX idx_tickets_user_status ON tickets(user_id, status);

-- ============================================
-- Sample Data (Test Data)
-- ============================================

-- Insert test client user
-- Password: password123 (hashed with bcrypt)
INSERT INTO users (username, email, password, full_name, role, is_active, created_at, updated_at) 
VALUES 
('testclient', 'client@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Client User', 'client', 1, datetime('now'), datetime('now'));

-- Insert sample services
INSERT INTO services (user_id, product_name, domain, price, billing_cycle, registration_date, due_date, ip_address, status, created_at, updated_at) 
VALUES 
(1, 'Unlimited L', 'example.com', 1680000.00, 'annually', '2025-11-10', '2026-01-09', '192.168.1.100', 'active', datetime('now'), datetime('now')),
(1, 'VPS Standard', 'subdomain.example.com', 250000.00, 'monthly', '2025-10-15', '2025-12-15', '192.168.1.101', 'active', datetime('now'), datetime('now')),
(1, 'Shared Hosting', 'test.example.com', 150000.00, 'quarterly', '2025-09-01', '2025-12-01', NULL, 'pending', datetime('now'), datetime('now'));

-- Insert sample invoices
INSERT INTO invoices (user_id, invoice_number, service_id, amount, due_date, paid_date, status, created_at, updated_at) 
VALUES 
(1, 'INV-2025-0001', 1, 1680000.00, '2025-11-20', NULL, 'unpaid', datetime('now'), datetime('now')),
(1, 'INV-2025-0002', 2, 250000.00, '2025-11-15', NULL, 'past_due', datetime('now'), datetime('now')),
(1, 'INV-2025-0003', 3, 150000.00, '2025-10-15', '2025-10-14', 'paid', datetime('now'), datetime('now'));

-- Insert sample tickets
INSERT INTO tickets (user_id, subject, department, priority, status, created_at, updated_at) 
VALUES 
(1, 'Server not responding', 'Technical Support', 'high', 'open', datetime('now'), datetime('now')),
(1, 'Question about billing', 'Billing', 'medium', 'answered', datetime('now'), datetime('now')),
(1, 'Need help with setup', 'Technical Support', 'low', 'closed', datetime('now'), datetime('now'));

-- ============================================
-- Verification Queries
-- ============================================

-- Show table counts
SELECT 
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM services) AS total_services,
    (SELECT COUNT(*) FROM invoices) AS total_invoices,
    (SELECT COUNT(*) FROM tickets) AS total_tickets;

-- ============================================
-- Test Login Credentials
-- ============================================
-- Username: testclient
-- Password: password123
-- ============================================
