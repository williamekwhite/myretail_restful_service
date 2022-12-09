<?php

namespace service;

use GuzzleHttp\Client;

use lib\Database;
use model\Product;
use proxy\ProductProxy;

class ProductService {

    const DB_PRODUCT_COLLECTION_NAME = 'products';

    /**
     * Get the Product
     * - Merges DB and API information about a product together and returns
     * - DB and API fields are required and a valid product will only be returned, when both are available
     * @param $productId
     * @return Product | false
     */
    public function getProduct(int $productId) {
        // Fetch DB information
        $productDBFields = $this->fetchProductFromDB($productId);

        // Fetch API information
        $productApiFields = $this->getProductApiDetails($productId);

        // Merge together
        if($productDBFields && $productApiFields) {
            // Create object and return
            return new Product(
                $productId,
                $productDBFields['current_price'],
                $productApiFields->item->product_description->title
            );
        }
        return false;
    }

    /**
     * Fetches product data from the DB
     * - Returns false if there is an error from the DB or if the product is not found
     * @param $productId
     * @return array|object|null
     */
    private function fetchProductFromDB(int $productId) {
        $collection = Database::getDatabase()->{self::DB_PRODUCT_COLLECTION_NAME};
        try {
            $document = $collection->findOne(['_id' => $productId]);
            return !is_null($document) ? $document : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Creates / Updates a Product document in the DB
     * - returns an int based on if server acknowledged the change and if it was an upsert
     *      0 = not successful
     *      1 = successful
     * @param int $productId
     * @param $fields
     * @param $upsert
     * @return int
     */
    public function updateProductDB(int $productId, $fields, $upsert = false) {
        $collection = Database::getDatabase()->{self::DB_PRODUCT_COLLECTION_NAME};
        try {
            $result = $collection->updateOne(['_id' => $productId], ['$set' => $fields], ['upsert' => $upsert]);
            return (int)$result->getMatchedCount();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Gets the Product Details from the API
     * - Parses the valid product object and fetches the needed fields everything under `product` from API object
     * @param $productId
     * @return Array | false
     */
    private function getProductApiDetails(int $productId) {
        // Fetch Product Details
        $productObject = ProductProxy::fetchFromAPI($productId);

        // Issue fetch product information, send error
        if(!$productObject) {
            return false;
        }

        /*
         * Parse product information return info
         * Fields to grab: title [product.item.product_description.title] -- Rename to 'name'
         * - Add HTML entity decode to format titles correctly
         */
        $data = $productObject->data->product;
        return !empty($data) ? $data : false;
    }

}