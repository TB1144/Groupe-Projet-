<?php

require_once __DIR__ . '/../../config/database.php';

class Competence
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        return $this->db->query('SELECT * FROM competences ORDER BY nom')
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByOffre(int $idOffre): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.* FROM competences c
             JOIN offre_competences oc ON c.id = oc.id_competence
             WHERE oc.id_offre = :id'
        );
        $stmt->execute([':id' => $idOffre]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
