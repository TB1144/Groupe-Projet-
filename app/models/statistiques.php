<?php
require_once __DIR__ . '/../../config/database.php';

class Statistiques
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Nombre total d'offres
    public function totalOffres(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM offres")->fetchColumn();
    }

    // Nombre total d'entreprises
    public function totalEntreprises(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM entreprises")->fetchColumn();
    }

    // Moyenne de candidatures par offre
    public function moyenneCandidatures(): float
    {
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
        $offres = $this->totalOffres();
        return $offres > 0 ? round($total / $offres, 1) : 0;
    }

    // Répartition des offres par durée
    public function repartitionDuree(): array
    {
        $stmt = $this->db->query(
            "SELECT duree, COUNT(*) AS nb
             FROM offres
             GROUP BY duree
             ORDER BY duree ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Top 5 offres les plus ajoutées en wishlist
    public function topWishlist(): array
    {
        $stmt = $this->db->query(
            "SELECT o.titre, e.nom AS entreprise_nom, COUNT(w.id) AS nb_ajouts
             FROM wishlists w
             JOIN offres o ON w.id_offre = o.id
             JOIN entreprises e ON o.id_entreprise = e.id
             GROUP BY w.id_offre
             ORDER BY nb_ajouts DESC
             LIMIT 5"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total candidatures
    public function totalCandidatures(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
    }

    // Max candidatures sur une offre
    public function maxCandidatures(): int
    {
        return (int)$this->db->query(
            "SELECT MAX(nb_candidatures) FROM offres"
        )->fetchColumn();
    }

    // % d'étudiants avec au moins 1 candidature
    public function pourcentageEtudiantsActifs(): int
    {
        $totalEtudiants = (int)$this->db->query(
            "SELECT COUNT(*) FROM users WHERE role = 'etudiant'"
        )->fetchColumn();

        $etudiantsAvecCandidature = (int)$this->db->query(
            "SELECT COUNT(DISTINCT id_etudiant) FROM candidatures"
        )->fetchColumn();

        if ($totalEtudiants === 0) return 0;
        return (int)round(($etudiantsAvecCandidature / $totalEtudiants) * 100);
    }
}