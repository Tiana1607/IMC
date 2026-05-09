<?php

use CodeIgniter\Router\RouteCollection;

// const ADMIN_REGIME_ID_ROUTE = 'admin/regimes/(:num)';
// const ADMIN_ACTIVITY_ID_ROUTE = 'admin/activities/(:num)';

/**
 * @var RouteCollection $routes
 */

// Landing
$routes->get('/', 'Home::index');

// Auth routes
//$routes->get('auth/', 'Auth::index');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->post('auth/ajax_check_login_email', 'Auth::ajax_check_login_email');
$routes->post('auth/ajax_check_login_password', 'Auth::ajax_check_login_password');
$routes->get('auth/register/step1', 'Auth::register_step1');
$routes->post('auth/register/step1', 'Auth::register_step1');
$routes->post('auth/ajax_check_email', 'Auth::emailExists');
$routes->get('auth/register/step2', 'Auth::register_step2');
$routes->post('auth/register/step2', 'Auth::register_step2');
$routes->get('auth/logout', 'Auth::logout');

// Dashboard
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Admin routes
$routes->get('admin', 'Admin\\DashboardController::index', ['filter' => 'auth']);
$routes->post('admin', 'Admin\\DashboardController::index', ['filter' => 'auth']);

$routes->get('admin/regimes', 'Admin\\RegimeController::index', ['filter' => 'auth']);
$routes->post('admin/regimes', 'Admin\\RegimeController::store', ['filter' => 'auth']);
$routes->get('admin/regimes/(:num)', 'Admin\\RegimeController::edit/$1', ['filter' => 'auth']);
$routes->post('admin/regimes/(:num)', 'Admin\\RegimeController::update/$1', ['filter' => 'auth']);
$routes->delete('admin/regimes/(:num)', 'Admin\\RegimeController::delete/$1', ['filter' => 'auth']);

$routes->get('admin/activities', 'Admin\\ActivityController::index', ['filter' => 'auth']);
$routes->post('admin/activities', 'Admin\\ActivityController::store', ['filter' => 'auth']);
$routes->get('admin/activities/(:num)', 'Admin\\ActivityController::edit/$1', ['filter' => 'auth']);
$routes->post('admin/activities/(:num)', 'Admin\\ActivityController::update/$1', ['filter' => 'auth']);
$routes->delete('admin/activities/(:num)', 'Admin\\ActivityController::delete/$1', ['filter' => 'auth']);
