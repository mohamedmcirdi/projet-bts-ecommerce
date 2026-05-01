<?php
// On inclut le fichier de connexion à la BDD
require 'php/connexion.php';

// Requête préparée pour récupérer tous les voyages avec leur destination
// On fait une jointure pour récupérer le nom de la ville et le pays
$requete = $pdo->prepare("
    SELECT v.id_voyage, v.titre, v.duree, v.prix, v.image, v.description,
           d.nom_ville, d.pays
    FROM voyage v
    INNER JOIN destination d ON v.id_dest = d.id_dest
    ORDER BY v.prix ASC
");
$requete->execute();
$voyages = $requete->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue – VoyageHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo">VoyageHub</div>
        <nav>
            <a href="index.html">Accueil</a>
            <a href="catalogue.php">Destinations</a>
            <a href="contact.html">Contact</a>
        </nav>
    </header>

    <section class="titre-page">
        <h2>Nos destinations</h2>
        <p>Découvrez nos <?php echo count($voyages); ?> offres de voyage</p>
    </section>

    <div class="filtre">
        <label for="filtreBudget">Filtrer par budget :</label>
        <select id="filtreBudget" onchange="filtrerDestinations()">
            <option value="tous">Tous les budgets</option>
            <option value="700">Moins de 700 €</option>
            <option value="1000">Moins de 1 000 €</option>
            <option value="1500">Moins de 1 500 €</option>
            <option value="2000">Moins de 2 000 €</option>
        </select>
    </div>

    <section class="cards" id="listeCartes">
        <?php foreach ($voyages as $voyage) : ?>
            <div class="card" data-prix="<?php echo $voyage['prix']; ?>">
                <img src="images/<?php echo htmlspecialchars($voyage['image']); ?>"
                     alt="<?php echo htmlspecialchars($voyage['nom_ville']); ?>"
                     onerror="this.src='images/placeholder.jpg'">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
                    <p class="lieu"><?php echo htmlspecialchars($voyage['nom_ville']) . ', ' . htmlspecialchars($voyage['pays']); ?></p>
                    <p class="duree"><?php echo $voyage['duree']; ?> jours</p>
                    <p class="prix">À partir de <?php echo number_format($voyage['prix'], 0, ',', ' '); ?> €</p>
                    <a href="produit.php?id=<?php echo $voyage['id_voyage']; ?>">Voir l'offre</a>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <p>© 2026 – VoyageHub – Tous droits réservés
            <a href="mentions.html">Mentions légales</a>
        </p>
    </footer>

    <script src="js/catalogue.js"></script>
</body>
</html>
