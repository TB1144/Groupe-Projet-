<?php

require_once __DIR__ . '/../models/etudiant.php';
require_once __DIR__ . '/../models/candidature.php';
require_once __DIR__ . '/../models/wishlist.php';

class EtudiantController
{
    private Etudiant $etudiantModel;
    private Candidature $candidatureModel;
    private Wishlist $wishlistModel;

    public function __construct()
    {
        $this->etudiantModel   = new Etudiant();
        $this->candidatureModel = new Candidature();
        $this->wishlistModel   = new Wishlist();
    }

    // -------------------------------------------------------------------------
    // SFx 16 – Rechercher et afficher un compte Etudiant
    // GET /etudiants  ou  GET /etudiants?search=nom&page=1
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function index(): void
    {
        $this->requireRole(['admin', 'pilote']);

        $search = trim($_GET['search'] ?? '');
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $etudiants = $this->etudiantModel->search($search, $limit, $offset);
        $total     = $this->etudiantModel->count($search);
        $totalPages = (int)ceil($total / $limit);

        require __DIR__ . '/../views/etudiants/index.php';
    }

    // -------------------------------------------------------------------------
    // SFx 16 – Afficher un étudiant et l'état de sa recherche de stage
    // GET /etudiants/{id}
    // Accès : Admin, Pilote, Etudiant (son propre profil uniquement)
    // -------------------------------------------------------------------------
    public function show(int $id): void
    {
        $this->requireRole(['admin', 'pilote', 'etudiant']);

        // Un étudiant ne peut voir que son propre profil
        if ($_SESSION['role'] === 'etudiant' && $_SESSION['user_id'] !== $id) {
            http_response_code(403);
            require __DIR__ . '/../views/errors/403.php';
            return;
        }

        $etudiant     = $this->etudiantModel->findById($id);
        $candidatures = $this->candidatureModel->findByEtudiant($id);
        $wishlist     = $this->wishlistModel->findByEtudiant($id);

        if (!$etudiant) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../views/etudiants/show.php';
    }

    // -------------------------------------------------------------------------
    // SFx 17 – Créer un compte Etudiant
    // GET  /etudiants/creer  → formulaire
    // POST /etudiants/creer  → traitement
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function create(): void
    {
        $this->requireRole(['admin', 'pilote']);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $nom    = trim($_POST['nom']    ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email']  ?? '');

            // Validation
            if (empty($nom)) {
                $errors[] = 'Le nom est obligatoire.';
            }
            if (empty($prenom)) {
                $errors[] = 'Le prénom est obligatoire.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse e-mail invalide.';
            }
            if ($this->etudiantModel->emailExists($email)) {
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            }

            if (empty($errors)) {
                $motDePasseTemp = bin2hex(random_bytes(8));   // mot de passe provisoire
                $hash           = password_hash($motDePasseTemp, PASSWORD_BCRYPT);

                $this->etudiantModel->create([
                    'nom'            => htmlspecialchars($nom,    ENT_QUOTES, 'UTF-8'),
                    'prenom'         => htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8'),
                    'email'          => $email,
                    'mot_de_passe'   => $hash,
                    'id_pilote'      => $_SESSION['role'] === 'pilote' ? $_SESSION['user_id'] : null,
                ]);

                // TODO : envoyer le mot de passe provisoire par e-mail
                $_SESSION['flash'] = ['type' => 'success', 'message' => "Compte créé. Mot de passe provisoire : $motDePasseTemp"];
                header('Location: /etudiants');
                exit;
            }
        }

        require __DIR__ . '/../views/etudiants/create.php';
    }

    // -------------------------------------------------------------------------
    // SFx 18 – Modifier un compte Etudiant
    // GET  /etudiants/{id}/modifier  → formulaire prérempli
    // POST /etudiants/{id}/modifier  → traitement
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function edit(int $id): void
    {
        $this->requireRole(['admin', 'pilote']);

        $etudiant = $this->etudiantModel->findById($id);
        if (!$etudiant) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $nom    = trim($_POST['nom']    ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email']  ?? '');

            if (empty($nom))   $errors[] = 'Le nom est obligatoire.';
            if (empty($prenom)) $errors[] = 'Le prénom est obligatoire.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse e-mail invalide.';
            }
            // Vérifier unicité email seulement si changé
            if ($email !== $etudiant['email'] && $this->etudiantModel->emailExists($email)) {
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            }

            if (empty($errors)) {
                $this->etudiantModel->update($id, [
                    'nom'    => htmlspecialchars($nom,    ENT_QUOTES, 'UTF-8'),
                    'prenom' => htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8'),
                    'email'  => $email,
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Compte étudiant mis à jour.'];
                header("Location: /etudiants/$id");
                exit;
            }
        }

        require __DIR__ . '/../views/etudiants/edit.php';
    }

    // -------------------------------------------------------------------------
    // SFx 19 – Supprimer un compte Etudiant
    // POST /etudiants/{id}/supprimer
    // Accès : Admin
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->requireRole(['admin']);
        $this->verifyCsrf();

        $etudiant = $this->etudiantModel->findById($id);
        if (!$etudiant) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $this->etudiantModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Compte étudiant supprimé.'];
        header('Location: /etudiants');
        exit;
    }

    // =========================================================================
    // Helpers privés
    // =========================================================================

    /**
     * Vérifie que l'utilisateur est connecté et possède l'un des rôles requis.
     * Redirige vers /login ou renvoie une 403 sinon.
     */
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

    /**
     * Vérifie le token CSRF soumis dans le formulaire.
     * À appeler sur tout POST.
     */
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
