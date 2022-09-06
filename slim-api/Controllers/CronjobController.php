<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once(__DIR__ . '/../Models/CronjobsModel.php');

define('CRONJOB_MODEL', new CronjobsModel());

class CronjobController {

    // GET get all cronjobs in the database
    public function getAllCronjobs(Request $request, Response $response) {
        $result = CRONJOB_MODEL->getAllCronjobs();

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

    // POST add a cronjob to database
    public function addCronjob(Request $request, Response $response) {
        $cronjob_command = $request->getParsedBody()['cronjob_command'];
        $execution_machine_id = $request->getParsedBody()['execution_machine_id']; //FIX IT
        $result = CRONJOB_MODEL->postCronjob($cronjob_command, $execution_machine_id);
        
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
}

?>