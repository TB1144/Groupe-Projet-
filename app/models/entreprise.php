<?php

require_once __DIR__ . '/../../config/database.php';

class Entreprise
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        return $this->db->query('SELECT * FROM entreprises ORDER BY nom')
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nom, ville, description, email, telephone FROM entreprises WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
        return $entreprise ?: null;
    }   

    public function search(string $nom = '', string $ville = '', int $limit = 10, int $offset = 0): array
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($nom !== '') {
            $conditions[] = 'nom LIKE :nom';
            $params[':nom'] = '%' . $nom . '%';
        }
        if ($ville !== '') {
            $conditions[] = 'ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare(
            "SELECT e.*, AVG(ev.note) AS moyenne
            FROM entreprises e
            LEFT JOIN evaluations ev ON ev.id_entreprise = e.id
            WHERE $where
            GROUP BY e.id
            ORDER BY e.nom ASC
            LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $nom = '', string $ville = ''): int
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($nom !== '') {
            $conditions[] = 'nom LIKE :nom';
            $params[':nom'] = '%' . $nom . '%';
        }
        if ($ville !== '') {
            $conditions[] = 'ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare("SELECT COUNT(*) FROM entreprises WHERE $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO entreprises (nom, description, ville, email, telephone)
             VALUES (:nom, :description, :ville, :email, :telephone)'
        );
        $stmt->execute([
            ':nom'         => $data['nom'],
            ':description' => $data['description'],
            ':ville'       => $data['ville'],
            ':email'       => $data['email'],
            ':telephone'   => $data['telephone'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE entreprises
             SET nom = :nom, description = :description, ville = :ville,
                 email = :email, telephone = :telephone
             WHERE id = :id'
        );
        $stmt->execute([
            ':nom'         => $data['nom'],
            ':description' => $data['description'],
            ':ville'       => $data['ville'],
            ':email'       => $data['email'],
            ':telephone'   => $data['telephone'],
            ':id'          => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM entreprises WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}