<?php

class Pilote {

    public function findAll(PDO $pdo): array {
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE role = 'pilote'"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(PDO $pdo, int $id) {
        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE id = ? AND role = 'pilote'"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare(
            "DELETE FROM users WHERE id = ? AND role = 'pilote'"
        );
        return $stmt->execute([$id]);
    }

}
?>