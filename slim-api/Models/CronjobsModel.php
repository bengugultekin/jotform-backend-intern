<?php

class CronjobsModel {

    private $id;
    private $cronjob_command;
    private $execution_machine_id;

    protected $tableName = "cronjobs";
    protected $primaryKey = ['id'];

    // GET all containers
    function getAllCronjobs() {
        try {
            $sql = "SELECT * FROM $this->tableName";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            $cronjobs = $stmt->fetchAll(PDO::FETCH_OBJ);
            // Make db null so that we do not get error when we do another db request
            $db = null;
            $query_result = array(
                "status" => true,
                "data" => $cronjobs);

            return $query_result;
        }
        catch (PDOException $e) {
            $query_result = array(
                "status" => false,
                "message" => $e->getMessage());
            return $query_result;
        }
    }

    // POST container into db
    function postCronjob($cronjob_command, $execution_machine_id) {
        try {
            $sql = "INSERT INTO $this->tableName (cronjob_command, execution_machine_id) 
                    VALUES (:cronjob_command, :execution_machine_id)";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':cronjob_command', $cronjob_command);
            $stmt->bindParam(':execution_machine_id', $execution_machine_id);
            if ($stmt->execute()) {
                // Make db null so that we do not get error when we do another db request
                $db = null;
                $query_result = array(
                    "status" => true,
                    "message" => "Cronjob added succesfully!");
                return $query_result;
            }
            else {
                $query_result = array(
                    "status" => false,
                    "message" => "Error in executing SQL state");
                return $query_result;
            }
        }
        catch (PDOException $e) {
            $query_result = array(
                "status" => false,
                "message" => $e->getMessage());
            return $query_result;
        }
    }
}


?>