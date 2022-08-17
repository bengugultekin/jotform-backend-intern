<?php

class DB {
    private $host = "localhost";
    private $usr = "postgres";
    private $password = "mypassword";
    private $db_name = "deneme_db";

    public function connect() {
        try {
            $dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name;";
            
            // make a database connection
            $pdo = new PDO($dsn, $this->usr, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
            if ($pdo) {
                // succesfull message here
                
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
        return $pdo;
    }
}
?>