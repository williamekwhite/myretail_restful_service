<?php
// Load Config Settings
require __DIR__ . '/../config.php';

use PHPUnit\Framework\TestCase;
use model\Product;
use service\ProductService;

final class ProductTest extends TestCase
{
    public function testProductGetters(): void
    {
        $product = new Product(1234, ["value"=> 12.90, "currency_code" => 'USD'], "Title");

        $this->assertEquals(
            $product->getId(),
            1234,
            "Setting / Getting ID Failed"
        );

        $this->assertEquals(
            $product->getCurrentPrice(),
            ["value"=> 12.90, "currency_code" => 'USD'],
            "Setting / Getting Value Failed"
        );

        $this->assertEquals(
            $product->getTitle(),
            'Title',
            "Setting / Getting Currency Code Failed"
        );

        $this->assertJsonStringEqualsJsonFile(
            __DIR__ .'/samples/product.json',
            json_encode($product->getResponseFormat()),
            "Product JSON is not formatted correctly"
        );
    }

    public function testProductService(): void
    {
        $productService = new ProductService();

        $productPayload = json_decode(file_get_contents(__DIR__ .'/samples/valid_product.json'));
        $productService->updateProductDB($productPayload->id, ["current_price" => (array)$productPayload->current_price]);
        $returnedProduct = $productService->getProduct($productPayload->id);

        $this->assertEquals(
            [$productPayload->id, $productPayload->name, $productPayload->current_price->value, $productPayload->current_price->currency_code],
            [$returnedProduct->getId(), $returnedProduct->getTitle(), $returnedProduct->getCurrentPrice()['value'], $returnedProduct->getCurrentPrice()['currency_code']],
            'Product updated and returned from DB does not match product payload'
        );
    }
}