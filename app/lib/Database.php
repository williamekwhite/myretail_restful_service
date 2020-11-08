<?php

namespace lib;

use MongoDB\Client;

/**
 * The Database class controls all I/O for the MongoDB datastore.
 * @package lib
 */
class Database
{
    // Client
    protected static $_client = null;

    /**
     * Get the MongoDB Client
     * @return Client|null
     */
    public static function getClient() {
        if(self::$_client) {
            return self::$_client;
        }

        $username = MONGODB_USERNAME;
        $password = MONGODB_PASSWORD;
        $cluster = MONGODB_CLUSTERADDR;

        self::$_client = new Client(
            "mongodb://$username:$password@$cluster"
        );

        return self::$_client;
    }

    /**
     * Get the application database
     * @return \MongoDB\Database
     */
    public static function getDatabase() {
        $client = self::getClient();
        return $client->{MONGODB_DATABASE};
    }

    /**
     * Test the client connection
     * @return bool
     */
    public static function isClientConnected() {
        try {
            self::getDatabase()->command(['count' => ProductData::DB_PRODUCT_COLLECTION_NAME]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}