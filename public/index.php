<?php
// public/index.php
//echo "Hello World!";


// Activation des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion de l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Inclusion du fichier de routes
require_once __DIR__ . '/../config/routes.php';

use Titus\Dolmen\core\Router;
use Titus\Dolmen\Controllers\HomeController;

try {
//    // Initialisation du routeur - c'est ici que manquait l'instanciation
//    $router = new Router();
//
//    // Définition des routes
//    $router->get('/', 'HomeController@index');
//    $router->get('/portfolio', 'PortfolioController@index');
//    $router->get('/portfolio/{id}', 'PortfolioController@show');
//    // Routes d'authentification
//    $router->get('/login', 'AuthController@showLoginForm');
//    $router->post('/login', 'AuthController@login');
//    $router->get('/register', 'AuthController@showRegistrationForm');
//    $router->post('/register', 'AuthController@register');
//    $router->get('/logout', 'AuthController@logout');
//
//// Route protégée pour le compte utilisateur
//    $router->get('/account', 'AccountController@index');
//
//    // Exécution du routeur
//    $router->run();
//    echo "Routes loaded successfully";
} catch (Exception $e) {
    // Gestion des erreurs
    echo "Une erreur est survenue : " . $e->getMessage();
}