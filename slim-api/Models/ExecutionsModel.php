<?php

class ExecutionsModel {

    public $id;
    public $container_id;
    public $exec_command;
    public $exec_response;
    public $exec_time;

    protected $tableName = "executions";
    protected $primaryKey = ['id'];

    // GET all executions
    function getAllExecutions() {
        try {
            $sql = "SELECT * FROM $this->tableName";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            $executions = $stmt->fetchAll(PDO::FETCH_OBJ);
            // Make db null so that we do not get error when we do another db request
            $db = null;
            $query_result = array(
                "status" => true,
                "data" => $executions);

            return $query_result;
        }
        catch (PDOException $e) {
            $query_result = array(
                "status" => false,
                "message" => $e->getMessage());
            return $query_result;
        }
    }

    // POST execution
    function postExecution($id, $user_cmd, $exec_result, $cur_date) {
        try {
            $sql = "INSERT INTO executions (container_id, exec_command, exec_response, exec_time) 
                    VALUES (:container_id, :exec_command, :exec_response, to_timestamp(:exec_time))";
            $db = new DB();
            $conn = $db->connect();
            $exec_stmt = $conn->prepare($sql);
            $exec_stmt->bindParam(':container_id', $id);
            $exec_stmt->bindParam(':exec_command', $user_cmd);
            $exec_stmt->bindParam(':exec_response', $exec_result);
            $exec_stmt->bindParam(':exec_time', $cur_date);
            $exec_stmt->execute();
            // Make db null so that we do not get error when we do another db request
            $db = null;
            $query_result = array(
                "status" => true,
                "message" => "Execution added to database successfully!");
            return $query_result;
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