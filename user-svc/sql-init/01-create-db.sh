#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER user_rw PASSWORD 'userPassword';
	CREATE DATABASE userdb;
	GRANT ALL PRIVILEGES ON DATABASE userdb TO user_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "userdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "user" (
		id SERIAL,
		name varchar(255),
		PRIMARY KEY (id)
	);

	INSERT INTO "user" (name) VALUES ('Vince'), ('Marie'), ('Raphaël'), ('Swan');
EOSQL
