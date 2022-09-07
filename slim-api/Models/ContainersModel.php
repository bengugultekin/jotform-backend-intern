<?php

class ContainersModel {

    private $id;
    private $container_name;
    private $created_at;

    protected $tableName = "containers";
    protected $primaryKey = ['id'];

    // GET all containers
    function getAllContainers() {
        try {
            $sql = "SELECT * FROM $this->tableName";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);
            // Make db null so that we do not get error when we do another db request
            $db = null;
            $query_result = array(
                "status" => true,
                "data" => $machines);

            return $query_result;
        }
        catch (PDOException $e) {
            $query_result = array(
                "status" => false,
                "message" => $e->getMessage());
            return $query_result;
        }
    }

    // GET one container by id
    function getContainerById($id) {
        try {
            $sql = "SELECT * FROM $this->tableName WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                // Make db null so that we do not get error when we do another db request
                $db = null;
                $query_result = array(
                    "status" => true,
                    "data" => $stmt->fetchAll(PDO::FETCH_OBJ));
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

    // POST container into db
    function postContainer($container_name, $cur_date) {
        try {
            $sql = "INSERT INTO $this->tableName (container_name, created_at) 
                    VALUES (:container_name, to_timestamp(:created_at))";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':container_name', $container_name);
            $stmt->bindParam(':created_at', $cur_date);
            if ($stmt->execute()) {
                // Make db null so that we do not get error when we do another db request
                $db = null;
                $query_result = array(
                    "status" => true,
                    "message" => "Container added succesfully!");
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

    // PUT container by id
    function putContainerById($id, $container_name) {
        try {
            $sql = "UPDATE $this->tableName SET container_name = :container_name WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();   
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(':container_name', $container_name);

            if ($stmt->execute()) {
                // Make db null so that we do not get error when we do another db request
                $db = null;
                $query_result = array(
                    "status" => true,
                    "message" => "Container updated succesfully!");
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

    // DELETE a container
    function deleteContainerById($id) {
        try {
            $sql = "DELETE FROM $this->tableName WHERE id = :id";
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            // Make db null so that we do not get error when we do another db request
            $db = null;

            if ($stmt->execute()) {
                // Make db null so that we do not get error when we do another db request
                $db = null;
                $query_result = array(
                    "status" => true,
                    "message" => "Container deleted succesfully!");
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

    // POST execute a command in given container
    function postExecCommandById($id, $user_cmd) {
        $sql = "SELECT container_name FROM containers WHERE id = :id";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);

            // Check if SQL is correct
            if ($stmt->execute()) {
                $machine = $stmt->fetch(PDO::FETCH_OBJ);

                // Check if machine exists in the database
                if ($machine != false) {
                    $exec_result = SSH2Connection($machine->container_name, $user_cmd);
                    
                    //Set new sql query to insert it into executions table
                    $cur_date = date_timestamp_get(date_create());
                    $exec_msg= EXECUTION_MODEL->postExecution($id, $user_cmd, $exec_result, $cur_date);
                    // Make db null so that we do not get error when we do another db request
                    $db = null;
                    $query_result = array(
                        "status" => true,
                        "exec_message" => $exec_msg['message'],
                        "message" => $exec_result);
                    return $query_result;
                }
                else {
                    $query_result = array(
                        "status" => false,
                        "type" => 404,
                        "message" => "Error, machine cannot be found.");
                    return $query_result;
                }
            }
            else {
                $query_result = array(
                    "status" => false,
                    "type" => 404,
                    "message" => "Error in executing SQL state");
                return $query_result;
            }
        }
        catch (PDOException $e) {
            $query_result = array(
                "status" => false,
                "type" => 500,
                "message" => $e->getMessage());
            return $query_result;
        }
    }
}

?>