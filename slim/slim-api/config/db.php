<?php

class DB {
    private $host = "localhost";
    private $usr = "postgres";
    private $password = "mypassword";
    private $db_name = "container_db";

    public function connect() {
        $dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name;";

        // make a database connection
        $pdo = new PDO($dsn, $this->usr, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
        if ($pdo) {
            echo "Connected to the $this->db_name database successfully!";
        }
        return $pdo;
    }
}
?>