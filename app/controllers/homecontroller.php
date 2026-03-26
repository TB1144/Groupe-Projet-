<?php
class HomeController {
    public function index(): void {
        $pageTitle = 'Accueil — Web4All';
        $metaDescription = 'Web4All — La plateforme exclusive de stages pour les étudiants du CESI.';
        require __DIR__ . '/../views/home/index.php';
    }
}
