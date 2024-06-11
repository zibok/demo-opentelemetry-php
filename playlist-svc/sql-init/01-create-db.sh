#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER playlist_rw PASSWORD 'playlistPassword';
	CREATE DATABASE playlistdb;
	GRANT ALL PRIVILEGES ON DATABASE playlistdb TO playlist_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "playlistdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "playlist" (
		id SERIAL,
		name varchar(255),
		owner integer,
		PRIMARY KEY (id)
	);
	CREATE INDEX playlist_owner ON "playlist" (owner);
EOSQL
