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

        foreach ($this->routes[$method] as $routePath => $callback) {
            // Remplacement des {id} par une regex simple
            $regex = "@^" . preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $routePath) . "$@";
            
            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                list($controllerName, $methodName) = explode('@', $callback);
                
                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        // Si aucune route ne match
        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}
?>