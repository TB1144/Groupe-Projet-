<?php
session_start();

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

require_once __DIR__ . '/../router.php';

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/offres', 'OffreController@index');
$router->get('/offres/creer', 'OffreController@create');
$router->post('/offres/creer', 'OffreController@create');
$router->get('/offres/{id}', 'OffreController@show');
$router->get('/offres/{id}/modifier', 'OffreController@edit');
$router->post('/offres/{id}/modifier', 'OffreController@edit');
$router->post('/offres/{id}/supprimer', 'OffreController@delete');
$router->post('/wishlist/toggle', 'WishlistController@toggle');
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

$router->run();
