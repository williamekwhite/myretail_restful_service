<?php
// Load Config Settings
require __DIR__ . '/../config.php';

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

use model\Product;
use proxy\ProductProxy;
use service\ProductService;

final class ProductAPITest extends TestCase
{
    public function testProductProxy(): void
    {
        $productResponse = ProductProxy::fetchFromAPI(12954218);
        $this->assertIsObject(
            $productResponse,
            "Redsky Product API call is failing"
        );

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ .'/samples/redsky-api-product.json',
            json_encode($productResponse),
            "Redsky Product API JSON response is not accurate"
        );
    }

    public function testProductGet(): void
    {
        $client = new Client(['http_errors' => false]);

        // Case - Bad ID
        $res = $client->request('GET', 'localhost/api/products/00000000000');
        $this->assertEquals(
            $res->getStatusCode(),
            404,
            "Bad Product ID returning wrong status"
        );

        // Case - Good ID
        $res = $client->request('GET', 'localhost/api/products/13264003');
        $this->assertEquals(
            $res->getStatusCode(),
            200,
            "Product Get not returning 200 when found"
        );

        // Case - Valid JSON body
        $productService = new ProductService();

        $productPayload = json_decode(file_get_contents(__DIR__ .'/samples/valid_product.json'));
        $productService->updateProductDB($productPayload->id, ["current_price" => (array)$productPayload->current_price]);

        $res = $client->request('GET', "localhost/api/products/{$productPayload->id}");
        $this->assertJsonStringEqualsJsonString(
            json_encode($productPayload),
            $res->getBody(),
            "Product Get API JSON response is not correct"
        );
    }
}