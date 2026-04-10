<?php
/**
 * config_us.php
 * Fichier de configuration pour la connexion à la base de données.
 * 
 * ⚠️ Adapter les valeurs en fonction de ton environnement local (XAMPP, Laragon, etc.)
 */

$DB_HOST = '127.0.0.1';   // Adresse du serveur MySQL
$DB_NAME = 'ap_bdd';    // Nom de la base de données
$DB_USER = 'root';        // Utilisateur MySQL
$DB_PASS = 'root';            // Mot de passe (vide par défaut sous XAMPP)
$DB_CHAR = 'utf8mb4';     // Encodage

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retourne les résultats en tableau associatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Utilisation des requêtes préparées réelles
];

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}",
        $DB_USER,
        $DB_PASS,
        $options
    );
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>Erreur de connexion à la base de données</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
