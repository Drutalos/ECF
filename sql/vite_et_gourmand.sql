DROP DATABASE IF EXISTS vite_et_gourmand
CREATE DATABASE vite_et_gourmand

CREATE TABLE commande
(
    commande_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    numero_commande VARCHAR(50) NOT NULL,
    date_commande DATE NOT NULL,
    date_prestation DATE NOT NULL,
    heure_livraison VARCHAR(50) NOT NULL,
    prix_menu DOUBLE NOT NULL,
    nombre_personne INT NOT NULL,
    prix_livraison DOUBLE NOT NULL,
    statut VARCHAR(50) NOT NULL,
    pret-materiel BOOL NOT NULL,
    restitution_materiel BOOL NOT NULL
)

CREATE TABLE utilisateur
(
    utilisateur_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    ville VARCHAR(50) NOT NULL,
    pays VARCHAR(50) NOT NULL,
    adresse_postale VARCHAR(50) NOT NULL
)

CREATE TABLE role 
(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL
)

CREATE TABLE avis
(
    avis_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    note VARCHAR(50) NOT NULL,
    description VARCHAR(50) NOT NULL,
    statut VARCHAR(50) NOT NULL
)

CREATE TABLE menu 
(
    menu_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(50) NOT NULL,
    nombre_personne_minimum INT NOT NULL,
    prix_par_personne DOUBLE NOT NULL,
    regime VARCHAR(50) NOT NULL,
    description VARCHAR(50) NOT NULL,
    quantite_restante INT NOT NULL
)

CREATE TABLE plat
(
    plat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titre_plat VARCHAR(50) NOT NULL,
    photo BLOB
)

CREATE TABLE allergene
(
    allergene-id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL
)

CREATE TABLE theme
(
    theme_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL
)