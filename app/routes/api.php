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

    $group->put('/products/{id}', function (Request $request, Response $response, $args) {
        // For now, only allow current_price field to be updated
        $fields = $request->getParsedBody();
        $fields = array_intersect_key($fields, ['current_price' => null]);
        if(empty($fields['current_price'])) {
            $payload = json_encode(['message' => 'The product price is missing']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // Update product
        $productData = new \lib\ProductData();
        $isProductUpdated = $productData->updateProductDB($args['id'], $fields);

        // If unsuccessful
        if($isProductUpdated === 0) {
            $payload = json_encode(['message' => 'There was an issue updating the product']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        // If upserted sucessfully
        if($isProductUpdated === 2) {
            $payload = json_encode(['message' => 'Product Inserted Successfully']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        }

        // If successfully updated
        $payload = json_encode(['message' => 'Product Updated Successfully']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

});