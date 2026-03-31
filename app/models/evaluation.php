<?php

require_once __DIR__ . '/../../config/database.php';

/**
 * Modèle Evaluation
 * Gère les évaluations laissées par les étudiants sur les entreprises (SFx 5).
 * Une évaluation est liée à une candidature validée (un étudiant ne peut évaluer
 * qu'une entreprise où il a effectué un stage).
 *
 * Table attendue :
 *   evaluations (id, id_entreprise, id_etudiant, note TINYINT, commentaire TEXT,
 *                date_evaluation DATETIME, created_at TIMESTAMP)
 */
class Evaluation
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // -------------------------------------------------------------------------
    // Lecture
    // -------------------------------------------------------------------------

    /**
     * Toutes les évaluations d'une entreprise, avec les infos de l'étudiant.
     */
    public function findByEntreprise(int $idEntreprise): array
    {
        $stmt = $this->db->prepare(
            'SELECT e.*, u.nom, u.prenom
             FROM evaluations e
             JOIN users u ON e.id_etudiant = u.id
             WHERE e.id_entreprise = :id
             ORDER BY e.date_evaluation DESC'
        );
        $stmt->execute([':id' => $idEntreprise]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Moyenne des notes pour une entreprise (retourne null s'il n'y a aucune éval).
     */
    public function moyenneByEntreprise(int $idEntreprise): ?float
    {
        $stmt = $this->db->prepare(
            'SELECT AVG(note) AS moyenne FROM evaluations WHERE id_entreprise = :id'
        );
        $stmt->execute([':id' => $idEntreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['moyenne'] !== null ? round((float)$result['moyenne'], 1) : null;
    }

    /**
     * Évaluation d'un étudiant précis pour une entreprise précise.
     * Utile pour vérifier si l'étudiant a déjà évalué l'entreprise.
     */
    public function findByEtudiantAndEntreprise(int $idEtudiant, int $idEntreprise): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM evaluations
             WHERE id_etudiant = :id_e AND id_entreprise = :id_ent
             LIMIT 1'
        );
        $stmt->execute([':id_e' => $idEtudiant, ':id_ent' => $idEntreprise]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Vérifie si un étudiant a le droit d'évaluer une entreprise :
     * il doit avoir une candidature acceptée liée à une offre de cette entreprise.
     */
    public function peutEvaluer(int $idEtudiant, int $idEntreprise): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM candidatures c
             JOIN offres o ON c.id_offre = o.id
             WHERE c.id_etudiant   = :id_e
               AND o.id_entreprise = :id_ent
               AND c.statut        = "acceptee"'
        );
        $stmt->execute([':id_e' => $idEtudiant, ':id_ent' => $idEntreprise]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // -------------------------------------------------------------------------
    // Écriture
    // -------------------------------------------------------------------------

    /**
     * Crée une nouvelle évaluation.
     *
     * @param array $data  ['id_entreprise', 'id_etudiant', 'note' (1-5), 'commentaire']
     */
    public function create(array $data): int
    {
        $this->validateNote($data['note']);

        $stmt = $this->db->prepare(
            'INSERT INTO evaluations (id_entreprise, id_etudiant, note, commentaire, date_evaluation)
             VALUES (:id_ent, :id_e, :note, :commentaire, NOW())'
        );
        $stmt->execute([
            ':id_ent'      => (int)$data['id_entreprise'],
            ':id_e'        => (int)$data['id_etudiant'],
            ':note'        => (int)$data['note'],
            ':commentaire' => $data['commentaire'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Modifie une évaluation existante.
     */
    public function update(int $id, array $data): void
    {
        $this->validateNote($data['note']);

        $stmt = $this->db->prepare(
            'UPDATE evaluations
             SET note = :note, commentaire = :commentaire, date_evaluation = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            ':note'        => (int)$data['note'],
            ':commentaire' => $data['commentaire'] ?? null,
            ':id'          => $id,
        ]);
    }

    /**
     * Supprime une évaluation.
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM evaluations WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // -------------------------------------------------------------------------
    // Privé
    // -------------------------------------------------------------------------

    private function validateNote(mixed $note): void
    {
        $n = (int)$note;
        if ($n < 1 || $n > 5) {
            throw new \InvalidArgumentException("La note doit être comprise entre 1 et 5. Reçu : $note");
        }
    }
}
