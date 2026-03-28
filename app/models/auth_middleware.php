<?php
/**
 * auth_middleware.php
 * 
 * À inclure en haut de chaque fichier / route protégée.
 * 
 * Usage :
 *   require __DIR__ . '/auth_middleware.php';           // connecté uniquement
 *   requireRole('admin');                               // admin uniquement
 *   requireRole(['admin', 'pilote']);                   // plusieurs rôles
 */

// Démarrage sécurisé de la session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // cookie envoyé uniquement en HTTPS
        'httponly' => true,          // inaccessible en JS
        'samesite' => 'Strict',
    ]);
    session_start();
}

/**
 * Vérifie que l'utilisateur est connecté.
 * Redirige vers /login sinon.
 */
function requireAuth(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
}

/**
 * Vérifie que l'utilisateur a le(s) rôle(s) requis.
 * @param string|string[] $roles
 */
function requireRole(string|array $roles): void {
    requireAuth();

    $roles = (array) $roles;

    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        exit('Accès refusé.');
    }
}