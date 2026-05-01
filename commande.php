<?php
require 'php/connexion.php';

// Récupération de l'ID du voyage depuis l'URL
$id_voyage = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Si aucun ID valide, on redirige vers le catalogue
if ($id_voyage <= 0) {
    header('Location: catalogue.php');
    exit;
}

// Requête préparée pour récupérer le voyage demandé
$requete = $pdo->prepare("
    SELECT v.id_voyage, v.titre, v.duree, v.prix, v.image,
           d.nom_ville, d.pays
    FROM voyage v
    INNER JOIN destination d ON v.id_dest = d.id_dest
    WHERE v.id_voyage = :id
");
$requete->execute([':id' => $id_voyage]);
$voyage = $requete->fetch();

// Si le voyage n'existe pas, on redirige
if (!$voyage) {
    header('Location: catalogue.php');
    exit;
}

// Récupération du nombre de personnes (peut être passé via produit.php)
$nb_personnes = isset($_GET['nb']) ? (int) $_GET['nb'] : 1;
if ($nb_personnes < 1 || $nb_personnes > 10) {
    $nb_personnes = 1;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver – <?php echo htmlspecialchars($voyage['titre']); ?> – VoyageHub</title>
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

    <section class="commande">
        <h2>Finaliser votre réservation</h2>

        <div class="recap">
            <h3>Votre voyage</h3>
            <p><strong><?php echo htmlspecialchars($voyage['titre']); ?></strong></p>
            <p><?php echo htmlspecialchars($voyage['nom_ville']) . ', ' . htmlspecialchars($voyage['pays']); ?> · <?php echo $voyage['duree']; ?> jours</p>
            <p class="prix-recap">Prix par personne : <?php echo number_format($voyage['prix'], 0, ',', ' '); ?> €</p>
        </div>

        <form action="traitement.php" method="POST" id="formCommande">
            <!-- ID du voyage caché pour qu'il soit envoyé en POST -->
            <input type="hidden" name="id_voyage" value="<?php echo $voyage['id_voyage']; ?>">

            <div class="champ">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required minlength="2" maxlength="50">
            </div>

            <div class="champ">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required minlength="2" maxlength="50">
            </div>

            <div class="champ">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="champ">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" pattern="[0-9 ]{10,15}" placeholder="0612345678">
            </div>

            <div class="champ">
                <label for="adresse">Adresse postale *</label>
                <input type="text" id="adresse" name="adresse" required minlength="5" maxlength="200">
            </div>

            <div class="champ">
                <label for="nb_personnes">Nombre de personnes *</label>
                <select id="nb_personnes" name="nb_personnes" onchange="recalculerTotal()" required>
                    <?php for ($i = 1; $i <= 6; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php echo ($i == $nb_personnes ? 'selected' : ''); ?>>
                            <?php echo $i; ?> personne<?php echo ($i > 1 ? 's' : ''); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="champ">
                <label for="paiement">Mode de paiement *</label>
                <select id="paiement" name="paiement" required>
                    <option value="">-- Choisir --</option>
                    <option value="Carte bancaire">Carte bancaire</option>
                    <option value="Virement">Virement bancaire</option>
                    <option value="PayPal">PayPal</option>
                </select>
            </div>

            <div class="champ">
                <label for="code_promo">Code promo (facultatif)</label>
                <input type="text" id="code_promo" name="code_promo" placeholder="Ex: BTS2026" onblur="verifierCodePromo()">
                <span id="messagePromo" class="message-promo"></span>
            </div>

            <!-- Récap dynamique calculé en JS -->
            <div class="recap" id="recapTotal" data-prix="<?php echo $voyage['prix']; ?>">
                <h3>Récapitulatif</h3>
                <div class="recap-ligne">
                    <span>Prix unitaire</span>
                    <span><?php echo number_format($voyage['prix'], 0, ',', ' '); ?> €</span>
                </div>
                <div class="recap-ligne">
                    <span>Nombre de personnes</span>
                    <span id="recapNb"><?php echo $nb_personnes; ?></span>
                </div>
                <div class="recap-ligne">
                    <span>Sous-total</span>
                    <span id="recapSousTotal"><?php echo number_format($voyage['prix'] * $nb_personnes, 0, ',', ' '); ?> €</span>
                </div>
                <div class="recap-ligne">
                    <span>Frais de dossier</span>
                    <span id="recapFrais">29 €</span>
                </div>
                <div class="recap-ligne" id="ligneRemise" style="display: none;">
                    <span>Remise code promo</span>
                    <span id="recapRemise">-0 €</span>
                </div>
                <div class="recap-ligne">
                    <span>Total</span>
                    <span id="recapTotalFinal"><?php echo number_format($voyage['prix'] * $nb_personnes + 29, 0, ',', ' '); ?> €</span>
                </div>
            </div>

            <div class="champ">
                <label>
                    <input type="checkbox" name="conditions" required>
                    J'accepte les conditions générales de vente *
                </label>
            </div>

            <button type="submit" class="btn-confirmer">Confirmer la commande</button>
        </form>
    </section>

    <footer>
        <p>© 2026 – VoyageHub – Tous droits réservés
            <a href="mentions.html">Mentions légales</a>
        </p>
    </footer>

    <script src="js/commande.js"></script>
</body>
</html>
