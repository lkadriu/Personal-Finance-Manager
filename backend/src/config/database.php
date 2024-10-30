<?php
// pfm/src/config/database.php

class Database {
    private $host = 'localhost';
    private $db_name = 'pfm';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Funksioni për krijimin e lidhjes
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
