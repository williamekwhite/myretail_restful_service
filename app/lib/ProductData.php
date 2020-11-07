<?php

namespace lib;

use GuzzleHttp\Client;

class ProductData
{
    /**
     * Get the Product
     * - Merges DB and API information about a product together and returns
     * @param $productId
     * @return array|false
     */
    public function getProduct($productId) {
        // Fetch API information
        $productApiFields = $this->getProductApiDetails($productId);

        // Merge together
        if(true && $productApiFields) {
            return $productApiFields;
        }
        return false;
    }


    /**
     * Gets the Product Details from the API
     * - Parses the valid product object and fetches the needed fields, currently: title
     * @param $productId
     * @return array|false
     */
    private function getProductApiDetails($productId) {
        // Fetch Product Details
        $productObject = $this->fetchProductDetailsFromAPI($productId);

        // Issue fetch product information, send error
        if(!$productObject) {
            return false;
        }

        /*
         * Parse product information
         * Fields to grab: title [product.item.product_description.title]
         */
        $fields = [];
        if(!empty($productObject->product->item->product_description->title)) {
            $fields['title'] = $productObject->product->item->product_description->title;
        }
        return !empty($fields) ? $fields : false;
    }


    /**
     * Fetches product data from the RedSky API
     * - Returns false if there is an error from the API or if the product is not found
     * @param $productId
     * @return false|mixed
     */
    private function fetchProductDetailsFromAPI($productId) {
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