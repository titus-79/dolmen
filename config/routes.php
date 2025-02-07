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

error_log("Routes configurées: " . print_r($router->getRoutes(), true));


// Exécutez le routeur
$router->run();