<?php
// config_user.php

$host = 'localhost';
$dbname = 'ap_bdd'; // ✅ ta vraie base de données
$username = 'root';
$password = 'root'; // ou ton mot de passe MySQL si tu en as un

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
