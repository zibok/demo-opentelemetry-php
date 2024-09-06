#!/bin/bash

export UID GID=$(id -g)

docker compose up -d

if [ "$1" == "--rebuild-apps" ]; then
  for app in lesfilmsquejekiffe user-svc catalog-svc favlist-svc; do
    docker compose exec dev bash -c "composer install -d /demo-opentelemetry-php/$app"
  done

  docker compose exec dev bash -c "cd /demo-opentelemetry-php/lesfilmsquejekiffe && npm install && npm run build"
fi
