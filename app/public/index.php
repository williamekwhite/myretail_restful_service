<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// Load Config Settings
require __DIR__ . '/../config.php';

// API routes
require __DIR__ . '/../routes/api.php';

// Instantiate Home Route
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to <em>myRetail RESTful service</em>!");
    return $response;
});

$app->run();