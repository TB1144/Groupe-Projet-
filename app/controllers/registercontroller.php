<?php

class RegisterController
{
    public function registerForm(): void
    {
        $pageTitle       = 'Créer un compte — Web4All';
        $metaDescription = 'Créez votre compte étudiant Web4All pour accéder aux offres de stage CESI.';
        require __DIR__ . '/../views/auth/register.php';
    }

    public function register(): void
    {
        $prenom   = trim($_POST['prenom']           ?? '');
        $nom      = trim($_POST['nom']              ?? '');
        $email    = trim($_POST['email']            ?? '');
        $password = $_POST['password']              ?? '';
        $confirm  = $_POST['password_confirm']      ?? '';

        // ── Validations serveur ──────────────────────────────────────────
        $errors = [];

        if (empty($prenom) || empty($nom)) {
            $errors[] = 'Le prénom et le nom sont obligatoires.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse e-mail invalide.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if ($password !== $confirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        // ── Vérification doublon email ───────────────────────────────────
        if (empty($errors)) {
            $userModel = new User();

            if ($userModel->findByEmail($email)) {
                $errors[] = 'Cette adresse e-mail est déjà utilisée.';
            }
        }

        // ── Erreurs : on réaffiche le formulaire ─────────────────────────
        if (!empty($errors)) {
            $error     = implode('<br>', array_map('htmlspecialchars', $errors));
            $pageTitle = 'Créer un compte — Web4All';
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        // ── Création du compte ───────────────────────────────────────────
        $userModel->create([
            'nom'      => $nom,
            'prenom'   => $prenom,
            'email'    => $email,
            'password' => $password,
            'role'     => 'etudiant',   // toujours étudiant à l'inscription
        ]);

        // ── Connexion automatique après inscription ───────────────────────
        $user = $userModel->findByEmail($email);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['nom']     = $user['nom'];
        $_SESSION['prenom']  = $user['prenom'];

        header('Location: /offres');
        exit;
    }
}