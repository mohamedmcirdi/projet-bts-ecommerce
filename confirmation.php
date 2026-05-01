<?php
require 'php/connexion.php';

// Récupération de l'ID de commande depuis l'URL
$id_commande = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_commande <= 0) {
    header('Location: catalogue.php');
    exit;
}

// Récupération des infos de la commande avec jointure
$req = $pdo->prepare("
    SELECT c.id_commande, c.date_commande, c.total, c.paiement, c.statut,
           cl.nom, cl.prenom, cl.email,
           v.titre, v.duree, v.prix,
           lc.nb_personnes, lc.sous_total,
           d.nom_ville, d.pays
    FROM commande c
    INNER JOIN client cl ON c.id_client = cl.id_client
    INNER JOIN ligne_commande lc ON c.id_commande = lc.id_commande
    INNER JOIN voyage v ON lc.id_voyage = v.id_voyage
    INNER JOIN destination d ON v.id_dest = d.id_dest
    WHERE c.id_commande = :id
");
$req->execute([':id' => $id_commande]);
$commande = $req->fetch();

if (!$commande) {
    header('Location: catalogue.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande – VoyageHub</title>
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

    <section class="confirmation">
        <h2>✓ Commande confirmée !</h2>
        <p class="sous-titre">
            Merci <?php echo htmlspecialchars($commande['prenom']); ?>, votre réservation a bien été enregistrée.
        </p>

        <div class="recap" style="text-align: left; margin-bottom: 24px;">
            <h3>Détails de votre commande #<?php echo $commande['id_commande']; ?></h3>
            <div class="recap-ligne">
                <span>Voyage</span>
                <span><?php echo htmlspecialchars($commande['titre']); ?></span>
            </div>
            <div class="recap-ligne">
                <span>Destination</span>
                <span><?php echo htmlspecialchars($commande['nom_ville']) . ', ' . htmlspecialchars($commande['pays']); ?></span>
            </div>
            <div class="recap-ligne">
                <span>Durée</span>
                <span><?php echo $commande['duree']; ?> jours</span>
            </div>
            <div class="recap-ligne">
                <span>Nombre de personnes</span>
                <span><?php echo $commande['nb_personnes']; ?></span>
            </div>
            <div class="recap-ligne">
                <span>Mode de paiement</span>
                <span><?php echo htmlspecialchars($commande['paiement']); ?></span>
            </div>
            <div class="recap-ligne">
                <span>Statut</span>
                <span><?php echo htmlspecialchars($commande['statut']); ?></span>
            </div>
            <div class="recap-ligne">
                <span>Total payé</span>
                <span><?php echo number_format($commande['total'], 2, ',', ' '); ?> €</span>
            </div>
        </div>

        <p style="margin-bottom: 24px; color: #666; font-size: 14px;">
            Un email de confirmation a été envoyé à <strong><?php echo htmlspecialchars($commande['email']); ?></strong>.
        </p>

        <a href="catalogue.php" class="btn-accueil">Retour au catalogue</a>
    </section>

    <footer>
        <p>© 2026 – VoyageHub – Tous droits réservés
            <a href="mentions.html">Mentions légales</a>
        </p>
    </footer>
</body>
</html>
