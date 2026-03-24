<?php
class CandidatureController {
    // Liste des candidatures (Vue différente selon le rôle)
    public function index(): void {
        $model = new Candidature();
        
        if ($_SESSION['role'] === 'etudiant') {
            $candidatures = $model->findByEtudiant($_SESSION['user_id']);
        } elseif ($_SESSION['role'] === 'pilote' || $_SESSION['role'] === 'admin') {
            $candidatures = $model->findAll();
        }

        require 'app/views/candidatures/index.php';
    }

    // Traitement de l'envoi d'une candidature (POST)
    public function postuler(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logique d'upload de fichier (CV/LM)
            // Enregistrement en base de données
            $model = new Candidature();
            $success = $model->create([
                'offre_id' => $_POST['offre_id'],
                'etudiant_id' => $_SESSION['user_id'],
                'cv_path' => $this->handleUpload($_FILES['cv']),
                'lm_text' => $_POST['lettre_motivation']
            ]);

            header("Location: /candidatures/success");
        }
    }

    private function handleUpload($file): string {
        // Logique de déplacement du fichier dans public/uploads/
        return "chemin/vers/cv.pdf";
    }
}
?>