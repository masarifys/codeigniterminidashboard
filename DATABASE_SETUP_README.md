# Database Setup Guide

This guide explains how to set up the database for the Client Dashboard application.

## Files Included

1. **database_setup.sql** - MySQL/MariaDB version
2. **database_setup_sqlite.sql** - SQLite version

## Option 1: Using MySQL/MariaDB (Recommended for Production)

### Method A: Using MySQL Command Line

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE codeigniter_dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Use the database
USE codeigniter_dashboard;

# Import the SQL file
SOURCE database_setup.sql;

# Or use this one-liner from terminal
mysql -u root -p codeigniter_dashboard < database_setup.sql
```

### Method B: Using phpMyAdmin

1. Open phpMyAdmin in your browser
2. Create a new database named `codeigniter_dashboard`
3. Select the database
4. Go to "Import" tab
5. Choose file `database_setup.sql`
6. Click "Go" to execute

### Update .env Configuration

After creating the database, update your `.env` file:

```env
database.default.hostname = localhost
database.default.database = codeigniter_dashboard
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

## Option 2: Using SQLite (Good for Development/Testing)

### Using Command Line

```bash
# Navigate to writable directory
cd writable/

# Create/open database and import
sqlite3 database.db < ../database_setup_sqlite.sql
```

### Update .env Configuration

```env
database.default.DBDriver = SQLite3
database.default.database = /absolute/path/to/writable/database.db
```

## Option 3: Using CodeIgniter Migrations (Recommended)

If you prefer to use CodeIgniter's migration system:

```bash
# Run all migrations
php spark migrate

# Seed test data
php spark db:seed TestDataSeeder
```

## Database Schema Overview

### Tables Created

1. **users** - User authentication and profile management
   - Stores admin and client user accounts
   - Includes password reset functionality
   
2. **services** - Client services/products
   - Tracks hosting services, domains, VPS, etc.
   - Includes billing cycle, pricing, and status
   
3. **invoices** - Billing and payments
   - Links to services and users
   - Tracks payment status (unpaid, paid, past_due, cancelled)
   
4. **tickets** - Support ticket system
   - Priority levels (low, medium, high)
   - Status tracking (open, answered, customer_reply, closed)

### Relationships

```
users (1) ──── (many) services
users (1) ──── (many) invoices
users (1) ──── (many) tickets
services (1) ──── (many) invoices
```

## Test Data Included

The SQL files include sample data for testing:

### Test User
- **Username:** testclient
- **Password:** password123
- **Email:** client@test.com
- **Role:** client

### Sample Services (3 records)
- Unlimited L (Annually) - Active
- VPS Standard (Monthly) - Active  
- Shared Hosting (Quarterly) - Pending

### Sample Invoices (3 records)
- INV-2025-0001: Rp 1.680.000,00 - Unpaid
- INV-2025-0002: Rp 250.000,00 - Past Due
- INV-2025-0003: Rp 150.000,00 - Paid

### Sample Tickets (3 records)
- Server not responding (High priority, Open)
- Question about billing (Medium priority, Answered)
- Need help with setup (Low priority, Closed)

## Verification

After importing, verify the setup:

```sql
-- Check tables
SHOW TABLES;

-- Check record counts
SELECT 
    (SELECT COUNT(*) FROM users) AS total_users,
    (SELECT COUNT(*) FROM services) AS total_services,
    (SELECT COUNT(*) FROM invoices) AS total_invoices,
    (SELECT COUNT(*) FROM tickets) AS total_tickets;

-- View sample data
SELECT * FROM users;
SELECT * FROM services;
SELECT * FROM invoices;
SELECT * FROM tickets;
```

## Troubleshooting

### Foreign Key Constraints Error
If you get foreign key constraint errors, make sure you're dropping tables in the correct order (tickets → invoices → services → users).

### Character Set Issues
For MySQL, ensure your database uses `utf8mb4` character set:
```sql
ALTER DATABASE codeigniter_dashboard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Permission Errors
Make sure your database user has the following privileges:
```sql
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON codeigniter_dashboard.* TO 'your_user'@'localhost';
FLUSH PRIVILEGES;
```

## Next Steps

After setting up the database:

1. Start the development server:
   ```bash
   php spark serve
   ```

2. Access the application:
   ```
   http://localhost:8080
   ```

3. Login with test credentials:
   - Username: `testclient`
   - Password: `password123`

4. Explore the client dashboard features:
   - Dashboard with statistics
   - Services management
   - Invoice tracking
   - Support tickets

## Notes

- The password `password123` is hashed using bcrypt
- All timestamps use DATETIME format
- Prices are stored as DECIMAL(10,2) for accurate financial calculations
- Foreign keys ensure referential integrity
- Indexes are added for optimized query performance

## Security Recommendations for Production

1. Change the default test user password
2. Use strong passwords for database users
3. Restrict database user permissions
4. Enable SSL for database connections
5. Regularly backup your database
6. Keep your database server updated

## Support

If you encounter any issues during setup, please check:
- Database server is running
- Correct credentials in `.env` file
- PHP has required database extensions enabled
- File permissions on writable directory (for SQLite)
