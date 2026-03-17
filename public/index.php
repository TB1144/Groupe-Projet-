<?php
session_start();

// inclure classes
spl_autoload_register(function ($class) {
    $paths = ['app/Controllers/', 'app/Models/', 'config/'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/../app/Router.php';

// instancier routeur
$router = new Router();

// inclusion des routes
//idéalement, on devrait inclure un fichier de routes séparé, mais pour la simplicité, on les met ici

$router->get('/', 'HomeController@index');
$router->get('/offres', 'OffreController@index');
$router->get('/offres/{id}', 'OffreController@show');
$router->post('/login', 'AuthController@login');

// ON LANCE LE ROUTEUR
$router->run();
?>