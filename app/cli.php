<?php
/**
 * Very simple CLI command runner
 */

// Instantiate the app
use Slim\Factory\AppFactory;
require __DIR__ . '/vendor/autoload.php';
$app = AppFactory::create();
require __DIR__ . '/config.php';
require __DIR__ . '/lib/Database.php';
require __DIR__ . '/lib/ProductData.php';

// Check for command
if(empty($argv[1])) {
    echo "No command given. Try again.";
}

// Execute the command
$command = $argv[1];

switch ($command) {
    case 'seed-db':
        // Seed the MongoDB instance with product info
        $productData = new \lib\ProductData();
        foreach([13860428, 54456119, 13264003, 12954218] as $productID) {
            $productData->updateProductDB($productID, ["current_price" => ["value" => (float) rand(5,150).".".rand(10,99), "currency_code" => "USD"]]);
        }
        echo "The database has been seeded successfully";
        break;
}