<?php

use Slim\Factory\AppFactory;

require dirname(__DIR__) . '/vendor/autoload.php';
require __DIR__ . '/../Routes/Router.php';

$app = AppFactory::create();
$app->run();


