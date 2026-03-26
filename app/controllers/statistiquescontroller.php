<?php
class StatistiquesController {
    public function index(): void {
        $pageTitle = 'Statistiques — Web4All';
        $metaDescription = 'Statistiques des offres et candidatures sur Web4All.';
        require __DIR__ . '/../views/statistiques/index.php';
    }
}
