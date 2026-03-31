<?php

class AuthController {

    /**
     * Redirige l'utilisateur s'il est déjà sessionné
     */
    private function redirectIfAuthenticated(): void {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }

    public function loginForm(): void {
        $this->redirectIfAuthenticated();

        $pageTitle       = 'Connexion — Web4All';
        $metaDescription = 'Accédez à votre compte étudiant Web4All.';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $this->redirectIfAuthenticated();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->renderLoginError("Veuillez remplir tous les champs.");
            return;
        }

        $userModel = new User();
        $user      = $userModel->checkCredentials($email, $password);

        if ($user) {
            // Régénération de l'ID de session pour éviter la fixation de session
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['nom']     = $user['nom'];
            $_SESSION['prenom']  = $user['prenom'];
            
            header('Location: /');
            exit;
        }

        $this->renderLoginError("Identifiants incorrects.");
    }

    private function renderLoginError(string $error): void {
        $pageTitle = 'Connexion — Web4All';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        session_unset(); // Libère toutes les variables de session
        session_destroy();
        header('Location: /login');
        exit;
    }
}