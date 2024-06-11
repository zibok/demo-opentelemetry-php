#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER catalog_rw PASSWORD 'catalogPassword';
	CREATE DATABASE catalogdb;
	GRANT ALL PRIVILEGES ON DATABASE catalogdb TO catalog_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "catalogdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "track" (
		id SERIAL,
		title varchar(255),
		author varchar(255),
		link varchar(255),
		PRIMARY KEY (id)
	);

	INSERT INTO "track" (title, author, link) VALUES ('Tell Me A Story', 'Zibok', 'https://soundcloud.com/zibok/tell-me-a-story');
	INSERT INTO "track" (title, author, link) VALUES ('Bounces', 'Zibok', 'https://soundcloud.com/zibok/bounces');
	INSERT INTO "track" (title, author, link) VALUES ('\o/ictory', 'Zibok', 'https://soundcloud.com/zibok/victory');
	INSERT INTO "track" (title, author, link) VALUES ('After Sunset', 'ANtarcticbreeze', 'https://soundcloud.com/musicformedia-1/antarcticbreeze-after-sunset-lofi-creative-commons-music');
EOSQL
