<?php
/**
 * get_games.php
 * Retourne la liste des jeux au format JSON pour une console donnée.
 */

require_once __DIR__ . '/config_admin_DB.php';

header('Content-Type: application/json; charset=utf-8');

$console_id = isset($_GET['console_id']) ? (int)$_GET['console_id'] : 0;

if ($console_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name FROM games WHERE console_id = ? ORDER BY name");
$stmt->execute([$console_id]);
$games = $stmt->fetchAll();

echo json_encode($games);
