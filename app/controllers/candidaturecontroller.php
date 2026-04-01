<?php

require_once __DIR__ . '/../models/candidature.php';
require_once __DIR__ . '/../models/offre.php';

class CandidatureController
{
    private Candidature $candidatureModel;
    private Offre       $offreModel;

    public function __construct()
    {
        $this->candidatureModel = new Candidature();
        $this->offreModel       = new Offre();
    }

    // -------------------------------------------------------------------------
    // SFx 20 – Formulaire de candidature
    // GET /offres/{id}/postuler
    // Accès : Étudiant uniquement
    // -------------------------------------------------------------------------
    public function form(int $idOffre): void
    {
        $this->requireRole(['etudiant']);

        $offre = $this->offreModel->findById($idOffre);
        if (!$offre) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        // Déjà postulé ?
        if ($this->candidatureModel->dejaPostule($_SESSION['user_id'], $idOffre)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous avez déjà postulé à cette offre.'];
            header("Location: /offres/$idOffre");
            exit;
        }

        $pageTitle = 'Postuler — ' . htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8');
        require __DIR__ . '/../views/candidatures/form.php';
    }

    // -------------------------------------------------------------------------
    // SFx 20 – Traitement de la candidature
    // POST /offres/{id}/postuler
    // Accès : Étudiant uniquement
    // -------------------------------------------------------------------------
    public function postuler(int $idOffre): void
    {
        $this->requireRole(['etudiant']);
        $this->verifyCsrf();

        $offre = $this->offreModel->findById($idOffre);
        if (!$offre) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        if ($this->candidatureModel->dejaPostule($_SESSION['user_id'], $idOffre)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous avez déjà postulé à cette offre.'];
            header("Location: /offres/$idOffre");
            exit;
        }

        $errors = [];
        $cvPath = null;

        // ── Validation lettre de motivation ──────────────────────────────────
        $lm = trim($_POST['lettre_motivation'] ?? '');
        if (empty($lm)) {
            $errors[] = 'La lettre de motivation est obligatoire.';
        }

        // ── Upload CV ────────────────────────────────────────────────────────
        if (empty($_FILES['cv']['name'])) {
            $errors[] = 'Le CV est obligatoire.';
        } else {
            $file     = $_FILES['cv'];
            $maxSize  = 5 * 1024 * 1024; // 5 Mo
            $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'Erreur lors de l\'upload du CV.';
            } elseif ($ext !== 'pdf') {
                $errors[] = 'Le CV doit être un fichier PDF.';
            } elseif ($file['size'] > $maxSize) {
                $errors[] = 'Le CV ne doit pas dépasser 5 Mo.';
            } else {
                $uploadDir = __DIR__ . '/../../public/uploads/cv/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid('cv_') . '_' . $_SESSION['user_id'] . '.pdf';
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $cvPath = $filename;
                } else {
                    $errors[] = 'Impossible de sauvegarder le CV.';
                }
            }
        }

        // ── Erreurs → réafficher le formulaire ───────────────────────────────
        if (!empty($errors)) {
            $error = implode('<br>', array_map('htmlspecialchars', $errors));
            $pageTitle = 'Postuler — ' . htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8');
            require __DIR__ . '/../views/candidatures/form.php';
            return;
        }

        // ── Création de la candidature ────────────────────────────────────────
        $this->candidatureModel->create([
            'id_etudiant'        => $_SESSION['user_id'],
            'id_offre'           => $idOffre,
            'cv'                 => $cvPath,
            'lettre_motivation'  => $lm,
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Candidature envoyée avec succès !'];
        header('Location: /candidatures');
        exit;
    }

    // -------------------------------------------------------------------------
    // SFx 21 / 22 – Liste des candidatures
    // GET /candidatures
    // GET /candidatures?page=2&search_etudiant=dupont&search_entreprise=google
    // -------------------------------------------------------------------------
    public function index(): void
    {
        $this->requireRole(['etudiant', 'pilote']);

        $page             = max(1, (int)($_GET['page'] ?? 1));
        $limit            = 10;
        $offset           = ($page - 1) * $limit;
        $searchEtudiant   = trim($_GET['search_etudiant']   ?? '');
        $searchEntreprise = trim($_GET['search_entreprise'] ?? '');

        if ($_SESSION['role'] === 'etudiant') {
            $total        = $this->candidatureModel->countByEtudiant($_SESSION['user_id'], $searchEntreprise);
            $candidatures = $this->candidatureModel->findByEtudiant($_SESSION['user_id'], $limit, $offset, $searchEntreprise);
        } elseif ($_SESSION['role'] === 'pilote') {
            $total        = $this->candidatureModel->countByPilote($_SESSION['user_id'], $searchEtudiant, $searchEntreprise);
            $candidatures = $this->candidatureModel->findByPilote($_SESSION['user_id'], $limit, $offset, $searchEtudiant, $searchEntreprise);
        }

        $totalPages = (int)ceil($total / $limit);

        $pageTitle = 'Mes candidatures — Web4All';
        require __DIR__ . '/../views/candidatures/index.php';
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


    public function supprimer(int $id): void
    {
        $this->requireRole(['etudiant', 'admin', 'pilote']);
        $this->verifyCsrf();

        $this->candidatureModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Candidature retirée.'];
        header('Location: /candidatures');
        exit;
    }
}