<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Controllers/MachineController.php';
require __DIR__ . '/../Controllers/CronjobController.php';

$app = AppFactory::create();
define('MACHINE_CONTROLLER', new MachineController());
define('CRONJOB_CONTROLLER', new CronjobController());

header("Access-Control-Allow-Origin: *");
/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

// GET Home Page
$app->get('/', function (Request $request, Response $response) {
    #require __DIR__ . '/../Views/homeView.php';  
    return $response; 
});
// GET /v1/machines
$app->get('/v1/machines', function (Request $request, Response $response) {
    #require __DIR__ . '/../Views/machinesView.php';
    $data = MACHINE_CONTROLLER->getAllMachines($request, $response);
    return $data;
});

// GET /v1/machine/{id}
$app->get('/v1/machine/{id}', function (Request $request, Response $response, array $args) {
    #require __DIR__ . '/../Views/machineView.php';
    $data = MACHINE_CONTROLLER->getMachine($request, $response, $args);
    return $data;
});

// POST /v1/add/machine
$app->post('/v1/add/machine', function (Request $request, Response $response) { 
    $data = MACHINE_CONTROLLER->addMachine($request, $response);
    return $data;
});

// PUT /v1/update/machine/{id}
$app->put('/v1/update/machine/{id}', function (Request $request, Response $response) {
    $data = MACHINE_CONTROLLER->updateMachine($request, $response);
    return $data;
 });

 // DELETE /v1/delete/machine/{id}
$app->delete('/v1/delete/machine/{id}', function (Request $request, Response $response, array $args) {
    $data = MACHINE_CONTROLLER->deleteMachine($request, $response, $args);
    return $data;
});

//POST /v1/machine/{id}/exec
$app->post('/v1/machine/{id}/exec', function (Request $request, Response $response, array $args ) {
    $data = MACHINE_CONTROLLER->executeCommand($request, $response, $args);
    return $data;
});

// GET /v1/executions
$app->get('/v1/executions', function (Request $request, Response $response) {
    #require __DIR__ . '/../Views/executionsView.php';
    $data = MACHINE_CONTROLLER->getAllExecutions($request, $response);
    return $data;
});

// GET /v1/executions/{id}
$app->get('/v1/executions/{id}', function (Request $request, Response $response, array $args) {
    #require __DIR__ . '/../Views/executionsView.php';
    $data = MACHINE_CONTROLLER->getAllExecutionsOfMachine($request, $response, $args);
    return $data;
});

//******************************* TEST PART STARTS*******************************//

// GET /v1/cronjobs
$app->get('/v1/cronjobs', function (Request $request, Response $response) {
    $data = CRONJOB_CONTROLLER->getAllCronjobs($request, $response);
    return $data;
});

// POST /v1/add/cronjob
$app->post('/v1/add/cronjob', function (Request $request, Response $response) { 
    $data = CRONJOB_CONTROLLER->addCronjob($request, $response);
    return $data;
});

//******************************* TEST PART ENDS*******************************//

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