<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

// Load Config Settings
require __DIR__ . '/../config.php';

// Instantiate Home Route
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to <em>myRetail RESTful service</em>!");
    return $response;
});

// Libraries
require __DIR__ . '/../lib/Database.php';
require __DIR__ . '/../lib/ProductData.php';

// API routes
require __DIR__ . '/../routes/api.php';

$app->run();