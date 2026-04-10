<?php
/**
 * delete_user.php
 * Suppression d’un utilisateur depuis le panneau admin
 */

require_once __DIR__ . '/config_user_CC.php'; // connexion PDO à la base 'cc'

// Vérifier que l'ID est fourni et valide
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = (int)$_GET['id'];

// Sécurité : ne pas permettre de supprimer le compte admin principal
// (optionnel — tu peux enlever cette partie si tu veux)
if ($id === 1) {
    die("❌ Vous ne pouvez pas supprimer l'utilisateur administrateur principal.");
}

try {
    // Supprimer l’utilisateur
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // Redirection vers la page d’administration
    header('Location: admin.php?deleted=1');
    exit;

} catch (PDOException $e) {
    // En cas d’erreur SQL
    echo "<h1>Erreur SQL :</h1>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    exit;
}
