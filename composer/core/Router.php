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

    public function getRoutes(): array {
        return $this->routes;
    }

    public function run(): void {
        // Récupère la méthode HTTP (GET, POST, etc.)
        $method = $_SERVER['REQUEST_METHOD'];

        // Récupère le chemin de l'URL
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Log pour le débogage
        error_log("Router::run - Méthode: $method, Chemin: $path");
        error_log("Routes disponibles: " . print_r($this->routes, true));

        // Si la route existe
        if (isset($this->routes[$method][$path])) {
            // Récupère le contrôleur et la méthode (exemple: 'HomeController@index')
            [$controller, $action] = explode('@', $this->routes[$method][$path]);

            // Ajoute le namespace complet
            $controller = "Titus\\Dolmen\\Controllers\\" . $controller;

            // Vérifie si le contrôleur existe
            if (class_exists($controller)) {
                // Log pour le débogage
                error_log("Contrôleur trouvé: $controller");
                // Crée une instance du contrôleur et appelle la méthode
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $action)) {
                    error_log("Méthode trouvée: $action");
                    $controllerInstance->$action();
                    return;
                } else {
                    error_log("Méthode non trouvée: $action");
                }
            } else {
                error_log("Contrôleur non trouvé: $controller");
            }
        } else {
            error_log("Route non trouvée pour $method $path");
        }

        // Si on arrive ici, c'est que la route n'existe pas
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
    }
}