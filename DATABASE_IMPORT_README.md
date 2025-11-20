# Database Import Guide

## Quick Setup (SQLite)

File `database_setup_complete.sql` sudah berisi struktur database lengkap beserta data testing.

### Cara Import:

1. **Pastikan direktori writable dapat diakses:**
   ```bash
   chmod -R 777 writable/
   ```

2. **Import database (pilih salah satu):**

   **Opsi A - Import langsung ke SQLite:**
   ```bash
   sqlite3 writable/database.db < database_setup_complete.sql
   ```

   **Opsi B - Import ke MySQL/MariaDB:**
   ```bash
   mysql -u username -p database_name < database_setup.sql
   ```

3. **Konfigurasi database di `.env`:**

   **Untuk SQLite:**
   ```env
   CI_ENVIRONMENT = development
   
   database.default.hostname = 
   database.default.database = /path/to/project/writable/database.db
   database.default.username = 
   database.default.password = 
   database.default.DBDriver = SQLite3
   database.default.DBPrefix = 
   ```

   **Untuk MySQL:**
   ```env
   CI_ENVIRONMENT = development
   
   database.default.hostname = localhost
   database.default.database = your_database_name
   database.default.username = your_username
   database.default.password = your_password
   database.default.DBDriver = MySQLi
   database.default.DBPrefix = 
   database.default.port = 3306
   ```

## Data Testing

Database sudah berisi data testing:

**User Login:**
- Username: `testclient`
- Password: `password123`
- Email: `client@test.com`

**Services (3 items):**
1. Unlimited L - example.com (Active)
   - Username: user_1
   - Password: pass_1
   - Server: server1.example.com
   - Panel URL: https://panel1.example.com

2. VPS Standard - subdomain.example.com (Active)
   - Username: user_2
   - Password: pass_2
   - Server: server2.example.com
   - Panel URL: https://panel2.example.com

3. Shared Hosting - test.example.com (Pending)
   - Username: user_3
   - Password: pass_3
   - Server: server3.example.com
   - Panel URL: https://panel3.example.com

**Invoices:**
- 3 invoice dengan status berbeda (unpaid, past_due, paid)

**Tickets:**
- 3 ticket dengan status berbeda (open, answered, closed)

## Struktur Database Baru

### Tabel: services
Kolom baru yang ditambahkan:
- `username` VARCHAR(255) NULL
- `password` VARCHAR(255) NULL
- `server` VARCHAR(255) NULL
- `panel_url` VARCHAR(255) NULL

### Tabel: service_cancellations (BARU)
Untuk menyimpan permintaan pembatalan layanan:
- `id` INT PRIMARY KEY
- `service_id` INT
- `user_id` INT
- `reason` TEXT
- `cancellation_type` ENUM('immediate', 'end_of_billing_period')
- `status` ENUM('pending', 'approved', 'cancelled')
- `requested_at` DATETIME
- `processed_at` DATETIME
- `created_at` DATETIME
- `updated_at` DATETIME

## Fitur Baru

### Halaman Service Detail
URL: `/client/service/{id}`

**Fitur:**
1. Menampilkan credentials (username, password, server)
2. Toggle show/hide password
3. Copy to clipboard untuk setiap field
4. Link ke panel login
5. Sidebar dengan menu navigasi
6. Status card dengan info lengkap
7. Actions: Upgrade, Perpanjang, Batalkan Layanan

### Halaman List Services
**Update:**
- Kolom IP dihapus dari tabel
- Tombol "Manage" sekarang redirect ke halaman detail service

## Routes Baru

```php
$routes->get('service/(:num)', 'Client::serviceDetail/$1');
$routes->get('service/(:num)/upgrade', 'Client::upgradeService/$1');
$routes->get('service/(:num)/renew', 'Client::renewService/$1');
$routes->post('service/(:num)/cancel', 'Client::cancelService/$1');
```

## File yang Ditambahkan/Diubah

### Baru:
- `app/Views/client/service_detail.php`
- `public/assets/css/service-detail.css`
- `app/Database/Migrations/2025-11-20-185008_AddCredentialsToServicesTable.php`
- `app/Database/Migrations/2025-11-20-185015_CreateServiceCancellationsTable.php`
- `database_setup_complete.sql`

### Diubah:
- `app/Views/client/services.php` (hapus kolom IP)
- `app/Controllers/Client.php` (tambah methods baru)
- `app/Config/Routes.php` (tambah routes baru)
- `app/Models/ServiceModel.php` (tambah allowedFields baru)

## Testing

Jalankan server development:
```bash
php spark serve
```

Akses:
- Login: http://localhost:8080/auth/login
- Services: http://localhost:8080/client/services
- Service Detail: http://localhost:8080/client/service/1

## Troubleshooting

### SQLite Permission Error
```bash
chmod 666 writable/database.db
chmod 777 writable/
```

### Database Connection Error
Pastikan path database di `.env` menggunakan absolute path untuk SQLite.

### Migration Already Ran
Database dump sudah include migration history, jadi tidak perlu menjalankan `php spark migrate` lagi.
