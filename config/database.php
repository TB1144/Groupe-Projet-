<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;
    // Constructeur privé pour empêcher l'instanciation directe de la classe Database
    private function __construct()
    {
        $this->connection = new PDO(
            'mysql:host=localhost;dbname=projet_web;charset=utf8',
            'web4all',
            'Web4All2026!',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // Permet de lancer une exception en cas d'erreur de connexion ou de requête
        );
    }
    // Permet d'obtenir l'instance unique de la classe Database (singleton)
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    // Permet d'obtenir la connexion PDO pour exécuter des requêtes sur la base de données
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}