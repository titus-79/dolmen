<?php

use Core\Router;

require_once '../core/Router.php';

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/portfolio', 'PortfolioController@index');
$router->get('/shop', 'ShopController@index');
$router->get('/event', 'EventController@index');

$router->run();