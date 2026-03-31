<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private PDO $db;

    private const SAFE_COLUMNS = 'id, nom, prenom, email, role';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ─────────────────────────────────────────
    // AUTH
    // ─────────────────────────────────────────

    public function checkCredentials(string $email, string $password): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT id, nom, prenom, email, password, role
             FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return null;
    }

    // ─────────────────────────────────────────
    // LECTURE
    // ─────────────────────────────────────────

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . " FROM users WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . " FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findAll(): array
    {
        return $this->db->query(
            "SELECT " . self::SAFE_COLUMNS . " FROM users ORDER BY nom, prenom"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRole(string $role): array
    {
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . " FROM users WHERE role = :role ORDER BY nom, prenom"
        );
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche avec filtre nom/prénom/email + rôle + pagination.
     */
    public function searchAll(string $search, string $role, int $limit, int $offset): array
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($search !== '') {
            $conditions[] = '(nom LIKE :search OR prenom LIKE :search2 OR email LIKE :search3)';
            $params[':search']  = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if (in_array($role, ['admin', 'pilote', 'etudiant'], true)) {
            $conditions[] = 'role = :role';
            $params[':role'] = $role;
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . "
             FROM users
             WHERE $where
             ORDER BY nom, prenom
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

    /**
     * Compte le total pour la pagination.
     */
    public function countAll(string $search, string $role): int
    {
        $conditions = ['1=1'];
        $params     = [];

        if ($search !== '') {
            $conditions[] = '(nom LIKE :search OR prenom LIKE :search2 OR email LIKE :search3)';
            $params[':search']  = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if (in_array($role, ['admin', 'pilote', 'etudiant'], true)) {
            $conditions[] = 'role = :role';
            $params[':role'] = $role;
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare("SELECT COUNT(*) FROM users WHERE $where");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // ─────────────────────────────────────────
    // ÉCRITURE
    // ─────────────────────────────────────────

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (nom, prenom, email, password, role)
             VALUES (:nom, :prenom, :email, :password, :role)"
        );
        return $stmt->execute([
            ':nom'      => $data['nom'],
            ':prenom'   => $data['prenom'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role'     => $data['role'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        if (!empty($data['password'])) {
            $stmt = $this->db->prepare(
                "UPDATE users SET nom = :nom, prenom = :prenom, email = :email,
                 password = :password, role = :role WHERE id = :id"
            );
            return $stmt->execute([
                ':nom'      => $data['nom'],
                ':prenom'   => $data['prenom'],
                ':email'    => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':role'     => $data['role'],
                ':id'       => $id,
            ]);
        }

        $stmt = $this->db->prepare(
            "UPDATE users SET nom = :nom, prenom = :prenom, email = :email,
             role = :role WHERE id = :id"
        );
        return $stmt->execute([
            ':nom'    => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email'  => $data['email'],
            ':role'   => $data['role'],
            ':id'     => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}