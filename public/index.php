<?php
// session_start();si j'ai bien compris auth_middleware.php gère ça et doit être inclus dans chaque route protégée
// pas besoin de le faire ici globalement

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
require_once __DIR__ . '/../app/models/auth_middleware.php'; // gère session_start() et vérifie si l'utilisateur est connecté

$router = new Router();

$router->get('/', 'HomeController@index');

// Offres
$router->get('/offres', 'OffreController@index');
$router->get('/offres/creer', 'OffreController@create');
$router->post('/offres/creer', 'OffreController@create');
$router->get('/offres/{id}', 'OffreController@show');
$router->get('/offres/{id}/modifier', 'OffreController@edit');
$router->post('/offres/{id}/modifier', 'OffreController@edit');
$router->post('/offres/{id}/supprimer', 'OffreController@delete');

// Entreprises
$router->get('/entreprises', 'EntrepriseController@index');
$router->get('/entreprises/{id}', 'EntrepriseController@show');

// Candidatures
$router->get('/candidatures', 'CandidatureController@index');
$router->post('/candidatures/postuler', 'CandidatureController@postuler');

// Wishlist
$router->post('/wishlist/toggle', 'WishlistController@toggle');

// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Inscription
$router->get('/register', 'RegisterController@registerForm');
$router->post('/register', 'RegisterController@register');

// Statistiques & Contact
$router->get('/statistiques', 'StatistiquesController@index');
$router->get('/contact', 'ContactController@index');
$router->post('/contact', 'ContactController@index');

// Mentions légales
$router->get('/mentions-legales', 'MentionsController@index');

// Liste des utilisateurs
// Utilisateurs
$router->get('/utilisateurs', 'UserController@index');
$router->get('/utilisateurs/{id}', 'UserController@show');
$router->get('/utilisateurs/{id}/modifier', 'UserController@edit');
$router->post('/utilisateurs/{id}/modifier', 'UserController@edit');
$router->post('/utilisateurs/{id}/supprimer', 'UserController@delete');

$router->run();
