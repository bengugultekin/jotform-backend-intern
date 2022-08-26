<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../Controllers/MachineController.php';

$app = AppFactory::create();
define('CONTROLLER', new MachineController());

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Hello from Slim 4 request handler');
    return $response;
});


// GET /v1/machines
$app->get('/v1/machines', function (Request $request, Response $response) {
    $data = CONTROLLER->getAllMachines($request, $response);
    return $data;
});

// GET /v1/machine/{id}
$app->get('/v1/machine/{id}', function (Request $request, Response $response, array $args) {
    $data = CONTROLLER->getMachine($request, $response, $args);
    return $data;
});

// POST /v1/add/machine
$app->post('/v1/add/machine', function (Request $request, Response $response) { 
    $data = CONTROLLER->addMachine($request, $response);
    return $data;
});

// PUT /v1/update/machine/{id}
$app->put('/v1/update/machine/{id}', function (Request $request, Response $response) {
    $data = CONTROLLER->updateMachine($request, $response);
    return $data;
 });

 // DELETE /v1/delete/machine/{id}
$app->delete('/v1/delete/machine/{id}', function (Request $request, Response $response, array $args) {
    $data = CONTROLLER->deleteMachine($request, $response, $args);
    return $data;
});

// Temporary endpoint ----> IT WILL BE REMOVED
// CREATE /v1/create/table
$app->get('/v1/create/table', function (Request $request, Response $response) {
    $data = CONTROLLER->createTable($request, $response);
    return $data;
});

$app->post('/v1/machine/{id}/exec', function (Request $request, Response $response, array $args ) {
    $data = CONTROLLER->executeCommand($request, $response, $args);
    return $data;
});

$app->get('/v1/executions', function (Request $request, Response $response) {
    $data = CONTROLLER->getAllExecutions($request, $response);
    return $data;
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