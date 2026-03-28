<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private PDO $db;

    // Colonnes à sélectionner — jamais le password sauf pour checkCredentials
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
            unset($user['password']); // ne jamais laisser le hash en mémoire/session
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
        // Le mot de passe est optionnel : on ne le change que s'il est fourni
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