#!/bin/bash

export UID GID=$(id -g)

docker compose exec dev bash