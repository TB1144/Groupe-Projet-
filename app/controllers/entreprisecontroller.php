<?php
class EntrepriseController {

    public function index(): void {
        $model = new Entreprise();
        $entreprises = $model->findAll();
        require __DIR__ . '/../views/entreprises/index.php';
    }

    public function show(int $id): void {
        $model = new Entreprise();
        $entreprise = $model->find($id);
        require __DIR__ . '/../views/entreprises/show.php';
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Entreprise();
            $model->insert($_POST);
        }
        header('Location: /entreprises');
    }

    public function edit(int $id): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Entreprise();
            $model->update($id, $_POST);
        }
        header('Location: /entreprises');
    }

    public function delete(int $id): void {
        $model = new Entreprise();
        $model->delete($id);
        header('Location: /entreprises');
    }
}
