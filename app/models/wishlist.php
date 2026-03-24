<?php

class Wishlist {

    public function findByStudent(PDO $pdo, int $studentId): array {

        $stmt = $pdo->prepare(
            "SELECT o.*
             FROM wishlist w
             JOIN offres o ON w.offre_id = o.id
             WHERE w.etudiant_id = ?"
        );

        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(PDO $pdo, int $studentId, int $offreId): bool {

        $stmt = $pdo->prepare(
            "INSERT INTO wishlist (etudiant_id, offre_id)
             VALUES (?, ?)"
        );

        return $stmt->execute([$studentId, $offreId]);
    }

    public function remove(PDO $pdo, int $studentId, int $offreId): bool {

        $stmt = $pdo->prepare(
            "DELETE FROM wishlist
             WHERE etudiant_id = ? AND offre_id = ?"
        );

        return $stmt->execute([$studentId, $offreId]);
    }

}
?>