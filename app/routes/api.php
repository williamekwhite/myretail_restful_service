<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

/*
 * API routes
 */
$app->group('/api', function (RouteCollectorProxy $group) {

    // Home API endpoint
    $group->get('', function (Request $request, Response $response, $args) {
        $payload = json_encode(['message' => 'Welcome to myRetail RESTful service API']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Products
    $group->get('/products/{id}', function (Request $request, Response $response, $args) {
        $productData = new \lib\ProductData();
        $product = $productData->getProduct($args['id']);

        // If no product found, return a 404 with a message
        if(!$product) {
            $payload = json_encode(['message' => 'Product not found']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $payload = json_encode($product);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

});