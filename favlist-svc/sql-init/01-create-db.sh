#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER favlist_rw PASSWORD 'favlistPassword';
	CREATE DATABASE favlistdb;
	GRANT ALL PRIVILEGES ON DATABASE favlistdb TO favlist_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "favlistdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "favlist" (
		id SERIAL,
		name varchar(255),
		owner integer,
		created_at timestamp NOT NULL DEFAULT NOW(),
		film_list integer[],
		PRIMARY KEY (id)
	);
	CREATE INDEX favlist_owner ON "favlist" (owner);

	GRANT ALL ON "favlist" TO favlist_rw;
	GRANT ALL ON "favlist_id_seq" TO favlist_rw;

	INSERT INTO "favlist" (name, owner, film_list) VALUES ('Mes films d''actions préférés', 1, '{1, 2, 3}');

	INSERT INTO "favlist" (name, owner, film_list) VALUES ('Mes films classiques préférés', 2, '{10, 11, 18}');
	INSERT INTO "favlist" (name, owner, film_list) VALUES ('Mes films d''animation préférés', 2, '{20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24, 20, 21, 12, 13, 22, 23, 24}');
	INSERT INTO "favlist" (name, owner, film_list) VALUES ('Mes courts-métrages préférés', 2, '{14, 15, 16, 19}');
EOSQL
