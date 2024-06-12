#!/bin/bash

export UID GID=$(id -g)

docker compose down --volumes --remove-orphans
