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

    // Create inital containers table in the database
    public function createContainersTable() {
        $sql = "CREATE TABLE containers (
            id serial PRIMARY KEY,
            container_name VARCHAR (50) UNIQUE NOT NULL,
            created_at TIMESTAMP NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            echo "Table containers created successfully\n";
            $db = null;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            echo "\n";
        }
    }

    // Create inital executions table in the database
    public function createExecutionsTable() {
        $sql = "CREATE TABLE executions (
            id serial PRIMARY KEY,
            container_id int NOT NULL,
            exec_command VARCHAR (100),
            exec_response text,
            exec_time TIMESTAMP NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            echo "Table executions created successfully\n";
            $db = null;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            echo "\n";
        }
    }

    // Create inital commands table for crontab in the database
    public function createCronjobsTable() {
        $sql = "CREATE TABLE cronjobs (
            id serial PRIMARY KEY,
            cronjob_command VARCHAR (100),
            execution_machine_id integer NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            echo "Table cronjobs created successfully\n";
            $db = null;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            echo "\n";
        }
    }
}

$init_db = new DB();
$init_db->connect();
$init_db->createContainersTable();
$init_db->createExecutionsTable();
$init_db->createCronjobsTable();

?>