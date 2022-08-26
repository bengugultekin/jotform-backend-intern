<?php

class DB {
    private $host = "postgresql_machine";
    private $usr = "mypostgres";
    private $password = "mypassword";
    private $db_name = "container_db";

    public function connect() {
        $dsn = "pgsql:host=$this->host;port=5432;dbname=$this->db_name;";

        // make a database connection
        $pdo = new PDO($dsn, $this->usr, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        return $pdo;
    }
}
?>
