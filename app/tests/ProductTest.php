<?php
// Load Config Settings
require __DIR__ . '/../config.php';

use PHPUnit\Framework\TestCase;
use model\Product;
use proxy\ProductProxy;

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
    }

    public function testProductProxy(): void
    {
        $productResponse = ProductProxy::fetchFromAPI(1234);
        echo $productResponse;
    }
}