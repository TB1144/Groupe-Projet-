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
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function search(string $nom = '', string $ville = '', float $noteMin = 0, int $limit = 10, int $offset = 0): array
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($nom !== '') {
            $conditions[] = 'e.nom LIKE :nom';
            $params[':nom'] = '%' . $nom . '%';
        }
        if ($ville !== '') {
            $conditions[] = 'e.ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }

        $where = implode(' AND ', $conditions);

        // Filtre note via HAVING
        $having = $noteMin > 0 ? "HAVING moyenne >= :note_min OR moyenne IS NULL" : '';
        // Si note filtrée, on exclut les non-évaluées
        if ($noteMin > 0) {
            $having = "HAVING moyenne >= :note_min";
            $params[':note_min'] = $noteMin;
        }

        $stmt = $this->db->prepare(
            "SELECT e.*, ROUND(AVG(ev.note), 1) AS moyenne
             FROM entreprises e
             LEFT JOIN evaluations ev ON ev.id_entreprise = e.id
             WHERE $where
             GROUP BY e.id
             $having
             ORDER BY e.nom ASC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            if ($key === ':note_min') {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(string $nom = '', string $ville = '', float $noteMin = 0): int
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($nom !== '') {
            $conditions[] = 'e.nom LIKE :nom';
            $params[':nom'] = '%' . $nom . '%';
        }
        if ($ville !== '') {
            $conditions[] = 'e.ville LIKE :ville';
            $params[':ville'] = '%' . $ville . '%';
        }

        $where  = implode(' AND ', $conditions);
        $having = $noteMin > 0 ? "HAVING moyenne >= :note_min" : '';
        if ($noteMin > 0) $params[':note_min'] = $noteMin;

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM (
                SELECT e.id, ROUND(AVG(ev.note), 1) AS moyenne
                FROM entreprises e
                LEFT JOIN evaluations ev ON ev.id_entreprise = e.id
                WHERE $where
                GROUP BY e.id
                $having
             ) AS sub"
        );
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    // Autocomplete — cherche n'importe où dans le nom (%query%)
    public function autocomplete(string $query, int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nom, ville FROM entreprises
             WHERE nom LIKE :q
             ORDER BY nom ASC
             LIMIT :limit"
        );
        $stmt->bindValue(':q',     '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,              PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Autocomplete pour les villes — cherche n'importe où dans la ville (%query%)
        public function autocompleteVille(string $query, int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT ville FROM entreprises
            WHERE ville LIKE :q
            ORDER BY ville ASC
            LIMIT :limit"
        );
        $stmt->bindValue(':q',     '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit,              PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            'UPDATE entreprises SET nom = :nom, description = :description,
             ville = :ville, email = :email, telephone = :telephone WHERE id = :id'
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
        $this->db->prepare('DELETE FROM entreprises WHERE id = :id')->execute([':id' => $id]);
    }
}