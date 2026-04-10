<?php
session_start();

// Vérifie si l'utilisateur est connecté et a le rôle "user"
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: Connexion.php");
    exit;
}

// Redirige vers accueil_user.php
header("Location: accueil_user.php");
exit;
?>
