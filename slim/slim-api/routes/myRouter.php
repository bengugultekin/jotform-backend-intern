<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';

$app = AppFactory::create();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();


// GET /v1/machines
$app->get('/v1/machines', function (Request $request, Response $response) {
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

// GET /v1/machine/{id}
$app->get('/v1/machine/{id}', function (Request $request, Response $response, array $args) {
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
});

// POST /v1/add/machine
$app->post('/v1/add/machine', function (Request $request, Response $response) {
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
});

// PUT /v1/update/machine/{id}
$app->put('/v1/update/machine/{id}', function (Request $request, Response $response) {

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
    });

// DELETE /v1/delete/machine/{id}
$app->get('/v1/delete/machine/{id}', function (Request $request, Response $response, array $args) {
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
});

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
?>