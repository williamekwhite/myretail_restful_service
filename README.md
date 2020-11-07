# Technical Assessment Case Study

The purpose of this application and code base is to show my technical abilities in solving a particular case study.

## The Case Study

I have chosen to go with Case Study #1 _myRetail RESTful service_. PHP has been selected as the coding language. Other frameworks and dependencies include: Slim, PHP Unit, Composer, and Docker. Docker is used to allow for a predictable environment that is universally available and usable when testing and editing.

The goal of the application is to house a RESTful service that can retrieve product and price details by ID. The two touch points of the application are as followed:
* Responds to an HTTP GET request at /products/{id} and delivers product data as JSON (where {id} will be a number. 
* BONUS: Accepts an HTTP PUT request at the same path (/products/{id}), containing a JSON request body similar to the GET response, and updates the product’s price in the data store.

### Considerations
* Information about products comes from a 3rd party API [https://redsky.target.com/v3/pdp/tcin/13860428?excludes=taxonomy,price,promotion,bulk_ship,rating_and_review_reviews,rating_and_review_statistics,question_answer_statistics&key=candidate#_blank](https://redsky.target.com/v3/pdp/tcin/13860428?excludes=taxonomy,price,promotion,bulk_ship,rating_and_review_reviews,rating_and_review_statistics,question_answer_statistics&key=candidate#_blank), however, for the purposes of this exercise, we assume this is an internal API hosted by myRetail.
* Reponses generated from the myRetail service, should contain several pieces of meta data about the product. Responses should be modeled as such `{"id":13860428,"name":"The Big Lebowski (Blu-ray) (Widescreen)","current_price":{"value": 13.49,"currency_code":"USD"}}`

## Requirements

* [Docker](https://docs.docker.com/get-docker/)
* [Docker Compose](https://docs.docker.com/compose/install/)
* [GitHub Personal Access Token](https://github.com/settings/tokens) (repo scope only)

## Setup

First, you need to configure your Docker Environment. You can do this by creating a `.env.app` file in the docker directory. Below is the text to use. Please set your GitHub Personal Access token that you fetched from above. This will allow your Docker container to access private GitHub repositories through composer.
```
COMPOSER_AUTH='{"github-oauth":{"github.com":"TOKEN HERE"}}'
```

To start the application you must first start the application. You can do this by starting docker-compose.
```
docker-compose up -d
```

Install Composer Dependencies
```
docker exec -d myretail_restful_service_app_1 composer install
```

If you visit [http://localhost:8888/](http://localhost:8888/) you should see a welcome message. This means you have successfully setup your environment.

