<?php
class OffreController {
    public function index(): void {
        $model = new Offre();
        $offres = $model->findAll($pdo);
        // Passe les données à la vue
        require 'app/Views/offres/index.php';
    }
}
?>