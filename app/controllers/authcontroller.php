<?php
class AuthController {
    // Afficher le formulaire de connexion
    public function login(): void {
        require 'app/Views/auth/login.php';
    }

    // Traiter la soumission du formulaire
    public function authenticate(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->checkCredentials($email, $password);

            if ($user) {
                $_SESSION['user'] = $user; // On stocke l'objet ou le tableau user
                header('Location: /offres');
            } else {
                $error = "Identifiants incorrects";
                require 'app/Views/auth/login.php';
            }
        }
    }

    public function logout(): void {
        session_destroy();
        header('Location: /login');
    }
}
?>