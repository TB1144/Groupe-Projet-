<?php
require_once __DIR__ . '/../../config/database.php';

class Wishlist
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // SFx 23 – Offres de la wishlist d'un étudiant (avec pagination + recherche)
    public function findByEtudiant(int $idEtudiant, int $limit = 10, int $offset = 0, string $searchEntreprise = ''): array
    {
        $sql = "SELECT w.id, o.id AS id_offre, o.titre, o.remuneration, o.duree,
                       o.date_offre, o.nb_candidatures,
                       e.nom AS entreprise_nom, e.ville AS entreprise_ville
                FROM wishlists w
                JOIN offres o ON w.id_offre = o.id
                JOIN entreprises e ON o.id_entreprise = e.id
                WHERE w.id_etudiant = :id";
        $params = [':id' => $idEtudiant];

        if ($searchEntreprise !== '') {
            $sql .= " AND e.nom LIKE :search_entreprise";
            $params[':search_entreprise'] = '%' . $searchEntreprise . '%';
        }

        $sql .= " ORDER BY w.id DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compte le total d'entrées wishlist pour un étudiant (pour la pagination + recherche)
    public function countByEtudiant(int $idEtudiant, string $searchEntreprise = ''): int
    {
        $sql = "SELECT COUNT(*) FROM wishlists w
                JOIN offres o ON w.id_offre = o.id
                JOIN entreprises e ON o.id_entreprise = e.id
                WHERE w.id_etudiant = :id";
        $params = [':id' => $idEtudiant];

        if ($searchEntreprise !== '') {
            $sql .= " AND e.nom LIKE :search_entreprise";
            $params[':search_entreprise'] = '%' . $searchEntreprise . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // Vérifie si une offre est déjà en wishlist
    public function exists(int $idEtudiant, int $idOffre): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM wishlists
             WHERE id_etudiant = :id_etudiant AND id_offre = :id_offre"
        );
        $stmt->execute([':id_etudiant' => $idEtudiant, ':id_offre' => $idOffre]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // SFx 24 – Ajouter une offre
    public function add(int $idEtudiant, int $idOffre): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO wishlists (id_etudiant, id_offre) VALUES (:id_etudiant, :id_offre)"
        );
        $stmt->execute([':id_etudiant' => $idEtudiant, ':id_offre' => $idOffre]);
    }

    // SFx 25 – Retirer une offre
    public function remove(int $idEtudiant, int $idOffre): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM wishlists WHERE id_etudiant = :id_etudiant AND id_offre = :id_offre"
        );
        $stmt->execute([':id_etudiant' => $idEtudiant, ':id_offre' => $idOffre]);
    }
}