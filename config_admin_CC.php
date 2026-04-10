<?php
// config_ad.php
// Configuration de la base de données

$host = '127.0.0.1';       // Adresse du serveur MySQL
$db   = 'ap_bdd';              // Nom de ta base de données
$user = 'root';        // Nom d'utilisateur MySQL
$pass = 'root';// Mot de passe MySQL
$charset = 'utf8mb4';      // Encodage

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active les erreurs PDO
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les résultats sous forme de tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Prépare les requêtes de manière native
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En cas d'erreur, arrêter le script et afficher le message
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
