<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    $paths = ['app/controllers/', 'app/models/', 'config/'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . strtolower($class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/../app/models/auth_middleware.php';

class Router
{
    private array $routes = [];

    public function get(string $path, string $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, string $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $filePath = __DIR__ . $path;
        if (file_exists($filePath) && !is_dir($filePath)) {
            return;
        }

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "405 - Method Not Allowed";
            return;
        }

        foreach ($this->routes[$method] as $routePath => $callback) {
            $regex = "@^" . preg_replace('/\{[a-zA-Z0-9]+\}/', '([a-zA-Z0-9]+)', $routePath) . "$@";

            if (preg_match($regex, $path, $matches)) {
                array_shift($matches);
                $matches = array_map(fn($m) => is_numeric($m) ? (int)$m : $m, $matches);
                [$controllerName, $methodName] = explode('@', $callback);
                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../app/views/errors/404.php';
    }
}

$router = new Router();

$router->get('/', 'HomeController@index');

// Offres
$router->get('/api/offres/autocomplete-titre',      'OffreController@autocompleteTitre');
$router->get('/api/offres/autocomplete-ville',      'OffreController@autocompleteVille');
$router->get('/offres',                             'OffreController@index');
$router->get('/offres/creer',                       'OffreController@create');
$router->post('/offres/creer',                      'OffreController@create');
$router->get('/offres/{id}',                        'OffreController@show');
$router->get('/offres/{id}/modifier',               'OffreController@edit');
$router->post('/offres/{id}/modifier',              'OffreController@edit');
$router->post('/offres/{id}/supprimer',             'OffreController@delete');
$router->get('/offres/{id}/postuler',               'CandidatureController@form');
$router->post('/offres/{id}/postuler',              'CandidatureController@postuler');

// Entreprises
$router->get('/api/entreprises/autocomplete',       'EntrepriseController@autocomplete');
$router->get('/api/entreprises/autocomplete-ville', 'EntrepriseController@autocompleteVille');
$router->get('/entreprises',                        'EntrepriseController@index');
$router->get('/entreprises/creer',                  'EntrepriseController@create');
$router->post('/entreprises/creer',                 'EntrepriseController@create');
$router->get('/entreprises/{id}',                   'EntrepriseController@show');
$router->get('/entreprises/{id}/modifier',          'EntrepriseController@edit');
$router->post('/entreprises/{id}/modifier',         'EntrepriseController@edit');
$router->post('/entreprises/{id}/supprimer',        'EntrepriseController@delete');
$router->post('/entreprises/{id}/evaluer',          'EntrepriseController@evaluer');

// Candidatures
$router->get('/candidatures',                       'CandidatureController@index');
$router->post('/candidatures/{id}/supprimer',       'CandidatureController@supprimer');

// Wishlist
$router->get('/wishlist',                           'WishlistController@index');
$router->post('/wishlist/toggle',                   'WishlistController@toggle');

// Auth — plus de /register public
$router->get('/login',                              'AuthController@loginForm');
$router->post('/login',                             'AuthController@login');
$router->get('/logout',                             'AuthController@logout');

// Utilisateurs
$router->get('/utilisateurs',                       'UserController@index');
$router->get('/utilisateurs/creer',                 'UserController@create');
$router->post('/utilisateurs/creer',                'UserController@create');
$router->get('/utilisateurs/{id}/modifier',         'UserController@edit');
$router->post('/utilisateurs/{id}/modifier',        'UserController@edit');
$router->post('/utilisateurs/{id}/supprimer',       'UserController@delete');

// Statistiques, Contact, Mentions
$router->get('/statistiques',                       'StatistiquesController@index');
$router->get('/contact',                            'ContactController@index');
$router->post('/contact',                           'ContactController@index');
$router->get('/mentions-legales',                   'MentionsController@index');

$router->run();