#!/bin/sh

# To start the application you must first start the application. You can do this by starting docker-compose from the `/docker` directory.
docker-compose --project-directory ./docker up -d

# Install Composer Dependencies inside of container
docker exec myretail_restful_service-app-1 composer install