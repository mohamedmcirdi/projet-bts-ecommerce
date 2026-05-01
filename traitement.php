<?php
require 'php/connexion.php';

// =====================================================
// TRAITEMENT DE LA COMMANDE
// =====================================================
// Ce script reçoit les données du formulaire de commande,
// les valide, calcule le total, et insère en BDD.

// Si le formulaire n'a pas été soumis en POST, on redirige
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catalogue.php');
    exit;
}

// =====================================================
// 1) RÉCUPÉRATION DES DONNÉES POST
// =====================================================
$id_voyage = isset($_POST['id_voyage']) ? (int) $_POST['id_voyage'] : 0;
$prenom = trim($_POST['prenom'] ?? '');
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$nb_personnes = isset($_POST['nb_personnes']) ? (int) $_POST['nb_personnes'] : 1;
$paiement = trim($_POST['paiement'] ?? '');
$code_promo = strtoupper(trim($_POST['code_promo'] ?? ''));
$conditions = isset($_POST['conditions']);

// =====================================================
// 2) VALIDATION CÔTÉ SERVEUR
// =====================================================
$erreurs = [];

if ($id_voyage <= 0) {
    $erreurs[] = "Voyage invalide.";
}

if (strlen($prenom) < 2) {
    $erreurs[] = "Le prénom doit contenir au moins 2 caractères.";
}

if (strlen($nom) < 2) {
    $erreurs[] = "Le nom doit contenir au moins 2 caractères.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erreurs[] = "L'adresse email n'est pas valide.";
}

if (strlen($adresse) < 5) {
    $erreurs[] = "L'adresse doit contenir au moins 5 caractères.";
}

if ($nb_personnes < 1 || $nb_personnes > 10) {
    $erreurs[] = "Le nombre de personnes doit être entre 1 et 10.";
}

if (!in_array($paiement, ['Carte bancaire', 'Virement', 'PayPal'])) {
    $erreurs[] = "Mode de paiement invalide.";
}

if (!$conditions) {
    $erreurs[] = "Vous devez accepter les conditions générales.";
}

// Si erreurs, on les affiche et on stoppe
if (!empty($erreurs)) {
    afficherErreurs($erreurs);
    exit;
}

// =====================================================
// 3) RÉCUPÉRATION DU VOYAGE EN BDD (sécurité)
// =====================================================
// On ne fait pas confiance au prix venu du formulaire,
// on le récupère depuis la BDD pour éviter toute manipulation.
$req = $pdo->prepare("SELECT * FROM voyage WHERE id_voyage = :id");
$req->execute([':id' => $id_voyage]);
$voyage = $req->fetch();

if (!$voyage) {
    afficherErreurs(["Le voyage demandé n'existe pas."]);
    exit;
}

// =====================================================
// 4) CALCUL MÉTIER DU TOTAL
// =====================================================
$prix_unitaire = (float) $voyage['prix'];
$sous_total = $prix_unitaire * $nb_personnes;

// Frais de dossier : 29€, offerts au-dessus de 1500€
$FRAIS_DOSSIER = 29;
$SEUIL_FRAIS_OFFERT = 1500;
$frais = ($sous_total >= $SEUIL_FRAIS_OFFERT) ? 0 : $FRAIS_DOSSIER;

// Code promo : BTS2026 = 10%, WELCOME = 5%
$codes_promo = [
    'BTS2026' => 0.10,
    'WELCOME' => 0.05
];

$remise = 0;
$code_promo_applique = '';
if (isset($codes_promo[$code_promo])) {
    $remise = $sous_total * $codes_promo[$code_promo];
    $code_promo_applique = $code_promo;
}

$total = $sous_total + $frais - $remise;

// =====================================================
// 5) INSERTION EN BDD
// =====================================================
try {
    // On démarre une transaction (tout ou rien)
    $pdo->beginTransaction();

    // 5a) Insertion du client (ou récupération s'il existe déjà)
    $req = $pdo->prepare("SELECT id_client FROM client WHERE email = :email");
    $req->execute([':email' => $email]);
    $client_existant = $req->fetch();

    if ($client_existant) {
        $id_client = $client_existant['id_client'];
        // On met à jour ses infos
        $req = $pdo->prepare("
            UPDATE client
            SET nom = :nom, prenom = :prenom, adresse = :adresse, telephone = :telephone
            WHERE id_client = :id
        ");
        $req->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':adresse' => $adresse,
            ':telephone' => $telephone,
            ':id' => $id_client
        ]);
    } else {
        // Nouveau client
        $req = $pdo->prepare("
            INSERT INTO client (nom, prenom, email, adresse, telephone)
            VALUES (:nom, :prenom, :email, :adresse, :telephone)
        ");
        $req->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':adresse' => $adresse,
            ':telephone' => $telephone
        ]);
        $id_client = $pdo->lastInsertId();
    }

    // 5b) Insertion de la commande
    $req = $pdo->prepare("
        INSERT INTO commande (total, paiement, statut, id_client)
        VALUES (:total, :paiement, 'confirmée', :id_client)
    ");
    $req->execute([
        ':total' => $total,
        ':paiement' => $paiement,
        ':id_client' => $id_client
    ]);
    $id_commande = $pdo->lastInsertId();

    // 5c) Insertion de la ligne de commande
    $req = $pdo->prepare("
        INSERT INTO ligne_commande (id_commande, id_voyage, quantite, nb_personnes, sous_total)
        VALUES (:id_commande, :id_voyage, 1, :nb_personnes, :sous_total)
    ");
    $req->execute([
        ':id_commande' => $id_commande,
        ':id_voyage' => $id_voyage,
        ':nb_personnes' => $nb_personnes,
        ':sous_total' => $sous_total
    ]);

    // Tout s'est bien passé, on valide la transaction
    $pdo->commit();

} catch (PDOException $e) {
    // En cas d'erreur, on annule tout
    $pdo->rollBack();
    afficherErreurs(["Erreur lors de l'enregistrement : " . $e->getMessage()]);
    exit;
}

// =====================================================
// 6) REDIRECTION VERS LA CONFIRMATION
// =====================================================
// On passe l'ID de la commande pour pouvoir l'afficher
header('Location: confirmation.php?id=' . $id_commande);
exit;


// =====================================================
// FONCTION D'AFFICHAGE DES ERREURS
// =====================================================
function afficherErreurs($erreurs) {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur de commande – VoyageHub</title>
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
        <h2 style="color: #d93025;">Erreur de commande</h2>
        <p class="sous-titre">Veuillez corriger les erreurs suivantes :</p>
        <ul style="text-align: left; max-width: 400px; margin: 0 auto 30px;">
            <?php foreach ($erreurs as $err) : ?>
                <li style="color: #d93025; margin-bottom: 8px;"><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="javascript:history.back()" class="btn-accueil">Retour au formulaire</a>
    </section>

    <footer>
        <p>© 2026 – VoyageHub – Tous droits réservés</p>
    </footer>
</body>
</html>
<?php
}
?>
