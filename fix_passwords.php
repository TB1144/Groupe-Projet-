<?php
require_once __DIR__ . '/config/database.php';

$hash = password_hash('password123', PASSWORD_DEFAULT);
$db   = Database::getInstance()->getConnection();
$db->prepare("UPDATE users SET password = ?")->execute([$hash]);
echo "les hashs ont été régénérés pour tous les utilisateurs avec le mot de passe 'password123'";
?>