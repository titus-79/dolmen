<?php
// composer/core/Router.php

namespace Titus\Dolmen\core;

class Router {
    private array $routes = [];

    public function get(string $path, string $handler): void {
        // Stocke la route avec sa méthode et son contrôleur
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, string $handler): void {
        // Convertit les paramètres de route (ex: {id}) en expression régulière
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$method][$pattern] = [
            'handler' => $handler,
            'path' => $path
        ];
    }
    public function getRoutes(): array {
        return $this->routes;
    }

    public function run(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        error_log("Router::run - Méthode: $method, URI: $uri");

        foreach ($this->routes[$method] ?? [] as $pattern => $route) {
            if (preg_match($pattern, $uri, $matches)) {
                // Extrait les paramètres de l'URL
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$controller, $action] = explode('@', $route['handler']);
                $controller = "Titus\\Dolmen\\Controllers\\" . $controller;

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $action)) {
                        // Appelle la méthode avec les paramètres extraits de l'URL
                        call_user_func_array([$controllerInstance, $action], $params);
                        return;
                    } else {
                        error_log("Méthode non trouvée: $action dans $controller");
                    }
                } else {
                    error_log("Contrôleur non trouvé: $controller");
                }
            }
        }

        // Si aucune route ne correspond
        header("HTTP/1.0 404 Not Found");
        error_log("Route non trouvée pour $method $uri");
        echo "Page non trouvée";
    }

}