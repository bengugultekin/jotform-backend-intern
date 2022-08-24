<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Config/Config.php';
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

    // POST execute a command on machine
    
    public function executeCommand(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $user_cmd = $request->getParsedBody()['command'];
        $sql = "SELECT container_name FROM containers WHERE id = $id";
        try {
            $db = new DB();
            $conn = $db->connect();

            $stmt = $conn->query($sql);
            $machine = $stmt->fetch(PDO::FETCH_OBJ);

            // Make db null so that we do not get error when we do another db request
            $db = null;

            $response->getBody(SSH2Connection($machine->container_name, $user_cmd));
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
