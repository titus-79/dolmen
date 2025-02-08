<?php

use Titus\Dolmen\core\Router;
use Titus\Dolmen\Controllers\HomeController;

// Créez une instance du routeur
$router = new Router();


// Définition des routes
$router->get('', 'HomeController@index');
$router->get('/', 'HomeController@index');
$router->get('/about', 'AboutController@about');
$router->get('/portfolio', 'PortfolioController@portfolio');
$router->get('/shop', 'ShopController@index');
$router->get('/shop/tirage/{id}', 'ShopController@show');
$router->post('/shop/add-to-cart/{id}', 'ShopController@addToCart');
$router->get('/shop/cart', 'ShopController@cart');
$router->post('/shop/checkout', 'ShopController@checkout');$router->get('/events', 'EventController@index');
$router->get('/events/upcoming', 'EventController@upcoming');
$router->get('/events/create', 'EventController@create');
$router->post('/events/create', 'EventController@store');
$router->get('/events/{id}', 'EventController@show');
$router->get('/contact', 'ContactController@index');
$router->get('/contact', 'ContactController@index');
$router->post('/contact/submit', 'ContactController@submit');

// Routes d'authentification
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegistrationForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/account', 'AccountController@index');
$router->get('/account/edit', 'AccountController@editProfile');
$router->post('/account/edit', 'AccountController@editProfile');
$router->get('/account/orders', 'AccountController@orderHistory');

// Route protégée pour le compte utilisateur
$router->get('/account', 'AccountController@index');

// Routes d'administration
$router->get('/admin', 'AdminController@index');

$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/users/create', 'AdminController@createUser');
$router->post('/admin/users/create', 'AdminController@createUser');
$router->get('/admin/users/edit/{id}', 'AdminController@editUser');
$router->post('/admin/users/edit/{id}', 'AdminController@editUser');
$router->get('/admin/users/delete/{id}', 'AdminController@deleteUser');

$router->get('/admin/newsletters', 'AdminController@newsletters');
$router->get('/admin/newsletters/create', 'AdminController@createNewsletter');
$router->post('/admin/newsletters/create', 'AdminController@createNewsletter');

$router->get('/admin/events', 'AdminController@events');
$router->get('/admin/events/edit/{id?}', 'AdminController@editEvent');
$router->post('/admin/events/edit/{id?}', 'AdminController@editEvent');
$router->get('/admin/events/delete/{id}', 'AdminController@deleteEvent');

$router->get('/admin/portfolio', 'AdminController@portfolio');
$router->get('/admin/portfolio/edit/{id?}', 'AdminController@editPortfolio');
$router->post('/admin/portfolio/edit/{id?}', 'AdminController@editPortfolio');
$router->get('/admin/portfolio/delete/{id}', 'AdminController@deletePortfolio');

$router->get('/admin/prints', 'AdminController@prints');
$router->get('/admin/prints/edit/{id?}', 'AdminController@editPrint');
$router->post('/admin/prints/edit/{id?}', 'AdminController@editPrint');
$router->get('/admin/prints/delete/{id}', 'AdminController@deletePrint');

$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/orders/{id}', 'AdminController@orderDetails');
$router->post('/admin/orders/{id}/status', 'AdminController@updateOrderStatus');

error_log("Routes configurées: " . print_r($router->getRoutes(), true));


// Exécutez le routeur
$router->run();