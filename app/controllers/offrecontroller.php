<?php

require_once __DIR__ . '/../models/offre.php';
require_once __DIR__ . '/../models/competence.php';
require_once __DIR__ . '/../models/entreprise.php';

class OffreController
{
    private Offre      $offreModel;
    private Competence $competenceModel;
    private Entreprise $entrepriseModel;

    public function __construct()
    {
        $this->offreModel      = new Offre();
        $this->competenceModel = new Competence();
        $this->entrepriseModel = new Entreprise();
    }

    // -------------------------------------------------------------------------
    // SFx 7 – Lister et rechercher les offres
    // GET /offres
    // GET /offres?titre=php&ville=paris&duree=6&page=2
    // Accès : public (anonyme)
    // -------------------------------------------------------------------------
    public function index(): void
    {
        // Récupération et nettoyage des paramètres GET
        $titre  = trim($_GET['titre']  ?? '');
        $ville  = trim($_GET['ville']  ?? '');
        $duree  = (int)($_GET['duree'] ?? 0);
        $page   = max(1, (int)($_GET['page'] ?? 1));

        $limit  = 10;
        $offset = ($page - 1) * $limit;

        // Requêtes SQL via le modèle
        $offres     = $this->offreModel->search($titre, $ville, $duree, $limit, $offset);
        $total      = $this->offreModel->count($titre, $ville, $duree);
        $totalPages = (int)ceil($total / $limit);

        // Récupère les IDs en wishlist pour l'étudiant connecté
        $wishlistIds = [];
        if (($_SESSION['role'] ?? '') === 'etudiant') {
            $wishlistModel = new Wishlist();
            $wishlistItems = $wishlistModel->findByEtudiant($_SESSION['user_id']);
            $wishlistIds   = array_column($wishlistItems, 'id_offre');
        }

        // Variables disponibles dans la vue :
        // $offres, $total, $page, $totalPages, $titre, $ville, $duree
        require __DIR__ . '/../views/offres/index.php';
    }

    // -------------------------------------------------------------------------
    // SFx 7 – Afficher une offre
    // GET /offres/{id}
    // Accès : public (anonyme)
    // -------------------------------------------------------------------------
    public function show(int $id): void
    {
        $offre = $this->offreModel->findById($id);

        if (!$offre) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        // Vérifie si l'offre est en wishlist pour l'étudiant connecté
        $enWishlist = false;
        if (($_SESSION['role'] ?? '') === 'etudiant') {
            $enWishlist = $this->offreModel->isInWishlist($id, $_SESSION['user_id']);
        }

        require __DIR__ . '/../views/offres/show.php';
    }

    // -------------------------------------------------------------------------
    // SFx 8 – Créer une offre
    // GET  /offres/creer   → formulaire
    // POST /offres/creer   → traitement
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function create(): void
    {
        $this->requireRole(['admin', 'pilote']);

        $entreprises  = $this->entrepriseModel->findAll();
        $competences  = $this->competenceModel->findAll();
        $errors       = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $data = [
                'titre'         => trim($_POST['titre']         ?? ''),
                'description'   => trim($_POST['description']   ?? ''),
                'id_entreprise' => (int)($_POST['id_entreprise'] ?? 0),
                'remuneration'  => trim($_POST['remuneration']  ?? ''),
                'duree'         => (int)($_POST['duree']         ?? 0),
                'date_offre'    => trim($_POST['date_offre']    ?? ''),
            ];
            $competenceIds = array_map('intval', $_POST['competences'] ?? []);

            // Validation
            if (empty($data['titre']))                  $errors[] = 'Le titre est obligatoire.';
            if (empty($data['description']))            $errors[] = 'La description est obligatoire.';
            if ($data['id_entreprise'] <= 0)            $errors[] = 'Sélectionnez une entreprise.';
            if ($data['duree'] <= 0)                    $errors[] = 'La durée est obligatoire.';
            if (empty($data['date_offre']))             $errors[] = 'La date de l\'offre est obligatoire.';
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_offre'])) {
                $errors[] = 'Format de date invalide.';
            }

            if (empty($errors)) {
                // Échappement HTML sur les champs texte libres
                $data['titre']       = htmlspecialchars($data['titre'],       ENT_QUOTES, 'UTF-8');
                $data['description'] = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');

                $id = $this->offreModel->create($data, $competenceIds);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Offre créée avec succès.'];
                header("Location: /offres/$id");
                exit;
            }
        }

        require __DIR__ . '/../views/offres/create.php';
    }

    // -------------------------------------------------------------------------
    // SFx 9 – Modifier une offre
    // GET  /offres/{id}/modifier  → formulaire prérempli
    // POST /offres/{id}/modifier  → traitement
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function edit(int $id): void
    {
        $this->requireRole(['admin', 'pilote']);

        $offre       = $this->offreModel->findById($id);
        $entreprises = $this->entrepriseModel->findAll();
        $competences = $this->competenceModel->findAll();

        if (!$offre) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        // IDs des compétences actuellement sélectionnées (pour pré-cocher les checkboxes)
        $competencesSelectionnees = array_column($offre['competences'], 'id');
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $data = [
                'titre'         => trim($_POST['titre']         ?? ''),
                'description'   => trim($_POST['description']   ?? ''),
                'id_entreprise' => (int)($_POST['id_entreprise'] ?? 0),
                'remuneration'  => trim($_POST['remuneration']  ?? ''),
                'duree'         => (int)($_POST['duree']         ?? 0),
                'date_offre'    => trim($_POST['date_offre']    ?? ''),
            ];
            $competenceIds = array_map('intval', $_POST['competences'] ?? []);

            if (empty($data['titre']))       $errors[] = 'Le titre est obligatoire.';
            if (empty($data['description'])) $errors[] = 'La description est obligatoire.';
            if ($data['id_entreprise'] <= 0) $errors[] = 'Sélectionnez une entreprise.';
            if ($data['duree'] <= 0)         $errors[] = 'La durée est obligatoire.';

            if (empty($errors)) {
                $data['titre']       = htmlspecialchars($data['titre'],       ENT_QUOTES, 'UTF-8');
                $data['description'] = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');

                $this->offreModel->update($id, $data, $competenceIds);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Offre mise à jour.'];
                header("Location: /offres/$id");
                exit;
            }

            $competencesSelectionnees = $competenceIds; // Conserver les choix en cas d'erreur
        }

        require __DIR__ . '/../views/offres/edit.php';
    }

    // -------------------------------------------------------------------------
    // SFx 10 – Supprimer une offre
    // POST /offres/{id}/supprimer
    // Accès : Admin
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->requireRole(['admin']);
        $this->verifyCsrf();

        $offre = $this->offreModel->findById($id);
        if (!$offre) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $this->offreModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Offre supprimée.'];
        header('Location: /offres');
        exit;
    }

    // =========================================================================
    // Helpers privés
    // =========================================================================

    private function requireRole(array $roles): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if (!in_array($_SESSION['role'], $roles, true)) {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }

    private function verifyCsrf(): void
    {
        if (
            empty($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
        ) {
            http_response_code(403);
            die('Token CSRF invalide.');
        }
    }
}