# Database Schema for New Tables

This document explains the SQL files for the new tables added to support the admin panel features.

## Files Included

1. **`new_tables_mysql.sql`** - MySQL/MariaDB schema
2. **`new_tables_sqlite.sql`** - SQLite schema

## New Tables Overview

### 1. `clients` Table
Stores client business information and project tracking.

**Fields:**
- `id` - Primary key
- `user_id` - Reference to users table
- `business_name` - Client's business name (required)
- `contact_person` - Contact person name
- `contact_email` - Contact email address
- `contact_phone` - Contact phone number
- `domain` - Client's domain
- `status` - Project status: progress, revision, completed, cancelled
- `notes` - Special notes for the client
- `created_at`, `updated_at` - Timestamps

**Usage:**
- Admin can manage client records with business details
- Track project status and milestones
- Store special notes and contact information

### 2. `service_packages` Table
Stores service package templates for client upgrades.

**Fields:**
- `id` - Primary key
- `name` - Package name (required)
- `description` - Package description
- `storage` - Storage allocation (e.g., "10 GB SSD")
- `bandwidth` - Bandwidth allocation (e.g., "100 GB")
- `price` - Package price (required)
- `billing_cycle` - monthly, quarterly, semi-annually, annually
- `features` - Package features (multi-line text)
- `notes` - Admin notes (internal use)
- `is_active` - Whether package is available for selection
- `created_at`, `updated_at` - Timestamps

**Usage:**
- Admin creates packages with pricing and features
- Clients can view available packages for upgrades
- Track package details and specifications

### 3. `reminders` Table
Stores automated reminders for renewals and expiry notifications.

**Fields:**
- `id` - Primary key
- `user_id` - Reference to users table
- `service_id` - Reference to services table (optional)
- `type` - Reminder type: domain_renewal, hosting_renewal, ssl_expiry, invoice_due, maintenance_due
- `reminder_date` - Date when reminder should be sent
- `message` - Reminder message
- `is_sent` - Whether reminder has been sent (0 or 1)
- `sent_at` - When reminder was sent
- `created_at`, `updated_at` - Timestamps

**Usage:**
- Automatic reminders before domain/hosting expiry
- SSL certificate expiry notifications
- Invoice due date reminders

### 4. `services` Table Extensions
New fields added to existing `services` table:

**Domain Fields:**
- `registrar` - Domain registrar name
- `domain_expiry_date` - Domain expiration date

**Hosting Fields:**
- `hosting_provider` - Hosting provider name
- `hosting_renewal_date` - Hosting renewal date

**SSL Fields:**
- `ssl_status` - active, inactive, expiring_soon
- `ssl_expiry_date` - SSL certificate expiration date

**Monitoring Fields:**
- `uptime_monitor_url` - URL to monitor
- `uptime_status` - up, down, unknown
- `last_uptime_check` - Last time uptime was checked

## Installation Instructions

### Using CodeIgniter Migrations (Recommended)

The migrations are already included in the codebase. Run:

```bash
php spark migrate
```

### Manual Installation - MySQL/MariaDB

```bash
# Import the SQL file
mysql -u your_username -p your_database < new_tables_mysql.sql

# Or using phpMyAdmin:
# 1. Open phpMyAdmin
# 2. Select your database
# 3. Click "Import" tab
# 4. Choose file: new_tables_mysql.sql
# 5. Click "Go"
```

### Manual Installation - SQLite

```bash
# If using SQLite database
sqlite3 writable/database.db < new_tables_sqlite.sql

# Or in SQLite command line:
sqlite3 writable/database.db
.read new_tables_sqlite.sql
.exit
```

## Verification

After installation, verify the tables were created:

### MySQL/MariaDB
```sql
SHOW TABLES LIKE 'clients';
SHOW TABLES LIKE 'service_packages';
SHOW TABLES LIKE 'reminders';
DESCRIBE services;
```

### SQLite
```sql
.tables
.schema clients
.schema service_packages
.schema reminders
.schema services
```

## Sample Data

Both SQL files include optional sample data for `service_packages`:
- Shared Hosting Basic - Rp 150,000/month
- VPS Standard - Rp 500,000/month

You can remove these INSERT statements if you don't want sample data.

## Seeding Data

To populate with more comprehensive test data, run the seeder:

```bash
php spark db:seed AdminPanelSeeder
```

This will create:
- Admin user (username: admin)
- Sample client record
- 4 service packages
- Updated services with domain/hosting info
- Sample reminders

## Related Migrations

These migrations correspond to:
- `2025-11-21-060500_CreateClientsTable.php`
- `2025-11-21-060501_CreateServicePackagesTable.php`
- `2025-11-21-060502_AddDomainHostingInfoToServices.php`
- `2025-11-21-060503_CreateRemindersTable.php`

## Database Relationships

```
users (existing)
  ├─→ clients (user_id)
  ├─→ services (user_id)
  └─→ reminders (user_id)

services (existing)
  └─→ reminders (service_id)

service_packages (standalone)
```

## Notes

1. **ENUM Types**: MySQL uses native ENUM, SQLite uses TEXT with CHECK constraints
2. **Indexes**: Created on foreign key columns for performance
3. **Character Set**: MySQL uses utf8mb4 for full Unicode support
4. **Auto Increment**: Different syntax between MySQL (AUTO_INCREMENT) and SQLite (AUTOINCREMENT)

## Rollback

To remove these tables (MySQL):
```sql
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS service_packages;
DROP TABLE IF EXISTS reminders;

ALTER TABLE services 
    DROP COLUMN registrar,
    DROP COLUMN domain_expiry_date,
    DROP COLUMN hosting_provider,
    DROP COLUMN hosting_renewal_date,
    DROP COLUMN ssl_status,
    DROP COLUMN ssl_expiry_date,
    DROP COLUMN uptime_monitor_url,
    DROP COLUMN uptime_status,
    DROP COLUMN last_uptime_check;
```

Or using CodeIgniter:
```bash
php spark migrate:rollback
```

## Support

For issues or questions about the database schema:
1. Check migration files in `app/Database/Migrations/`
2. Review model files in `app/Models/`
3. Check the main README.md for feature documentation
