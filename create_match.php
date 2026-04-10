<?php
/**
 * create.php
 * Formulaire de création d’un nouveau match
 */
require_once __DIR__ . '/config_user_DB.php';
require_once __DIR__ . '/functions.php';

$teams    = getTeams($pdo);
$consoles = getConsoles($pdo);

$errors = [];
$team_id = $console_id = $game_id = 0;
$opponent = $match_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id    = (int)($_POST['team_id'] ?? 0);
    $opponent   = trim($_POST['opponent'] ?? '');
    $console_id = (int)($_POST['console_id'] ?? 0);
    $game_id    = (int)($_POST['game_id'] ?? 0);
    $match_date = $_POST['match_date'] ?? null;

    if ($team_id <= 0)    $errors[] = "Veuillez sélectionner une équipe.";
    if ($console_id <= 0) $errors[] = "Veuillez sélectionner une console.";
    if ($game_id <= 0)    $errors[] = "Veuillez sélectionner un jeu.";

    // 🟢 Si aucun adversaire n'est renseigné, on indique que c’est un match solo
    if (empty($opponent)) {
        $opponent = "Team solo";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO matches (team_id, opponent, console_id, game_id, match_date)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$team_id, $opponent, $console_id, $game_id, $match_date]);
        header('Location: liste_match.php');
        exit;
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Créer un match</title>
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
    <h1>➕ Créer un match</h1>
    <a href="liste_match.php" class="btn btn-secondary">⬅ Retour</a>

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
      <!-- Équipe -->
      <div class="mb-3">
        <label class="form-label">Équipe</label>
        <select name="team_id" class="form-select" required>
          <option value="0">-- Sélectionner une équipe --</option>
          <?php foreach ($teams as $t): ?>
            <option value="<?= $t['id'] ?>" <?= $team_id==$t['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($t['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Adversaire -->
      <div class="mb-3">
        <label class="form-label">Adversaire (à laisser vide pour une session)</label>
        <input type="text" name="opponent" class="form-control" value="<?= htmlspecialchars($opponent) ?>">
      </div>

      <!-- Console -->
      <div class="mb-3">
        <label class="form-label">Console</label>
        <select name="console_id" id="console-select" class="form-select" required>
          <option value="0">-- Sélectionner une console --</option>
          <?php foreach ($consoles as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $console_id==$c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Jeu -->
      <div class="mb-3">
        <label class="form-label">Jeu</label>
        <select name="game_id" id="game-select" class="form-select" required>
          <option value="0">-- Sélectionner une console d'abord --</option>
        </select>
      </div>

      <!-- Date -->
      <div class="mb-3">
        <label class="form-label">Date et heure</label>
        <input type="datetime-local" name="match_date" class="form-control"
               value="<?= htmlspecialchars($match_date) ?>">
      </div>

      <!-- Bouton -->
      <button type="submit" class="btn btn-success">✅ Créer le match</button>
    </form>
  </div>

  <!-- Script pour charger les jeux dynamiquement -->
  <script>
  async function loadGames(consoleId, selectedId = null) {
    const sel = document.getElementById('game-select');
    sel.innerHTML = '<option value="0">Chargement...</option>';
    if (!consoleId) {
      sel.innerHTML = '<option value="0">-- Sélectionner une console d\'abord --</option>';
      return;
    }
    try {
      const res = await fetch('get_games.php?console_id=' + encodeURIComponent(consoleId));
      const games = await res.json();
      sel.innerHTML = '<option value="0">-- Sélectionner un jeu --</option>';
      games.forEach(g => {
        const opt = new Option(g.name, g.id);
        if (selectedId && selectedId == g.id) opt.selected = true;
        sel.appendChild(opt);
      });
    } catch (e) {
      sel.innerHTML = '<option value="0">Erreur de chargement</option>';
    }
  }

  document.getElementById('console-select').addEventListener('change', function() {
    loadGames(this.value);
  });

  <?php if ($console_id): ?>
    loadGames(<?= $console_id ?>, <?= $game_id ?>);
  <?php endif; ?>
  </script>
</body>
</html>
