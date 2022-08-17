<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

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
?>