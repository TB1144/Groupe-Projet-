<?php
class Offre {
    public function findAll(PDO $pdo): array {
        $stmt = $pdo->query('SELECT o.*, e.nom as entreprise 
                             FROM offres o 
                             JOIN entreprises e ON o.entreprise_id = e.id');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>