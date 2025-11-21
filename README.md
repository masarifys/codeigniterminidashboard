# CodeIgniter Mini Dashboard

A lightweight billing and client management dashboard built with CodeIgniter 4.

## Features

- User authentication (Admin and Client roles)
- Client management
- Service and package management
- Invoice generation and payment tracking
- Ticket system
- Gmail OAuth integration for email notifications
- Duitku payment gateway integration

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) for MySQL database
- [libcurl](http://php.net/manual/en/curl.requirements.php) for HTTP requests
- json (enabled by default)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/masarifys/codeigniterminidashboard.git
cd codeigniterminidashboard
```

2. Install dependencies (if needed):
```bash
composer install
```

3. Configure your database and environment settings in `.env` file (copy from `env`):
```bash
cp env .env
```

Edit `.env` and configure:
- Database credentials
- Base URL
- Encryption key

4. Run database migrations:
```bash
php spark migrate
```

5. Seed the database with initial data (optional):
```bash
php spark db:seed AdminPanelSeeder
```

## Clean URLs Configuration

This application is configured with clean URLs (without index.php). Make sure:

1. **Apache mod_rewrite** is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

2. **Root `.htaccess`** redirects to public folder (already configured)

3. **Public `.htaccess`** removes index.php from URLs (already configured)

4. **App config** has `$indexPage = ''` (already configured in `app/Config/App.php`)

5. Your Apache virtual host should point to the `public` folder:
```apache
DocumentRoot /path/to/project/public
```

## Usage

### Default Login

After seeding the database, you can login with:
- **Admin**: Check seeder for default credentials
- **Client**: Register a new account or use seeded credentials

### Authentication

The application uses session-based authentication with the following features:
- Secure password hashing
- Account activation status check
- Role-based dashboard redirects
- Password reset functionality

### URLs

All URLs are clean without index.php:
- Login: `https://yourdomain.com/auth/login`
- Dashboard: `https://yourdomain.com/admin/dashboard` or `https://yourdomain.com/client/dashboard`
- Logout: `https://yourdomain.com/auth/logout`

## Security Features

- CSRF protection enabled
- XSS filtering
- SQL injection protection via query builder
- Secure password hashing with bcrypt
- Security headers configured in .htaccess
- Session security

## About CodeIgniter 4

This project is built on CodeIgniter 4, a modern PHP framework. More information can be found at the [official site](https://codeigniter.com).
