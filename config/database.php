<?php
$pdo = new PDO(
    'mysql:host=localhost;dbname=projet_web;charset=utf8',
    'user', 'password',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
?>