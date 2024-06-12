#!/bin/bash

export UID GID=$(id -g)

docker compose build --pull