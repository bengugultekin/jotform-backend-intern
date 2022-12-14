<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';


// // This also works
// $app = new AppFactory();
// $app = $app->create();

/*
$app = AppFactory::create();

$app->get('/{name}', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello " . $args['name']);
    return $response;
});
*/

// Machines routes
require __DIR__ . '/../Routes/Router.php';

$app->run();

?>