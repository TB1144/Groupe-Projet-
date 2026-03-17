<?php

class Candidature {

    public function create(PDO $pdo, array $data): bool {

        $stmt = $pdo->prepare(
            "INSERT INTO candidatures (etudiant_id, offre_id, cv, lettre_motivation)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['etudiant_id'],
            $data['offre_id'],
            $data['cv'],
            $data['lettre']
        ]);
    }

    public function findByStudent(PDO $pdo, int $id): array {

        $stmt = $pdo->prepare(
            "SELECT * FROM candidatures WHERE etudiant_id = ?"
        );

        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>