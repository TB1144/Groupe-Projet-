<?php

class UserController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // -------------------------------------------------------------------------
    // GET /utilisateurs
    // Admin : tous les utilisateurs
    // Pilote : uniquement ses étudiants
    // -------------------------------------------------------------------------
    public function index(): void
    {
        $this->requireRole(['admin', 'pilote']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $currentRole = $_SESSION['role'];
        $currentId   = (int)$_SESSION['user_id'];

        $search = trim($_GET['search'] ?? '');
        $role   = $_GET['role'] ?? '';
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        if ($currentRole === 'pilote') {
            $role       = 'etudiant';
            $users      = $this->userModel->searchByPilote($currentId, $search, $limit, $offset);
            $total      = $this->userModel->countByPilote($currentId, $search);
        } else {
            $users      = $this->userModel->searchAll($search, $role, $limit, $offset);
            $total      = $this->userModel->countAll($search, $role);
        }

        $totalPages = (int)ceil($total / $limit);

        $pageTitle = 'Utilisateurs — Web4All';
        require __DIR__ . '/../views/users/index.php';
    }

    // -------------------------------------------------------------------------
    // GET  /utilisateurs/creer
    // POST /utilisateurs/creer
    // Admin : peut créer étudiant ou pilote
    // Pilote : peut créer étudiant uniquement (rattaché à lui-même)
    // -------------------------------------------------------------------------
    public function create(): void
    {
        $this->requireRole(['admin', 'pilote']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $currentRole = $_SESSION['role'];
        $currentId   = (int)$_SESSION['user_id'];

        $pilotes = $currentRole === 'admin' ? $this->userModel->findByRole('pilote') : [];
        $errors  = [];
        $user    = ['nom' => '', 'prenom' => '', 'email' => '', 'role' => 'etudiant', 'id_pilote' => null];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $nom    = trim($_POST['nom']    ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email']  ?? '');
            // Pilote ne peut créer que des étudiants
            $role   = $currentRole === 'pilote' ? 'etudiant' : ($_POST['role'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($nom === '')    $errors[] = 'Le nom est obligatoire.';
            if ($prenom === '') $errors[] = 'Le prénom est obligatoire.';
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse email est invalide.';
            }
            // Admin peut créer étudiant ou pilote, pas admin
            if (!in_array($role, ['pilote', 'etudiant'], true)) {
                $errors[] = 'Rôle invalide.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
            }
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Cette adresse email est déjà utilisée.';
            }

            $id_pilote = null;
            if ($role === 'etudiant') {
                $id_pilote = $currentRole === 'pilote'
                    ? $currentId
                    : (!empty($_POST['id_pilote']) ? (int)$_POST['id_pilote'] : null);
            }

            if (empty($errors)) {
                $this->userModel->create([
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'email'     => $email,
                    'password'  => $password,
                    'role'      => $role,
                    'id_pilote' => $id_pilote,
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Utilisateur créé avec succès.'];
                header('Location: /utilisateurs');
                exit;
            }

            // Repopulate pour réaffichage en cas d'erreur
            $user = compact('nom', 'prenom', 'email', 'role', 'id_pilote');
        }

        $pageTitle = 'Créer un utilisateur — Web4All';
        require __DIR__ . '/../views/users/create.php';
    }

    // -------------------------------------------------------------------------
    // GET  /utilisateurs/{id}/modifier
    // POST /utilisateurs/{id}/modifier
    // Admin : tous les utilisateurs
    // Pilote : uniquement ses étudiants
    // -------------------------------------------------------------------------
    public function edit(int $id): void
    {
        $this->requireRole(['admin', 'pilote']);

        $currentRole = $_SESSION['role'];
        $currentId   = (int)$_SESSION['user_id'];

        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        if ($currentRole === 'pilote') {
            if ($user['role'] !== 'etudiant' || (int)$user['id_pilote'] !== $currentId) {
                http_response_code(403);
                require __DIR__ . '/../views/errors/403.php';
                return;
            }
        }

        $pilotes = $this->userModel->findByRole('pilote');
        $errors  = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $nom    = trim($_POST['nom']    ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email']  ?? '');
            // Pilote ne peut pas changer le rôle, admin ne peut pas mettre admin
            if ($currentRole === 'pilote') {
                $role = 'etudiant';
            } else {
                $role = $_POST['role'] ?? '';
                if (!in_array($role, ['pilote', 'etudiant'], true)) {
                    $errors[] = 'Rôle invalide.';
                }
            }

            if ($nom === '')    $errors[] = 'Le nom est obligatoire.';
            if ($prenom === '') $errors[] = 'Le prénom est obligatoire.';
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse email est invalide.';
            }

            $id_pilote = null;
            if ($role === 'etudiant') {
                $id_pilote = $currentRole === 'pilote'
                    ? $currentId
                    : (!empty($_POST['id_pilote']) ? (int)$_POST['id_pilote'] : null);
            }

            if (empty($errors)) {
                $data = [
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'email'     => $email,
                    'role'      => $role,
                    'id_pilote' => $id_pilote,
                ];

                $password = $_POST['password'] ?? '';
                if ($password !== '') {
                    if (strlen($password) < 8) {
                        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
                    } else {
                        $data['password'] = $password;
                    }
                }

                if (empty($errors)) {
                    $this->userModel->update($id, $data);
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Utilisateur mis à jour.'];
                    header('Location: /utilisateurs');
                    exit;
                }
            }

            $user = array_merge($user, compact('nom', 'prenom', 'email', 'role', 'id_pilote'));
        }

        $pageTitle = 'Modifier un utilisateur — Web4All';
        require __DIR__ . '/../views/users/edit.php';
    }

    // -------------------------------------------------------------------------
    // POST /utilisateurs/{id}/supprimer
    // Admin : tous les utilisateurs
    // Pilote : uniquement ses étudiants
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->requireRole(['admin', 'pilote']);
        $this->verifyCsrf();

        $currentRole = $_SESSION['role'];
        $currentId   = (int)$_SESSION['user_id'];

        if ($id === $currentId) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vous ne pouvez pas supprimer votre propre compte.'];
            header('Location: /utilisateurs');
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        if ($currentRole === 'pilote') {
            if ($user['role'] !== 'etudiant' || (int)$user['id_pilote'] !== $currentId) {
                http_response_code(403);
                require __DIR__ . '/../views/errors/403.php';
                return;
            }
        }

        $this->userModel->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Utilisateur supprimé.'];
        header('Location: /utilisateurs');
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