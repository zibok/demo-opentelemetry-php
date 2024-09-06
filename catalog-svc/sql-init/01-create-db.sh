#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER catalog_rw PASSWORD 'catalogPassword';
	CREATE DATABASE catalogdb;
	GRANT ALL PRIVILEGES ON DATABASE catalogdb TO catalog_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "catalogdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "film" (
		id SERIAL,
		title varchar(255),
		author varchar(255),
		genre varchar(255),
		PRIMARY KEY (id)
	);

	GRANT ALL ON "film" TO catalog_rw;

	INSERT INTO "film" (title, author, genre) VALUES ('Matrix', 'Lilly & Lana Wachowski', 'Action');
	INSERT INTO "film" (title, author, genre) VALUES ('Batman', 'Tim Burton', 'Action');
	INSERT INTO "film" (title, author, genre) VALUES ('Kill Bill', 'Quentin Tarantino', 'Action');
	INSERT INTO "film" (title, author, genre) VALUES ('Les tontons flingueurs', 'Georges Lautner', 'Comédie');
	INSERT INTO "film" (title, author, genre) VALUES ('Métropolis', 'Fritz Lang', 'Classique');
	INSERT INTO "film" (title, author, genre) VALUES ('Les ailes du désir', 'Wim Wenders', 'Classique');
	INSERT INTO "film" (title, author, genre) VALUES ('Camping', 'Fabien Onteniente', 'Comédie');
EOSQL
