#!/bin/bash

export UID GID=$(id -g)

docker compose up -d

for app in matemamusic user-svc catalog-svc playlist-svc; do
  docker compose exec dev bash -c "composer install -d /demo-opentelemetry-php/$app"
done
