#!/bin/bash

docker-compose up -d --build

docker-compose run --rm composer update

docker-compose run --rm artisan migrate --seed

echo "Visit Fridient using: http://localhost:8080"
