<?php

namespace proxy;

use GuzzleHttp\Client;

class ProductProxy {

    /**
     * Fetches product data from the RedSky API
     * - Returns false if there is an error from the API or if the product is not found
     * @param $productId
     * @return false|mixed
     */
    public static function fetchFromAPI(int $productId) {
        if(empty($productId)) {
            return false;
        }

        // Make the Request
        try {
            $client = new Client();
            $res = $client->request('GET', REDSKY_API_PRODUCT_ENDPOINT, [
                'query' => [
                    'key' => REDSKY_API_KEY,
                    'tcin' => $productId,
                    'pricing_store_id' => REDSKY_API_PRODUCT_PRICING_STORE_ID
                ]
            ]);

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