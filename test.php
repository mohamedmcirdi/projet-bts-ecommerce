<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=voyagehub;charset=utf8mb4", "root", "");
    echo "✅ Connexion OK !";
    
    $req = $pdo->query("SELECT COUNT(*) as nb FROM voyage");
    $res = $req->fetch();
    echo "<br>Nombre de voyages dans la BDD : " . $res['nb'];
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>