<?php
/**
 * edit_user.php
 * Formulaire d'édition d’un utilisateur existant
 */

require_once __DIR__ . '/config_admin_CC.php';
require_once __DIR__ . '/fonctions_user.php';

// Vérifier que l'ID est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php');
    exit;
}

$id = (int)$_GET['id'];

// Récupérer l’utilisateur à modifier
$user = findUser($pdo, $id);

if (!$user) {
    echo "<h1>Utilisateur introuvable</h1>";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = trim($_POST['role'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '') $errors[] = "Le nom d'utilisateur est requis.";
    if ($email === '')    $errors[] = "L'adresse e-mail est requise.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Adresse e-mail invalide.";

    if (empty($errors)) {
        try {
            if ($password !== '') {
                // Si un nouveau mot de passe est fourni
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = ?, email = ?, password = ?, role = ?
                    WHERE id = ?
                ");
                $stmt->execute([$username, $email, $password_hash, $role, $id]);
            } else {
                // Pas de changement de mot de passe
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = ?, email = ?, role = ?
                    WHERE id = ?
                ");
                $stmt->execute([$username, $email, $role, $id]);
            }

            header('Location: admin.php?updated=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erreur SQL : " . $e->getMessage();
        }
    }
} else {
    // Préremplissage
    $username = $user['username'];
    $email    = $user['email'];
    $role     = $user['role'];
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Modifier l'utilisateur #<?= htmlspecialchars($user['id']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h1 class="mb-4">✏️ Modifier l'utilisateur #<?= htmlspecialchars($user['id']) ?></h1>
    <a href="admin.php" class="btn btn-secondary mb-3">⬅ Retour</a>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm p-4 bg-white">
      <!-- Nom d'utilisateur -->
      <div class="mb-3">
        <label class="form-label">Nom d'utilisateur</label>
        <input type="text" name="username" class="form-control" required
               value="<?= htmlspecialchars($username) ?>">
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label">Adresse e-mail</label>
        <input type="email" name="email" class="form-control" required
               value="<?= htmlspecialchars($email) ?>">
      </div>

      <!-- Mot de passe -->
      <div class="mb-3">
        <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
        <input type="password" name="password" class="form-control">
      </div>

      <!-- Rôle -->
      <div class="mb-3">
        <label class="form-label">Rôle</label>
        <select name="role" class="form-select" required>
          <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Utilisateur</option>
          <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>
      </div>

      <!-- Bouton -->
      <button type="submit" class="btn btn-warning">💾 Enregistrer les modifications</button>
    </form>
  </div>
</body>
</html>
