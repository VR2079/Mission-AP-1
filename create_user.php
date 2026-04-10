<?php
/**
 * create_user.php
 * Formulaire de création d’un nouvel utilisateur (Admin)
 */
 ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config_admin_CC.php'; // connexion PDO
require_once __DIR__ . '/fonctions_user.php'; // fonctions communes si besoin

$errors = [];
$username = $email = $password = $role = '';

// Vérification du POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    // Validation basique
    if ($username === '') $errors[] = "Le nom d'utilisateur est obligatoire.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Adresse e-mail invalide.";
    if ($password === '') $errors[] = "Le mot de passe est obligatoire.";
    if ($role === '') $errors[] = "Veuillez sélectionner un rôle.";

    // Si aucune erreur
    if (empty($errors)) {
        // Hachage du mot de passe
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertion
        $stmt = $pdo->prepare("
    INSERT INTO users (username, email, password, role, created_at)
    VALUES (?, ?, ?, ?, NOW())
	");
	try {
		$stmt->execute([$username, $email, $password_hash, $role]);
		header('Location: admin.php');
		exit;
	} catch (PDOException $e) {
		if (str_contains($e->getMessage(), 'Duplicate')) {
        $errors[] = "Cet e-mail est déjà utilisé.";
		} else {
        $errors[] = "Erreur SQL : " . $e->getMessage();
			}
		}
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Créer un utilisateur</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0b1a2a; /* Bleu foncé */
      color: #ffffff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
      max-width: 700px;
      padding: 30px;
      margin-top: 40px;
      background-color: #0d1b2a; /* Fond sombre pour le formulaire */
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }

    h1 {
      color: #ffffff;
      margin-bottom: 20px;
      border-bottom: 2px solid #1b2a44;
      padding-bottom: 10px;
    }

    a.btn-secondary {
      background-color: #1b2a44;
      border-color: #1b2a44;
      color: #ffffff;
      margin-bottom: 20px;
    }
    a.btn-secondary:hover {
      background-color: #14203b;
      border-color: #14203b;
      color: #ffffff;
    }

    .form-label {
      color: #ffffff;
    }

    .form-control, .form-select {
      background-color: #1b2a44;
      color: #ffffff;
      border: 1px solid #14203b;
    }
    .form-control:focus, .form-select:focus {
      background-color: #1b2a44;
      color: #ffffff;
      border-color: #2a3b5c;
      box-shadow: none;
    }

    .btn-success {
      background-color: #28a745;
      border-color: #28a745;
      color: #ffffff;
    }
    .btn-success:hover {
      background-color: #1e7e34;
      border-color: #1e7e34;
      color: #ffffff;
    }

    .alert-danger {
      background-color: #d9534f;
      color: #ffffff;
      border: none;
    }

    /* Card-like form */
    form.card {
      background-color: #0d1b2a;
      border: 1px solid #14203b;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>➕ Créer un utilisateur</h1>
    <a href="admin.php" class="btn btn-secondary">⬅ Retour</a>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" class="card shadow-sm">
      <!-- Nom d'utilisateur -->
      <div class="mb-3">
        <label class="form-label">Nom d'utilisateur</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label">Adresse e-mail</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <!-- Mot de passe -->
      <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <!-- Rôle -->
      <div class="mb-3">
        <label class="form-label">Rôle</label>
        <select name="role" class="form-select" required>
          <option value="">-- Sélectionner un rôle --</option>
          <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Administrateur</option>
          <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Utilisateur</option>
        </select>
      </div>

      <!-- Bouton -->
      <button type="submit" class="btn btn-success w-100">✅ Créer l'utilisateur</button>
    </form>
  </div>
</body>
</html>
