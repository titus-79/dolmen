<?php
// public/index.php
//echo "Hello World!";


// Activation des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion de l'autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\core\Router;
use Titus\Dolmen\Controllers\HomeController;

try {
    // Initialisation du routeur - c'est ici que manquait l'instanciation
    $router = new Router();

    // Définition des routes
    $router->get('/', 'HomeController@index');
    $router->get('/portfolio', 'PortfolioController@index');
    $router->get('/portfolio/{id}', 'PortfolioController@show');

    // Exécution du routeur
    $router->run();
} catch (Exception $e) {
    // Gestion des erreurs
    echo "Une erreur est survenue : " . $e->getMessage();
}