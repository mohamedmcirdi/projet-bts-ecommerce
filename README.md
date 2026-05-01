# VoyageHub - Site e-commerce d'agence de voyage

Projet réalisé en BTS SIO 1ère année dans le cadre du module Conception et Développement d'Application.

Le but du projet est de créer un site e-commerce pour une agence de voyage fictive avec un catalogue de destinations, une page produit, un formulaire de commande et une base de données.

## Technologies utilisées

- HTML / CSS
- JavaScript
- PHP
- MySQL (avec PDO)
- XAMPP pour le serveur local

## Installation

1. Cloner le projet dans le dossier `htdocs` de XAMPP
2. Démarrer Apache et MySQL dans XAMPP
3. Aller sur `http://localhost/phpmyadmin`
4. Importer le fichier `bdd/voyagehub.sql`
5. Aller sur `http://localhost/projet-bts-ecommerce/`

Si la connexion ne marche pas, vérifier le port dans `php/connexion.php` (par défaut 3306).

## Pages du site

- `index.html` : page d'accueil
- `catalogue.php` : catalogue des voyages (chargés depuis la BDD)
- `produit.php` : page d'un voyage avec son ID dans l'URL
- `commande.php` : formulaire de commande
- `traitement.php` : traitement du formulaire (validation + insertion en BDD)
- `confirmation.php` : page de confirmation
- `contact.html`, `apropos.html`, `faq.html`, `panier.html`, `mentions.html`

## Base de données

5 tables : `client`, `destination`, `voyage`, `commande`, `ligne_commande`.

Le MCD et le MLD sont dans le dossier `bdd/`.

## Auteurs

- Mohamed Mcirdi
- Leo Bigote
