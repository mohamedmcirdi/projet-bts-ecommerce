<?php
require 'php/connexion.php';

// Récupération de l'ID du voyage depuis l'URL (ex: produit.php?id=3)
// On utilise (int) pour forcer un entier et éviter les injections SQL
$id_voyage = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Si aucun ID valide, on redirige vers le catalogue
if ($id_voyage <= 0) {
    header('Location: catalogue.php');
    exit;
}

// Requête préparée pour récupérer le voyage demandé
$requete = $pdo->prepare("
    SELECT v.id_voyage, v.titre, v.duree, v.prix, v.image, v.description,
           d.nom_ville, d.pays, d.description AS description_dest
    FROM voyage v
    INNER JOIN destination d ON v.id_dest = d.id_dest
    WHERE v.id_voyage = :id
");
$requete->execute([':id' => $id_voyage]);
$voyage = $requete->fetch();

// Si le voyage n'existe pas, on redirige vers le catalogue
if (!$voyage) {
    header('Location: catalogue.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($voyage['titre']); ?> – VoyageHub</title>
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

    <section class="produit">
        <img src="images/<?php echo htmlspecialchars($voyage['image']); ?>"
             alt="<?php echo htmlspecialchars($voyage['nom_ville']); ?>"
             onerror="this.src='images/placeholder.jpg'">

        <div class="produit-body">
            <h2><?php echo htmlspecialchars($voyage['titre']); ?></h2>
            <p class="lieu-produit">
                <?php echo htmlspecialchars($voyage['nom_ville']) . ', ' . htmlspecialchars($voyage['pays']); ?>
            </p>
            <p><?php echo htmlspecialchars($voyage['description']); ?></p>

            <div class="infos">
                <div class="info-bloc">
                    <span>Durée</span>
                    <strong><?php echo $voyage['duree']; ?> jours</strong>
                </div>
                <div class="info-bloc">
                    <span>Hôtel</span>
                    <strong>4 étoiles</strong>
                </div>
                <div class="info-bloc">
                    <span>Transport</span>
                    <strong>Inclus</strong>
                </div>
                <div class="info-bloc">
                    <span>Prix par personne</span>
                    <strong><?php echo number_format($voyage['prix'], 0, ',', ' '); ?> €</strong>
                </div>
            </div>

            <div class="choix-personnes">
                <label for="nbPersonnes">Nombre de personnes :</label>
                <select id="nbPersonnes" onchange="calculerPrix()">
                    <option value="1">1 personne</option>
                    <option value="2">2 personnes</option>
                    <option value="3">3 personnes</option>
                    <option value="4">4 personnes</option>
                </select>
            </div>

            <p id="prixTotal" data-prix-base="<?php echo $voyage['prix']; ?>">
                Total : <?php echo number_format($voyage['prix'], 0, ',', ' '); ?> €
            </p>

            <a href="commande.php?id=<?php echo $voyage['id_voyage']; ?>" class="btn-panier">
                Réserver maintenant
            </a>
        </div>
    </section>

    <footer>
        <p>© 2026 – VoyageHub – Tous droits réservés
            <a href="mentions.html">Mentions légales</a>
        </p>
    </footer>

    <script src="js/produit.js"></script>
</body>
</html>
