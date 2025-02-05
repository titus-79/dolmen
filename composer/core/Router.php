<?php
// composer/core/Router.php

namespace Titus\Dolmen\core;

class Router {
    private array $routes = [];

    public function get(string $path, string $handler): void {
        // Stocke la route avec sa méthode et son contrôleur
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function run(): void {
        // Récupère la méthode HTTP (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Récupère le chemin de l'URL
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Si la route existe
        if (isset($this->routes[$method][$path])) {
            // Récupère le contrôleur et la méthode (exemple: 'HomeController@index')
            [$controller, $action] = explode('@', $this->routes[$method][$path]);

            // Ajoute le namespace complet
            $controller = "Titus\\Dolmen\\Controllers\\" . $controller;

            // Vérifie si le contrôleur existe
            if (class_exists($controller)) {
                // Crée une instance du contrôleur et appelle la méthode
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                    return;
                }
            }
        }

        // Si on arrive ici, c'est que la route n'existe pas
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
    }
}