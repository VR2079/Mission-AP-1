<?php
/**
 * functions.php
 * Fonctions utilitaires pour récupérer les données de la base
 */

require_once __DIR__ . '/config_admin_DB.php';

/**
 * Récupère toutes les équipes
 */
function getTeams(PDO $pdo): array {
    $stmt = $pdo->query('SELECT id, name FROM teams ORDER BY name');
    return $stmt->fetchAll();
}

/**
 * Récupère toutes les consoles
 */
function getConsoles(PDO $pdo): array {
    $stmt = $pdo->query('SELECT id, name FROM consoles ORDER BY id');
    return $stmt->fetchAll();
}

/**
 * Récupère les jeux pour une console donnée
 */
function getGamesByConsole(PDO $pdo, int $console_id): array {
    $stmt = $pdo->prepare('SELECT id, name FROM games WHERE console_id = ? ORDER BY name');
    $stmt->execute([$console_id]);
    return $stmt->fetchAll();
}

/**
 * Trouve un match par son ID avec toutes les infos liées
 */
function findMatch(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("
        SELECT m.*, 
               t.name AS team_name, 
               c.name AS console_name, 
               g.name AS game_name
        FROM matches m
        JOIN teams t ON m.team_id = t.id
        JOIN consoles c ON m.console_id = c.id
        JOIN games g ON m.game_id = g.id
        WHERE m.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
