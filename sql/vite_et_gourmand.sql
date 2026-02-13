DROP DATABASE IF EXISTS vite_et_gourmand;
CREATE DATABASE vite_et_gourmand;
USE vite_et_gourmand;

-- Table utilisateur
CREATE TABLE utilisateur
(
    utilisateur_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    ville VARCHAR(50) NOT NULL,
    pays VARCHAR(50) NOT NULL,
    adresse_postale VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table role
CREATE TABLE role 
(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table theme
CREATE TABLE theme
(
    theme_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table allergene
CREATE TABLE allergene
(
    allergene_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

-- Table plat
CREATE TABLE plat
(
    plat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titre_plat VARCHAR(100) NOT NULL,
    type_plat ENUM('entree', 'plat', 'dessert') NOT NULL,
    description TEXT,
    photo VARCHAR(255)
);

-- Table menu
CREATE TABLE menu 
(
    menu_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    nombre_personne_minimum INT NOT NULL,
    prix_par_personne DECIMAL(10,2) NOT NULL,
    regime VARCHAR(50) NOT NULL,
    conditions TEXT,
    quantite_restante INT NOT NULL DEFAULT 0,
    theme_id INT,
    image_path VARCHAR(255),
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (theme_id) REFERENCES theme(theme_id)
);

-- Table de liaison menu_plat (un menu = plusieurs plats)
CREATE TABLE menu_plat
(
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE
);

-- Table de liaison plat_allergene (un plat = plusieurs allergènes)
CREATE TABLE plat_allergene
(
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plat(plat_id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergene(allergene_id) ON DELETE CASCADE
);

-- Table commande
CREATE TABLE commande
(
    commande_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    date_commande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_prestation DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    lieu_livraison VARCHAR(255) NOT NULL,
    ville_livraison VARCHAR(100) NOT NULL,
    prix_menu DECIMAL(10,2) NOT NULL,
    nombre_personne INT NOT NULL,
    prix_livraison DECIMAL(10,2) NOT NULL DEFAULT 0,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'accepte', 'en_preparation', 'en_livraison', 'livre', 'attente_retour_materiel', 'termine', 'annule') DEFAULT 'en_attente',
    pret_materiel BOOLEAN NOT NULL DEFAULT FALSE,
    restitution_materiel BOOLEAN DEFAULT FALSE,
    motif_annulation TEXT,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (menu_id) REFERENCES menu(menu_id)
);

-- Table avis
CREATE TABLE avis
(
    avis_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    utilisateur_id INT NOT NULL,
    commande_id INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    statut ENUM('en_attente', 'valide', 'refuse') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (commande_id) REFERENCES commande(commande_id)
);

-- Insertion des rôles
INSERT INTO role (libelle) VALUES 
('utilisateur'),
('employe'),
('administrateur');

-- Insertion des thèmes
INSERT INTO theme (libelle) VALUES 
('Noël'),
('Pâques'),
('Classique'),
('Événement'),
('Été'),
('Hiver');

-- Insertion des allergènes
INSERT INTO allergene (libelle) VALUES 
('Gluten'),
('Lactose'),
('Œufs'),
('Fruits à coque'),
('Arachides'),
('Soja'),
('Poisson'),
('Crustacés'),
('Céleri'),
('Moutarde'),
('Sésame'),
('Sulfites');

-- Insertion de plats d'exemple
INSERT INTO plat (titre_plat, type_plat, description) VALUES 
('Verrine de mousse d\'avocat et crevettes citronnées', 'entree', 'Entrée fraîche et légère'),
('Burger du Chef', 'plat', 'Burger maison avec frites'),
('Cœur fondant au chocolat', 'dessert', 'Dessert chocolaté coulant'),
('Salade de quinoa aux légumes grillés', 'entree', 'Entrée végétarienne'),
('Lasagnes aux légumes de saison', 'plat', 'Plat végétarien réconfortant'),
('Tarte aux fruits frais', 'dessert', 'Dessert fruité de saison');

-- Insertion de menus d'exemple
INSERT INTO menu (titre, description, nombre_personne_minimum, prix_par_personne, regime, conditions, quantite_restante, theme_id, image_path) VALUES 
('Menu Classique', 'Notre menu signature pour toutes occasions', 2, 25.00, 'Classique', 'Commande minimum 48h avant', 50, 3, 'assets/img/burger.jpg'),
('Menu Végétarien', 'Menu 100% végétarien de saison', 2, 22.00, 'Végétarien', 'Commande minimum 48h avant', 30, 3, 'assets/img/veggie.jpg');

-- Liaison menu-plats (Classique)
INSERT INTO menu_plat (menu_id, plat_id) VALUES 
(1, 1), -- Verrine avocat
(1, 2), -- Burger
(1, 3); -- Fondant chocolat

-- Liaison menu-plats (Végétarien)
INSERT INTO menu_plat (menu_id, plat_id) VALUES 
(2, 4), -- Salade quinoa
(2, 5), -- Lasagnes légumes
(2, 6); -- Tarte fruits

-- Liaison plats-allergènes
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES 
(1, 7), -- Verrine = Crustacés
(2, 1), -- Burger = Gluten
(2, 3), -- Burger = Œufs
(3, 2), -- Fondant = Lactose
(3, 3), -- Fondant = Œufs
(5, 1), -- Lasagnes = Gluten
(5, 2); -- Lasagnes = Lactose

-- Création compte admin (mot de passe: Admin123!)
INSERT INTO utilisateur (email, password, nom, prenom, telephone, ville, pays, adresse_postale, role_id) 
VALUES ('admin@vitegourmand.fr', '$2y$10$YourHashedPasswordHere', 'Admin', 'Vite&Gourmand', '0556000000', 'Bordeaux', 'France', '1 Rue de Bordeaux', 3);