<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Disable Public Error Messages
$app->addErrorMiddleware(false,false,false);

// Instantiate Home Route
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to <em>myRetail RESTful service</em>!");
    return $response;
});

// Libraries
require __DIR__ . '/../lib/ProductData.php';

// API routes
require __DIR__ . '/../routes/api.php';

$app->run();