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
    // Liste tous les utilisateurs avec filtre par rôle + pagination
    // Accès : Admin uniquement
    // -------------------------------------------------------------------------
    public function index(): void
    {
        $this->requireRole(['admin']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $search = trim($_GET['search'] ?? '');
        $role   = $_GET['role'] ?? '';
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $users      = $this->userModel->searchAll($search, $role, $limit, $offset);
        $total      = $this->userModel->countAll($search, $role);
        $totalPages = (int)ceil($total / $limit);

        $pageTitle = 'Utilisateurs — Web4All';
        require __DIR__ . '/../views/users/index.php';
    }

    // -------------------------------------------------------------------------
    // GET /utilisateurs/{id}/modifier
    // POST /utilisateurs/{id}/modifier
    // Accès : Admin uniquement
    // -------------------------------------------------------------------------
    public function edit(int $id): void {
        $this->requireRole(['admin']);

        $user = $this->userModel->findById($id);
        if (!$user) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $pilotes = $this->userModel->findByRole('pilote');
        $errors  = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $nom    = trim($_POST['nom']    ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email']  ?? '');
            $role   = $_POST['role'] ?? '';

            // Validations
            if ($nom === '')    $errors[] = 'Le nom est obligatoire.';
            if ($prenom === '') $errors[] = 'Le prénom est obligatoire.';
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'L\'adresse email est invalide.';
            }
            if (!in_array($role, ['admin', 'pilote', 'etudiant'], true)) {
                $errors[] = 'Rôle invalide.';
            }

            $id_pilote = ($role === 'etudiant' && !empty($_POST['id_pilote']))
                ? (int)$_POST['id_pilote']
                : null;

            if (empty($errors)) {
                $data = [
                    'nom'       => $nom,
                    'prenom'    => $prenom,
                    'email'     => $email,
                    'role'      => $role,
                    'id_pilote' => $id_pilote,
                ];

                // Only rehash if a new password was provided
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

            // Repopulate $user with submitted values so the form reflects them on error
            $user = array_merge($user, [
                'nom'       => $nom,
                'prenom'    => $prenom,
                'email'     => $email,
                'role'      => $role,
                'id_pilote' => $id_pilote,
            ]);
        }

        require __DIR__ . '/../views/users/edit.php';
    }

    // -------------------------------------------------------------------------
    // POST /utilisateurs/{id}/supprimer
    // Accès : Admin uniquement
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->requireRole(['admin']);
        $this->verifyCsrf();

        // Empêche l'admin de se supprimer lui-même
        if ($id === (int)$_SESSION['user_id']) {
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