<?php

class StatistiquesController
{
    public function index(): void
    {
        $model = new Statistiques();

        // KPI
        $totalOffres      = $model->totalOffres();
        $totalEntreprises = $model->totalEntreprises();
        $moyenneCand      = $model->moyenneCandidatures();

        // Répartition durée → calcul du % dominant
        $repartitionDuree = $model->repartitionDuree();
        $maxDuree = null;
        $maxNb    = 0;
        foreach ($repartitionDuree as $row) {
            if ($row['nb'] > $maxNb) {
                $maxNb    = $row['nb'];
                $maxDuree = $row['duree'];
            }
        }
        $pourcentageDureeMax = $totalOffres > 0
            ? round(($maxNb / $totalOffres) * 100)
            : 0;

        // Carrousel
        $topWishlist             = $model->topWishlist();
        $totalCandidatures       = $model->totalCandidatures();
        $maxCandidatures         = $model->maxCandidatures();
        $pourcentageEtudiantsActifs = $model->pourcentageEtudiantsActifs();

        $pageTitle       = 'Statistiques — Web4All';
        $metaDescription = 'Statistiques des offres et candidatures sur Web4All.';
        require __DIR__ . '/../views/statistiques/index.php';
    }
}