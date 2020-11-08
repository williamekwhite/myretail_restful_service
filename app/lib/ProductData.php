<?php

namespace lib;

use GuzzleHttp\Client;

/**
 * The ProductData Class contains all relevant processing of product data
 * @package lib
 */
class ProductData
{
    const DB_PRODUCT_COLLECTION_NAME = 'products';

    /**
     * Get the Product
     * - Merges DB and API information about a product together and returns
     * - DB and API fields are required and a valid product will only be returned, when both are available
     * @param $productId
     * @return array|false
     */
    public function getProduct(int $productId) {
        // Fetch DB information
        $productDBFields = $this->getProductDBDetails($productId);

        // Fetch API information
        $productApiFields = $this->getProductApiDetails($productId);

        // Merge together
        if($productDBFields && $productApiFields) {
            return ["id" => $productId] + $productApiFields + $productDBFields;
        }
        return false;
    }

    /**
     * Gets the Product Details from the DB
     * - Parses the valid product document and fetches the needed fields, currently: current_price
     * @param int $productId
     * @return array|false
     */
    private function getProductDBDetails(int $productId) {
        $product = $this->fetchProductFromDB($productId);
        if(!$product) {
            return false;
        }

        /*
         * Parse product information
         * Fields to grab: current_price [product.current_price]
         */
        $fields = [];
        if(!empty($product->current_price)) {
            $fields['current_price'] = $product->current_price;
        }
        return !empty($fields) ? $fields : false;

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
     * Updates a Product document in the DB
     * - returns an int based on if server acknowledged the change and if it was an upsert
     *      0 = not successful
     *      1 = successful
     *      2 = successful and an upsert
     * @param int $productId
     * @param $fields
     * @return int
     */
    public function updateProductDB(int $productId, $fields) {
        $collection = Database::getDatabase()->{self::DB_PRODUCT_COLLECTION_NAME};
        try {
            $result = $collection->updateOne(['_id' => $productId], ['$set' => $fields], ['upsert' => true]);
            return (int)$result->isAcknowledged() + $result->getUpsertedCount();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Gets the Product Details from the API
     * - Parses the valid product object and fetches the needed fields, currently: title
     * @param $productId
     * @return array|false
     */
    private function getProductApiDetails(int $productId) {
        // Fetch Product Details
        $productObject = $this->fetchProductFromAPI($productId);

        // Issue fetch product information, send error
        if(!$productObject) {
            return false;
        }

        /*
         * Parse product information
         * Fields to grab: title [product.item.product_description.title] -- Rename to 'name'
         * - Add HTML entity decode to format titles correctly
         */
        $fields = [];
        if(!empty($productObject->product->item->product_description->title)) {
            $fields['name'] = html_entity_decode($productObject->product->item->product_description->title);
        }
        return !empty($fields) ? $fields : false;
    }


    /**
     * Fetches product data from the RedSky API
     * - Returns false if there is an error from the API or if the product is not found
     * @param $productId
     * @return false|mixed
     */
    private function fetchProductFromAPI(int $productId) {
        if(empty($productId)) {
            return false;
        }

        // Format Request URL
        $request_url = "https://redsky.target.com/v3/pdp/tcin/$productId";
        $request_url .= "?excludes=taxonomy,price,promotion,bulk_ship,rating_and_review_reviews,rating_and_review_statistics,question_answer_statistics&key=candidate";

        // Make the Request
        try {
            $client = new Client();
            $res = $client->request('GET', $request_url);

            // If something went wrong
            if($res->getStatusCode() !== 200) {
                return false;
            }

            // Is valid JSON?
            $jsonObject = json_decode($res->getBody());
            if(is_null($jsonObject)) {
                return false;
            }
            return $jsonObject;

        } catch(\Exception $e) { // Log later
            return false;
        }
    }

}