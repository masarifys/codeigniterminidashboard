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
    
    // Client management
    $routes->get('clients', 'Admin::clients');
    $routes->get('client/(:num)', 'Admin::clientDetail/$1');
    $routes->get('client/create', 'Admin::createClient');
    $routes->post('client/create', 'Admin::createClient');
    $routes->get('client/(:num)/edit', 'Admin::editClient/$1');
    $routes->post('client/(:num)/edit', 'Admin::editClient/$1');
    $routes->get('client/(:num)/delete', 'Admin::deleteClient/$1');
    
    // Service management
    $routes->get('services', 'Admin::services');
    $routes->get('service/(:num)/edit', 'Admin::editService/$1');
    $routes->post('service/(:num)/edit', 'Admin::editService/$1');
    
    // Package management
    $routes->get('packages', 'Admin::packages');
    $routes->get('package/create', 'Admin::createPackage');
    $routes->post('package/create', 'Admin::createPackage');
    $routes->get('package/(:num)/edit', 'Admin::editPackage/$1');
    $routes->post('package/(:num)/edit', 'Admin::editPackage/$1');
    $routes->get('package/(:num)/delete', 'Admin::deletePackage/$1');
    
    // Monitoring
    $routes->get('monitoring', 'Admin::monitoring');
    
    // Billing
    $routes->get('billing', 'Admin::billing');
    $routes->get('invoice/create', 'Admin::createInvoice');
    $routes->post('invoice/create', 'Admin::createInvoice');
    
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