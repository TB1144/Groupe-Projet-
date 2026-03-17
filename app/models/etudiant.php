<?php

class Etudiant {

    public function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM etudiants");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail(PDO $pdo, string $email) {
        $stmt = $pdo->prepare(
            "SELECT * FROM etudiants WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>