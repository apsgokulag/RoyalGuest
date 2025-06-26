<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('/login', 'AuthController::index');
$routes->post('/login', 'AuthController::login');
$routes->get('/signup', 'AuthController::signup');
$routes->post('/signup', 'AuthController::register'); // Changed from /register to /signup
$routes->post('/register', 'AuthController::register'); // Keep this for backward compatibility
$routes->get('/logout', 'AuthController::logout');
$routes->get('/home', 'AuthController::home');

// API Routes
$routes->group('api', ['namespace' => 'App\Controllers\API'], function($routes) {
    // Auth
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->get('me', 'AuthController::me', ['filter' => 'jwt']);
    
    // Guests API
    $routes->get('guests', 'GuestsController::index', ['filter' => 'jwt']);
    $routes->get('guests/(:num)', 'GuestsController::show/$1', ['filter' => 'jwt']);
    $routes->post('guests', 'GuestsController::create', ['filter' => 'jwt']);
    $routes->put('guests/(:num)', 'GuestsController::update/$1', ['filter' => 'jwt']);
    $routes->delete('guests/(:num)', 'GuestsController::delete/$1', ['filter' => 'jwt']);
    
    // Service Requests API
    $routes->get('requests', 'RequestsController::index', ['filter' => 'jwt']);
    $routes->get('requests/(:num)', 'RequestsController::show/$1', ['filter' => 'jwt']);
    $routes->post('requests', 'RequestsController::create', ['filter' => 'jwt']);
    $routes->put('requests/(:num)/status', 'RequestsController::updateStatus/$1', ['filter' => 'jwt']);
    $routes->delete('requests/(:num)', 'RequestsController::delete/$1', ['filter' => 'jwt']);
});

// Admin Panel Routes
$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');
    
    // Guests Management
    $routes->get('guests', 'GuestsController::index');
    $routes->get('guests/create', 'GuestsController::create');
    $routes->post('guests/store', 'GuestsController::store');
    $routes->get('guests/edit/(:num)', 'GuestsController::edit/$1');       // Edit form
    $routes->post('guests/update/(:num)', 'GuestsController::update/$1');  // Submit update
    $routes->get('guests/delete/(:num)', 'GuestsController::delete/$1');   // Delete

    // Service Requests Management
    $routes->get('requests', 'RequestsController::index');
    $routes->get('requests/create', 'RequestsController::create');
    $routes->post('requests', 'RequestsController::store');
    $routes->post('requests/store', 'RequestsController::store');
    $routes->get('requests/edit/(:num)', 'RequestsController::edit/$1');       
    $routes->post('requests/update/(:num)', 'RequestsController::update/$1');  
});