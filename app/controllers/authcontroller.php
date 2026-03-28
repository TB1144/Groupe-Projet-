<?php
class AuthController {

    public function loginForm(): void {
        $pageTitle = 'Connexion — Web4All';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user      = $userModel->checkCredentials($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['nom']     = $user['nom'];
            $_SESSION['prenom']  = $user['prenom']; // rajout de prenom pour affichage dans le header
            header('Location: /offres');
            exit;
        }

        $error = "Identifiants incorrects.";
        $pageTitle = 'Connexion — Web4All';
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout(): void {
        session_destroy();
        header('Location: /login');
        exit;
    }
}
