<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Config/DBConnect.php';


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

    public function connectMachine (Request $request, Response $response) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $connection = ssh2_connect('ubuntu_client_machine1', 22);
        ssh2_auth_password($connection, 'root', 'mypassword');

        $stream = ssh2_exec($connection, 'pwd');
        stream_set_blocking($stream, true);
        $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        // echo stream_get_contents($stream_out);
        $response->getBody()->write(json_encode(stream_get_contents($stream_out)));
        return $response;
    }
}
