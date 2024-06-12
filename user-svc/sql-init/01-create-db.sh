#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" <<-EOSQL
	CREATE USER user_rw PASSWORD 'userPassword';
	CREATE DATABASE userdb;
	GRANT ALL PRIVILEGES ON DATABASE userdb TO user_rw;
EOSQL

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "userdb" <<-EOSQL
	CREATE TABLE IF NOT EXISTS "mmm_user" (
		id SERIAL,
		name varchar(255),
		PRIMARY KEY (id)
	);

  GRANT ALL ON "mmm_user" TO user_rw;

	INSERT INTO "mmm_user" (name) VALUES ('Vince'), ('Marie'), ('Raphaël'), ('Swan');
EOSQL
