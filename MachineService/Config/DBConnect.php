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

    // Temporary function ----> IT WILL BE REMOVED
    // Create inital table in the database
    public function createTable() {
        $sql = "CREATE TABLE containers (
            id serial PRIMARY KEY,
            container_name VARCHAR (50) UNIQUE NOT NULL,
            created_at TIMESTAMP NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            echo "Table containers created successfully";
            $db = null;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function createExecTable() {
        $sql = "CREATE TABLE execution (
            id serial PRIMARY KEY,
            container_id int NOT NULL,
            exec_command VARCHAR (100),
            exec_response VARCHAR (500),
            exec_time TIMESTAMP NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            echo "Table execution created successfully";
            $db = null;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }


}

$test = new DB();
$test->connect();
//$test->createTable();
//$test->createExecTable();
?>