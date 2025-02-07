<?php
// test_routes.php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\core\Router;

echo "Test des routes configurées...\n\n";

$router = new Router();

// Récupérer toutes les routes définies
$routes = $router->getRoutes();

// Afficher les routes
echo "Routes définies :\n";
foreach ($routes as $method => $methodRoutes) {
    foreach ($methodRoutes as $path => $handler) {
        echo "[$method] $path => $handler\n";
    }
}

// Tester la route /account spécifiquement
echo "\nTest de la route /account :\n";
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/account';

try {
    $router->run();
    echo "Route /account traitée avec succès\n";
} catch (Exception $e) {
    echo "Erreur lors du traitement de la route /account : " . $e->getMessage() . "\n";
}

//docker-compose exec php php test_routes.php