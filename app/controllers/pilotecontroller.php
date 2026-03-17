<?php
class PiloteController {
    public function index(): void {
        $model = new User();
        $pilotes = $model->getUsersByRole('pilote');
        require 'app/Views/pilotes/index.php';
    }

    public function create(): void {
        require 'app/Views/pilotes/create.php';
    }

    public function store(): void {
        // Logique de création de compte pilote (SFx 13)
        header('Location: /pilotes');
    }
    
    public function delete(int $id): void {
        // SFx 15
    }
}
?>