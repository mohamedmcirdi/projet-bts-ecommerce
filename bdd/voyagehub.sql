-- =====================================================
-- Base de données : VoyageHub
-- Projet BTS SIO - Conception et développement d'application
-- =====================================================

-- Création de la base
DROP DATABASE IF EXISTS voyagehub;
CREATE DATABASE voyagehub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voyagehub;

-- =====================================================
-- Table : destination
-- =====================================================
CREATE TABLE destination (
    id_dest INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table : voyage
-- =====================================================
CREATE TABLE voyage (
    id_voyage INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    duree INT NOT NULL COMMENT 'Durée en jours',
    prix DECIMAL(8,2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    id_dest INT NOT NULL,
    FOREIGN KEY (id_dest) REFERENCES destination(id_dest)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table : client
-- =====================================================
CREATE TABLE client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    adresse VARCHAR(255),
    telephone VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table : commande
-- =====================================================
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    paiement VARCHAR(50) NOT NULL,
    statut VARCHAR(30) DEFAULT 'en attente',
    id_client INT NOT NULL,
    FOREIGN KEY (id_client) REFERENCES client(id_client)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Table : ligne_commande (association)
-- =====================================================
CREATE TABLE ligne_commande (
    id_commande INT NOT NULL,
    id_voyage INT NOT NULL,
    quantite INT NOT NULL DEFAULT 1,
    nb_personnes INT NOT NULL DEFAULT 1,
    sous_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_commande, id_voyage),
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_voyage) REFERENCES voyage(id_voyage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERTION DE DONNÉES DE TEST
-- =====================================================

-- Destinations
INSERT INTO destination (nom_ville, pays, description, image) VALUES
('Paris', 'France', 'La ville lumière, capitale culturelle de l''Europe avec ses monuments emblématiques.', 'paris.jpg'),
('New York', 'États-Unis', 'La ville qui ne dort jamais, entre gratte-ciels et culture cosmopolite.', 'newyork.jpg'),
('Tokyo', 'Japon', 'Tradition millénaire et modernité futuriste s''entremêlent dans la capitale japonaise.', 'tokyo.jpg'),

-- Voyages
INSERT INTO voyage (titre, duree, prix, image, description, id_dest) VALUES
('Voyage à Paris', 5, 899.00, 'paris.jpg', 'Découvrez Paris en 5 jours : Tour Eiffel, Louvre, croisière sur la Seine et hôtel 4 étoiles.', 1),
('Voyage à New York, 7, 1299.00, 'newyork.jpg', 'Une semaine inoubliable à New York avec visite de Manhattan, Brooklyn et Central Park.', 2),
('Voyage à Tokyo,10, 1799.00, 'tokyo.jpg', '10 jours pour découvrir Tokyo, Kyoto et le mont Fuji avec un guide francophone.', 3),

-- Clients de test
INSERT INTO client (nom, prenom, email, adresse, telephone) VALUES
('Dupont', 'Marie', 'marie.dupont@email.fr', '12 rue de la Paix, 75002 Paris', '0612345678'),
('Martin', 'Lucas', 'lucas.martin@email.fr', '5 avenue Victor Hugo, 69006 Lyon', '0698765432');

-- Commande de test
INSERT INTO commande (total, paiement, statut, id_client) VALUES
(1798.00, 'Carte bancaire', 'confirmée', 1);

-- Lignes de commande de test
INSERT INTO ligne_commande (id_commande, id_voyage, quantite, nb_personnes, sous_total) VALUES
(1, 1, 1, 2, 1798.00);
