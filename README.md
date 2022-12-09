# Technical Assessment Case Study

The purpose of this application and code base is to show my technical abilities in solving a particular case study.

## The Case Study

I have chosen to go with Case Study #1 _myRetail RESTful service_. PHP has been selected as the coding language. Other frameworks and dependencies include: Slim, Composer, MongoDB, and Docker. Docker is used to allow for a predictable environment that is universally available and usable when testing and editing.

The goal of the application is to house a RESTful service that can retrieve product and price details by ID. The two touch points of the application are as followed:
* Responds to an HTTP GET request at /products/{id} and delivers product data as JSON (where {id} will be a number. 
* BONUS: Accepts an HTTP PUT request at the same path (/products/{id}), containing a JSON request body similar to the GET response, and updates the product’s price in the data store.

### Considerations
* ~~Information about products comes from a 3rd party API [https://redsky.target.com/v3/pdp/tcin/13860428?excludes=taxonomy,price,promotion,bulk_ship,rating_and_review_reviews,rating_and_review_statistics,question_answer_statistics&key=candidate#_blank](https://redsky.target.com/v3/pdp/tcin/13860428?excludes=taxonomy,price,promotion,bulk_ship,rating_and_review_reviews,rating_and_review_statistics,question_answer_statistics&key=candidate#_blank), however, for the purposes of this exercise, we assume this is an internal API hosted by myRetail.~~
* Product information comes from a 3rd party API hosted with Target, an API key is needed and will need to be fetched from a browser session. However, for the purposes of this exercise, we assume this is an internal API hosted by myRetail.
* Reponses generated from the myRetail service, should contain several pieces of metadata about the product. Responses should be modeled as such `{"id":13860428,"name":"The Big Lebowski (Blu-ray) (Widescreen)","current_price":{"value": 13.49,"currency_code":"USD"}}`
* A NoSQL datastore must be available to store product pricing information, that is merged with the 3rd party API data.

## Requirements

* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [GitHub Personal Access Token](https://github.com/settings/tokens) (repo scope only)

## Setup

First, you need to configure your Docker Environment. You can do this by creating a `.env.app` file in the `/docker` directory. Below is the text to use. Please set your GitHub Personal Access token that you fetched from above. This will allow your Docker container to access private GitHub repositories through composer.
```
COMPOSER_AUTH='{"github-oauth":{"github.com":"TOKEN HERE"}}'
```

To install, run the install command
```
./install.sh
```

Setup application config file. Start in the `/app` directory. You may need to adjust settings based on your setup.
```
cp config.php.example config.php
```

Seed the DB
```
docker exec myretail_restful_service-app-1 php cli.php seed-db
```



If you visit [http://localhost:8888](http://localhost:8888) you should see a welcome message. This means you have successfully setup your environment.

## MongoDB
MongoDB is running alongside the application. It is the most popular NoSQL data store in current use. It is used in this specific application to store product price data.

## Mongo Express
Mongo Express is a Web-based MongoDB admin interface written with Node.js, Express and Bootstrap3. It is configured to run with this docker-compose configuration. You can visit [http://localhost:8081](http://localhost:8081) to access

## API Endpoints
Once the applicaton is up and running, several API endpoints are available for viewing. There is also a PUT endpoint that will update product pricing in the database. HTTP GET requests can easily be called in any browser of choice, but PUT requests not so much. For all testing and viewing API responses, it suggested that [Postman](https://www.postman.com/) be used. Below are some sample endpoints that can be requested. They are in cURL request format for easy testing. The URLs can be seen each command.

### API Home
```
curl --request GET \
  --url http://localhost:8888/api
```

### Product Details
```
curl --request GET \
  --url http://localhost:8888/api/products/13860428
```

### Update Product
```
curl --request PUT \
  --url http://localhost:8888/api/products/13264003 \
  --header 'Content-Type: application/json' \
  --data '{"current_price": {"value": 13.49,"currency_code": "GBP"}}'
```