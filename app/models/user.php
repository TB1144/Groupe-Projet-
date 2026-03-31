<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private PDO $db;

    private const SAFE_COLUMNS = 'u.id, u.nom, u.prenom, u.email, u.role, u.id_pilote';

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

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . " FROM users u WHERE u.id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . " FROM users u WHERE u.email = :email LIMIT 1"
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
            "SELECT " . self::SAFE_COLUMNS . " FROM users u WHERE u.role = :role ORDER BY u.nom, u.prenom"
        );
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche avec filtre nom/prénom/email + rôle + pagination.
     */
    public function searchAll(string $search, string $role, int $limit, int $offset): array {
        $conditions = ['1=1'];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(u.nom LIKE :search OR u.prenom LIKE :search2 OR u.email LIKE :search3)';
            $params[':search']  = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if (in_array($role, ['admin', 'pilote', 'etudiant'], true)) {
            $conditions[] = 'u.role = :role';
            $params[':role'] = $role;
        }

        $where = implode(' AND ', $conditions);
        $stmt = $this->db->prepare(
            "SELECT " . self::SAFE_COLUMNS . ", p.nom AS pilote_nom, p.prenom AS pilote_prenom
            FROM users u
            LEFT JOIN users p ON u.id_pilote = p.id
            WHERE $where
            ORDER BY u.nom, u.prenom
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
            $conditions[] = '(u.nom LIKE :search OR u.prenom LIKE :search2 OR u.email LIKE :search3)';
            $params[':search']  = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
            $params[':search3'] = '%' . $search . '%';
        }

        if (in_array($role, ['admin', 'pilote', 'etudiant'], true)) {
            $conditions[] = 'u.role = :role';
            $params[':role'] = $role;
        }

        $where = implode(' AND ', $conditions);
        $stmt  = $this->db->prepare("SELECT COUNT(*) FROM users u WHERE $where");
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

    public function update(int $id, array $data): bool {
        // Si ce user devient étudiant, il ne peut plus être pilote référent d'autres étudiants
        if ($data['role'] === 'etudiant' || $data['role'] === 'admin') {
            $stmt = $this->db->prepare(
                "UPDATE users SET id_pilote = NULL WHERE id_pilote = :id"
            );
            $stmt->execute([':id' => $id]);
        }

        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, email = :email, role = :role, id_pilote = :id_pilote";
        $params = [
            ':nom'       => $data['nom'],
            ':prenom'    => $data['prenom'],
            ':email'     => $data['email'],
            ':role'      => $data['role'],
            ':id_pilote' => $data['id_pilote'],
            ':id'        => $id,
        ];

        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";
        return $this->db->prepare($sql)->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}