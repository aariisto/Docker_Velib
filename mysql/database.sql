/* Script de création des tables 
SGBD Utilisé : MYSQL


1ère étape Création de la base de donnée que l’on va utiliser :
(Veillez à bien respecter l'ordre de création des tables)

*/


/*
2ème étape Connexion à la base de donnée :
On relance le shell et on se connecte directement
*/ 

USE user;


-- 3eme étape Création des tables : 

-- table users :


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- table vélo :

CREATE TABLE velo (
    id_velo INT  PRIMARY KEY,
    type VARCHAR(50)
);

-- table station :

CREATE TABLE stations (
    station_id BIGINT PRIMARY KEY,
    lat DOUBLE PRECISION,
    lon DOUBLE PRECISION,
    station VARCHAR(255)
);


-- table reservations : 

CREATE TABLE reservations (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    confirmationID VARCHAR(255) NOT NULL,
    id_velo INT NOT NULL,
    client_id INT NOT NULL,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    station_id BIGINT,
    FOREIGN KEY (id_velo) REFERENCES velo(id_velo) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (station_id) REFERENCES stations(station_id) ON DELETE CASCADE
);



-- table recherche : 

CREATE TABLE recherches (
    id  INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    recherche VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resultat TINYINT(1),
    station_id BIGINT,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (station_id) REFERENCES stations(station_id) ON DELETE CASCADE
);

-- 4eme étape Creation des vues :

-- 1ere vue, celle-ci va afficher l'historique des recherches,
CREATE VIEW recherches_vue AS
SELECT 
   *
FROM 
    recherches
LEFT JOIN 
    stations USING(station_id);


-- 2eme vue, est un recapitulatif de la reservation

CREATE VIEW reservations_vue AS
SELECT
    *
FROM
    reservations
JOIN
    stations USING(station_id)
JOIN
    velo USING(id_velo);  -- Suppose une table station_details pour lat/lon




INSERT INTO velo (id_velo, type) VALUES (1, 'electrique');
INSERT INTO velo (id_velo, type) VALUES (2, 'mecanique');
INSERT INTO velo (id_velo, type) VALUES (3, NULL);


