<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing
$routes->get('/', 'Home::index');

// Auth routes
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
