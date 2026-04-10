<?php
/**
 * fonctions_user.php
 * Fonctions utilitaires pour la gestion des utilisateurs
 */

require_once __DIR__ . '/config_admin_CC.php';

/**
 * Récupère tous les utilisateurs
 */
function getUsers(PDO $pdo): array {
    $stmt = $pdo->query('SELECT id, username, email, role, created_at FROM users ORDER BY id DESC');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Trouve un utilisateur par son ID
 */
function findUser(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Trouve un utilisateur par son e-mail (utile pour la connexion)
 */
function findUserByEmail(PDO $pdo, string $email): ?array {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Supprime un utilisateur
 */
function deleteUser(PDO $pdo, int $id): bool {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    return $stmt->execute([$id]);
}

/**
 * Met à jour les informations d’un utilisateur
 */
function updateUser(PDO $pdo, int $id, string $username, string $email, string $role): bool {
    $stmt = $pdo->prepare('
        UPDATE users
        SET username = ?, email = ?, role = ?
        WHERE id = ?
    ');
    return $stmt->execute([$username, $email, $role, $id]);
}

/**
 * Crée un nouvel utilisateur (utile si tu veux l’appeler directement ailleurs)
 */
function createUser(PDO $pdo, string $username, string $email, string $password, string $role): bool {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('
        INSERT INTO users (username, email, password, role, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ');
    return $stmt->execute([$username, $email, $password_hash, $role]);
}
