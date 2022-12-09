#!/bin/sh

# Rebuild files --- Run if new files added
#docker exec myretail_restful_service-app-1 composer dump-autoload

# Run tests
docker exec myretail_restful_service-app-1 ./vendor/bin/phpunit tests