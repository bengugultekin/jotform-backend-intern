<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../Config/DBConnect.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/SSH2Connection.php';


class MachineController {

    // GET get all machines in the database
    public function getAllMachines(Request $request, Response $response) {
        $sql = "SELECT * FROM containers";
        try {
            $db = new DB();
            $conn = $db->connect();
    
            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            // Make db null so that we do not get error when we do another db request
            $db = null;
            
            $response->getBody()->write(json_encode($machines));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } 
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // GET get a machine using its id
    public function getMachine(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $sql = "SELECT * FROM containers WHERE id = $id";
        try {
            $db = new DB();
            $conn = $db->connect();
    
            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            // Make db null so that we do not get error when we do another db request
            $db = null;
            
            $response->getBody()->write(json_encode($machines));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } 
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // POST add a machine to database
    public function addMachine(Request $request, Response $response) {
        $container_name = $request->getParsedBody()['container_name'];
        $cur_date = date_timestamp_get(date_create());
        $sql = "INSERT INTO containers (container_name, created_at) VALUES (:container_name, to_timestamp(:created_at))";
        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':container_name', $container_name);
            $stmt->bindParam(':created_at', $cur_date);

            $result = $stmt->execute();

            // Make db null so that we do not get error when we do another db request
            $db = null;
            
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } 
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // PUT update a machine using its id
    public function updateMachine(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $container_name = $data["container_name"];

        $sql = "UPDATE containers SET container_name = :container_name WHERE id = $id";

        try {
            $db = new DB();
            $conn = $db->connect();
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':container_name', $container_name);

            $result = $stmt->execute();

            $db = null;
            echo "Update successful! ";
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }
    }

    // DELETE delete a machine
    public function deleteMachine(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $sql = "DELETE FROM containers WHERE id = $id";
        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Make db null so that we do not get error when we do another db request
            $db = null;

            $response->getBody()->write(json_encode($machines));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // Temporary function ----> IT WILL BE REMOVED
    // Create inital table in the database
    public function createTable(Request $request, Response $response) {
        $sql = "CREATE TABLE containers (
            id serial PRIMARY KEY,
            container_name VARCHAR (50) UNIQUE NOT NULL,
            created_at TIMESTAMP NOT NULL);";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            // Make db null so that we do not get error when we do another db request
            $db = null;
    
            $response->getBody()->write(json_encode($machines));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    public function executeCommand(Request $request, Response $response, array $args) {
        $id = $args['id'];
        //var_dump($args);
        //var_dump($request->getBody()->getContents());
        $command = $request->getParsedBody()['command'];
        $sql = "SELECT container_name FROM containers WHERE id = :id";
        try {
            $db = new DB();
            $conn = $db->connect();
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) {
                $machine = $stmt->fetch(PDO::FETCH_OBJ);
                // Make db null so that we do not get error when we do another db request
                $db = null;
                if ($machine != false) {
                    $response->getBody()->write(SSH2Connection($machine->container_name, $command));
                    $container_id = $id;
                    $exec_command = $request->getParsedBody()['command'];
                    $exec_response = $response->getBody(SSH2Connection($machine->container_name, $command));
                    $exec_time = date_timestamp_get(date_create());
                    $sql = "INSERT INTO execution (container_id, exec_command, exec_response, exec_time) VALUES (:container_id, :exec_command, :exec_response, to_timestamp(:exec_time))";
                    try {
                        $db = new DB();
                        $conn = $db->connect();

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':container_id', $container_id);
                        $stmt->bindParam(':exec_command', $exec_command);
                        $stmt->bindParam(':exec_response', $exec_response);
                        $stmt->bindParam(':exec_time', $exec_time);

                        $result = $stmt->execute();

                        // Make db null so that we do not get error when we do another db request
                        $db = null;
                        
                        $response->getBody()->write(json_encode($result));
                        return $response
                            ->withHeader("content-type", "application/json")
                            ->withStatus(200);
                    } 
                    catch (PDOException $e) {
                        $error = array(
                            "message" => $e->getMessage()
                        );
                        $response->getBody()->write(json_encode($error));
                        return $response
                            ->withHeader("content-type", "application/json")
                            ->withStatus(500);
                    }

                    return $response
                        ->withHeader("content-type", "application/json")
                        ->withStatus(200);
                }
                else {
                    $response->getBody()->write(json_encode("Request entity cannot be processed by the server"));
                    return $response
                        ->withHeader("content-type", "application/json")
                        ->withStatus(404);
                }
            }
            else {
                $response->getBody()->write(json_encode("Error in executing SQL state"));
                return $response
                    ->withHeader("content-type", "application/json")
                    ->withStatus(404);
            }
        }
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }
    
    // GET get all machines in the database
    public function getAllExecutions(Request $request, Response $response) {
        $sql = "SELECT * FROM execution";
        try {
            $db = new DB();
            $conn = $db->connect();
    
            $stmt = $conn->query($sql);
            $machines = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            // Make db null so that we do not get error when we do another db request
            $db = null;
            
            $response->getBody()->write(json_encode($machines));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        } 
        catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );
            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }
}
