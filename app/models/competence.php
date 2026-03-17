<?php

class Competence {

    public function findAll(PDO $pdo): array {

        $stmt = $pdo->query(
            "SELECT * FROM competences"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // jai pas compris ca
    public function findByOffer(PDO $pdo, int $offreId): array {

        $stmt = $pdo->prepare(
            "SELECT c.*
             FROM competences c
             JOIN offre_competence oc
             ON c.id = oc.competence_id
             WHERE oc.offre_id = ?"
        );

        $stmt->execute([$offreId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>