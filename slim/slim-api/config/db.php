<?php

class DB {
    private $host = "localhost";
    private $usr = "postgres";
    private $password = "mypassword";
    private $db_name = "test_db";

    public function connect() {
        try {
            $dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name;";
            
            // make a database connection
            $pdo = new PDO($dsn, $this->usr, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
            if ($pdo) {
                echo "Connected to the $this->db_name database successfully!";
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $pdo;
    }
}
?>