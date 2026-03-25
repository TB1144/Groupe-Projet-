<?php

require_once __DIR__ . '/../../config/database.php';

class Offre
{
    private PDO $db;

    public function __construct()
    {
        // Aligné sur le pattern des autres modèles du projet
        $this->db = Database::getInstance()->getConnection();
    }

    // -------------------------------------------------------------------------
    // SFx 7 – Rechercher et afficher des offres (avec pagination)
    // -------------------------------------------------------------------------

    /**
     * Recherche des offres selon plusieurs critères + pagination.
     *
     * @param string $titre      Recherche dans le titre ou les compétences
     * @param string $ville      Recherche dans la ville de l'entreprise
     * @param int    $duree      Durée exacte en mois (0 = toutes)
     * @param int    $limit      Nombre de résultats par page
     * @param int    $offset     Décalage (page courante)
     * @return array
     */
    public function search(
        string $titre  = '',
        string $ville  = '',
        int    $duree  = 0,
        int    $limit  = 10,
        int    $offset = 0
    ): array {
        $conditions = ['1=1'];
        $params     = [];

        if ($titre !== '') {
            // Recherche dans le titre ET dans les compétences associées
            $conditions[] = '(o.titre LIKE :titre OR EXISTS (
                SELECT 1 FROM offre_competences oc
                JOIN competences c ON oc.id_competence = c.id
                WHERE oc.id_offre = o.id AND c.nom LIKE :titre2
            ))';
            $params[':titre']  = '%' . $titre . '%';
            $params[':titre2'] = '%' . $titre . '%';
        }

        if ($ville !== '') {
            $conditions[] = 'e.ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }

        if ($duree > 0) {
            $conditions[] = 'o.duree = :duree';
            $params[':duree'] = $duree;
        }

        $where = implode(' AND ', $conditions);

        $stmt = $this->db->prepare(
            "SELECT o.id, o.titre, o.description, o.remuneration, o.duree,
                    o.date_offre, o.nb_candidatures,
                    e.nom AS entreprise_nom, e.ville AS entreprise_ville
             FROM offres o
             JOIN entreprises e ON o.id_entreprise = e.id
             WHERE $where
             ORDER BY o.date_offre DESC
             LIMIT :limit OFFSET :offset"
        );

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compte le total de résultats (pour calculer le nombre de pages).
     */
    public function count(
        string $titre = '',
        string $ville = '',
        int    $duree = 0
    ): int {
        $conditions = ['1=1'];
        $params     = [];

        if ($titre !== '') {
            $conditions[] = '(o.titre LIKE :titre OR EXISTS (
                SELECT 1 FROM offre_competences oc
                JOIN competences c ON oc.id_competence = c.id
                WHERE oc.id_offre = o.id AND c.nom LIKE :titre2
            ))';
            $params[':titre']  = '%' . $titre . '%';
            $params[':titre2'] = '%' . $titre . '%';
        }
        if ($ville !== '') {
            $conditions[] = 'e.ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }
        if ($duree > 0) {
            $conditions[] = 'o.duree = :duree';
            $params[':duree'] = $duree;
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare(
            "SELECT COUNT(*) FROM offres o
             JOIN entreprises e ON o.id_entreprise = e.id
             WHERE $where"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Trouve une offre par son ID avec ses compétences.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, e.nom AS entreprise_nom, e.ville AS entreprise_ville,
                    e.id AS entreprise_id
             FROM offres o
             JOIN entreprises e ON o.id_entreprise = e.id
             WHERE o.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$offre) {
            return null;
        }

        // Récupérer les compétences liées
        $offre['competences'] = $this->getCompetences($id);
        return $offre;
    }

    /**
     * Retourne les compétences d'une offre.
     */
    public function getCompetences(int $idOffre): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.id, c.nom FROM competences c
             JOIN offre_competences oc ON c.id = oc.id_competence
             WHERE oc.id_offre = :id'
        );
        $stmt->execute([':id' => $idOffre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------------------------------------------------------------------
    // SFx 8 – Créer une offre
    // -------------------------------------------------------------------------

    /**
     * @param array $data  [titre, description, id_entreprise, remuneration, duree, date_offre]
     * @param array $competenceIds  IDs des compétences sélectionnées
     */
    public function create(array $data, array $competenceIds = []): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO offres (titre, description, id_entreprise, remuneration, duree, date_offre)
             VALUES (:titre, :description, :id_entreprise, :remuneration, :duree, :date_offre)'
        );
        $stmt->execute([
            ':titre'         => $data['titre'],
            ':description'   => $data['description'],
            ':id_entreprise' => (int)$data['id_entreprise'],
            ':remuneration'  => $data['remuneration'],
            ':duree'         => (int)$data['duree'],
            ':date_offre'    => $data['date_offre'],
        ]);

        $idOffre = (int)$this->db->lastInsertId();
        $this->syncCompetences($idOffre, $competenceIds);
        return $idOffre;
    }

    // -------------------------------------------------------------------------
    // SFx 9 – Modifier une offre
    // -------------------------------------------------------------------------
    public function update(int $id, array $data, array $competenceIds = []): void
    {
        $stmt = $this->db->prepare(
            'UPDATE offres
             SET titre = :titre, description = :description,
                 id_entreprise = :id_entreprise, remuneration = :remuneration,
                 duree = :duree, date_offre = :date_offre
             WHERE id = :id'
        );
        $stmt->execute([
            ':titre'         => $data['titre'],
            ':description'   => $data['description'],
            ':id_entreprise' => (int)$data['id_entreprise'],
            ':remuneration'  => $data['remuneration'],
            ':duree'         => (int)$data['duree'],
            ':date_offre'    => $data['date_offre'],
            ':id'            => $id,
        ]);
        $this->syncCompetences($id, $competenceIds);
    }

    // -------------------------------------------------------------------------
    // SFx 10 – Supprimer une offre
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        // Les entrées dans offre_competences sont supprimées par CASCADE (clé étrangère)
        $stmt = $this->db->prepare('DELETE FROM offres WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------------------------------
    // Wishlist helpers (SFx 24 – top offres ajoutées en wish-list pour SFx 11)
    // -------------------------------------------------------------------------
    public function isInWishlist(int $idOffre, int $idEtudiant): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM wishlists
             WHERE id_offre = :id_offre AND id_etudiant = :id_etudiant'
        );
        $stmt->execute([':id_offre' => $idOffre, ':id_etudiant' => $idEtudiant]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // -------------------------------------------------------------------------
    // Privé
    // -------------------------------------------------------------------------

    /**
     * Synchronise les compétences d'une offre (supprime tout puis réinsère).
     */
    private function syncCompetences(int $idOffre, array $competenceIds): void
    {
        $del = $this->db->prepare('DELETE FROM offre_competences WHERE id_offre = :id');
        $del->execute([':id' => $idOffre]);

        if (empty($competenceIds)) {
            return;
        }

        $ins = $this->db->prepare(
            'INSERT INTO offre_competences (id_offre, id_competence) VALUES (:id_offre, :id_comp)'
        );
        foreach ($competenceIds as $idComp) {
            $ins->execute([':id_offre' => $idOffre, ':id_comp' => (int)$idComp]);
        }
    }
}