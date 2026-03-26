<?php
require_once __DIR__ . '/../../config/database.php';

class Candidature {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEtudiant(int $id): array {
        $stmt = $this->db->prepare(
            "SELECT c.*, o.titre AS offre_titre, e.nom AS entreprise_nom
             FROM candidatures c
             JOIN offres o ON c.id_offre = o.id
             JOIN entreprises e ON o.id_entreprise = e.id
             WHERE c.id_etudiant = :id
             ORDER BY c.date_candidature DESC"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll(): array {
        $stmt = $this->db->query(
            "SELECT c.*, o.titre AS offre_titre, e.nom AS entreprise_nom,
                    u.nom AS etudiant_nom, u.prenom AS etudiant_prenom
             FROM candidatures c
             JOIN offres o ON c.id_offre = o.id
             JOIN entreprises e ON o.id_entreprise = e.id
             JOIN users u ON c.id_etudiant = u.id
             ORDER BY c.date_candidature DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO candidatures (id_etudiant, id_offre, cv, lettre_motivation, date_candidature)
             VALUES (:id_etudiant, :id_offre, :cv, :lettre_motivation, :date_candidature)"
        );
        return $stmt->execute([
            ':id_etudiant'      => $data['id_etudiant'],
            ':id_offre'         => $data['id_offre'],
            ':cv'               => $data['cv'] ?? null,
            ':lettre_motivation'=> $data['lettre_motivation'] ?? null,
            ':date_candidature' => date('Y-m-d'),
        ]);
    }
}
