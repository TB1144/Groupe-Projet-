<?php

class User {

    public function findByEmail(PDO $pdo, string $email) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(PDO $pdo): array {
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRole(PDO $pdo, string $role): array {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(PDO $pdo, array $data): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO users (nom, prenom, email, password, role)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role']
        ]);
    }

}
?>