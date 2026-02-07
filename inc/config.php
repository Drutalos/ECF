<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=vite_et_gourmand', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !"; // Tu peux enlever cette ligne après
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
//connexion MongoDb
require 'vendor/autoload.php';
$client = new MongoDB\Client(uri: "Mongodb://localhost:27017");
$db = $client->selectDatabase(databaseName: "ECF_test");
$horaires = $db->horaires;
// Démarrer la session (pour la connexion des utilisateurs)
//session_start();

?>