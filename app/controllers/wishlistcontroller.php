<?php

class WishlistController
{
    private Wishlist $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new Wishlist();
    }

    // -------------------------------------------------------------------------
    // SFx 23 – Afficher la wishlist
    // GET /wishlist
    // GET /wishlist?page=2
    // Accès : Étudiant uniquement
    // -------------------------------------------------------------------------
    public function index(): void
    {
        $this->requireRole(['etudiant']);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $page             = max(1, (int)($_GET['page'] ?? 1));
        $limit            = 10;
        $offset           = ($page - 1) * $limit;
        $searchEntreprise = trim($_GET['search_entreprise'] ?? '');

        $total      = $this->wishlistModel->countByEtudiant($_SESSION['user_id'], $searchEntreprise);
        $items      = $this->wishlistModel->findByEtudiant($_SESSION['user_id'], $limit, $offset, $searchEntreprise);
        $totalPages = (int)ceil($total / $limit);

        require __DIR__ . '/../views/wishlist/index.php';
    }

    // -------------------------------------------------------------------------
    // SFx 24 & 25 – Toggle wishlist (ajouter ou retirer)
    // POST /wishlist/toggle
    // Accès : Étudiant uniquement
    // -------------------------------------------------------------------------
    public function toggle(): void
    {
        $this->requireRole(['etudiant']);
        $this->verifyCsrf();

        $idOffre = (int)($_POST['id_offre'] ?? 0);
        $retour  = $_POST['retour'] ?? '/offres';

        if ($idOffre <= 0) {
            header('Location: ' . $retour);
            exit;
        }

        if ($this->wishlistModel->exists($_SESSION['user_id'], $idOffre)) {
            $this->wishlistModel->remove($_SESSION['user_id'], $idOffre);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Offre retirée de votre wishlist.'];
        } else {
            $this->wishlistModel->add($_SESSION['user_id'], $idOffre);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Offre ajoutée à votre wishlist.'];
        }

        header('Location: ' . $retour);
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