PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE `migrations` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`version` VARCHAR NOT NULL,
	`class` VARCHAR NOT NULL,
	`group` VARCHAR NOT NULL,
	`namespace` VARCHAR NOT NULL,
	`time` INT NOT NULL,
	`batch` INT NOT NULL
);
INSERT INTO migrations VALUES(1,'2025-11-20-172400','App\Database\Migrations\CreateServicesTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(2,'2025-11-20-172407','App\Database\Migrations\CreateInvoicesTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(3,'2025-11-20-172407','App\Database\Migrations\CreateTicketsTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(4,'2025-11-20-173253','App\Database\Migrations\CreateUsersTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(5,'2025-11-20-181000','App\Database\Migrations\CreateInvoiceItemsTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(6,'2025-11-20-181001','App\Database\Migrations\CreateTransactionsTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(7,'2025-11-20-185008','App\Database\Migrations\AddCredentialsToServicesTable','default','App',1763664821,1);
INSERT INTO migrations VALUES(8,'2025-11-20-185015','App\Database\Migrations\CreateServiceCancellationsTable','default','App',1763664821,1);
CREATE TABLE `services` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_id` INT NOT NULL,
	`product_name` VARCHAR NOT NULL,
	`domain` VARCHAR NOT NULL,
	`price` DECIMAL NOT NULL,
	`billing_cycle` TEXT CHECK(`billing_cycle` IN ('monthly','quarterly','semi-annually','annually')) NOT NULL DEFAULT 'monthly',
	`registration_date` DATE NOT NULL,
	`due_date` DATE NOT NULL,
	`ip_address` VARCHAR NULL,
	`status` TEXT CHECK(`status` IN ('active','suspended','cancelled','pending')) NOT NULL DEFAULT 'pending',
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
, `username` VARCHAR NULL, `password` VARCHAR NULL, `server` VARCHAR NULL, `panel_url` VARCHAR NULL);
INSERT INTO services VALUES(1,2,'Unlimited L','example.com',1680000,'annually','2025-11-10','2026-01-09','192.168.1.100','active','2025-11-21 09:00:00','2025-11-21 09:00:00','user_1','pass_1','server1.example.com','https://panel1.example.com');
INSERT INTO services VALUES(2,2,'VPS Standard','subdomain.example.com',250000,'monthly','2025-10-15','2025-12-15','192.168.1.101','active','2025-11-21 09:00:00','2025-11-21 09:00:00','user_2','pass_2','server2.example.com','https://panel2.example.com');
INSERT INTO services VALUES(3,2,'Shared Hosting','test.example.com',150000,'quarterly','2025-09-01','2025-12-01',NULL,'pending','2025-11-21 09:00:00','2025-11-21 09:00:00','user_3','pass_3','server3.example.com','https://panel3.example.com');
CREATE TABLE `invoices` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_id` INT NOT NULL,
	`invoice_number` VARCHAR NOT NULL UNIQUE,
	`service_id` INT NULL,
	`amount` DECIMAL NOT NULL,
	`due_date` DATE NOT NULL,
	`paid_date` DATE NULL,
	`status` TEXT CHECK(`status` IN ('unpaid','paid','past_due','cancelled')) NOT NULL DEFAULT 'unpaid',
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
);
INSERT INTO invoices VALUES(1,2,'INV-2025-0001',1,1680000,'2025-11-20',NULL,'unpaid','2025-11-21 09:00:00','2025-11-21 09:00:00');
INSERT INTO invoices VALUES(2,2,'INV-2025-0002',2,250000,'2025-11-15',NULL,'past_due','2025-11-21 09:00:00','2025-11-21 09:00:00');
INSERT INTO invoices VALUES(3,2,'INV-2025-0003',3,150000,'2025-10-15','2025-10-14','paid','2025-11-21 09:00:00','2025-11-21 09:00:00');
CREATE TABLE `tickets` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`user_id` INT NOT NULL,
	`subject` VARCHAR NOT NULL,
	`department` VARCHAR NOT NULL,
	`priority` TEXT CHECK(`priority` IN ('low','medium','high')) NOT NULL DEFAULT 'medium',
	`status` TEXT CHECK(`status` IN ('open','answered','customer_reply','closed')) NOT NULL DEFAULT 'open',
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
);
INSERT INTO tickets VALUES(1,2,'Server not responding','Technical Support','high','open','2025-11-21 09:00:00','2025-11-21 09:00:00');
INSERT INTO tickets VALUES(2,2,'Question about billing','Billing','medium','answered','2025-11-21 09:00:00','2025-11-21 09:00:00');
INSERT INTO tickets VALUES(3,2,'Need help with setup','Technical Support','low','closed','2025-11-21 09:00:00','2025-11-21 09:00:00');
CREATE TABLE `users` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`username` VARCHAR NOT NULL UNIQUE,
	`email` VARCHAR NOT NULL UNIQUE,
	`password` VARCHAR NOT NULL,
	`full_name` VARCHAR NOT NULL,
	`role` TEXT CHECK(`role` IN ('admin','client')) NOT NULL DEFAULT 'client',
	`is_active` TINYINT NOT NULL DEFAULT 1,
	`reset_token` VARCHAR NULL,
	`reset_expires` DATETIME NULL,
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
);
INSERT INTO users VALUES(1,'admin','admin@example.com','$2y$10$5X2z9MVb4SkvW2YyaDnukO3VZZVo6acVA9bSj1UgrB5K05hokSdMq','Admin User','admin',1,NULL,NULL,'2025-11-21 09:00:00','2025-11-21 09:00:00');
INSERT INTO users VALUES(2,'testclient','client@test.com','$2y$10$Xvdp6TGZA5Oj5RS6r9mQAOCcyb88WsUBlfhOXTwdFUvBjUxW7GzsW','Test Client User','client',1,NULL,NULL,'2025-11-21 09:00:00','2025-11-21 09:00:00');
CREATE TABLE `invoice_items` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`invoice_id` INT NOT NULL,
	`description` TEXT NOT NULL,
	`amount` DECIMAL NOT NULL,
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL,
	CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE `transactions` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`invoice_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`transaction_id` VARCHAR NOT NULL,
	`gateway` VARCHAR NOT NULL,
	`amount` DECIMAL NOT NULL,
	`transaction_date` DATETIME NOT NULL,
	`status` TEXT CHECK(`status` IN ('pending','success','failed')) NOT NULL DEFAULT 'pending',
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL,
	CONSTRAINT `transactions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE `service_cancellations` (
	`id` INTEGER PRIMARY KEY AUTOINCREMENT,
	`service_id` INT NOT NULL,
	`user_id` INT NOT NULL,
	`reason` TEXT NULL,
	`cancellation_type` TEXT CHECK(`cancellation_type` IN ('immediate','end_of_billing_period')) NOT NULL DEFAULT 'end_of_billing_period',
	`status` TEXT CHECK(`status` IN ('pending','approved','cancelled')) NOT NULL DEFAULT 'pending',
	`requested_at` DATETIME NULL,
	`processed_at` DATETIME NULL,
	`created_at` DATETIME NULL,
	`updated_at` DATETIME NULL
);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('migrations',8);
INSERT INTO sqlite_sequence VALUES('users',2);
INSERT INTO sqlite_sequence VALUES('services',3);
INSERT INTO sqlite_sequence VALUES('invoices',3);
INSERT INTO sqlite_sequence VALUES('tickets',3);
CREATE INDEX `services_user_id` ON `services` (`user_id`);
CREATE INDEX `invoices_user_id` ON `invoices` (`user_id`);
CREATE INDEX `invoices_service_id` ON `invoices` (`service_id`);
CREATE INDEX `tickets_user_id` ON `tickets` (`user_id`);
CREATE INDEX `invoice_items_invoice_id` ON `invoice_items` (`invoice_id`);
CREATE INDEX `transactions_invoice_id` ON `transactions` (`invoice_id`);
CREATE INDEX `transactions_user_id` ON `transactions` (`user_id`);
CREATE INDEX `service_cancellations_service_id` ON `service_cancellations` (`service_id`);
CREATE INDEX `service_cancellations_user_id` ON `service_cancellations` (`user_id`);
COMMIT;
