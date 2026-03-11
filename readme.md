# DTO Demo

## Overview

Structure simple pour la gestion de données via des DTOs, avec Symfony et API Platform.

- **Backend:** Symfony 7 & API Platform 4
- **Infrastructure:** Fully dockerized environment with Caddy as a reverse proxy for seamless local development.

## Fonctionnement de l'API Demo

L'accès des données dans cet exemple se fait autour d'une ressource centrale `StoreDTO`, elle-même liée à 
plusieurs autres ressources.

### Opérations de lecture (GET)

Lors d'un GET sur `StoreDTO` :

- Les ressources liées (`manager`, `toys`, `categories`) sont retournées directement sous forme d'objets imbriqués (**embedded**).
- L'identifiant auto-généré par API Platform (`@id`) est volontairement omis dans les objets imbriqués pour simplifier la structure.

### Opérations d'écriture (POST, PUT, PATCH)

Le traitement des ressources imbriquées suit une logique de création/mise à jour "intelligente" :

- **Mise à jour** : Si un `id` est présent dans l'objet imbriqué, l'entité correspondante est récupérée en base et mise à jour avec les nouvelles données.
- **Création** : Si aucun `id` n'est fourni, une nouvelle entité est créée et associée à la ressource principale.


## Install

Add this line to your hosts file:

    127.0.0.1 local.api.objectmapper.net

Copy the .env files, and edit it if needed:

    cp .env.skeleton .env
    cp api/.env.skeleton api/.env

Change the DB root password in :

* api/.env
* .env

Run from the project directory:

    docker-compose build
    docker-compose up

Install dependencies : 

    docker exec -it objectmapper_api bash
    composer install

Create the database and populate it :

    bin/console doctrine:database:create
    bin/console doctrine:schema:create
    bin/console doctrine:schema:update --force
    bin/console foundry:load-fixtures app:seed --no-interaction
    exit


## Run

Run from the project directory:

    docker-compose up

## Database Population

To populate the database with representative sample data (this will purge the database first):

    docker exec -it objectmapper_api bin/console foundry:load-fixtures app:seed --no-interaction

## Tests

Populate the test database :

    docker exec -it objectmapper_api bin/console doctrine:database:create --env=test
    docker exec -it objectmapper_api bin/console doctrine:schema:create --env=test
    docker exec -it objectmapper_api bin/console foundry:load-fixtures app:seed --no-interaction --env=test

Execute the tests :

    docker exec -it objectmapper_api ./vendor/bin/phpunit -c phpunit.xml.dist
