<?php
// core/Router.php
namespace Core;

class Router {
    private array $routes = [];

    // Méthode pour enregistrer une route GET
    public function get(string $path, string $controller): void {
        // Stocke la route avec sa méthode et son contrôleur
        $this->routes['GET'][$path] = $controller;
    }

    // Méthode pour enregistrer une route POST
    public function post(string $path, string $controller): void {
        $this->routes['POST'][$path] = $controller;
    }

    // Méthode qui exécute le contrôleur correspondant à l'URL actuelle
    public function run(): void {
        // Récupère la méthode HTTP (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];
        // Récupère le chemin de l'URL
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Vérifie si la route existe
        if (isset($this->routes[$method][$path])) {
            // Récupère le contrôleur et la méthode (exemple: 'HomeController@index')
            [$controller, $action] = explode('@', $this->routes[$method][$path]);

            // Ajoute le namespace complet
            $controller = "App\\Controllers\\" . $controller;

            // Crée une instance du contrôleur et appelle la méthode
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                    return;
                }
            }
        }

        // Si la route n'existe pas, affiche une erreur 404
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
    }
}