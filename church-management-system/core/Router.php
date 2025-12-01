<?php

namespace Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Normalize the request URI
        if (empty($requestUri) || $requestUri === '') {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callController($route['controller'], $route['action'], $matches);
                return;
            }
        }

        // Route not found
        http_response_code(404);
        echo "404 Not Found - Request URI: " . htmlspecialchars($requestUri) . " (Method: " . $requestMethod . ")";
    }

    private function convertToRegex($path)
    {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    private function callController($controllerName, $action, $params = [])
    {
        try {
            $controllerClass = 'Controllers\\' . $controllerName;

            if (!class_exists($controllerClass)) {
                throw new \Exception("Controller $controllerClass not found");
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $action)) {
                throw new \Exception("Action $action not found in $controllerClass");
            }

            call_user_func_array([$controller, $action], $params);
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
}
