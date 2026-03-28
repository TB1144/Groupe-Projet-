<?php

class EntrepriseController
{
    private Entreprise $model;

    public function __construct()
    {
        $this->model = new Entreprise();
    }

    // -------------------------------------------------------------------------
    // GET /entreprises
    // -------------------------------------------------------------------------
    public function index(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $nom    = trim($_GET['nom']   ?? '');
        $ville  = trim($_GET['ville'] ?? '');
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $entreprises = $this->model->search($nom, $ville, $limit, $offset);
        $total       = $this->model->count($nom, $ville);
        $totalPages  = (int)ceil($total / $limit);

        $pageTitle = 'Entreprises — Web4All';
        require __DIR__ . '/../views/entreprises/index.php';
    }

    // -------------------------------------------------------------------------
    // GET /entreprises/{id}
    // -------------------------------------------------------------------------
    public function show(int $id): void
    {
        $entreprise = $this->model->findById($id);

        if (!$entreprise) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $pageTitle = htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') . ' — Web4All';
        require __DIR__ . '/../views/entreprises/show.php';
    }

    // -------------------------------------------------------------------------
    // GET  /entreprises/creer
    // POST /entreprises/creer
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function create(): void
    {
        $this->requireRole(['admin', 'pilote']);

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $data = $this->extractData();

            if (empty($data['nom']))   $errors[] = 'Le nom est obligatoire.';
            if (empty($data['ville'])) $errors[] = 'La ville est obligatoire.';
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse e-mail invalide.';
            }

            if (empty($errors)) {
                $id = $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Entreprise créée avec succès.'];
                header("Location: /entreprises/$id");
                exit;
            }
        }

        $pageTitle = 'Créer une entreprise — Web4All';
        require __DIR__ . '/../views/entreprises/create.php';
    }

    // -------------------------------------------------------------------------
    // GET  /entreprises/{id}/modifier
    // POST /entreprises/{id}/modifier
    // Accès : Admin, Pilote
    // -------------------------------------------------------------------------
    public function edit(int $id): void
    {
        $this->requireRole(['admin', 'pilote']);

        $entreprise = $this->model->findById($id);

        if (!$entreprise) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $data = $this->extractData();

            if (empty($data['nom']))   $errors[] = 'Le nom est obligatoire.';
            if (empty($data['ville'])) $errors[] = 'La ville est obligatoire.';
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Adresse e-mail invalide.';
            }

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Entreprise mise à jour.'];
                header("Location: /entreprises/$id");
                exit;
            }
        }

        $pageTitle = 'Modifier ' . htmlspecialchars($entreprise['nom'], ENT_QUOTES, 'UTF-8') . ' — Web4All';
        require __DIR__ . '/../views/entreprises/edit.php';
    }

    // -------------------------------------------------------------------------
    // POST /entreprises/{id}/supprimer
    // Accès : Admin
    // -------------------------------------------------------------------------
    public function delete(int $id): void
    {
        $this->requireRole(['admin']);
        $this->verifyCsrf();

        $entreprise = $this->model->findById($id);
        if (!$entreprise) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }

        $this->model->delete($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Entreprise supprimée.'];
        header('Location: /entreprises');
        exit;
    }

    // =========================================================================
    // Helpers privés
    // =========================================================================

    private function extractData(): array
    {
        return [
            'nom'         => trim($_POST['nom']         ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'ville'       => trim($_POST['ville']       ?? ''),
            'email'       => trim($_POST['email']       ?? ''),
            'telephone'   => trim($_POST['telephone']   ?? ''),
        ];
    }

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