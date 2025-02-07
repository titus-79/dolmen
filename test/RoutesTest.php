<?php
namespace Titus\Dolmen\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use Titus\Dolmen\core\Router;

class RoutesTest
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->setupRoutes();
    }

    private function setupRoutes(): void
    {
        // Configuration des routes de test
        $this->router->get('/login', 'AuthController@showLoginForm');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/account', 'AccountController@index');
        $this->router->get('/account/edit', 'AccountController@editProfile');
        $this->router->get('/', 'HomeController@index');
    }

    public function testRoutes(): void
    {
        echo "Test des routes configurées...\n\n";

        $routes = $this->router->getRoutes();

        echo "Routes GET définies :\n";
        foreach ($routes['GET'] ?? [] as $path => $handler) {
            echo "  GET $path => $handler\n";
            $this->testRoute('GET', $path, $handler);
        }

        echo "\nRoutes POST définies :\n";
        foreach ($routes['POST'] ?? [] as $path => $handler) {
            echo "  POST $path => $handler\n";
            $this->testRoute('POST', $path, $handler);
        }
    }

    private function testRoute(string $method, string $path, string $handler): void
    {
        [$controllerName, $action] = explode('@', $handler);
        $controllerClass = "Titus\\Dolmen\\Controllers\\$controllerName";

        echo "    Vérification du contrôleur '$controllerClass':\n";
        if (class_exists($controllerClass)) {
            echo "    ✅ Classe contrôleur trouvée\n";

            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                echo "    ✅ Méthode '$action' trouvée\n";
            } else {
                echo "    ❌ Méthode '$action' non trouvée\n";
            }
        } else {
            echo "    ❌ Classe contrôleur non trouvée\n";
        }
        echo "\n";
    }
}

// Exécution du test
$test = new RoutesTest();
$test->testRoutes();