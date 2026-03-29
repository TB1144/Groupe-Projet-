<?php
require_once __DIR__ . '/../../config/database.php';

class Candidature
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // -------------------------------------------------------------------------
    // SFx 21 – Candidatures de l'étudiant connecté
    // -------------------------------------------------------------------------
    public function findByEtudiant(int $idEtudiant): array
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, o.titre AS offre_titre, e.nom AS entreprise_nom
             FROM candidatures c
             JOIN offres o ON c.id_offre = o.id
             JOIN entreprises e ON o.id_entreprise = e.id
             WHERE c.id_etudiant = :id
             ORDER BY c.date_candidature DESC"
        );
        $stmt->execute([':id' => $idEtudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------------
    // SFx 22 – Toutes les candidatures (admin/pilote)
    // -------------------------------------------------------------------------
    public function findAll(): array
    {
        return $this->db->query(
            "SELECT c.*, o.titre AS offre_titre, e.nom AS entreprise_nom,
                    u.nom AS etudiant_nom, u.prenom AS etudiant_prenom
             FROM candidatures c
             JOIN offres o ON c.id_offre = o.id
             JOIN entreprises e ON o.id_entreprise = e.id
             JOIN users u ON c.id_etudiant = u.id
             ORDER BY c.date_candidature DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------------
    // Vérifie si l'étudiant a déjà postulé à cette offre
    // -------------------------------------------------------------------------
    public function dejaPostule(int $idEtudiant, int $idOffre): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM candidatures
             WHERE id_etudiant = :id_etudiant AND id_offre = :id_offre"
        );
        $stmt->execute([':id_etudiant' => $idEtudiant, ':id_offre' => $idOffre]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // -------------------------------------------------------------------------
    // SFx 20 – Créer une candidature
    // -------------------------------------------------------------------------
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO candidatures (id_etudiant, id_offre, cv, lettre_motivation, date_candidature)
             VALUES (:id_etudiant, :id_offre, :cv, :lettre_motivation, :date_candidature)"
        );
        $ok = $stmt->execute([
            ':id_etudiant'       => $data['id_etudiant'],
            ':id_offre'          => $data['id_offre'],
            ':cv'                => $data['cv'] ?? null,
            ':lettre_motivation' => $data['lettre_motivation'] ?? null,
            ':date_candidature'  => date('Y-m-d'),
        ]);

        // Met à jour le compteur de candidatures sur l'offre
        if ($ok) {
            $this->db->prepare(
                "UPDATE offres SET nb_candidatures = nb_candidatures + 1 WHERE id = :id"
            )->execute([':id' => $data['id_offre']]);
        }

        return $ok;
    }

    // -------------------------------------------------------------------------
    // SFx 20 – Supprimer une candidature
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        // Récupère cv et id_offre avant suppression
        $stmt = $this->db->prepare("SELECT id_offre, cv FROM candidatures WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->db->prepare("DELETE FROM candidatures WHERE id = :id")->execute([':id' => $id]);

        if ($row) {
            // Décrémente le compteur
            $this->db->prepare(
                "UPDATE offres SET nb_candidatures = GREATEST(nb_candidatures - 1, 0) WHERE id = :id"
            )->execute([':id' => $row['id_offre']]);

            // Supprime le fichier CV du disque
            if (!empty($row['cv'])) {
                $path = __DIR__ . '/../../../public/uploads/cv/' . $row['cv'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
}
}