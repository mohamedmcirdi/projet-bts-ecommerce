# MCD et MLD — VoyageHub

Projet BTS SIO 1ère année — Conception et développement d'application
Plateforme e-commerce d'agence de voyage

---

## 1. MCD — Modèle Conceptuel de Données

### Entités

**CLIENT**
- id_client (identifiant)
- nom
- prenom
- email
- adresse
- telephone

**DESTINATION**
- id_dest (identifiant)
- nom_ville
- pays
- description
- image

**VOYAGE**
- id_voyage (identifiant)
- titre
- duree
- prix
- image
- description

**COMMANDE**
- id_commande (identifiant)
- date_commande
- total
- paiement
- statut

### Associations et cardinalités

| Association | Entité 1 | Cardinalités | Entité 2 |
|---|---|---|---|
| `passe` | CLIENT | 1,1 ⟷ 0,n | COMMANDE |
| `contient` (avec quantite, nb_personnes) | COMMANDE | 1,n ⟷ 0,n | VOYAGE |
| `situe` | VOYAGE | 1,1 ⟷ 1,n | DESTINATION |

### Lecture

- Un client passe 0 ou plusieurs commandes ; chaque commande appartient à un seul client.
- Une commande contient au moins un voyage et un voyage peut être commandé plusieurs fois.
- L'association `contient` porte les attributs `quantite`, `nb_personnes` et `sous_total` car ils dépendent à la fois du voyage et de la commande.
- Chaque voyage est situé dans une seule destination ; une destination peut accueillir plusieurs voyages.

---

## 2. MLD — Modèle Logique de Données

Passage du MCD au MLD : les associations `1,n` deviennent des clés étrangères dans la table côté `1,1`. L'association `n,n` (`contient`) devient une table intermédiaire `ligne_commande`.

```
CLIENT (id_client, nom, prenom, email, adresse, telephone)
   id_client : clé primaire

DESTINATION (id_dest, nom_ville, pays, description, image)
   id_dest : clé primaire

VOYAGE (id_voyage, titre, duree, prix, image, description, #id_dest)
   id_voyage : clé primaire
   #id_dest : clé étrangère vers DESTINATION

COMMANDE (id_commande, date_commande, total, paiement, statut, #id_client)
   id_commande : clé primaire
   #id_client : clé étrangère vers CLIENT

LIGNE_COMMANDE (#id_commande, #id_voyage, quantite, nb_personnes, sous_total)
   (#id_commande, #id_voyage) : clé primaire composée
   #id_commande : clé étrangère vers COMMANDE
   #id_voyage : clé étrangère vers VOYAGE
```

---

## 3. Choix techniques

- SGBD : **MySQL** (via XAMPP / phpMyAdmin)
- Encodage : **utf8mb4** pour gérer tous les caractères (accents, emojis)
- Moteur : **InnoDB** pour le support des clés étrangères
- Le `ON DELETE CASCADE` sur `ligne_commande` permet de supprimer automatiquement les lignes quand une commande est supprimée.
