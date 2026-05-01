<?php
// Fichier de connexion à la base de données VoyageHub
// Utilisation de PDO (PHP Data Objects) pour la sécurité (requêtes préparées)

// Paramètres de connexion (XAMPP - port 3307 sur cette installation)
$hote = '127.0.0.1';
$port = '3306';
$nom_bdd = 'voyagehub';
$utilisateur = 'root';
$mot_de_passe = '';

try {
    // Création de la connexion PDO
    $pdo = new PDO(
        "mysql:host=$hote;port=$port;dbname=$nom_bdd;charset=utf8mb4",
        $utilisateur,
        $mot_de_passe
    );

    // Configuration : on veut que les erreurs SQL lèvent des exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Configuration : on récupère les résultats sous forme de tableau associatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la connexion échoue, on affiche un message clair et on stoppe le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>