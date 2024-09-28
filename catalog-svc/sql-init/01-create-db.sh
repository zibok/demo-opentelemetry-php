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
	INSERT INTO "film" (title, author, genre) VALUES ('Spider-Man: New Generation (Spider-Man: Into the Spider-Verse)', 'Peter Ramsey, Bob Persichetti, Rodney Rothman', 'Action, Animation');
	INSERT INTO "film" (title, author, genre) VALUES ('Bienvenue chez les ch''tis', 'Danny Boon', 'Comédie');
	INSERT INTO "film" (title, author, genre) VALUES ('Les ailes du désir', 'Wim Wenders', 'Classique');
	INSERT INTO "film" (title, author, genre) VALUES ('Autant en emporte le vent (Gone with the wind)', 'Victor Fleming', 'Classique');
	INSERT INTO "film" (title, author, genre) VALUES ('La Traversée', 'Florence Mirailhe', 'Animation');
	INSERT INTO "film" (title, author, genre) VALUES ('La bergère et le ramoneur', 'Paul Grimault, Jacques Prévert', 'Animation, Conte');
	INSERT INTO "film" (title, author, genre) VALUES ('Le roi et l''oiseau', 'Paul Grimault, Jacques Prévert', 'Animation, Conte');
	INSERT INTO "film" (title, author, genre) VALUES ('Le tigre de Tasmanie', 'Vergine Keaton', 'Animation, Court-métrage');
	INSERT INTO "film" (title, author, genre) VALUES ('La Chute', 'Boris Labbé', 'Animation, Court-métrage');
	INSERT INTO "film" (title, author, genre) VALUES ('L''heure de l''ours', 'Agnès Patron', 'Animation, Court-métrage');
	INSERT INTO "film" (title, author, genre) VALUES ('In the Mood for Love', 'Wong Kar Wai', 'Romantique');
	INSERT INTO "film" (title, author, genre) VALUES ('Tesla : lumière mondiale', 'Matthew Rankin', 'Biopic, Court-métrage');
	INSERT INTO "film" (title, author, genre) VALUES ('Flow', 'Gints Zilbalodis', 'Animation, Aventure');
	INSERT INTO "film" (title, author, genre) VALUES ('J''ai perdu mon corps', 'Jérémy Clapin', 'Animation, Fantastique');
	INSERT INTO "film" (title, author, genre) VALUES ('Mars Express', 'Jérémie Périn', 'Animation, Science-Fiction');
	INSERT INTO "film" (title, author, genre) VALUES ('Ghost in the shell', 'Oshii Mamoru', 'Animation, Science-Fiction');
	INSERT INTO "film" (title, author, genre) VALUES ('Ghost in the Shell 2: Innocence', 'Oshii Mamoru', 'Animation, Science-Fiction');
	
EOSQL
