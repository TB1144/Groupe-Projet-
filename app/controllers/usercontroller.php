<?php
require_once 'models/user.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($email, $password) {
        $user = $this->userModel->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php'); // redirect
        } else {
            echo "Email ou mot de passe incorrect";
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: login.html');
    }

    public function register($nom, $prenom, $email, $password, $role='etudiant') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $this->userModel->create($nom, $prenom, $email, $hashed, $role);
    }
}
?>