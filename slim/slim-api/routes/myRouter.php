<?php

use PhpParser\Node\Arg;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// List Method
$app->get('/machines/all', function (Request $request, Response $response) {
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
});

// View Method 
$app->get('/machines/all/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "SELECT * FROM containers WHERE container_id=$id";
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
});

// Delete Method
$app->get('/machines/del/{id}', function (Request $request, Response $response, array $args) {
    $id = $args['id'];
    $sql = "DELETE FROM containers WHERE container_id = $id ";
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
});

// Create Method 
$app->post('/machines/add', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $container_id = $data["container_id"];
    $containername = $data["containername"];
    $date = date('Y/m/d h:i:s a', time());
   
    $sql = "INSERT INTO containers (container_id, containername, created_on) VALUES (:container_id, :containername, :created_on)";
   
    try {
      $db = new Db();
      $conn = $db->connect();
     
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':container_id', $container_id);
      $stmt->bindParam(':containername', $containername);
      $stmt->bindParam(':created_on', $date);
   
      $result = $stmt->execute();
   
      $db = null;
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
   });

   // Update Method
   $app->put('/machines/update/{container_id}', function (Request $request, Response $response, array $args) {

    $container_id = $request->getAttribute('container_id');
    $data = $request->getParsedBody();
    $containername = $data["containername"];
    $created_on = date('Y/m/d h:i:s a', time());

    $sql = "UPDATE containers SET
            containername = :containername,
            created_on = :created_on
    WHERE container_id = $container_id";

    try {
        $db = new Db();
        $conn = $db->connect();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':containername', $containername);
        $stmt->bindParam(':created_on', $created_on);

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
    });
?>