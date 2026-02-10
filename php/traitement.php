<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation – VoyageHub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <h1>VoyageHub</h1>
</header>

<main>
    <h2>Commande confirmée</h2>

    <?php
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];

    // Prix fixe du voyage (traitement métier simple)
    $prix = 899;
    ?>

    <p>Merci <strong><?php echo $nom; ?></strong> pour votre commande.</p>

    <p><strong>Email :</strong> <?php echo $email; ?></p>
    <p><strong>Adresse :</strong> <?php echo $adresse; ?></p>

    <p><strong>Destination :</strong> Paris</p>
    <p><strong>Prix total :</strong> <?php echo $prix; ?> €</p>

    <a href="../index.html">Retour à l’accueil</a>
</main>

<footer>
    <p>© 2026 – VoyageHub</p>
</footer>

</body>
</html>
