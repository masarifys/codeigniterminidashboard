<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Auth::index');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('logout', 'Auth::logout');
    
    // Password Reset routes
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::forgotPassword');
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('reset-password/(:any)', 'Auth::resetPassword/$1');
});

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('delete-user/(:num)', 'Admin::deleteUser/$1');
    $routes->get('toggle-user/(:num)', 'Admin::toggleUserStatus/$1');
    
    // Gmail OAuth management
    $routes->get('gmail-setup', 'GmailAuth::index');
    $routes->get('gmail-setup/authorize', 'GmailAuth::authorize');
    $routes->get('gmail-setup/test', 'GmailAuth::testEmail');
    $routes->get('gmail-setup/revoke', 'GmailAuth::revoke');
});

// Gmail OAuth callback (di luar admin group)
$routes->get('auth/gmail-callback', 'GmailAuth::callback');

// Client routes
$routes->group('client', function($routes) {
    $routes->get('dashboard', 'Client::dashboard');
    $routes->get('services', 'Client::services'); // Menu semua layanan
    $routes->get('service/(:num)', 'Client::serviceDetail/$1'); // Service detail
    $routes->get('service/(:num)/upgrade', 'Client::upgradeService/$1'); // Upgrade service
    $routes->get('service/(:num)/renew', 'Client::renewService/$1'); // Renew service
    $routes->post('service/(:num)/cancel', 'Client::cancelService/$1'); // Cancel service
    $routes->get('invoices', 'Client::invoices'); // Menu invoices
    $routes->get('invoice/(:num)', 'Client::invoiceDetail/$1');
    $routes->get('invoice/(:num)/pay', 'Client::payInvoice/$1');
    $routes->post('payment/callback', 'Client::paymentCallback');
    $routes->get('profile', 'Client::profile');
    $routes->post('profile', 'Client::profile');
    $routes->get('support', 'Client::support'); // Live support
    $routes->get('tickets', 'Client::tickets'); // Trouble tickets
    $routes->get('test-duitku', 'Client::testDuitku'); // Test Duitku configuration (dev only)
});