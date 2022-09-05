<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../Config/Config.php';
require_once(__DIR__ . '/../Models/ContainersModel.php');
require_once(__DIR__ . '/../Models/ExecutionsModel.php');
require __DIR__ . '/SSH2Connection.php';

define('CONTAINER_MODEL', new ContainersModel());
define('EXECUTION_MODEL', new ExecutionsModel());

class MachineController {

    // GET get all machines in the database
    public function getAllMachines(Request $request, Response $response) {
        $result = CONTAINER_MODEL->getAllContainers();

        if($result['status']) {
            $response->getBody()->write(json_encode($result['data']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // GET get a machine using its id
    public function getMachine(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $result = CONTAINER_MODEL->getContainerById($id);

        if($result['status']) {
            $response->getBody()->write(json_encode($result['data']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // POST add a machine to database
    public function addMachine(Request $request, Response $response) {
        $container_name = $request->getParsedBody()['container_name'];
        $cur_date = date_timestamp_get(date_create());
        $result = CONTAINER_MODEL->postContainer($container_name, $cur_date);
        
        if($result['status']) {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // PUT update a machine using its id
    public function updateMachine(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $container_name = $request->getParsedBody()['container_name'];
        $result = CONTAINER_MODEL->putContainerById($id, $container_name);
        
        if($result['status']) {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // DELETE delete a machine
    public function deleteMachine(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $result = CONTAINER_MODEL->deleteContainerById($id);

        if($result['status']) {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }

    // POST execute a command on machine
    public function executeCommand(Request $request, Response $response, array $args) {
        $id = $args['id'];
        $user_cmd = $request->getParsedBody()['command'];
        $result = CONTAINER_MODEL->postExecCommandById($id, $user_cmd);

        if($result['status']) {
            $response->getBody()->write(json_encode($result['message'] . $result['exec_message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus($result['type']);
        }
    }

    // GET get all executions in the database
    public function getAllExecutions(Request $request, Response $response) {
        $result = EXECUTION_MODEL->getAllExecutions();

        if($result['status']) {
            $response->getBody()->write(json_encode($result['data']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(200);
        }
        else {
            $response->getBody()->write(json_encode($result['message']));
            return $response
                ->withHeader("content-type", "application/json")
                ->withStatus(500);
        }
    }
}
