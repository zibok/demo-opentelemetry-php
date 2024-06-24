#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER playlist_rw PASSWORD 'playlistPassword';
	CREATE DATABASE playlistdb;
	GRANT ALL PRIVILEGES ON DATABASE playlistdb TO playlist_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "playlistdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "mmm_playlist" (
		id SERIAL,
		name varchar(255),
		owner integer,
		created_at timestamp NOT NULL DEFAULT NOW(),
		track_list integer[],
		PRIMARY KEY (id)
	);
	CREATE INDEX playlist_owner ON "mmm_playlist" (owner);

	GRANT ALL ON "mmm_playlist" TO playlist_rw;
	GRANT ALL ON "mmm_playlist_id_seq" TO playlist_rw;

	INSERT INTO "mmm_playlist" (name, owner, track_list) VALUES ('My preferred Zibok sounds', 1, '{1, 2, 3}');
EOSQL
