<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation – VoyageHub</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background-color: #1a73e8;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            color: white;
            font-size: 22px;
            font-weight: bold;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin-left: 24px;
            font-size: 15px;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        /* BOITE DE CONFIRMATION AU CENTRE */
        .confirmation {
            max-width: 600px;
            margin: 60px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 40px;
            text-align: center;
        }

        .confirmation h2 {
            font-size: 26px;
            color: #1a3c5e;
            margin-bottom: 10px;
        }

        .confirmation .sous-titre {
            font-size: 15px;
            color: #666;
            margin-bottom: 30px;
        }

        /* TABLEAU RÉCAPITULATIF */
        .recap {
            background-color: #f0f4ff;
            border: 1px solid #d0daf0;
            border-radius: 8px;
            padding: 20px 24px;
            text-align: left;
            margin-bottom: 28px;
        }

        .recap h3 {
            font-size: 15px;
            color: #1a3c5e;
            margin-bottom: 14px;
        }

        .recap-ligne {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #555;
            padding: 8px 0;
            border-bottom: 1px solid #e0e8f8;
        }

        .recap-ligne:last-child {
            border-bottom: none;
            font-weight: bold;
            color: #1a73e8;
            font-size: 16px;
        }

        .btn-accueil {
            display: inline-block;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn-accueil:hover {
            background-color: #1558b0;
        }

        footer {
            background-color: #1a3c5e;
            color: #ccc;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            margin-top: 40px;
        }

        footer a {
            color: #7ab3f5;
            text-decoration: none;
            margin-left: 8px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">VoyageHub</div>
    <nav>
        <a href="index.html">Accueil</a>
        <a href="catalogue.html">Destinations</a>
        <a href="contact.html">Contact</a>
    </nav>
</header>

<?php
    // On récupère les données envoyées par le formulaire de commande
    $nom      = isset($_POST['nom'])      ? htmlspecialchars($_POST['nom'])      : '';
    $prenom   = isset($_POST['prenom'])   ? htmlspecialchars($_POST['prenom'])   : '';
    $email    = isset($_POST['email'])    ? htmlspecialchars($_POST['email'])    : '';
    $adresse  = isset($_POST['adresse'])  ? htmlspecialchars($_POST['adresse'])  : '';
    $paiement = isset($_POST['paiement']) ? htmlspecialchars($_POST['paiement']) : '';

    // Prix fixe du voyage Paris
    $prix = 899;
?>

<section class="confirmation">

    <h2>✅ Commande confirmée !</h2>
    <p class="sous-titre">
        Merci <strong><?php echo $prenom . ' ' . $nom; ?></strong>, votre réservation est bien enregistrée.
    </p>

    <div class="recap">
        <h3>Récapitulatif</h3>

        <div class="recap-ligne">
            <span>Destination</span>
            <span>Paris – 5 jours</span>
        </div>
        <div class="recap-ligne">
            <span>Email</span>
            <span><?php echo $email; ?></span>
        </div>
        <div class="recap-ligne">
            <span>Adresse</span>
            <span><?php echo $adresse; ?></span>
        </div>
        <div class="recap-ligne">
            <span>Paiement</span>
            <span><?php echo $paiement; ?></span>
        </div>
        <div class="recap-ligne">
            <span>Total payé</span>
            <span><?php echo $prix; ?> €</span>
        </div>
    </div>

    <a href="index.html" class="btn-accueil">Retour à l'accueil</a>

</section>

<footer>
    <p>© 2026 – VoyageHub – Tous droits réservés
        <a href="mentions.html">Mentions légales</a>
    </p>
</footer>

</body>
</html>