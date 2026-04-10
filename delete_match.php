<?php
/**
 * delete.php
 * Supprime un match de la base de données
 */

require_once __DIR__ . '/config_admin_DB.php';

// Vérifier que l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index_ad.php');
    exit;
}

$id = (int)$_GET['id'];

// Supprimer le match
$stmt = $pdo->prepare("DELETE FROM matches WHERE id = ?");
$stmt->execute([$id]);

// Rediriger vers la liste
header('Location: index_ad.php');
exit;
