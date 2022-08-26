<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();

require __DIR__ . '/../Routes/Router.php';



$app->run();


