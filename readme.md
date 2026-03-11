# Symfony ObjectMapper Issues Reproduction

## Overview

This is a minimal reproduction application designed to demonstrate and reproduce issues encountered with the Symfony **ObjectMapper** service.

- **Backend:** Symfony 8.0 & API Platform 4.2
- **Infrastructure:** Fully dockerized environment with Caddy as a reverse proxy.

### Symfony 8.1 Backport

Although the project uses Symfony 8.0 (to maintain compatibility with API Platform 4), it includes custom classes 
located in `api/src/ObjectMapper`. These classes are a temporary backport of features planned for **Symfony 8.1** 
(specifically the `ReverseClassObjectMapperMetadataFactory`), simulating the behavior of the next major Symfony version's ObjectMapper.

## API Demo Logic

The data access in this example revolves around a central `Store` resource, linked to several other resources.

### Read Operations (GET)

When performing a GET on a Store:
- Linked resources (`manager`, `toys`, `categories`) are returned directly as **embedded** objects.
- The auto-generated API Platform identifier (`@id`) is intentionally omitted in embedded objects to simplify the structure.


## Installation

Add this line to your hosts file:

    127.0.0.1 local.api.objectmapper.net

Copy the .env files and edit them :

    cp .env.skeleton .env
    cp api/.env.skeleton api/.env

Change the DB root password in:
- `api/.env`
- `.env`

Run from the project directory:

    docker-compose build
    docker-compose up -d

Install dependencies:

    docker exec -it objectmapper_api composer install

Create the database and populate it:

    docker exec -it objectmapper_api bin/console doctrine:database:create
    docker exec -it objectmapper_api bin/console doctrine:schema:create
    docker exec -it objectmapper_api bin/console foundry:load-fixtures app:seed --no-interaction

## Usage

Run from the project directory:

    docker-compose up

## Database Population

To populate the database with representative sample data (this will purge the database first):

    docker exec -it objectmapper_api bin/console foundry:load-fixtures app:seed --no-interaction

## Tests

Populate the test database:

    docker exec -it objectmapper_api bin/console doctrine:database:create --env=test
    docker exec -it objectmapper_api bin/console doctrine:schema:create --env=test
    docker exec -it objectmapper_api bin/console foundry:load-fixtures app:seed --no-interaction --env=test

Execute the tests:

    docker exec -it objectmapper_api ./vendor/bin/phpunit -c phpunit.xml.dist
