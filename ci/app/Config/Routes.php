<?php

namespace Config;

use CodeIgniter\Config\Services;

$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// ============================================================
// API Routes
// ============================================================
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    // Transaction (requires API auth)
    $routes->group('transaction', ['filter' => 'apiauth'], function ($routes) {
        $routes->post('create', 'Api\Transaction::create');
        $routes->get('status/(:segment)', 'Api\Transaction::status/$1');
        $routes->post('registration/(:segment)', 'Api\Transaction::registration/$1');
    });

    // Webhook (no API auth - uses signature verification)
    $routes->post('webhook/payment', 'Webhook::payment');
});


// ============================================================
// Auth Routes
// ============================================================
$routes->get('login', 'Admin\Auth::login');
$routes->post('login', 'Admin\Auth::doLogin');
$routes->get('logout', 'Admin\Auth::logout');

// ============================================================
// Admin Dashboard Routes
// ============================================================
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    // Businesses
    $routes->get('businesses', 'Business::index');
    $routes->get('businesses/create', 'Business::create');
    $routes->post('businesses/store', 'Business::store');
    $routes->get('businesses/edit/(:segment)', 'Business::edit/$1');
    $routes->post('businesses/update/(:segment)', 'Business::update/$1');
    $routes->get('businesses/delete/(:segment)', 'Business::delete/$1');

    // Transactions
    $routes->get('transactions', 'Transaction::index');
    $routes->get('transactions/view/(:segment)', 'Transaction::view/$1');
    $routes->get('transactions/refresh/(:segment)', 'Transaction::refresh/$1');
    $routes->get('transactions/registration/(:segment)', 'Transaction::registration/$1');

    // Dashboard API
    $routes->get('api/stats', 'Dashboard::stats');
});

// ============================================================
// Home Route
// ============================================================
$routes->get('/', 'Home::index');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
