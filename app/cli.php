<?php
require __DIR__ . '/vendor/autoload.php';

// Load Config Settings
require __DIR__ . '/config.php';

use service\ProductService;

// Check for command
if(empty($argv[1])) {
    echo "No command given. Try again.";
}

// Execute the command
$command = $argv[1];

switch ($command) {
    case 'seed-db':
        // Seed the MongoDB instance with product info
        $productService = new ProductService();
        foreach([13860428, 13264003, 12954218, 85895671] as $productID) {
            $productService->updateProductDB($productID, ["current_price" => ["value" => (float)(rand(5,150).".".rand(10,99)), "currency_code" => "USD"]], true);
        }
        echo "The database has been seeded successfully";
        break;
}