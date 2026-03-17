<?php
class EntrepriseController {

    public function index(): void {
        $model = new Entreprise();
        $entreprises = $model->findAll($pdo);
        require 'app/views/entreprises/index.php';
    }

    public function show(int $id): void {
        $model = new Entreprise();
        $entreprise = $model->find($id);
        require 'app/views/entreprises/show.php';
    }

    public function create(array $data): void {
        $model = new Entreprise();
        $model->insert($data);
        header('Location: /entreprises');
    }

    public function edit(int $id, array $data): void {
        $model = new Entreprise();
        $model->update($id, $data);
        header('Location: /entreprises');
    }

    public function delete(int $id): void {
        $model = new Entreprise();
        $model->delete($id);
        header('Location: /entreprises');
    }

    public function evaluer(int $idEntreprise, int $idEtudiant, int $note, string $commentaire): void {
        $model = new Evaluation();
        $model->insert([
            'entreprise_id' => $idEntreprise,
            'etudiant_id' => $idEtudiant,
            'note' => $note,
            'commentaire' => $commentaire
        ]);
        header('Location: /entreprises/'.$idEntreprise);
    }
}
?>