<?php

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 🐾 1. allow static files (css, js, images...)
        $filePath = __DIR__ . '/public' . $path;
        if (file_exists($filePath) && !is_dir($filePath)) {
            return false; // let apache handle it
        }

        // 🐾 2. check if method exists
        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "405 - Method Not Allowed";
            return;
        }

        // 🐾 3. match routes
        foreach ($this->routes[$method] as $routePath => $callback) {

            $regex = "@^" . preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $routePath) . "$@";

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);

                list($controllerName, $methodName) = explode('@', $callback);

                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        // 🐾 4. 404 fallback
        http_response_code(404);
        echo "404 - Page non trouvée nyaaa ;-;";
    }
}