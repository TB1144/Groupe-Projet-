<?php
class Entreprise {
    private $db;

    public function __construct() {
        // Utilisation db
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM entreprises");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>